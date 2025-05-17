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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>About us</h3>
      <p> <a href="home.php">Home</a> / About </p>
   </div>

   <section class="about">

      <div class="flex">

         <div class="image">
            <img src="images/about-img.jpg" alt="">
         </div>

         <div class="content">
            <h3>why choose us?</h3>
            <p>We offer a carefully curated selection of books, fast and reliable delivery, and a passion for helping readers find their next favorite read. Your satisfaction is our top priority.</p>
            <p>With a user-friendly website and dedicated customer support, we make your book-buying experience easy, enjoyable, and trustworthy.</p>
            <a href="contact.php" class="btn">contact us</a>
         </div>

      </div>

   </section>

   <section class="reviews">

      <h1 class="title">Client's reviews</h1>

      <div class="box-container">

         <div class="box">
            <img src="images/pic-1.png" alt="">
            <p>I’ve been buying books online for years, but this bookstore has truly impressed me. The site is easy to navigate, the selection is excellent, and my order arrived faster than expected. The books were packaged carefully, and even included a small thank-you note — such a thoughtful touch! I’ll definitely be coming back for more.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Ethan Lee</h3>
         </div>

         <div class="box">
            <img src="images/pic-2.png" alt="">
            <p>As someone who reads a lot, I really appreciate how well-organized this store is. It was easy to find what I was looking for, and I even discovered a few new titles I hadn’t heard of. My only suggestion would be to add more options in the fantasy genre, but other than that, everything was smooth. Great service and good prices!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Elisa Poet</h3>
         </div>

         <div class="box">
            <img src="images/pic-3.png" alt="">
            <p>What really stood out to me was the customer support. I accidentally ordered the wrong book and contacted the store. They responded within an hour and helped me fix it right away. The book I needed was delivered on time, and they even followed up to make sure I was satisfied. That kind of service is rare these days!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Jim Rohn</h3>
         </div>

         <div class="box">
            <img src="images/pic-4.png" alt="">
            <p>I love everything about this bookstore — from the minimalist design of the website to the wide range of categories. I’m a parent and often buy books for my kids, and they always have great children’s options available. Plus, their educational and self-help sections are full of hidden gems.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Ariana Grande</h3>
         </div>

         <div class="box">
            <img src="images/pic-5.png" alt="">
            <p>Honestly, I was skeptical at first because I hadn’t heard of this site before. But I’m so glad I gave it a try. I ordered three books and they all arrived in great condition. The prices were better than what I found on bigger platforms, and I liked supporting a more personal business. I’ll definitely recommend it to friends.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Mark Manson</h3>
         </div>

         <div class="box">
            <img src="images/pic-6.png" alt="">
            <p>This bookstore has helped me fall in love with reading again. I used to struggle finding books that suited my taste, but their recommendations were spot on. The site feels curated and personal, not just another huge online retailer. I’ve already placed my second order and can’t wait for my next book haul!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Thảo Ngọc</h3>
         </div>

      </div>

   </section>

   <section class="authors">

      <h1 class="title">Greate authors</h1>

      <div class="box-container">

         <div class="box">
            <img src="images/author-1.jpg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Jordan Peterson</h3>
         </div>

         <div class="box">
            <img src="images/author-2.jpg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Mel Robbins</h3>
         </div>

         <div class="box">
            <img src="images/author-3.jpg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Joseph Nguyen</h3>
         </div>

         <div class="box">
            <img src="images/author-4.jpg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>The Present Writer</h3>
         </div>

         <div class="box">
            <img src="images/author-5.jpg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Tim Vu</h3>
         </div>

         <div class="box">
            <img src="images/author-6.jpg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Barbara Oakley</h3>
         </div>

      </div>

   </section>







   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>