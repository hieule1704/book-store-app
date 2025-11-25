<?php

include 'config.php';

// remove direct session_start(); and include the secure session config instead
include_once __DIR__ . '/session_config.php';

$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
if (!$admin_id) {
   header('Location: login.php');
   exit;
}

if (isset($_POST['update_order'])) {

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'payment status has been updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = intval($_GET['delete']);
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = $delete_id") or die('query failed');
   // Optional: Delete from order_items too (though FK ON DELETE CASCADE handles this usually)
   header('Location: admin_orders.php');
   exit;
}

// --- CSV EXPORT LOGIC ---
if (isset($_GET['export']) && $_GET['export'] == 1) {
   header('Content-Type: text/csv; charset=utf-8');
   header('Content-Disposition: attachment; filename=orders_export_' . date('Ymd_His') . '.csv');
   $output = fopen('php://output', 'w');

   // Improved Headers
   fputcsv($output, ['Order ID', 'User ID', 'Placed On', 'Customer Name', 'Product Name', 'Quantity', 'Unit Price', 'Subtotal', 'Payment Status']);

   // Join tables to get item details
   $query = "
      SELECT o.id, o.user_id, o.placed_on, o.name, p.book_name, oi.quantity, oi.price, o.payment_status
      FROM orders o
      JOIN order_items oi ON o.id = oi.order_id
      JOIN products p ON oi.product_id = p.id
      ORDER BY o.id DESC
   ";

   $result = mysqli_query($conn, $query);
   while ($row = mysqli_fetch_assoc($result)) {
      fputcsv($output, [
         $row['id'],
         $row['user_id'],
         $row['placed_on'],
         $row['name'],
         $row['book_name'],
         $row['quantity'],
         $row['price'],
         ($row['quantity'] * $row['price']), // Subtotal
         $row['payment_status']
      ]);
   }
   fclose($output);
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">

   <?php include 'admin_header.php'; ?>

   <section class="container my-5">
      <h1 class="text-center text-uppercase mb-4">Placed Orders</h1>
      <div class="mb-3 text-end">
         <a href="admin_orders.php?export=1" class="btn btn-success">
            <i class="fa fa-file-excel"></i> Export to Excel
         </a>
      </div>
      <div class="row g-4">
         <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders` ORDER BY placed_on DESC") or die('query failed');
         if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
               $order_id = $fetch_orders['id'];
         ?>
               <div class="col-md-6 col-lg-4">
                  <div class="card shadow h-100">
                     <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                           <div>
                              <p class="mb-0 fw-bold">Order #<?php echo $order_id; ?></p>
                              <small class="text-muted"><?php echo $fetch_orders['placed_on']; ?></small>
                           </div>
                           <span class="badge <?php echo ($fetch_orders['payment_status'] == 'completed') ? 'bg-success' : 'bg-warning text-dark'; ?>">
                              <?php echo $fetch_orders['payment_status']; ?>
                           </span>
                        </div>

                        <p class="mb-1"><strong>Name:</strong> <span><?php echo htmlspecialchars($fetch_orders['name']); ?></span></p>
                        <p class="mb-1"><strong>Email:</strong> <span><?php echo htmlspecialchars($fetch_orders['email']); ?></span></p>
                        <p class="mb-1"><strong>Address:</strong> <span class="small"><?php echo htmlspecialchars($fetch_orders['address']); ?></span></p>

                        <hr>

                        <p class="mb-2 fw-bold">Items Purchased:</p>
                        <ul class="list-unstyled small mb-3 bg-light p-2 rounded">
                           <?php
                           // FETCH ITEMS FOR THIS ORDER FROM NEW TABLE
                           $items_query = mysqli_query($conn, "
                                 SELECT oi.quantity, oi.price, p.book_name 
                                 FROM `order_items` oi
                                 JOIN `products` p ON oi.product_id = p.id
                                 WHERE oi.order_id = '$order_id'
                              ");

                           if (mysqli_num_rows($items_query) > 0) {
                              while ($item = mysqli_fetch_assoc($items_query)) {
                                 echo "<li>• " . htmlspecialchars($item['book_name']) . " <span class='text-muted'>x" . $item['quantity'] . "</span></li>";
                              }
                           } else {
                              // Fallback for old legacy orders
                              echo "<li>" . $fetch_orders['total_products'] . "</li>";
                           }
                           ?>
                        </ul>

                        <div class="d-flex justify-content-between mb-3">
                           <strong>Total Price:</strong>
                           <span class="text-danger fw-bold fs-5">$<?php echo number_format($fetch_orders['total_price']); ?></span>
                        </div>

                        <form action="" method="post" class="d-flex flex-column gap-2">
                           <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                           <div class="input-group">
                              <select name="update_payment" class="form-select">
                                 <option value="" selected disabled>Change Status</option>
                                 <option value="pending">Pending</option>
                                 <option value="completed">Completed</option>
                              </select>
                              <input type="submit" value="Update" name="update_order" class="btn btn-warning">
                           </div>
                           <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('delete this order?');" class="btn btn-outline-danger w-100">Delete Order</a>
                        </form>
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

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>