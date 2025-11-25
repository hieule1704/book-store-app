<?php

include 'config.php';
// Include the secure session config
include_once __DIR__ . '/session_config.php';

if (isset($_POST['submit'])) {

   // 1. Sanitize inputs FIRST
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $remember = isset($_POST['remember']);

   // 2. RUN THE QUERY (This was missing/out of order in your code)
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   // 3. NOW check the rows
   if (mysqli_num_rows($select_users) > 0) {

      $row = mysqli_fetch_assoc($select_users);

      // STRICT CHECK: If user is NOT verified (0), block them.
      if ($row['is_verified'] != 1) {
         $message[] = 'Account not verified! Please check your email.';
      } else {
         // --- LOGIN SUCCESS LOGIC ---
         if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];

            if ($remember) {
               $token = bin2hex(random_bytes(16));
               $token_esc = mysqli_real_escape_string($conn, $token);
               mysqli_query($conn, "UPDATE `users` SET remember_token = '$token_esc' WHERE id = {$row['id']}");
               setcookie('remember_token', $token, time() + (86400 * 30), "/");
            }
            header('location:admin_page.php');
            exit;
         } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];

            if ($remember) {
               $token = bin2hex(random_bytes(16));
               $token_esc = mysqli_real_escape_string($conn, $token);
               mysqli_query($conn, "UPDATE `users` SET remember_token = '$token_esc' WHERE id = {$row['id']}");
               setcookie('remember_token', $token, time() + (86400 * 30), "/");
            }
            header('location:home.php');
            exit;
         }
      }
   } else {
      $message[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light" style="background: linear-gradient(135deg, #e0e7ff 0%, #fff 100%); min-height:100vh;">

   <style>
      .fade-in {
         animation: fadeIn 1s ease;
      }

      @keyframes fadeIn {
         from {
            opacity: 0;
            transform: translateY(30px);
         }

         to {
            opacity: 1;
            transform: translateY(0);
         }
      }

      .login-card {
         border-radius: 1.5rem;
         box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
         background: rgba(255, 255, 255, 0.95);
      }

      .login-title {
         font-weight: 700;
         color: #2d3a8c;
      }

      .desc {
         color: #6b7280;
         font-size: 1rem;
         margin-bottom: 1.5rem;
      }

      .form-floating label {
         color: #6b7280;
      }

      .form-floating input:focus~label {
         color: #2d3a8c;
      }
   </style>

   <?php
   if (isset($message)) {
      foreach ($message as $msg) {
         echo '
         <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index:1050; min-width:300px;">
            <span>' . $msg . '</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
      }
   }
   ?>

   <div class="container d-flex align-items-center justify-content-center min-vh-100">
      <div class="login-card card p-4 fade-in" style="max-width: 400px; width: 100%;">
         <div class="text-center mb-3">
            <img src="https://cdn-icons-png.flaticon.com/512/5087/5087579.png" alt="Login" width="64" class="mb-2">
            <h3 class="login-title mb-1">Welcome Back!</h3>
            <div class="desc">Sign in to continue shopping.</div>
         </div>
         <form action="" method="post">
            <div class="form-floating mb-3">
               <input type="email" name="email" id="loginEmail" placeholder="Enter your email" required class="form-control">
               <label for="loginEmail">Email address</label>
            </div>
            <div class="form-floating mb-3">
               <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required class="form-control">
               <label for="loginPassword">Password</label>
            </div>
            <div class="form-check mb-3">
               <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
               <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <div class="d-grid mb-3">
               <input type="submit" name="submit" value="Login now" class="btn btn-primary btn-lg">
            </div>
            <div class="d-grid mb-3">
               <a href="google_login.php" class="btn btn-danger btn-lg"><i class="fab fa-google me-2"></i> Login with Google</a>
            </div>
            <p class="text-center mb-0">Don't have an account? <a href="register.php">Register now</a></p>
         </form>
      </div>
   </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>