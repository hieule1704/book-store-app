<?php

include 'config.php';

// replace direct session_start() with secure session config
include_once __DIR__ . '/session_config.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
   header('Location: login.php');
   exit;
}

// Detect "Buy now" product
$buy_now_product = null;
if (
   isset($_POST['buy_now']) &&
   isset($_POST['product_id']) &&
   isset($_POST['product_quantity'])
) {
   $buy_now_product = [
      'id' => (int)$_POST['product_id'],
      'quantity' => (int)$_POST['product_quantity']
   ];
}

if (isset($_POST['order_btn'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = mysqli_real_escape_string($conn, $_POST['number']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'Flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $legacy_products_string = [];
   $final_order_items = [];
   $is_stock_valid = true;

   // --- STEP 1: GATHER ITEMS & CHECK STOCK ---
   if ($buy_now_product) {
      $pid = intval($buy_now_product['id']);
      $qty = intval($buy_now_product['quantity']);
      $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$pid'") or die('query failed');

      if (mysqli_num_rows($product_query) > 0) {
         $fetch_product = mysqli_fetch_assoc($product_query);
         if ($fetch_product['stock_quantity'] < $qty) {
            $message[] = 'Sorry! "' . $fetch_product['book_name'] . '" out of stock.';
            $is_stock_valid = false;
         } else {
            $final_order_items[] = ['product_id' => $fetch_product['id'], 'quantity' => $qty, 'price' => $fetch_product['price']];
            $legacy_products_string[] = $fetch_product['book_name'] . ' (' . $qty . ')';
            $cart_total = $fetch_product['price'] * $qty;
         }
      }
   } else {
      $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if (mysqli_num_rows($cart_query) > 0) {
         while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $book_name = mysqli_real_escape_string($conn, $cart_item['name']);
            $product_lookup = mysqli_query($conn, "SELECT * FROM `products` WHERE book_name = '$book_name' LIMIT 1");
            if (mysqli_num_rows($product_lookup) > 0) {
               $real_product = mysqli_fetch_assoc($product_lookup);
               if ($real_product['stock_quantity'] < $cart_item['quantity']) {
                  $message[] = 'Sorry! "' . $real_product['book_name'] . '" out of stock.';
                  $is_stock_valid = false;
               } else {
                  $final_order_items[] = ['product_id' => $real_product['id'], 'quantity' => $cart_item['quantity'], 'price' => $real_product['price']];
                  $legacy_products_string[] = $real_product['book_name'] . ' (' . $cart_item['quantity'] . ')';
                  $cart_total += ($real_product['price'] * $cart_item['quantity']);
               }
            }
         }
      } else {
         $message[] = 'Your cart is empty';
         $is_stock_valid = false;
      }
   }

   // --- STEP 2: PLACE ORDER ---
   if ($is_stock_valid && $cart_total > 0) {
      $total_products = implode(', ', $legacy_products_string);
      $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

      if (mysqli_num_rows($order_query) > 0) {
         $message[] = 'Order already placed!';
      } else {
         mysqli_begin_transaction($conn);
         $transaction_ok = true;

         // Insert Order
         $insert_order = mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')");

         if ($insert_order) {
            $order_id = mysqli_insert_id($conn);
            // Insert Items & Update Stock
            foreach ($final_order_items as $item) {
               $pid = $item['product_id'];
               $qty = $item['quantity'];
               $price = $item['price'];
               mysqli_query($conn, "INSERT INTO `order_items` (order_id, product_id, quantity, price) VALUES ('$order_id', '$pid', '$qty', '$price')");
               mysqli_query($conn, "UPDATE `products` SET stock_quantity = stock_quantity - $qty WHERE id = '$pid'");
            }
         } else {
            $transaction_ok = false;
         }

         if ($transaction_ok) {
            mysqli_commit($conn);
            if (!$buy_now_product) {
               mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");
            }

            // --- CRITICAL: ROUTING BASED ON PAYMENT METHOD ---
            if ($method == 'qr code') {
               // Option 2: QR Code (Auto/Scan)
               header("Location: payment.php?id=$order_id");
            } elseif ($method == 'bank transfer') {
               // Option 3: Manual Transfer (Show Text)
               header("Location: bank_transfer.php?id=$order_id");
            } else {
               // Option 1: Cash on Delivery (Done)
               echo "<script>alert('Order placed successfully! We will verify it soon.'); window.location.href='orders.php';</script>";
            }
            exit;
         } else {
            mysqli_rollback($conn);
            $message[] = 'Order failed!';
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
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
         <div class="col-lg-8">
            <div class="card shadow border-0">
               <div class="card-header bg-success text-white text-center">
                  <h4 class="mb-0 text-uppercase">Billing Details</h4>
               </div>
               <div class="card-body">
                  <form action="" method="post">
                     <?php if ($buy_now_product): ?>
                        <input type="hidden" name="buy_now" value="1">
                        <input type="hidden" name="product_id" value="<?php echo $buy_now_product['id']; ?>">
                        <input type="hidden" name="product_quantity" value="<?php echo $buy_now_product['quantity']; ?>">
                     <?php endif; ?>
                     <div class="row g-3">
                        <div class="col-md-6">
                           <label class="form-label">Name</label>
                           <input type="text" name="name" required class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Phone Number</label>
                           <input type="text" name="number" required class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Email</label>
                           <input type="email" name="email" required class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label fw-bold text-primary">Payment Method</label>
                           <select name="method" class="form-select">
                              <option value="cash on delivery">Cash on Delivery (COD)</option>
                              <option value="qr code">QR Code (VietQR)</option>
                              <option value="bank transfer">Direct Bank Transfer (Manual)</option>
                           </select>
                        </div>
                        <div class="col-md-4">
                           <label class="form-label">Flat No.</label>
                           <input type="number" min="0" name="flat" required class="form-control">
                        </div>
                        <div class="col-md-4">
                           <label class="form-label">Street</label>
                           <input type="text" name="street" required class="form-control">
                        </div>
                        <div class="col-md-4">
                           <label class="form-label">City</label>
                           <input type="text" name="city" required class="form-control">
                        </div>
                        <div class="col-md-4">
                           <label class="form-label">Province</label>
                           <input type="text" name="state" required class="form-control">
                        </div>
                        <div class="col-md-4">
                           <label class="form-label">Country</label>
                           <input type="text" name="country" required class="form-control">
                        </div>
                        <div class="col-md-4">
                           <label class="form-label">Zip Code</label>
                           <input type="number" min="0" name="pin_code" required class="form-control">
                        </div>
                     </div>
                     <div class="d-grid mt-4">
                        <input type="submit" value="Place Order" class="btn btn-success btn-lg" name="order_btn">
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