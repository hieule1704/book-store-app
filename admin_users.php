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
   mysqli_query($conn, "DELETE FROM `users` WHERE id = $delete_id") or die('query failed');
   header('Location: admin_users.php');
   exit;
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