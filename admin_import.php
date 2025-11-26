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

            $count_insert = 0;
            $count_duplicate = 0;
            $count_invalid = 0;

            while (($data = fgetcsv($handle)) !== FALSE) {
                // CSV Structure Expected: 
                // Name [0], Author ID [1], Publisher ID [2], Price [3], Stock [4], Image [5]

                // Basic validation to ensure row isn't empty
                if (empty($data[0]) || empty($data[1]) || empty($data[2])) {
                    continue;
                }

                $name = mysqli_real_escape_string($conn, $data[0]);
                $author_id = intval($data[1]);
                $publisher_id = intval($data[2]);
                $price = intval($data[3]);
                $stock = intval($data[4]);
                $image = isset($data[5]) && !empty($data[5]) ? mysqli_real_escape_string($conn, $data[5]) : 'no-picture-book.jpg';

                // 1. VALIDATE FOREIGN KEYS (Integrity Check)
                // Ensure Author exists
                $check_author = mysqli_query($conn, "SELECT id FROM `author` WHERE id = '$author_id'");
                // Ensure Publisher exists
                $check_publisher = mysqli_query($conn, "SELECT id FROM `publisher` WHERE id = '$publisher_id'");

                if (mysqli_num_rows($check_author) == 0 || mysqli_num_rows($check_publisher) == 0) {
                    // Skip if ID is invalid to prevent database errors
                    $count_invalid++;
                    continue;
                }

                // 2. CHECK DUPLICATES (Ignore if exists)
                $check_product = mysqli_query($conn, "SELECT id FROM `products` WHERE book_name = '$name'");

                if (mysqli_num_rows($check_product) > 0) {
                    // Product exists, do nothing (Skip)
                    $count_duplicate++;
                } else {
                    // 3. INSERT NEW (Only if valid and unique)
                    $query = "INSERT INTO `products` (book_name, author_id, publisher_id, price, stock_quantity, image) 
                              VALUES ('$name', '$author_id', '$publisher_id', '$price', '$stock', '$image')";

                    if (mysqli_query($conn, $query)) {
                        $count_insert++;
                    }
                }
            }
            fclose($handle);

            // Detailed result message
            $msg = "Import Complete! ";
            $msg .= "Inserted: <strong>$count_insert</strong> new products. ";
            if ($count_duplicate > 0) $msg .= "Skipped <strong>$count_duplicate</strong> duplicates. ";
            if ($count_invalid > 0) $msg .= "Skipped <strong>$count_invalid</strong> rows with invalid Author/Publisher IDs.";

            $message[] = $msg;
        } else {
            $message[] = "Please upload a valid CSV file.";
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
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-csv me-2"></i>Bulk Product Import</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle"></i> Note:</strong>
                            <ul class="mb-0 ps-3 small">
                                <li>This tool will <strong>only insert new products</strong>.</li>
                                <li>Existing book names will be <strong>skipped</strong> (no duplicates).</li>
                                <li>Rows with invalid Author IDs or Publisher IDs will be <strong>skipped</strong> to protect database integrity.</li>
                            </ul>
                        </div>

                        <p class="text-muted small">
                            Expected CSV Columns: <br>
                            <code>Name, Author_ID, Publisher_ID, Price, Stock, Image_Filename</code>
                        </p>

                        <form action="" method="post" enctype="multipart/form-data" class="mt-4">
                            <div class="mb-4">
                                <label for="csv_file" class="form-label">Select CSV File</label>
                                <input type="file" name="csv_file" id="csv_file" class="form-control" required accept=".csv">
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="import" class="btn btn-primary btn-lg">
                                    <i class="fas fa-cloud-upload-alt me-2"></i> Start Import
                                </button>
                            </div>
                        </form>

                        <div class="mt-4 text-center border-top pt-3">
                            <p class="small text-muted mb-2">Need a template?</p>
                            <a href="#" onclick="downloadSample()" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download me-1"></i> Download Sample CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Quick JS function to generate a sample CSV on the fly
        function downloadSample() {
            const csvContent = "data:text/csv;charset=utf-8," +
                "Book Name,Author ID,Publisher ID,Price,Stock,Image.jpg\n" +
                "Harry Potter,1,2,25,100,harry.jpg\n" +
                "Clean Code,5,3,40,50,code.jpg";

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "sample_products.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>

</html>