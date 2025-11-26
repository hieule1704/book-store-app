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
   // FEATURE 2: RECENTLY VIEWED (Save to Cookie)
   $viewed = $_COOKIE['viewed_products'] ?? '';
   $viewed_arr = array_filter(explode(',', $viewed));
   if (!in_array($product_id, $viewed_arr)) {
      array_unshift($viewed_arr, $product_id); // Add to beginning
      $viewed_arr = array_slice($viewed_arr, 0, 4); // Keep only last 4
      setcookie('viewed_products', implode(',', $viewed_arr), time() + (86400 * 30), "/");
   }

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

   <style>
      /* KEEP page-specific styles only (removed the shared :root and theme-toggle rules).
         Dark-mode variables and toggle are now in header.php so they're consistent site-wide. */

      /* Product Card Hover */
      .product-card {
         transition: transform 0.25s cubic-bezier(.4, 2, .6, 1), box-shadow 0.25s;
         will-change: transform;
         z-index: 1;
         position: relative;
         overflow: hidden;
         box-shadow: 0 4px 6px var(--card-shadow);
      }

      .product-card:hover {
         transform: scale(1.06) translateY(-8px);
         box-shadow: 0 10px 20px var(--card-shadow);
         z-index: 2;
      }

      /* Product Tags */
      .product-tag {
         position: absolute;
         top: 15px;
         left: 15px;
         padding: 5px 12px;
         border-radius: 20px;
         font-size: 0.75rem;
         font-weight: 700;
         text-transform: uppercase;
         color: white;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
         z-index: 10;
      }

      .tag-bestseller {
         background: linear-gradient(45deg, #ffc107, #ffca2c);
         color: #000;
      }

      .tag-new {
         background: linear-gradient(45deg, #198754, #20c997);
      }

      .tag-sale {
         background: linear-gradient(45deg, #dc3545, #f75667);
      }

      .tag-default {
         background: #6c757d;
      }

      /* Flash Sale Animation */
      @keyframes pulse-red {
         0% {
            transform: scale(1);
         }

         50% {
            transform: scale(1.05);
         }

         100% {
            transform: scale(1);
         }
      }

      .flash-sale-banner {
         background: linear-gradient(90deg, #dc3545 0%, #fd7e14 100%);
         color: white;
      }

      .countdown-box {
         background: rgba(0, 0, 0, 0.2);
         padding: 5px 15px;
         border-radius: 10px;
         font-family: 'Courier New', monospace;
         font-weight: bold;
         display: inline-block;
      }

      /* Theme toggle button styling removed from here; now centralized in header.php */
   </style>
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

   <!-- FLASH SALE BANNER -->
   <div class="flash-sale-banner py-3 text-center">
      <div class="container d-flex justify-content-center align-items-center gap-3 flex-wrap">
         <span class="fs-4 fw-bold"><i class="fas fa-bolt text-warning me-2"></i> FLASH SALE</span>
         <span class="fs-5">Ends in:</span>
         <span id="countdown" class="countdown-box fs-4">02:15:30</span>
         <span class="badge bg-warning text-dark ms-2">UP TO 50% OFF</span>
      </div>
   </div>

   <script>
      // Simple Countdown Script
      let time = (2 * 3600) + (15 * 60) + 30;

      setInterval(() => {
         let h = Math.floor(time / 3600);
         let m = Math.floor((time % 3600) / 60);
         let s = time % 60;

         h = h < 10 ? '0' + h : h;
         m = m < 10 ? '0' + m : m;
         s = s < 10 ? '0' + s : s;

         document.getElementById('countdown').innerText = `${h}:${m}:${s}`;

         if (time > 0) time--;
         else time = 7200;
      }, 1000);
   </script>

   <!-- Ribbon Carousel -->
   <section class="py-4" style="width:100vw; max-width:100vw; margin-left:calc(-50vw + 50%); background: var(--light-bg);">
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
         $select_products = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
            LEFT JOIN `author` a ON p.author_id = a.id
            LEFT JOIN `publisher` pub ON p.publisher_id = pub.id
            ORDER BY p.id DESC LIMIT 8") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {

               $tag = strtolower(trim($fetch_products['tag'] ?? ''));
               $tagClass = '';
               if (!empty($tag)) {
                  if (strpos($tag, 'bestseller') !== false) $tagClass = 'tag-bestseller';
                  elseif (strpos($tag, 'new') !== false) $tagClass = 'tag-new';
                  elseif (strpos($tag, 'sale') !== false) $tagClass = 'tag-sale';
                  else $tagClass = 'tag-default';
               }

               // FEATURE 1: STOCK DISPLAY LOGIC
               $stock = $fetch_products['stock_quantity'];
               $stockText = "";
               $stockClass = "";
               $disableBtn = "";

               if ($stock == 0) {
                  $stockText = "Out of Stock";
                  $stockClass = "text-danger";
                  $disableBtn = "disabled";
               } elseif ($stock < 5) {
                  $stockText = "Only $stock left!";
                  $stockClass = "text-warning fw-bold";
               } else {
                  $stockText = "In Stock ($stock)";
                  $stockClass = "text-success";
               }
         ?>
               <div class="col-md-3 col-sm-6 mb-4 align-items-stretch">
                  <form action="" method="post" class="card shadow product-card">

                     <?php if (!empty($tag)): ?>
                        <div class="product-tag <?php echo $tagClass; ?>">
                           <?php echo htmlspecialchars($fetch_products['tag']); ?>
                        </div>
                     <?php endif; ?>

                     <a href="detail.php?id=<?php echo htmlspecialchars($fetch_products['id']); ?>" class="bg-white d-flex justify-content-center align-items-center p-2" style="height: 250px;">
                        <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo htmlspecialchars($fetch_products['book_name']); ?>" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                     </a>
                     <div class="card-body d-flex flex-column text-center">
                        <div title="<?php echo htmlspecialchars($fetch_products['book_name']); ?>" class="mb-2 fw-bold fs-5 line-clamp-2"><?php echo htmlspecialchars($fetch_products['book_name']); ?></div>
                        <div class="mb-2 text-secondary small">
                           <span>
                              <i class="fa-solid fa-user"></i>
                              <a href="view_author.php?id=<?php echo $fetch_products['author_id']; ?>" class="text-decoration-none text-secondary">
                                 <?php echo htmlspecialchars($fetch_products['author_name']); ?>
                              </a>
                           </span>
                        </div>

                        <!-- STOCK DISPLAY -->
                        <div class="mb-2 small <?php echo $stockClass; ?>">
                           <i class="fas fa-box"></i> <?php echo $stockText; ?>
                        </div>

                        <div class="mb-2 text-danger fs-5 fw-bold">$<?php echo number_format($fetch_products['price'], 0, ',', '.'); ?></div>
                        <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['book_name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                        <input type="hidden" name="product_quantity" value="1">
                        <div class="mt-auto d-flex gap-2">
                           <button type="submit" name="add_to_cart" class="btn btn-primary w-50" <?php echo $disableBtn; ?>>Add</button>
                           <button type="submit" name="more_detail" class="btn btn-info w-50 text-white">View</button>
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

   <!-- FEATURE 2: RECENTLY VIEWED HISTORY -->
   <?php
   $viewed_ids = $_COOKIE['viewed_products'] ?? '';
   if (!empty($viewed_ids)):
      $viewed_products_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id IN ($viewed_ids)");
      if (mysqli_num_rows($viewed_products_query) > 0):
   ?>
         <section class="container py-5 bg-light rounded">
            <h3 class="mb-4 fw-bold"><i class="fas fa-history text-secondary"></i> Recently Viewed</h3>
            <div class="row g-3">
               <?php while ($v_product = mysqli_fetch_assoc($viewed_products_query)): ?>
                  <div class="col-6 col-md-3 col-lg-2">
                     <a href="detail.php?id=<?php echo $v_product['id']; ?>" class="card shadow-sm text-decoration-none text-dark h-100">
                        <img src="uploaded_img/<?php echo $v_product['image']; ?>" class="card-img-top p-2" style="height:120px; object-fit:contain;" alt="Book">
                        <div class="card-body p-2 text-center">
                           <small class="d-block text-truncate fw-bold"><?php echo htmlspecialchars($v_product['book_name']); ?></small>
                           <span class="text-danger fw-bold small">$<?php echo $v_product['price']; ?></span>
                        </div>
                     </a>
                  </div>
               <?php endwhile; ?>
            </div>
         </section>
   <?php endif;
   endif; ?>

   <!-- FEATURE 3: NEWSLETTER SUBSCRIPTION -->
   <section class="py-5 mt-5" style="background: linear-gradient(45deg, #4f46e5, #06b6d4);">
      <div class="container">
         <div class="row justify-content-center text-center text-white">
            <div class="col-lg-6">
               <h2 class="fw-bold mb-3"><i class="fas fa-envelope-open-text"></i> Join Our Newsletter</h2>
               <p class="mb-4">Subscribe to get the latest updates, flash sales, and exclusive offers delivered to your inbox.</p>
               <form id="newsletterForm" class="d-flex gap-2">
                  <input type="email" name="email" id="subEmail" class="form-control form-control-lg" placeholder="Enter your email address" required>
                  <button type="submit" class="btn btn-warning btn-lg fw-bold">Subscribe</button>
               </form>
               <div id="subMessage" class="mt-3 fw-bold"></div>
            </div>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

   <script>
      // FEATURE 3: AJAX NEWSLETTER
      document.getElementById('newsletterForm').addEventListener('submit', function(e) {
         e.preventDefault();
         const email = document.getElementById('subEmail').value;
         const msgDiv = document.getElementById('subMessage');

         const formData = new FormData();
         formData.append('email', email);

         fetch('subscribe.php', {
               method: 'POST',
               body: formData
            })
            .then(response => response.text())
            .then(data => {
               if (data.trim() === 'success') {
                  msgDiv.innerHTML = '<span class="text-white bg-success px-3 py-1 rounded">Thank you for subscribing!</span>';
                  document.getElementById('subEmail').value = '';
               } else {
                  msgDiv.innerHTML = '<span class="text-warning">' + data + '</span>';
               }
            });
      });

      // FEATURE 4: DARK MODE LOGIC
      function toggleTheme() {
         const body = document.body;
         const icon = document.getElementById('theme-icon');

         if (body.getAttribute('data-theme') === 'dark') {
            body.removeAttribute('data-theme');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            localStorage.setItem('theme', 'light');
         } else {
            body.setAttribute('data-theme', 'dark');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            localStorage.setItem('theme', 'dark');
         }
      }

      // Check saved theme on load
      if (localStorage.getItem('theme') === 'dark') {
         document.body.setAttribute('data-theme', 'dark');
         document.getElementById('theme-icon').classList.remove('fa-moon');
         document.getElementById('theme-icon').classList.add('fa-sun');
      }
   </script>

</body>


</html>