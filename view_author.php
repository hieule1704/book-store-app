<?php
include 'config.php';
include_once __DIR__ . '/session_config.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
// Allow guests to view, but track ID if logged in for cart logic

$author_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Fetch Author Details
$author_query = mysqli_query($conn, "SELECT * FROM `author` WHERE id = '$author_id'");
if (mysqli_num_rows($author_query) > 0) {
    $fetch_author = mysqli_fetch_assoc($author_query);
} else {
    header('Location: shop.php');
    exit;
}

// 2. Add to Cart Logic (Copy from shop.php for functionality)
if (isset($_POST['add_to_cart'])) {
    if (!$user_id) {
        header('Location: login.php');
        exit;
    }
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'");
    if (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')");
        $message[] = 'Product added to cart!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($fetch_author['author_name']); ?> - Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="bg-white py-5 shadow-sm mb-5">
        <div class="container text-center">
            <img src="uploaded_img/<?php echo htmlspecialchars($fetch_author['profile_picture']); ?>"
                class="rounded-circle shadow mb-3 border border-4 border-light"
                style="width: 150px; height: 150px; object-fit: cover;"
                alt="<?php echo htmlspecialchars($fetch_author['author_name']); ?>">
            <h1 class="fw-bold"><?php echo htmlspecialchars($fetch_author['author_name']); ?></h1>
            <p class="text-muted">Explore all books by this author</p>
        </div>
    </div>

    <section class="container mb-5">
        <h3 class="text-uppercase mb-4 border-bottom pb-2">Books by <?php echo htmlspecialchars($fetch_author['author_name']); ?></h3>
        <div class="row g-4">
            <?php
            // 3. Fetch Products by Author
            $select_products = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
            LEFT JOIN `author` a ON p.author_id = a.id
            LEFT JOIN `publisher` pub ON p.publisher_id = pub.id
            WHERE p.author_id = '$author_id'
            ORDER BY p.id DESC") or die('query failed');

            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    // Reuse your existing Product Card Logic
                    $stock = $fetch_products['stock_quantity'];
                    $isDisabled = $stock == 0 ? 'disabled' : '';
                    $stockBadge = $stock == 0
                        ? '<span class="badge bg-danger position-absolute bottom-0 end-0 m-2">Out of Stock</span>'
                        : ($stock < 5 ? '<span class="badge bg-warning text-dark position-absolute bottom-0 end-0 m-2">Low Stock</span>' : '');
            ?>
                    <div class="col-md-3 col-sm-6">
                        <form action="" method="post" class="card h-100 shadow-sm border-0 product-card">
                            <div class="position-relative bg-light text-center p-3" style="height: 240px;">
                                <a href="detail.php?id=<?php echo $fetch_products['id']; ?>">
                                    <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                </a>
                                <?php echo $stockBadge; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="text-muted small text-uppercase mb-1"><?php echo htmlspecialchars($fetch_products['publisher_name']); ?></div>
                                <h6 class="fw-bold text-truncate mb-2">
                                    <a href="detail.php?id=<?php echo $fetch_products['id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($fetch_products['book_name']); ?>
                                    </a>
                                </h6>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fw-bold text-primary fs-5">$<?php echo $fetch_products['price']; ?></span>
                                    </div>
                                    <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['book_name']); ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                                    <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

                                    <div class="d-flex gap-2">
                                        <input type="number" min="1" name="product_quantity" value="1" class="form-control" style="width: 60px;">
                                        <button type="submit" name="add_to_cart" class="btn btn-primary w-100" <?php echo $isDisabled; ?>>Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<div class="col-12 text-center py-5 text-muted">No books found for this author.</div>';
            }
            ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>