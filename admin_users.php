<?php

include 'config.php';

// remove direct session_start(); and include the secure session config instead
include_once __DIR__ . '/session_config.php';

$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
if (!$admin_id) {
   header('Location: login.php');
   exit;
}

if (isset($_GET['delete'])) {
   $delete_id = intval($_GET['delete']);

   // Start transaction to keep DB consistent
   mysqli_begin_transaction($conn);

   try {
      // 1. Delete related cart items
      $res = mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = $delete_id");
      if ($res === false) throw new Exception(mysqli_error($conn));

      // 2. Delete related messages
      $res = mysqli_query($conn, "DELETE FROM `message` WHERE user_id = $delete_id");
      if ($res === false) throw new Exception(mysqli_error($conn));

      // 3. Delete order_items for orders that belong to the user (if any)
      $order_ids = [];
      $orders_res = mysqli_query($conn, "SELECT id FROM `orders` WHERE user_id = $delete_id") or throw new Exception(mysqli_error($conn));
      while ($row = mysqli_fetch_assoc($orders_res)) {
         $order_ids[] = intval($row['id']);
      }

      if (!empty($order_ids)) {
         $ids = implode(',', $order_ids);

         // delete related order_items
         $res = mysqli_query($conn, "DELETE FROM `order_items` WHERE order_id IN ($ids)");
         if ($res === false) throw new Exception(mysqli_error($conn));

         // delete orders
         $res = mysqli_query($conn, "DELETE FROM `orders` WHERE id IN ($ids)");
         if ($res === false) throw new Exception(mysqli_error($conn));
      }

      // 4. Now safe to delete the user
      $res = mysqli_query($conn, "DELETE FROM `users` WHERE id = $delete_id");
      if ($res === false) throw new Exception(mysqli_error($conn));

      mysqli_commit($conn);
      header('Location: admin_users.php');
      exit;
   } catch (Exception $e) {
      mysqli_rollback($conn);
      // short error output for admin debugging
      die('Delete failed: ' . $e->getMessage());
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users</title>

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
      <h1 class="text-center text-uppercase mb-4">User Accounts</h1>
      <div class="text-end mb-3">
         <a href="admin_export.php?table=users" class="btn btn-success">
            <i class="fas fa-file-download me-2"></i> Export Messages
         </a>
      </div>
      <div class="row g-4">
         <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
         while ($fetch_users = mysqli_fetch_assoc($select_users)) {
         ?>
            <div class="col-md-4 col-sm-6">
               <div class="card shadow h-100">
                  <div class="card-body">
                     <p class="mb-1"><strong>User ID:</strong> <span><?php echo $fetch_users['id']; ?></span></p>
                     <p class="mb-1"><strong>Username:</strong> <span><?php echo $fetch_users['name']; ?></span></p>
                     <p class="mb-1"><strong>Email:</strong> <span><?php echo $fetch_users['email']; ?></span></p>
                     <p class="mb-3">
                        <strong>User type:</strong>
                        <span class="<?php echo ($fetch_users['user_type'] == 'admin') ? 'text-warning fw-bold' : 'text-secondary'; ?>">
                           <?php echo $fetch_users['user_type']; ?>
                        </span>
                     </p>
                     <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('delete this user?');" class="btn btn-danger w-100">Delete user</a>
                  </div>
               </div>
            </div>
         <?php
         };
         ?>
      </div>
   </section>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>