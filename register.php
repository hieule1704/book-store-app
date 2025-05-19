<?php

include 'config.php';

if (isset($_POST['submit'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $user_type = 'user'; // Always set as 'user'

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('Query failed: ' . mysqli_error($conn));

   // $message use to annouce the result of the process
   if (mysqli_num_rows($select_users) > 0) {
      $message[] = 'user already exist!';
   } else {
      if ($pass != $cpass) {
         $message[] = 'confirm password not matched!';
      } else {
         mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$cpass', '$user_type')") or die('Query failed: ' . mysqli_error($conn));
         $message[] = 'registered successfully!';
         header('location:login.php');
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <!-- <link rel="stylesheet" href="css/style.css"> -->
   <!-- <link rel="stylesheet" href="css/style.css"> -->

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

      .register-card {
         border-radius: 1.5rem;
         box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
         background: rgba(255, 255, 255, 0.95);
      }

      .register-title {
         font-weight: 700;
         letter-spacing: 1px;
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
   <div class="alert alert-warning alert-dismissible fade show position-absolute top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index:1050; min-width:300px;">
      <span>' . $msg . '</span>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>
   ';
      }
   }
   ?>

   <div class="container d-flex align-items-center justify-content-center min-vh-100">
      <div class="register-card card p-4 fade-in" style="max-width: 400px; width: 100%;">
         <div class="text-center mb-3">
            <img src="https://cdn-icons-png.flaticon.com/512/5087/5087579.png" alt="Register" width="64" class="mb-2" style="filter: drop-shadow(0 2px 8px #a5b4fc);">
            <h3 class="register-title mb-1">Create Account</h3>
            <div class="desc">Register to start shopping, track your orders, and enjoy exclusive deals!</div>
         </div>
         <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-floating mb-3">
               <input type="text" name="name" id="registerName" placeholder="Enter your name" required class="form-control">
               <label for="registerName">Full Name</label>
            </div>
            <div class="form-floating mb-3">
               <input type="email" name="email" id="registerEmail" placeholder="Enter your email" required class="form-control">
               <label for="registerEmail">Email address</label>
            </div>
            <div class="form-floating mb-3">
               <input type="password" name="password" id="registerPassword" placeholder="Enter your password" required class="form-control">
               <label for="registerPassword">Password</label>
            </div>
            <div class="form-floating mb-3">
               <input type="password" name="cpassword" id="registerCPassword" placeholder="Confirm your password" required class="form-control">
               <label for="registerCPassword">Confirm Password</label>
            </div>
            <div class="d-grid mb-3">
               <input type="submit" name="submit" value="Register now" class="btn btn-primary btn-lg">
            </div>
            <p class="text-center mb-0">Already have an account? <a href="login.php">Login now</a></p>
         </form>
      </div>
   </div>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>