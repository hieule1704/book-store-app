<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $result = mysqli_query($conn, "SELECT * FROM products where id = '$product_id'");
    $product = mysqli_fetch_assoc($result);
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $product['name'];
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
    $product_name = $product['name'];
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
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $product['name']; ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="d-flex row justify-content-center" style="max-width: 1200px; margin: 0 auto">
            <div class="col-sm-4">
                <div class="card">
                    <img class="img-fluid p-4" style="height: 40rem; object-fit: contain;" src="uploaded_img/<?php echo $product['image']; ?>" alt="">
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card p-4" style="height: 40rem; max-height: 40rem; overflow-y: auto;">
                    <div class="card-body">
                        <h2 class="card-title fs-1 fw-bold mb-4"><?php echo $product['name']; ?></h2>
                        <div class="fs-6">
                            <p>Publisher:</p>
                            <p>Supplier:</p>
                            <p>Author:</p>
                            <p>Publish Year:</p>
                            <p>Book Layout:</p>
                        </div>
                        <div class="text-danger fw-bold fs-3">Price: $<?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                        <form method="POST">
                            <div class="d-flex align-items-center mt-4">
                                <input type="number" min="1" name="product_quantity" value="1" class="form-control" style="max-width:120px;">
                                <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg ms-2">Add to cart</button>
                                <button type="submit" name="buy_now" class="btn btn-danger btn-lg ms-4">Buy now</button>
                            </div>
                        </form>
                        <div class="mt-3">
                            <h2 class="fs-4">Description</h2>
                            <p>The annual The White Book is the most authoritative civil procedure resource in England & Wales.

                                The White Book contains the sources of law relating to the practice and procedures of the High Court and the County Court for the handling of civil litigation, subject to the Civil Procedure Rules (CPR), and is supplemented by substantial and comprehensive expert commentary. The White Book is relied upon in court by more judges and lawyers than any other legal text and is trusted for its authority and commentary.

                                The 2025 edition contains the amendments made to the Civil Procedure Rules (CPR) and related legislation which have come into force since publication of the last edition and includes the latest amending CPR SIs and PD Updates; updated Court Guides; and relevant Practice Notes and Guidance. All relevant commentary has been updated, together with recent and important case law, such as Al Sadeq v Dechert LLP [2024] EWCA Civ 28 (Pt 31 – legal professional privilege); Deutsche Bank AG v Sebastian Holdings (Limitation); Morris v Williams & Co Solicitors (A Firm) [2024] EWCA Civ 376 (Pt 19 – multiple claimants & claim forms); Solicitors Regulation Authority Ltd v Khan and Commercial Bank of Dubai PSC v Al Sari (Pt 81 – contempt of court); Dos Santos v Unitel SA [2024] EWCA Civ 1109 (Pt 25 – freezing injunctions); Parsdome Holdings Ltd v Plastic Energy Global SL [2024] EWCA Civ 1293 (Pt 25); and, from the Supreme Court, Re McAleenon, Application for Judicial Review (Northern Ireland) [2024] UKSC 31.

                                Included with The White Book 2025 is the 11th edition of Costs & Funding following the Civil Justice Reforms: Q&A, brought fully up to date with recent costs & funding developments, case law and new questions and answers.

                                The White Book, including supplements, is available in print, eBook powered by Thomson Reuters ProView</p>
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