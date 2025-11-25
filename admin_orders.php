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
   header('Location: admin_orders.php');
   exit;
}

if (isset($_GET['export']) && $_GET['export'] == 1) {
   header('Content-Type: text/csv; charset=utf-8');
   header('Content-Disposition: attachment; filename=orders_export_' . date('Ymd_His') . '.csv');
   $output = fopen('php://output', 'w');
   // Column headers
   fputcsv($output, ['ID', 'User ID', 'Placed On', 'Name', 'Number', 'Email', 'Address', 'Total Products', 'Total Price', 'Payment Method', 'Payment Status']);
   $orders = mysqli_query($conn, "SELECT * FROM `orders`");
   while ($row = mysqli_fetch_assoc($orders)) {
      fputcsv($output, [
         $row['id'],
         $row['user_id'],
         $row['placed_on'],
         $row['name'],
         $row['number'],
         $row['email'],
         $row['address'],
         $row['total_products'],
         $row['total_price'],
         $row['method'],
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
   <title>orders</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <!-- <link rel="stylesheet" href="css/admin_style.css"> -->

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
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
         if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
         ?>
               <div class="col-md-6 col-lg-4">
                  <div class="card shadow h-100">
                     <div class="card-body">
                        <p class="mb-1"><strong>User ID:</strong> <span><?php echo $fetch_orders['user_id']; ?></span></p>
                        <p class="mb-1"><strong>Placed on:</strong> <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                        <p class="mb-1"><strong>Name:</strong> <span><?php echo $fetch_orders['name']; ?></span></p>
                        <p class="mb-1"><strong>Number:</strong> <span><?php echo $fetch_orders['number']; ?></span></p>
                        <p class="mb-1"><strong>Email:</strong> <span><?php echo $fetch_orders['email']; ?></span></p>
                        <p class="mb-1"><strong>Address:</strong> <span><?php echo $fetch_orders['address']; ?></span></p>
                        <p class="mb-1"><strong>Total products:</strong> <span><?php echo $fetch_orders['total_products']; ?></span></p>
                        <p class="mb-1"><strong>Total price:</strong> <span class="text-danger fw-bold">$<?php echo $fetch_orders['total_price']; ?>/-</span></p>
                        <p class="mb-3"><strong>Payment method:</strong> <span><?php echo $fetch_orders['method']; ?></span></p>
                        <form action="" method="post" class="d-flex flex-column gap-2">
                           <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                           <select name="update_payment" class="form-select mb-2">
                              <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                              <option value="pending">pending</option>
                              <option value="completed">completed</option>
                           </select>
                           <div class="d-flex gap-2">
                              <input type="submit" value="Update" name="update_order" class="btn btn-warning flex-fill">
                              <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('delete this order?');" class="btn btn-danger flex-fill">Delete</a>
                           </div>
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