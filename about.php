<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <!-- <link rel="stylesheet" href="css/style.css"> -->

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="bg-light py-4 mb-4">
      <div class="container">
         <h3 class="mb-1">About us</h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
               <li class="breadcrumb-item"><a href="home.php">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">About</li>
            </ol>
         </nav>
      </div>
   </div>

   <section class="container py-5">
      <div class="row align-items-center">
         <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="images/about-img.jpg" alt="" class="img-fluid rounded shadow">
         </div>
         <div class="col-lg-6">
            <h3 class="fw-bold mb-3">Why choose us?</h3>
            <p>We offer a carefully curated selection of books, fast and reliable delivery, and a passion for helping readers find their next favorite read. Your satisfaction is our top priority.</p>
            <p>With a user-friendly website and dedicated customer support, we make your book-buying experience easy, enjoyable, and trustworthy.</p>
            <a href="contact.php" class="btn btn-primary">Contact us</a>
         </div>
      </div>
   </section>

   <section class="container py-5">
      <h1 class="text-center text-uppercase mb-4">Client's reviews</h1>
      <div class="row g-4">
         <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow">
               <img src="images/pic-1.png" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <p class="card-text">I’ve been buying books online for years, but this bookstore has truly impressed me. The site is easy to navigate, the selection is excellent, and my order arrived faster than expected. The books were packaged carefully, and even included a small thank-you note — such a thoughtful touch! I’ll definitely be coming back for more.</p>
                  <div class="mb-2 text-warning">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold mb-0">Ethan Lee</h6>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow">
               <img src="images/pic-2.png" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <p class="card-text">As someone who reads a lot, I really appreciate how well-organized this store is. It was easy to find what I was looking for, and I even discovered a few new titles I hadn’t heard of. My only suggestion would be to add more options in the fantasy genre, but other than that, everything was smooth. Great service and good prices!</p>
                  <div class="mb-2 text-warning">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold mb-0">Elisa Poet</h6>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow">
               <img src="images/pic-3.png" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <p class="card-text">What really stood out to me was the customer support. I accidentally ordered the wrong book and contacted the store. They responded within an hour and helped me fix it right away. The book I needed was delivered on time, and they even followed up to make sure I was satisfied. That kind of service is rare these days!</p>
                  <div class="mb-2 text-warning">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold mb-0">Jim Rohn</h6>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow">
               <img src="images/pic-4.png" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <p class="card-text">I love everything about this bookstore — from the minimalist design of the website to the wide range of categories. I’m a parent and often buy books for my kids, and they always have great children’s options available. Plus, their educational and self-help sections are full of hidden gems.</p>
                  <div class="mb-2 text-warning">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold mb-0">Ariana Grande</h6>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow">
               <img src="images/pic-5.png" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <p class="card-text">Honestly, I was skeptical at first because I hadn’t heard of this site before. But I’m so glad I gave it a try. I ordered three books and they all arrived in great condition. The prices were better than what I found on bigger platforms, and I liked supporting a more personal business. I’ll definitely recommend it to friends.</p>
                  <div class="mb-2 text-warning">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold mb-0">Mark Manson</h6>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow">
               <img src="images/pic-6.png" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <p class="card-text">This bookstore has helped me fall in love with reading again. I used to struggle finding books that suited my taste, but their recommendations were spot on. The site feels curated and personal, not just another huge online retailer. I’ve already placed my second order and can’t wait for my next book haul!</p>
                  <div class="mb-2 text-warning">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h6 class="fw-bold mb-0">Thảo Ngọc</h6>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section class="container py-5">
      <h1 class="text-center text-uppercase mb-4">Great authors</h1>
      <div class="row g-4">
         <div class="col-md-4 col-lg-2">
            <div class="card h-100 text-center shadow">
               <img src="images/author-1.jpg" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <div class="mb-2 d-flex justify-content-center gap-2">
                     <a href="#" class="text-secondary"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-linkedin"></i></a>
                  </div>
                  <h6 class="fw-bold mb-0">Jordan Peterson</h6>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-lg-2">
            <div class="card h-100 text-center shadow">
               <img src="images/author-2.jpg" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <div class="mb-2 d-flex justify-content-center gap-2">
                     <a href="#" class="text-secondary"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-linkedin"></i></a>
                  </div>
                  <h6 class="fw-bold mb-0">Mel Robbins</h6>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-lg-2">
            <div class="card h-100 text-center shadow">
               <img src="images/author-3.jpg" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <div class="mb-2 d-flex justify-content-center gap-2">
                     <a href="#" class="text-secondary"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-linkedin"></i></a>
                  </div>
                  <h6 class="fw-bold mb-0">Joseph Nguyen</h6>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-lg-2">
            <div class="card h-100 text-center shadow">
               <img src="images/author-4.jpg" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <div class="mb-2 d-flex justify-content-center gap-2">
                     <a href="#" class="text-secondary"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-linkedin"></i></a>
                  </div>
                  <h6 class="fw-bold mb-0">The Present Writer</h6>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-lg-2">
            <div class="card h-100 text-center shadow">
               <img src="images/author-5.jpg" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <div class="mb-2 d-flex justify-content-center gap-2">
                     <a href="#" class="text-secondary"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-linkedin"></i></a>
                  </div>
                  <h6 class="fw-bold mb-0">Tim Vu</h6>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-lg-2">
            <div class="card h-100 text-center shadow">
               <img src="images/author-6.jpg" alt="" class="card-img-top" style="max-height:120px;object-fit:contain;">
               <div class="card-body">
                  <div class="mb-2 d-flex justify-content-center gap-2">
                     <a href="#" class="text-secondary"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                     <a href="#" class="text-secondary"><i class="fab fa-linkedin"></i></a>
                  </div>
                  <h6 class="fw-bold mb-0">Barbara Oakley</h6>
               </div>
            </div>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>