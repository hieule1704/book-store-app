<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['order_btn'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'Flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($cart_query) > 0) {
      while ($cart_item = mysqli_fetch_assoc($cart_query)) {
         $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ', $cart_products);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if ($cart_total == 0) {
      $message[] = 'your cart is empty';
   } else {
      if (mysqli_num_rows($order_query) > 0) {
         $message[] = 'order already placed!';
      } else {
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'order placed successfully!';
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
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
   <title>Checkout</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="bg-light py-4 mb-4">
      <div class="container">
         <h3 class="mb-1">Checkout</h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
               <li class="breadcrumb-item"><a href="home.php">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
         </nav>
      </div>
   </div>

   <section class="container mb-5">
      <div class="row g-4 justify-content-center">
         <!-- Order Summary Card -->
         <div class="col-lg-5">
            <div class="card shadow border-0">
               <div class="card-header bg-primary text-white text-center">
                  <h4 class="mb-0 text-uppercase">Order Summary</h4>
               </div>
               <div class="card-body">
                  <?php
                  $grand_total = 0;
                  $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                  if (mysqli_num_rows($select_cart) > 0) {
                     while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                        $grand_total += $total_price;
                  ?>
                        <div class="d-flex justify-content-between border-bottom py-2">
                           <span><?php echo $fetch_cart['name']; ?></span>
                           <span class="text-secondary">(<?php echo '$' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity']; ?>)</span>
                        </div>
                  <?php
                     }
                  } else {
                     echo '<div class="alert alert-info text-center my-3">Your cart is empty</div>';
                  }
                  ?>
               </div>
               <div class="card-footer bg-white text-end fs-5">
                  Grand total: <span class="fw-bold text-primary">$<?php echo number_format($grand_total, 0, ',', '.'); ?></span>
               </div>
            </div>
         </div>
         <!-- Checkout Form Card -->
         <div class="col-lg-7">
            <div class="card shadow border-0">
               <div class="card-header bg-success text-white text-center">
                  <h4 class="mb-0 text-uppercase">Shipping & Payment</h4>
               </div>
               <div class="card-body">
                  <form action="" method="post">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <label class="form-label">Your name</label>
                           <input type="text" name="name" required placeholder="Enter your name" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Your number</label>
                           <input type="number" name="number" required placeholder="Enter your number" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Your email</label>
                           <input type="email" name="email" required placeholder="Enter your email" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Payment method</label>
                           <select name="method" class="form-select">
                              <option value="cash on delivery">Cash on delivery</option>
                              <option value="credit card">Credit card</option>
                              <option value="paypal">Paypal</option>
                              <option value="paytm">Paytm</option>
                           </select>
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Flat number</label>
                           <input type="number" min="0" name="flat" required placeholder="e.g. Flat No. 1" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Street name</label>
                           <input type="text" name="street" required placeholder="e.g. Vo Thi Sau" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">City</label>
                           <input type="text" name="city" required placeholder="e.g. Long Xuyen" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">State / Province</label>
                           <input type="text" name="state" required placeholder="e.g. An Giang" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Country</label>
                           <input type="text" name="country" required placeholder="e.g. Vietnam" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Postal code</label>
                           <input type="number" min="0" name="pin_code" required placeholder="e.g. 90000" class="form-control">
                        </div>
                     </div>
                     <div class="d-grid mt-4">
                        <input type="submit" value="Order now" class="btn btn-success btn-lg" name="order_btn" <?php echo ($grand_total == 0) ? 'disabled' : ''; ?>>
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