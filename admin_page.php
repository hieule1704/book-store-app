<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin panel</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <!-- <link rel="stylesheet" href="css/admin_style.css"> -->

</head>

<body class="bg-light">

   <?php include 'admin_header.php'; ?>

   <!-- admin dashboard section starts  -->

   <section class="container my-5">
      <h1 class="text-center text-uppercase mb-4">Dashboard</h1>
      <div class="row g-4">
         <div class="col-md-3 col-sm-6">
            <div class="card text-center shadow">
               <div class="card-body">
                  <?php
                  $total_pendings = 0;
                  $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
                  if (mysqli_num_rows($select_pending) > 0) {
                     while ($fetch_pendings = mysqli_fetch_assoc($select_pending)) {
                        $total_price = $fetch_pendings['total_price'];
                        $total_pendings += $total_price;
                     };
                  };
                  ?>
                  <h3 class="card-title text-danger">$<?php echo $total_pendings; ?>/-</h3>
                  <p class="card-text text-secondary">total pendings</p>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="card text-center shadow">
               <div class="card-body">
                  <?php
                  $total_completed = 0;
                  $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
                  if (mysqli_num_rows($select_completed) > 0) {
                     while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
                        $total_price = $fetch_completed['total_price'];
                        $total_completed += $total_price;
                     };
                  };
                  ?>
                  <h3 class="card-title text-success">$<?php echo $total_completed; ?>/-</h3>
                  <p class="card-text text-secondary">completed payments</p>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="card text-center shadow">
               <div class="card-body">
                  <?php
                  $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                  $number_of_orders = mysqli_num_rows($select_orders);
                  ?>
                  <h3 class="card-title"><?php echo $number_of_orders; ?></h3>
                  <p class="card-text text-secondary">order placed</p>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="card text-center shadow">
               <div class="card-body">
                  <?php
                  $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                  $number_of_products = mysqli_num_rows($select_products);
                  ?>
                  <h3 class="card-title"><?php echo $number_of_products; ?></h3>
                  <p class="card-text text-secondary">products added</p>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="card text-center shadow">
               <div class="card-body">
                  <?php
                  $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
                  $number_of_users = mysqli_num_rows($select_users);
                  ?>
                  <h3 class="card-title"><?php echo $number_of_users; ?></h3>
                  <p class="card-text text-secondary">normal users</p>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="card text-center shadow">
               <div class="card-body">
                  <?php
                  $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
                  $number_of_admins = mysqli_num_rows($select_admins);
                  ?>
                  <h3 class="card-title"><?php echo $number_of_admins; ?></h3>
                  <p class="card-text text-secondary">admin users</p>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="card text-center shadow">
               <div class="card-body">
                  <?php
                  $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                  $number_of_account = mysqli_num_rows($select_account);
                  ?>
                  <h3 class="card-title"><?php echo $number_of_account; ?></h3>
                  <p class="card-text text-secondary">total accounts</p>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="card text-center shadow">
               <div class="card-body">
                  <?php
                  $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                  $number_of_messages = mysqli_num_rows($select_messages);
                  ?>
                  <h3 class="card-title"><?php echo $number_of_messages; ?></h3>
                  <p class="card-text text-secondary">new messages</p>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- admin dashboard section ends -->

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>