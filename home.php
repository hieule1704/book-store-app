<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart!';
   } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <!-- <link rel="stylesheet" href="css/style.css"> -->

</head>

<body>

   <?php include 'header.php'; ?>

   <!-- Hero Section -->
   <section class="bg-light py-5">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-7">
               <h3 class="display-5 fw-bold mb-3">Hand Picked Book to your door.</h3>
               <p class="lead mb-4">Discover a curated selection of books delivered straight to your door. From timeless classics to modern must-reads, we bring the joy of reading to you.</p>
               <a href="about.php" class="btn btn-primary btn-lg">Discover more</a>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
               <img src="images/about-img.jpg" alt="Books" class="img-fluid rounded shadow">
            </div>
         </div>
      </div>
   </section>

   <!-- Latest Products Section -->
   <section class="container py-5">
      <h1 class="text-center text-uppercase mb-4">Latest products</h1>
      <div class="row g-4">
         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <div class="col-md-4 col-sm-6">
                  <form action="" method="post" class="card h-100 shadow">
                     <img class="card-img-top" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                     <div class="card-body d-flex flex-column">
                        <div class="mb-2 fw-bold fs-5"><?php echo $fetch_products['name']; ?></div>
                        <div class="mb-2 text-danger fw-bold">$<?php echo $fetch_products['price']; ?>/-</div>
                        <input type="number" min="1" name="product_quantity" value="1" class="form-control mb-2" style="max-width:120px;">
                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-primary mt-auto">Add to cart</button>
                     </div>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-info text-center">No products added yet!</div></div>';
         }
         ?>
      </div>
      <div class="text-center mt-4">
         <a href="shop.php" class="btn btn-warning">Load more</a>
      </div>
   </section>

   <!-- About Section -->
   <section class="container py-5">
      <div class="row align-items-center">
         <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="images/about-img.jpg" alt="" class="img-fluid rounded shadow">
         </div>
         <div class="col-lg-6">
            <h3 class="fw-bold mb-3">About us</h3>
            <p>We are a passionate online bookstore dedicated to bringing knowledge and inspiration to readers. Every book is carefully selected to spark curiosity and enrich your reading experience.</p>
            <p>Join us on this literary journey and discover the joy of reading. Whether you're a lifelong book lover or just starting your reading adventure, we have something special for you.</p>
            <a href="about.php" class="btn btn-primary">Read more</a>
         </div>
      </div>
   </section>

   <!-- Contact Section -->
   <section class="bg-light py-5">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
               <h3 class="fw-bold mb-3">Have any questions?</h3>
               <p class="mb-4">We're here to help! If you have any questions about our books, your order, or anything else, feel free to reach out to us anytime.</p>
               <a href="contact.php" class="btn btn-outline-primary btn-lg">Contact us</a>
            </div>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>