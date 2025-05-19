<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $result = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
            LEFT JOIN `author` a ON p.author_id = a.id
            LEFT JOIN `publisher` pub ON p.publisher_id = pub.id
            WHERE p.id = $product_id");
    $product = mysqli_fetch_assoc($result);
} else {
    header('location:home.php');
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $product['book_name'];
    $product_price = $product['price'];
    $product_image = $product['image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Sản phẩm đã có trong giỏ hàng!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'Đã thêm vào giỏ hàng!';
    }
}

if (isset($_POST['buy_now'])) {
    $product_name = $product['book_name'];
    $product_price = $product['price'];
    $product_image = $product['image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) == 0) {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
    }
    header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <!-- Bootstrap 5.3.x CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>

<body>

    <?php include 'header.php'; ?>

    <div class="bg-light py-4 mb-4">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['book_name']); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="d-flex row justify-content-center" style="max-width: 1200px; margin: 0 auto">
            <div class="col-sm-4">
                <div class="card">
                    <img class="img-fluid p-4" style="height: 40rem; object-fit: contain;" src="uploaded_img/<?php echo htmlspecialchars($product['image']); ?>" alt="">
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card p-4" style="height: 40rem; max-height: 40rem; overflow-y: auto;">
                    <div class="card-body">
                        <h2 class="card-title fs-1 fw-bold mb-4"><?php echo htmlspecialchars($product['book_name']); ?></h2>
                        <div class="fs-6">
                            <p>Publisher: <?php echo htmlspecialchars($product['publisher_name']) ?></p>
                            <p>Author: <?php echo htmlspecialchars($product['author_name']) ?></p>
                            <p>Publish Year: <?php echo htmlspecialchars($product['publish_year']) ?></p>
                            <p>Book Layout: <?php echo htmlspecialchars($product['total_page']) ?> pages</p>
                        </div>
                        <div class="text-danger fw-bold fs-3">Price: $<?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                        <form method="POST">
                            <div class="d-flex align-items-center mt-4">
                                <input type="number" min="1" name="product_quantity" value="1" class="form-control" style="max-width:120px;">
                                <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg ms-2">Add to cart</button>
                                <button type="submit" name="buy_now" class="btn btn-danger btn-lg ms-2">Buy now</button>
                            </div>
                        </form>
                        <div class="mt-3">
                            <h2 class="fs-4">Description</h2>
                            <p><?php echo htmlspecialchars($product['book_description']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php include 'footer.php'; ?>

    <!-- Bootstrap 5.3.x JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>