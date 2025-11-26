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
   mysqli_query($conn, "DELETE FROM `message` WHERE id = $delete_id") or die('query failed');
   header('Location: admin_contacts.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

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
      <h1 class="text-center text-uppercase mb-4">Messages</h1>
      <!-- EXPORT BUTTON -->
      <div class="text-end mb-3">
         <a href="admin_export.php?table=message" class="btn btn-success">
            <i class="fas fa-file-download me-2"></i> Export Messages
         </a>
      </div>
      <div class="row g-4">
         <?php
         $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
         if (mysqli_num_rows($select_message) > 0) {
            while ($fetch_message = mysqli_fetch_assoc($select_message)) {
         ?>
               <div class="col-md-6 col-lg-4">
                  <div class="card shadow h-100">
                     <div class="card-body">
                        <p class="mb-1"><strong>User ID:</strong> <span><?php echo $fetch_message['user_id']; ?></span></p>
                        <p class="mb-1"><strong>Name:</strong> <span><?php echo $fetch_message['name']; ?></span></p>
                        <p class="mb-1"><strong>Number:</strong> <span><?php echo $fetch_message['number']; ?></span></p>
                        <p class="mb-1"><strong>Email:</strong> <span><?php echo $fetch_message['email']; ?></span></p>
                        <p class="mb-3"><strong>Message:</strong> <span><?php echo $fetch_message['message']; ?></span></p>
                        <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="btn btn-danger w-100">Delete message</a>
                     </div>
                  </div>
               </div>
         <?php
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-info text-center">You have no messages!</div></div>';
         }
         ?>
      </div>
   </section>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>