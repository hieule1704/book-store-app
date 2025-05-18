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
   <title>Admin Dashboard</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <style>
      body {
         background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
         min-height: 100vh;
      }

      .dashboard-card {
         border: none;
         border-radius: 1rem;
         transition: transform 0.2s, box-shadow 0.2s;
         box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
         background: #fff;
      }

      .dashboard-card:hover {
         transform: translateY(-6px) scale(1.03);
         box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
      }

      .dashboard-icon {
         font-size: 2.5rem;
         margin-bottom: 0.5rem;
         color: #6366f1;
      }

      .dashboard-title {
         font-size: 1.1rem;
         font-weight: 600;
         color: #374151;
      }

      .dashboard-value {
         font-size: 2rem;
         font-weight: 700;
         color: #111827;
      }

      .dashboard-section {
         margin-top: 2rem;
      }

      .dashboard-header {
         letter-spacing: 2px;
         font-weight: 800;
         color: #4f46e5;
         margin-bottom: 2rem;
      }

      @media (max-width: 767px) {
         .dashboard-card {
            margin-bottom: 1.5rem;
         }
      }
   </style>
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="container dashboard-section">
      <h1 class="text-center dashboard-header text-uppercase">Admin Dashboard</h1>
      <div class="row g-4 justify-content-center">

         <!-- Pending Payments -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon text-danger"><i class="fas fa-hourglass-half"></i></div>
                  <?php
                  $total_pendings = 0;
                  $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
                  if (mysqli_num_rows($select_pending) > 0) {
                     while ($fetch_pendings = mysqli_fetch_assoc($select_pending)) {
                        $total_pendings += $fetch_pendings['total_price'];
                     }
                  }
                  ?>
                  <div class="dashboard-value text-danger">$<?php echo $total_pendings; ?></div>
                  <div class="dashboard-title">Pending Payments</div>
               </div>
            </div>
         </div>

         <!-- Completed Payments -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon text-success"><i class="fas fa-check-circle"></i></div>
                  <?php
                  $total_completed = 0;
                  $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
                  if (mysqli_num_rows($select_completed) > 0) {
                     while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
                        $total_completed += $fetch_completed['total_price'];
                     }
                  }
                  ?>
                  <div class="dashboard-value text-success">$<?php echo $total_completed; ?></div>
                  <div class="dashboard-title">Completed Payments</div>
               </div>
            </div>
         </div>

         <!-- Orders Placed -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon text-primary"><i class="fas fa-shopping-bag"></i></div>
                  <?php
                  $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                  $number_of_orders = mysqli_num_rows($select_orders);
                  ?>
                  <div class="dashboard-value"><?php echo $number_of_orders; ?></div>
                  <div class="dashboard-title">Orders Placed</div>
               </div>
            </div>
         </div>

         <!-- Products Added -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon text-warning"><i class="fas fa-book"></i></div>
                  <?php
                  $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                  $number_of_products = mysqli_num_rows($select_products);
                  ?>
                  <div class="dashboard-value"><?php echo $number_of_products; ?></div>
                  <div class="dashboard-title">Books Added</div>
               </div>
            </div>
         </div>

         <!-- Authors -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon" style="color:#10b981;"><i class="fas fa-user-pen"></i></div>
                  <?php
                  $select_authors = mysqli_query($conn, "SELECT * FROM `author`") or die('query failed');
                  $number_of_authors = mysqli_num_rows($select_authors);
                  ?>
                  <div class="dashboard-value"><?php echo $number_of_authors; ?></div>
                  <div class="dashboard-title">Authors</div>
               </div>
            </div>
         </div>

         <!-- Publishers -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon" style="color:#f59e42;"><i class="fas fa-building"></i></div>
                  <?php
                  $select_publishers = mysqli_query($conn, "SELECT * FROM `publisher`") or die('query failed');
                  $number_of_publishers = mysqli_num_rows($select_publishers);
                  ?>
                  <div class="dashboard-value"><?php echo $number_of_publishers; ?></div>
                  <div class="dashboard-title">Publishers</div>
               </div>
            </div>
         </div>

         <!-- Blogs -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon" style="color:#e11d48;"><i class="fas fa-newspaper"></i></div>
                  <?php
                  $select_blogs = mysqli_query($conn, "SELECT * FROM `blogs`") or die('query failed');
                  $number_of_blogs = mysqli_num_rows($select_blogs);
                  ?>
                  <div class="dashboard-value"><?php echo $number_of_blogs; ?></div>
                  <div class="dashboard-title">Blogs</div>
               </div>
            </div>
         </div>

         <!-- Normal Users -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon" style="color:#0ea5e9;"><i class="fas fa-users"></i></div>
                  <?php
                  $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
                  $number_of_users = mysqli_num_rows($select_users);
                  ?>
                  <div class="dashboard-value"><?php echo $number_of_users; ?></div>
                  <div class="dashboard-title">Normal Users</div>
               </div>
            </div>
         </div>

         <!-- Admin Users -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon" style="color:#f43f5e;"><i class="fas fa-user-shield"></i></div>
                  <?php
                  $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
                  $number_of_admins = mysqli_num_rows($select_admins);
                  ?>
                  <div class="dashboard-value"><?php echo $number_of_admins; ?></div>
                  <div class="dashboard-title">Admin Users</div>
               </div>
            </div>
         </div>

         <!-- Total Accounts -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon" style="color:#6366f1;"><i class="fas fa-user-circle"></i></div>
                  <?php
                  $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                  $number_of_account = mysqli_num_rows($select_account);
                  ?>
                  <div class="dashboard-value"><?php echo $number_of_account; ?></div>
                  <div class="dashboard-title">Total Accounts</div>
               </div>
            </div>
         </div>

         <!-- New Messages -->
         <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card dashboard-card text-center">
               <div class="card-body">
                  <div class="dashboard-icon" style="color:#fbbf24;"><i class="fas fa-envelope"></i></div>
                  <?php
                  $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                  $number_of_messages = mysqli_num_rows($select_messages);
                  ?>
                  <div class="dashboard-value"><?php echo $number_of_messages; ?></div>
                  <div class="dashboard-title">New Messages</div>
               </div>
            </div>
         </div>

      </div>
   </section>

   <footer class="text-center py-4 mt-5 text-secondary small">
      &copy; <?php echo date('Y'); ?> Double H Admin Dashboard. All rights reserved.
   </footer>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>