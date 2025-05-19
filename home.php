<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}


if (isset($_POST['add_to_cart'])) {

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Check if product already in cart for this user
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');


   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart!';
   } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }
}

if (isset($_POST['more_detail'])) {
   $product_id = $_POST['product_id'];
   header("Location: detail.php?id=$product_id");
   exit();
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
   <link rel="stylesheet" href="style.css">

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

   <!-- Featured Book Ribbon Carousel (Images Only) -->
   <section class="py-4" style="width:100vw; max-width:100vw; margin-left:calc(-50vw + 50%); background: #f8f9fa;">
      <div class="container-fluid px-0">
         <div id="bookRibbonCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
               <div class="carousel-item active">
                  <div class="d-flex justify-content-center align-items-center" style="height: 480px;">
                     <img src="other_resource/ribbon1.jpg" class="rounded-4 shadow-lg border border-4 border-primary"
                        style="height:440px; width:90vw; max-width:1600px; object-fit:cover; background:#fff;" alt="Featured Book 1">
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="d-flex justify-content-center align-items-center" style="height: 480px;">
                     <img src="other_resource/ribbon2.jpg" class="rounded-4 shadow-lg border border-4 border-warning"
                        style="height:440px; width:90vw; max-width:1600px; object-fit:cover; background:#fff;" alt="Featured Book 2">
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="d-flex justify-content-center align-items-center" style="height: 480px;">
                     <img src="other_resource/ribbon3.jpg" class="rounded-4 shadow-lg border border-4 border-success"
                        style="height:440px; width:90vw; max-width:1600px; object-fit:cover; background:#fff;" alt="Featured Book 3">
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="d-flex justify-content-center align-items-center" style="height: 480px;">
                     <img src="other_resource/ribbon4.jpg" class="rounded-4 shadow-lg border border-4 border-danger"
                        style="height:440px; width:90vw; max-width:1600px; object-fit:cover; background:#fff;" alt="Featured Book 4">
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="d-flex justify-content-center align-items-center" style="height: 480px;">
                     <img src="other_resource/ribbon5.jpg" class="rounded-4 shadow-lg border border-4 border-info"
                        style="height:440px; width:90vw; max-width:1600px; object-fit:cover; background:#fff;" alt="Featured Book 5">
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="d-flex justify-content-center align-items-center" style="height: 480px;">
                     <img src="other_resource/ribbon6.jpg" class="rounded-4 shadow-lg border border-4 border-primary"
                        style="height:440px; width:90vw; max-width:1600px; object-fit:cover; background:#fff;" alt="Featured Book 6">
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="d-flex justify-content-center align-items-center" style="height: 480px;">
                     <img src="other_resource/ribbon7.jpg" class="rounded-4 shadow-lg border border-4 border-warning"
                        style="height:440px; width:90vw; max-width:1600px; object-fit:cover; background:#fff;" alt="Featured Book 7">
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="d-flex justify-content-center align-items-center" style="height: 480px;">
                     <img src="other_resource/ribbon8.jpg" class="rounded-4 shadow-lg border border-4 border-success"
                        style="height:440px; width:90vw; max-width:1600px; object-fit:cover; background:#fff;" alt="Featured Book 8">
                  </div>
               </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bookRibbonCarousel" data-bs-slide="prev">
               <span class="carousel-control-prev-icon bg-primary rounded-circle" aria-hidden="true"></span>
               <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bookRibbonCarousel" data-bs-slide="next">
               <span class="carousel-control-next-icon bg-primary rounded-circle" aria-hidden="true"></span>
               <span class="visually-hidden">Next</span>
            </button>
         </div>
      </div>
   </section>

   <!-- Latest Products Section -->
   <section class="container py-5">
      <h1 class="text-center text-uppercase mb-4">Latest products</h1>
      <div class="d-flex g-4 row">
         <?php
         // Updated query to use new products table structure
         $select_products = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
            LEFT JOIN `author` a ON p.author_id = a.id
            LEFT JOIN `publisher` pub ON p.publisher_id = pub.id
            ORDER BY p.id DESC LIMIT 8") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <div class="col-md-3 col-sm-6 mb-4 align-items-stretch">
                  <form action="" method="post" class="card shadow">
                     <a href="detail.php?id=<?php echo htmlspecialchars($fetch_products['id']); ?>" class="bg-white d-flex justify-content-center align-items-center p-2" style="height: 250px;">
                        <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo htmlspecialchars($fetch_products['book_name']); ?>" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                     </a>
                     <div class="card-body d-flex flex-column text-center">
                        <div title="<?php echo htmlspecialchars($fetch_products['book_name']); ?>" class="mb-2 fw-bold fs-5 line-clamp-2"><?php echo htmlspecialchars($fetch_products['book_name']); ?></div>
                        <div class="mb-2 text-secondary small">
                           <span><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($fetch_products['author_name']); ?></span>
                           <span class="ms-2"><i class="fa-solid fa-building"></i> <?php echo htmlspecialchars($fetch_products['publisher_name']); ?></span>
                        </div>
                        <div class="mb-2 text-danger fs-5 fw-bold">$<?php echo number_format($fetch_products['price'], 0, ',', '.'); ?></div>
                        <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['book_name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                        <input type="hidden" name="product_quantity" value="1">
                        <div class="mt-auto d-flex gap-2">
                           <button type="submit" name="add_to_cart" class="btn btn-primary w-50">Add to cart</button>
                           <button type="submit" name="buy_now" formaction="checkout.php" formmethod="post" class="btn btn-success w-50">Buy now</button>
                        </div>
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

   <!-- Best seller Section -->
   <section class="container py-5">
      <h1 class="text-center text-uppercase mb-4">Best seller</h1>
      <div class="row g-4">
         <?php
         // Updated query to use new products table structure
         $select_products = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
            LEFT JOIN `author` a ON p.author_id = a.id
            LEFT JOIN `publisher` pub ON p.publisher_id = pub.id
            WHERE tag = 'bestseller' LIMIT 8") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <div class="col-md-3 col-sm-6 mb-4 align-items-stretch">
                  <form action="" method="post" class="card shadow">
                     <a href="detail.php?id=<?php echo htmlspecialchars($fetch_products['id']); ?>" class="bg-white d-flex justify-content-center align-items-center p-2" style="height: 250px;">
                        <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo htmlspecialchars($fetch_products['book_name']); ?>" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                     </a>
                     <div class="card-body d-flex flex-column text-center">
                        <div title="<?php echo htmlspecialchars($fetch_products['book_name']); ?>" class="mb-2 fw-bold fs-5 line-clamp-2"><?php echo htmlspecialchars($fetch_products['book_name']); ?></div>
                        <div class="mb-2 text-secondary small">
                           <span><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($fetch_products['author_name']); ?></span>
                           <span class="ms-2"><i class="fa-solid fa-building"></i> <?php echo htmlspecialchars($fetch_products['publisher_name']); ?></span>
                        </div>
                        <div class="mb-2 text-danger fs-5 fw-bold">$<?php echo number_format($fetch_products['price'], 0, ',', '.'); ?></div>
                        <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['book_name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                        <input type="hidden" name="product_quantity" value="1">
                        <div class="mt-auto d-flex gap-2">
                           <button type="submit" name="add_to_cart" class="btn btn-primary w-50">Add to cart</button>
                           <button type="submit" name="buy_now" formaction="checkout.php" formmethod="post" class="btn btn-success w-50">Buy now</button>
                        </div>
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