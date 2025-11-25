<?php
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/session_config.php';

// If no active session but remember cookie exists, attempt auto-login
if (empty($_SESSION['user_id']) && empty($_SESSION['admin_id']) && !empty($_COOKIE['remember_token'])) {
   $token = mysqli_real_escape_string($conn, $_COOKIE['remember_token']);
   $res = mysqli_query($conn, "SELECT * FROM `users` WHERE remember_token = '$token' LIMIT 1");
   if ($res && mysqli_num_rows($res) > 0) {
      $user = mysqli_fetch_assoc($res);
      if ($user['user_type'] === 'admin') {
         $_SESSION['admin_name']  = $user['name'];
         $_SESSION['admin_email'] = $user['email'];
         $_SESSION['admin_id']    = $user['id'];
      } else {
         $_SESSION['user_name']  = $user['name'];
         $_SESSION['user_email'] = $user['email'];
         $_SESSION['user_id']    = $user['id'];
      }
      // optionally refresh token/cookie here for rotation
   } else {
      // invalid token: clear cookie
      setcookie('remember_token', '', time() - 3600, '/');
   }
}

// ensure $user_id is defined for later use
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if (isset($message)) {
   foreach ($message as $msg) {
      echo '
     <div class="alert alert-info alert-dismissible fade show position-absolute top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index:1050; min-width:300px;">
       <span>' . $msg . '</span>
       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     </div>
     ';
   }
}
?>

<!-- Top bar with social links and login/register -->
<div class="bg-white border-bottom py-2">
   <div class="container d-flex justify-content-between align-items-center">
      <div>
         <a href="https://www.facebook.com/lechihieu17.04.2004/" class="text-secondary me-2" target="_blank"><i class="fab fa-facebook-f"></i></a>
         <a href="https://www.instagram.com/chihieu04/" class="text-secondary me-2" target="_blank"><i class="fab fa-twitter"></i></a>
         <a href="https://www.instagram.com/chihieu04/" class="text-secondary me-2" target="_blank"><i class="fab fa-instagram"></i></a>
         <a href="https://www.linkedin.com/in/hieu-le-chi-8b1040297/" class="text-secondary" target="_blank"><i class="fab fa-linkedin"></i></a>
      </div>
      <div class="d-flex align-items-center">
         <span class="fw-semibold text-success">GET FREE SHIPPING ON ORDERS ABOVE $250!</span>
         <a href="shop.php" class="btn btn-sm btn-primary ms-3">Shop now</a>
      </div>
   </div>
</div>

<!-- Main navbar -->
<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light shadow-sm">
   <div class="container">
      <a class="navbar-brand fw-bold" href="home.php">Bookly.</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavbar">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
               <a class="nav-link" href="home.php">
                  <i class="fas fa-home me-1"></i> Home
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="shop.php">
                  <i class="fas fa-book me-1"></i> Shop
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="about.php">
                  <i class="fas fa-info-circle me-1"></i> About
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="blog.php">
                  <i class="fa-solid fa-newspaper"></i> Blogs
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="contact.php">
                  <i class="fas fa-envelope me-1"></i> Contact
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="orders.php">
                  <i class="fas fa-box me-1"></i> Orders
               </a>
            </li>
         </ul>
         <div class="d-flex align-items-center gap-3">
            <a href="search_page.php" class="text-secondary"><i class="fas fa-search"></i></a>
            <a href="cart.php" class="text-secondary position-relative">
               <i class="fas fa-shopping-cart"></i>
               <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number);
               ?>
               <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <?php echo $cart_rows_number; ?>
               </span>
            </a>
            <div class="dropdown">
               <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-user"></i>
               </a>
               <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <li><span class="dropdown-item-text">Username: <strong><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?></strong></span></li>
                  <li><span class="dropdown-item-text">Email: <strong><?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?></strong></span></li>
                  <li>
                     <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</nav>