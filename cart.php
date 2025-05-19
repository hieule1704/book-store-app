<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['update_cart'])) {
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = 'Cart quantity updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="bg-light py-4 mb-4">
      <div class="container">
         <h3 class="mb-1">Shopping cart</h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
               <li class="breadcrumb-item"><a href="home.php">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Cart</li>
            </ol>
         </nav>
      </div>
   </div>

   <section class="container py-5">
      <h1 class="text-center text-uppercase mb-4">Products added</h1>
      <div class="row g-4">
         <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
         ?>
               <div class="col-md-3 col-sm-6 align-items-stretch">
                  <div class="card shadow h-100">
                     <div class="card-header d-flex justify-content-end p-2">
                        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('delete this from cart?');">
                           <i class="fas fa-times"></i>
                        </a>
                     </div>
                     <div class="bg-white d-flex justify-content-center align-items-center p-2" style="height: 250px;">
                        <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="<?php echo htmlspecialchars($fetch_cart['name']); ?>" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                     </div>
                     <div class="card-body d-flex flex-column">
                        <div class="fw-bold fs-5 mb-2 line-clamp-2" title="<?php echo $fetch_cart['name']; ?>"><?php echo $fetch_cart['name']; ?></div>
                        <div class="mb-2 text-danger fw-bold">$<?php echo number_format($fetch_cart['price'], 0, ',', '.'); ?></div>
                        <form action="" method="post" class="d-flex align-items-center mb-2">
                           <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                           <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" class="form-control me-2" style="max-width: 80px;">
                           <input type="submit" name="update_cart" value="Update" class="btn btn-warning btn-sm">
                        </form>
                        <div class="sub-total mb-2">Sub total: <span class="fw-bold">$<?php echo number_format($sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']), 0, ',', '.'); ?></span></div>
                     </div>
                     </form>
                  </div>
            <?php
               $grand_total += $sub_total;
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-info text-center">Your cart is empty</div></div>';
         }
            ?>
               </div>

               <div class="text-center my-4">
                  <a href="cart.php?delete_all" class="btn btn-danger <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('delete all from cart?');">Delete all</a>
               </div>

               <div class="card mx-auto" style="max-width: 400px;">
                  <div class="card-body text-center">
                     <p class="mb-3 fs-5">Grand total: <span class="fw-bold text-primary">$<?php echo number_format($grand_total, 0, ',', '.'); ?></span></p>
                     <p class="mb-3 fs-5">Grand total: <span class="fw-bold text-primary">$<?php echo number_format($grand_total, 0, ',', '.'); ?></span></p>
                     <div class="d-flex justify-content-center gap-2">
                        <a href="shop.php" class="btn btn-warning">Continue shopping</a>
                        <a href="checkout.php" class="btn btn-primary <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to checkout</a>
                     </div>
                  </div>
               </div>
   </section>








   <?php include 'footer.php'; ?>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>