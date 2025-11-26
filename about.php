<?php

include 'config.php';

// replace direct session_start() with secure session config
include_once __DIR__ . '/session_config.php';

// safe session read
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
   header('Location: login.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">

   <style>
      .feature-icon {
         width: 60px;
         height: 60px;
         background: rgba(13, 110, 253, 0.1);
         color: #0d6efd;
         display: flex;
         align-items: center;
         justify-content: center;
         border-radius: 50%;
         font-size: 1.5rem;
         margin-bottom: 1rem;
      }

      .review-card {
         transition: transform 0.3s;
         border: 1px solid rgba(0, 0, 0, 0.05);
      }

      .review-card:hover {
         transform: translateY(-5px);
         box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
      }

      .stat-box {
         border-right: 1px solid #dee2e6;
      }

      .stat-box:last-child {
         border-right: none;
      }
   </style>

</head>

<body class="bg-light">

   <?php include 'header.php'; ?>

   <!-- Header Banner -->
   <div class="bg-dark py-5 text-white text-center" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('images/about-bg.jpg') center/cover;">
      <div class="container">
         <h1 class="fw-bold display-5">Our Story</h1>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
               <li class="breadcrumb-item"><a href="home.php" class="text-white-50 text-decoration-none">Home</a></li>
               <li class="breadcrumb-item active text-white" aria-current="page">About</li>
            </ol>
         </nav>
      </div>
   </div>

   <!-- About Section -->
   <section class="container py-5">
      <div class="row align-items-center g-5">
         <div class="col-lg-6">
            <div class="position-relative">
               <img src="images/about-img.jpg" alt="About us" class="img-fluid rounded-4 shadow-lg w-100">
               <div class="position-absolute bottom-0 end-0 bg-white p-4 rounded-top-4 shadow d-none d-md-block" style="margin-right: -20px; margin-bottom: -20px;">
                  <h2 class="fw-bold text-primary mb-0">10+</h2>
                  <small class="text-muted">Years Experience</small>
               </div>
            </div>
         </div>
         <div class="col-lg-6">
            <h6 class="text-primary fw-bold text-uppercase">Who We Are</h6>
            <h2 class="fw-bold mb-4 display-6">We Provide the Best Books for You to Read.</h2>
            <p class="lead text-muted mb-4">We believe that books have the power to change lives. Our mission is to make knowledge and stories accessible to everyone, everywhere.</p>

            <div class="row g-4 mb-4">
               <div class="col-sm-6">
                  <div class="d-flex align-items-start">
                     <i class="fas fa-check-circle text-success fs-4 me-3 mt-1"></i>
                     <div>
                        <h6 class="fw-bold mb-1">Curated Selection</h6>
                        <p class="small text-muted mb-0">Only the best titles make it to our shelves.</p>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="d-flex align-items-start">
                     <i class="fas fa-check-circle text-success fs-4 me-3 mt-1"></i>
                     <div>
                        <h6 class="fw-bold mb-1">Fast Delivery</h6>
                        <p class="small text-muted mb-0">Get your books delivered in record time.</p>
                     </div>
                  </div>
               </div>
            </div>
            <a href="contact.php" class="btn btn-primary btn-lg px-4 rounded-pill">Get in Touch</a>
         </div>
      </div>
   </section>

   <!-- Stats Section -->
   <section class="bg-white py-5 border-top border-bottom">
      <div class="container">
         <div class="row text-center g-4">
            <div class="col-md-3 col-6 stat-box">
               <h2 class="fw-bold text-primary">15k+</h2>
               <p class="text-muted mb-0">Books Sold</p>
            </div>
            <div class="col-md-3 col-6 stat-box">
               <h2 class="fw-bold text-primary">12k+</h2>
               <p class="text-muted mb-0">Happy Readers</p>
            </div>
            <div class="col-md-3 col-6 stat-box">
               <h2 class="fw-bold text-primary">500+</h2>
               <p class="text-muted mb-0">Authors</p>
            </div>
            <div class="col-md-3 col-6 stat-box">
               <h2 class="fw-bold text-primary">50+</h2>
               <p class="text-muted mb-0">Awards Won</p>
            </div>
         </div>
      </div>
   </section>

   <!-- Features Section -->
   <section class="container py-5">
      <div class="row g-4 text-center">
         <div class="col-md-4">
            <div class="p-4 bg-white rounded shadow-sm h-100">
               <div class="feature-icon mx-auto"><i class="fas fa-truck"></i></div>
               <h5 class="fw-bold">Free Shipping</h5>
               <p class="text-muted small">Order over $250 and get free shipping anywhere in the country.</p>
            </div>
         </div>
         <div class="col-md-4">
            <div class="p-4 bg-white rounded shadow-sm h-100">
               <div class="feature-icon mx-auto"><i class="fas fa-lock"></i></div>
               <h5 class="fw-bold">Secure Payment</h5>
               <p class="text-muted small">100% secure payment with SSL encryption and trusted gateways.</p>
            </div>
         </div>
         <div class="col-md-4">
            <div class="p-4 bg-white rounded shadow-sm h-100">
               <div class="feature-icon mx-auto"><i class="fas fa-headset"></i></div>
               <h5 class="fw-bold">24/7 Support</h5>
               <p class="text-muted small">Our dedicated support team is here to help you anytime.</p>
            </div>
         </div>
      </div>
   </section>

   <!-- Client Reviews Section -->
   <section class="container py-5 mb-5">
      <div class="text-center mb-5">
         <h6 class="text-primary fw-bold text-uppercase">Testimonials</h6>
         <h2 class="fw-bold">What Our Readers Say</h2>
      </div>
      <div class="row g-4">
         <?php
         $reviews = [
            [
               "img" => "images/pic-1.png",
               "text" => "The selection is excellent, and my order arrived faster than expected. The books were packaged carefully.",
               "name" => "Ethan Lee"
            ],
            [
               "img" => "images/pic-2.png",
               "text" => "I discovered a few new titles I hadn’t heard of. Great service and good prices! Highly recommended.",
               "name" => "Elisa Poet"
            ],
            [
               "img" => "images/pic-3.png",
               "text" => "Customer support responded within an hour and helped me fix my order right away. Rare service these days!",
               "name" => "Jim Rohn"
            ]
         ];
         foreach ($reviews as $review) {
         ?>
            <div class="col-md-4">
               <div class="card h-100 border-0 shadow-sm review-card p-3 text-center">
                  <div class="card-body">
                     <div class="mb-3 text-warning">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                     </div>
                     <p class="card-text text-muted fst-italic">"<?php echo $review['text']; ?>"</p>
                     <div class="d-flex justify-content-center align-items-center mt-4">
                        <img src="<?php echo $review['img']; ?>" class="rounded-circle shadow-sm me-3" width="50" height="50">
                        <div class="text-start">
                           <h6 class="fw-bold mb-0"><?php echo $review['name']; ?></h6>
                           <small class="text-muted">Verified Buyer</small>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         <?php } ?>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>