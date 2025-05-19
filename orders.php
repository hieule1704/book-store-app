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
   <title>orders</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="bg-light py-4 mb-4">
      <div class="container">
         <h3 class="mb-1">Your orders</h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
               <li class="breadcrumb-item"><a href="home.php">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
         </nav>
      </div>
   </div>

   <section class="container py-5">
      <h1 class="text-center text-uppercase mb-4">Placed orders</h1>
      <div class="row g-4">
         <?php
         $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($order_query) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
         ?>
               <div class="col-md-6 col-lg-4">
                  <div class="card shadow h-100">
                     <div class="card-body">
                        <p class="mb-1"><strong>Placed on:</strong> <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                        <p class="mb-1"><strong>Name:</strong> <span><?php echo $fetch_orders['name']; ?></span></p>
                        <p class="mb-1"><strong>Number:</strong> <span><?php echo $fetch_orders['number']; ?></span></p>
                        <p class="mb-1"><strong>Email:</strong> <span><?php echo $fetch_orders['email']; ?></span></p>
                        <p class="mb-1"><strong>Address:</strong> <span><?php echo $fetch_orders['address']; ?></span></p>
                        <p class="mb-1"><strong>Payment method:</strong> <span><?php echo $fetch_orders['method']; ?></span></p>
                        <p class="mb-1"><strong>Your orders:</strong> <span><?php echo $fetch_orders['total_products']; ?></span></p>
                        <p class="mb-1"><strong>Total price:</strong> <span class="text-danger fw-bold">$<?php echo $fetch_orders['total_price']; ?>/-</span></p>
                        <p class="mb-0"><strong>Payment status:</strong>
                           <span class="<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'text-danger' : 'text-success'; ?>">
                              <?php echo $fetch_orders['payment_status']; ?>
                           </span>
                        </p>
                     </div>
                  </div>
               </div>
         <?php
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-info text-center">No orders placed yet!</div></div>';
         }
         ?>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>