<?php
include 'config.php';
include_once __DIR__ . '/session_config.php';

$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
if (!$admin_id) {
    header('Location: login.php');
    exit;
}

$message = [];

if (isset($_POST['import'])) {
    if ($_FILES['csv_file']['name']) {
        $filename = explode(".", $_FILES['csv_file']['name']);
        if (end($filename) == "csv") {
            $handle = fopen($_FILES['csv_file']['tmp_name'], "r");

            // Skip Header Row
            fgetcsv($handle);

            $count_update = 0;
            $count_insert = 0;

            while (($data = fgetcsv($handle)) !== FALSE) {
                // CSV Structure Expected: 
                // Name [0], Author ID [1], Publisher ID [2], Price [3], Stock [4], Image [5]

                $name = mysqli_real_escape_string($conn, $data[0]);
                $author_id = intval($data[1]);
                $publisher_id = intval($data[2]);
                $price = intval($data[3]);
                $stock = intval($data[4]);
                $image = isset($data[5]) ? mysqli_real_escape_string($conn, $data[5]) : 'no-picture-book.jpg';

                // Check if exists
                $check = mysqli_query($conn, "SELECT id, stock_quantity FROM products WHERE book_name = '$name'");

                if (mysqli_num_rows($check) > 0) {
                    // Update Stock
                    $row = mysqli_fetch_assoc($check);
                    $new_stock = $row['stock_quantity'] + $stock;
                    mysqli_query($conn, "UPDATE products SET stock_quantity = '$new_stock', price = '$price' WHERE id = " . $row['id']);
                    $count_update++;
                } else {
                    // Insert New
                    mysqli_query($conn, "INSERT INTO products (book_name, author_id, publisher_id, price, stock_quantity, image) 
                                         VALUES ('$name', '$author_id', '$publisher_id', '$price', '$stock', '$image')");
                    $count_insert++;
                }
            }
            fclose($handle);
            $message[] = "Import Success! Updated: $count_update, Inserted: $count_insert";
        } else {
            $message[] = "Please upload a CSV file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">

    <?php include 'admin_header.php'; ?>

    <section class="container my-5">
        <h1 class="text-center text-uppercase mb-4">Import Data</h1>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Import Products via CSV</h5>
                        <p class="text-muted small">
                            CSV Format: <br>
                            <code>Name, Author_ID, Publisher_ID, Price, Stock, Image_Filename</code>
                        </p>

                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="file" name="csv_file" class="form-control" required accept=".csv">
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="import" class="btn btn-primary">
                                    <i class="fas fa-file-import me-2"></i> Import Now
                                </button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="example_import.csv" download class="text-decoration-none">Download Sample CSV</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>