<?php

include 'config.php';

// replace direct session_start() with secure session config
include_once __DIR__ . '/session_config.php';

// safe session read
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
   header('Location: login.php');
   exit;
}

// --- NEW: Handle "Buy Again" POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_again']) && !empty($_POST['order_id'])) {
   $order_id = intval($_POST['order_id']);

   // Fetch order items for that order
   $items_q = mysqli_query($conn, "SELECT oi.product_id, oi.quantity, p.book_name, p.price, p.image FROM `order_items` oi JOIN `products` p ON oi.product_id = p.id WHERE oi.order_id = '$order_id'");
   if ($items_q && mysqli_num_rows($items_q) > 0) {
      while ($it = mysqli_fetch_assoc($items_q)) {
         $pid = intval($it['product_id']);
         $qty = intval($it['quantity']);
         $name = mysqli_real_escape_string($conn, $it['book_name']);
         $price = intval($it['price']);
         $image = mysqli_real_escape_string($conn, $it['image']);

         // If product already in user's cart, increase quantity, else insert
         $exists = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' AND name = '$name' LIMIT 1");
         if ($exists && mysqli_num_rows($exists) > 0) {
            $row = mysqli_fetch_assoc($exists);
            $newQty = intval($row['quantity']) + $qty;
            mysqli_query($conn, "UPDATE `cart` SET quantity = '$newQty', price = '$price', image = '$image' WHERE id = " . intval($row['id']));
         } else {
            mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, quantity, image) VALUES ('$user_id', '$name', '$price', '$qty', '$image')");
         }
      }
   }
   // Redirect to checkout to continue with order
   header('Location: checkout.php');
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Orders</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

   <?php include 'header.php'; ?>

   <div class="bg-primary py-5 mb-5 text-white text-center">
      <div class="container">
         <h1 class="fw-bold display-5">My Orders</h1>
         <p class="lead">Track your past and current purchases.</p>
      </div>
   </div>

   <section class="container mb-5">
      <div class="row justify-content-center">
         <div class="col-lg-10">
            <?php
            $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' ORDER BY id DESC") or die('query failed');

            if (mysqli_num_rows($order_query) > 0) {
               while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
                  $order_id = $fetch_orders['id'];
                  $statusClass = ($fetch_orders['payment_status'] == 'completed') ? 'bg-success' : 'bg-warning text-dark';
            ?>
                  <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                     <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                           <span class="text-muted small text-uppercase fw-bold">Order Placed</span><br>
                           <span class="fw-bold"><?php echo $fetch_orders['placed_on']; ?></span>
                        </div>
                        <div>
                           <span class="text-muted small text-uppercase fw-bold">Total</span><br>
                           <span class="fw-bold text-primary">$<?php echo number_format($fetch_orders['total_price']); ?></span>
                        </div>
                        <div>
                           <span class="text-muted small text-uppercase fw-bold">Order #</span><br>
                           <span><?php echo $order_id; ?></span>
                        </div>
                        <div class="ms-md-auto">
                           <span class="badge <?php echo $statusClass; ?> px-3 py-2 rounded-pill text-uppercase">
                              <?php echo $fetch_orders['payment_status']; ?>
                           </span>
                        </div>
                     </div>

                     <div class="card-body p-4">
                        <div class="row">
                           <div class="col-md-8">
                              <h6 class="fw-bold mb-3 border-bottom pb-2">Items Ordered</h6>

                              <!-- FETCH ITEMS DYNAMICALLY FROM ORDER_ITEMS TABLE -->
                              <?php
                              // Check if order_items table exists and has data for this order
                              // Use a JOIN to get product images and names
                              $items_query = mysqli_query($conn, "
                              SELECT oi.*, p.image, p.book_name 
                              FROM `order_items` oi 
                              JOIN `products` p ON oi.product_id = p.id 
                              WHERE oi.order_id = '$order_id'
                           ");

                              if (mysqli_num_rows($items_query) > 0) {
                                 // NEW WAY: Show list with images
                                 while ($item = mysqli_fetch_assoc($items_query)) {
                              ?>
                                    <div class="d-flex align-items-center mb-3">
                                       <img src="uploaded_img/<?php echo $item['image']; ?>" class="rounded border me-3" style="width: 60px; height: 80px; object-fit: cover;" alt="Book">
                                       <div>
                                          <h6 class="mb-1 fw-bold"><a href="detail.php?id=<?php echo $item['product_id']; ?>" class="text-decoration-none text-dark"><?php echo $item['book_name']; ?></a></h6>
                                          <div class="text-muted small">
                                             Quantity: <?php echo $item['quantity']; ?> &times; $<?php echo $item['price']; ?>
                                          </div>
                                       </div>
                                    </div>
                              <?php
                                 }
                              } else {
                                 // FALLBACK: Old text string method if migration isn't fully applied to old orders
                                 echo '<p class="text-muted">' . $fetch_orders['total_products'] . '</p>';
                              }
                              ?>
                           </div>

                           <div class="col-md-4 border-start ps-md-4 mt-4 mt-md-0">
                              <h6 class="fw-bold mb-3 border-bottom pb-2">Delivery Details</h6>
                              <p class="mb-1"><strong>Name:</strong> <?php echo $fetch_orders['name']; ?></p>
                              <p class="mb-1"><strong>Phone:</strong> <?php echo $fetch_orders['number']; ?></p>
                              <p class="mb-1"><strong>Address:</strong> <br><span class="text-muted small"><?php echo $fetch_orders['address']; ?></span></p>
                              <p class="mb-0"><strong>Method:</strong> <?php echo $fetch_orders['method']; ?></p>
                           </div>
                        </div>
                     </div>

                     <?php if ($fetch_orders['payment_status'] == 'completed'): ?>
                        <div class="card-footer bg-light p-3 text-end">
                           <a href="contact.php" class="btn btn-sm btn-outline-secondary">Need Help?</a>
                           <form method="post" class="d-inline ms-2">
                              <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                              <button type="submit" name="buy_again" class="btn btn-sm btn-primary">Buy Again</button>
                           </form>
                        </div>
                     <?php endif; ?>
                  </div>
            <?php
               }
            } else {
               echo '
               <div class="text-center py-5">
                  <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                  <h3>No orders yet</h3>
                  <p class="text-muted">Looks like you haven\'t placed any orders yet.</p>
                  <a href="shop.php" class="btn btn-primary mt-2">Start Shopping</a>
               </div>';
            }
            ?>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>