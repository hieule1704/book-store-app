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
   } else {
      setcookie('remember_token', '', time() - 3600, '/');
   }
}

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

<!-- SHARED STYLES FOR DARK MODE & HEADER -->
<style>
   /* 1. DEFINE VARIABLES */
   :root {
      --bg-color: #ffffff;
      --text-color: #212529;
      --card-bg: #ffffff;
      --light-bg: #f8f9fa;
      --border-color: #dee2e6;
      --nav-link-color: #555;
      --icon-color: #666;
   }

   [data-theme="dark"] {
      --bg-color: #121212;
      --text-color: #e0e0e0;
      --card-bg: #1e1e1e;
      --light-bg: #2c2c2c;
      --border-color: #444;
      --nav-link-color: #ccc;
      --icon-color: #ccc;
   }

   /* 2. APPLY VARIABLES */
   body {
      background-color: var(--bg-color);
      color: var(--text-color);
      transition: background-color 0.3s, color 0.3s;
   }

   .card {
      background-color: var(--card-bg);
      color: var(--text-color);
      border: 1px solid var(--border-color);
   }

   .bg-light {
      background-color: var(--light-bg) !important;
      color: var(--text-color) !important;
   }

   /* 3. HEADER SPECIFIC FIXES */
   .header-top {
      background-color: var(--card-bg);
      border-bottom: 1px solid var(--border-color) !important;
      transition: background-color 0.3s;
   }

   .header-top .social-icon {
      color: var(--icon-color) !important;
      transition: color 0.3s;
   }

   .header-top .social-icon:hover {
      color: #0d6efd !important;
      /* Primary color on hover */
   }

   .custom-navbar {
      background-color: var(--light-bg) !important;
      border-bottom: 1px solid var(--border-color);
      transition: background-color 0.3s;
   }

   .navbar-brand {
      color: var(--text-color) !important;
   }

   .nav-link {
      color: var(--nav-link-color) !important;
      transition: color 0.2s;
   }

   .nav-link:hover {
      color: #0d6efd !important;
   }

   .header-icon {
      color: var(--icon-color) !important;
   }

   .dropdown-menu {
      background-color: var(--card-bg);
      border-color: var(--border-color);
   }

   .dropdown-item-text,
   .dropdown-item {
      color: var(--text-color);
   }

   .dropdown-item:hover {
      background-color: var(--light-bg);
      color: var(--text-color);
   }

   /* Floating theme toggle */
   .theme-toggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 10000;
      background: var(--card-bg);
      border: 2px solid var(--text-color);
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
   }
</style>

<!-- Top bar with social links -->
<div class="header-top py-2">
   <div class="container d-flex justify-content-between align-items-center">
      <div>
         <a href="https://www.facebook.com" class="social-icon me-2" target="_blank"><i class="fab fa-facebook-f"></i></a>
         <a href="https://www.twitter.com" class="social-icon me-2" target="_blank"><i class="fab fa-twitter"></i></a>
         <a href="https://www.instagram.com" class="social-icon me-2" target="_blank"><i class="fab fa-instagram"></i></a>
         <a href="https://www.linkedin.com" class="social-icon" target="_blank"><i class="fab fa-linkedin"></i></a>
      </div>
      <div class="d-flex align-items-center">
         <?php if (isset($_SESSION['user_name'])): ?>
            <span class="small me-3" style="color: var(--text-color)">Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span>
         <?php else: ?>
            <a href="login.php" class="small me-3 text-decoration-none" style="color: var(--text-color)">Login</a>
            <a href="register.php" class="small me-3 text-decoration-none" style="color: var(--text-color)">Register</a>
         <?php endif; ?>
         <a href="shop.php" class="btn btn-sm btn-primary">Shop now</a>
      </div>
   </div>
</div>

<!-- Main navbar -->
<nav class="navbar sticky-top navbar-expand-lg custom-navbar shadow-sm">
   <div class="container">
      <a class="navbar-brand fw-bold" href="home.php">Bookly.</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
         style="background-color: var(--border-color);">
         <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNavbar">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
            <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
            <li class="nav-item"><a class="nav-link" href="blog.php">Blogs</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
         </ul>

         <div class="d-flex align-items-center gap-3">
            <a href="search_page.php" class="header-icon"><i class="fas fa-search"></i></a>
            <a href="cart.php" class="header-icon position-relative">
               <i class="fas fa-shopping-cart"></i>
               <?php
               $cart_rows_number = 0;
               if ($user_id) {
                  $select_cart_number = mysqli_query($conn, "SELECT id FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                  $cart_rows_number = mysqli_num_rows($select_cart_number);
               }
               ?>
               <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <?php echo $cart_rows_number; ?>
               </span>
            </a>

            <div class="dropdown">
               <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle header-icon" id="userDropdown" data-bs-toggle="dropdown">
                  <i class="fas fa-user"></i>
               </a>
               <ul class="dropdown-menu dropdown-menu-end">
                  <li><span class="dropdown-item-text">User: <strong><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?></strong></span></li>
                  <li><span class="dropdown-item-text small"><?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?></span></li>
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

<!-- Theme toggle script (Shared) -->
<div class="theme-toggle" id="themeToggle" title="Toggle theme">
   <i class="fas fa-moon" id="theme-icon" style="pointer-events:none; color: var(--text-color);"></i>
</div>

<script>
   (function() {
      var saved = localStorage.getItem('theme');
      if (saved === 'dark') document.body.setAttribute('data-theme', 'dark');

      var icon = document.getElementById('theme-icon');

      function updateIcon() {
         if (document.body.getAttribute('data-theme') === 'dark') {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
         } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
         }
      }
      updateIcon();

      document.getElementById('themeToggle').addEventListener('click', function() {
         if (document.body.getAttribute('data-theme') === 'dark') {
            document.body.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
         } else {
            document.body.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
         }
         updateIcon();
      });
   })();
</script>