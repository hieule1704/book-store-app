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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <!-- <link rel="stylesheet" href="css/style.css"> -->

</head>

<body class="bg-light">

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
      <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
         <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <h3 class="mb-4 text-center text-uppercase">Register now</h3>
            <div class="mb-3">
               <input type="text" name="name" placeholder="Enter your name" required class="form-control">
            </div>
            <div class="mb-3">
               <input type="email" name="email" placeholder="Enter your email" required class="form-control">
            </div>
            <div class="mb-3">
               <input type="password" name="password" placeholder="Enter your password" required class="form-control">
            </div>
            <div class="mb-3">
               <input type="password" name="cpassword" placeholder="Confirm your password" required class="form-control">
            </div>
            <div class="d-grid mb-3">
               <input type="submit" name="submit" value="Register now" class="btn btn-primary">
            </div>
            <p class="text-center mb-0">Already have an account? <a href="login.php">Login now</a></p>
         </form>
      </div>
   </div>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>