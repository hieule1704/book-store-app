<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit; // Add exit after header redirect
}

// Detect "Buy now" product
$buy_now_product = null;
if (
   isset($_POST['buy_now']) &&
   isset($_POST['product_id']) &&
   isset($_POST['product_quantity'])
) {
   $buy_now_product = [
      'id' => $_POST['product_id'],
      'quantity' => $_POST['product_quantity']
   ];
}

if (isset($_POST['order_btn'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'Flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $legacy_products_string = []; // To store string "Book (1), Book (2)" for admin panel compatibility
   $final_order_items = []; // To store clean data for new order_items table
   $is_stock_valid = true;

   // ---------------------------------------------------------
   // STEP 1: GATHER ITEMS & CHECK STOCK
   // ---------------------------------------------------------

   if ($buy_now_product) {
      // --- BUY NOW LOGIC ---
      $pid = $buy_now_product['id'];
      $qty = $buy_now_product['quantity'];

      // Get real price and stock from DB (Security: Don't trust POST price)
      $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$pid'") or die('query failed');

      if (mysqli_num_rows($product_query) > 0) {
         $fetch_product = mysqli_fetch_assoc($product_query);

         // Stock Check
         if ($fetch_product['stock_quantity'] < $qty) {
            $message[] = 'Sorry! "' . $fetch_product['book_name'] . '" only has ' . $fetch_product['stock_quantity'] . ' left in stock.';
            $is_stock_valid = false;
         } else {
            $final_order_items[] = [
               'product_id' => $fetch_product['id'],
               'quantity' => $qty,
               'price' => $fetch_product['price']
            ];
            $legacy_products_string[] = $fetch_product['book_name'] . ' (' . $qty . ')';
            $cart_total = $fetch_product['price'] * $qty;
         }
      }
   } else {
      // --- CART LOGIC ---
      $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($cart_query) > 0) {
         while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            // Since your cart table uses 'name' and not 'product_id', we must look up the product
            // This fixes the missing link in your database design
            $book_name = mysqli_real_escape_string($conn, $cart_item['name']);
            $product_lookup = mysqli_query($conn, "SELECT * FROM `products` WHERE book_name = '$book_name' LIMIT 1");

            if (mysqli_num_rows($product_lookup) > 0) {
               $real_product = mysqli_fetch_assoc($product_lookup);

               // Stock Check
               if ($real_product['stock_quantity'] < $cart_item['quantity']) {
                  $message[] = 'Sorry! "' . $real_product['book_name'] . '" only has ' . $real_product['stock_quantity'] . ' left in stock.';
                  $is_stock_valid = false;
               } else {
                  $final_order_items[] = [
                     'product_id' => $real_product['id'],
                     'quantity' => $cart_item['quantity'],
                     'price' => $real_product['price']
                  ];
                  $legacy_products_string[] = $real_product['book_name'] . ' (' . $cart_item['quantity'] . ')';
                  $cart_total += ($real_product['price'] * $cart_item['quantity']);
               }
            }
         }
      } else {
         $message[] = 'your cart is empty';
         $is_stock_valid = false;
      }
   }

   // ---------------------------------------------------------
   // STEP 2: PLACE ORDER IF STOCK IS VALID
   // ---------------------------------------------------------

   if ($is_stock_valid && $cart_total > 0) {

      $total_products = implode(', ', $legacy_products_string);

      // Check duplicate order
      $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

      if (mysqli_num_rows($order_query) > 0) {
         $message[] = 'order already placed!';
      } else {

         // A. Insert into MAIN ORDERS table
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');

         // Get the ID of the order we just created
         $order_id = mysqli_insert_id($conn);

         // B. Insert into ORDER_ITEMS and DEDUCT STOCK
         foreach ($final_order_items as $item) {
            $pid = $item['product_id'];
            $qty = $item['quantity'];
            $price = $item['price'];

            // 1. Insert into normalized table
            mysqli_query($conn, "INSERT INTO `order_items` (order_id, product_id, quantity, price) VALUES ('$order_id', '$pid', '$qty', '$price')");

            // 2. Deduct Stock
            mysqli_query($conn, "UPDATE `products` SET stock_quantity = stock_quantity - $qty WHERE id = '$pid'");
         }

         // C. Clean up
         $message[] = 'order placed successfully!';
         if (!$buy_now_product) {
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         }
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

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
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
         <div class="col-lg-5">
            <div class="card shadow border-0">
               <div class="card-header bg-primary text-white text-center">
                  <h4 class="mb-0 text-uppercase">Order Summary</h4>
               </div>
               <div class="card-body">
                  <?php
                  $display_total = 0;
                  // RE-LOGIC for Display (Visual only)
                  if ($buy_now_product) {
                     // Fetch display details for Buy Now
                     $pid = $buy_now_product['id'];
                     $pqty = $buy_now_product['quantity'];
                     $res = mysqli_query($conn, "SELECT * FROM products WHERE id='$pid'");
                     if ($row = mysqli_fetch_assoc($res)) {
                        $display_total = $row['price'] * $pqty;
                        echo '<div class="d-flex justify-content-between border-bottom py-2">';
                        echo '<span>' . htmlspecialchars($row['book_name']) . '</span>';
                        echo '<span class="text-secondary">($' . $row['price'] . ' x ' . $pqty . ')</span>';
                        echo '</div>';
                     }
                  } else {
                     $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                     if (mysqli_num_rows($select_cart) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                           $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                           $display_total += $total_price;
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
                  }
                  ?>
               </div>
               <div class="card-footer bg-white text-end fs-5">
                  Grand total: <span class="fw-bold text-primary">$<?php echo number_format($display_total, 0, ',', '.'); ?></span>
               </div>
            </div>
         </div>
         <div class="col-lg-7">
            <div class="card shadow border-0">
               <div class="card-header bg-success text-white text-center">
                  <h4 class="mb-0 text-uppercase">Shipping & Payment</h4>
               </div>
               <div class="card-body">
                  <form action="" method="post">
                     <?php if ($buy_now_product): ?>
                        <input type="hidden" name="buy_now" value="1">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($buy_now_product['id']); ?>">
                        <input type="hidden" name="product_quantity" value="<?php echo htmlspecialchars($buy_now_product['quantity']); ?>">
                     <?php endif; ?>
                     <div class="row g-3">
                        <div class="col-md-6">
                           <label class="form-label">Your name</label>
                           <input type="text" name="name" required placeholder="Enter your name" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Your number</label>
                           <input type="text" name="number" required placeholder="Enter your number" class="form-control">
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
                        <input type="submit" value="Order now" class="btn btn-success btn-lg" name="order_btn" <?php echo ($display_total == 0) ? 'disabled' : ''; ?>>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>


</html>