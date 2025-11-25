<?php

include 'config.php';

// remove direct session_start(); and include the secure session config instead
// replace direct session_start() with secure session config
include_once __DIR__ . '/session_config.php';

// safe session read
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
   header('Location: login.php');
   exit;
}

if (isset($_POST['send'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if (mysqli_num_rows($select_message) > 0) {
      $message[] = 'message sent already!';
   } else {
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'message sent successfully!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="bg-light py-4 mb-4">
      <div class="container">
         <h3 class="mb-1">Contact us</h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
               <li class="breadcrumb-item"><a href="home.php">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Contact</li>
            </ol>
         </nav>
      </div>
   </div>

   <section class="container py-5">
      <div class="row justify-content-center">
         <div class="col-lg-6">
            <div class="card shadow">
               <div class="card-body">
                  <h3 class="mb-4 text-center text-uppercase">Say something!</h3>
                  <form action="" method="post">
                     <div class="mb-3">
                        <input type="text" name="name" required placeholder="Enter your name" class="form-control">
                     </div>
                     <div class="mb-3">
                        <input type="email" name="email" required placeholder="Enter your email" class="form-control">
                     </div>
                     <div class="mb-3">
                        <input type="number" name="number" required placeholder="Enter your number" class="form-control">
                     </div>
                     <div class="mb-3">
                        <textarea name="message" class="form-control" placeholder="Enter your message" rows="5"></textarea>
                     </div>
                     <div class="d-grid">
                        <input type="submit" value="Send message" name="send" class="btn btn-primary">
                     </div>
                  </form>
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