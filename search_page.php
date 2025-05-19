<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
};

if (isset($_POST['add_to_cart'])) {

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart!';
   } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }
};

if (isset($_POST['more_detail'])) {
   $product_id = $_POST['product_id'];
   header("Location: detail.php?id=$product_id");
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">


<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search page</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <title>Search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>


<body>

   <?php include 'header.php'; ?>

   <div class="bg-light py-4 mb-4">
      <div class="container">
         <h3 class="mb-1">Search page</h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
               <li class="breadcrumb-item"><a href="home.php">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Search</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="bg-light py-4 mb-4">
      <div class="container">
         <h3 class="mb-1">Search page</h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
               <li class="breadcrumb-item"><a href="home.php">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Search</li>
            </ol>
         </nav>
      </div>
   </div>

   <section class="container py-4">
      <form action="" method="post" class="row justify-content-center mb-4">
         <div class="col-md-6 col-lg-5">
            <div class="input-group">
               <input type="text" name="search" placeholder="Search products..." class="form-control" required>
               <button type="submit" name="submit" class="btn btn-primary">Search</button>
            </div>
         </div>
      </form>
      <section class="container py-4">
         <form action="" method="post" class="row justify-content-center mb-4">
            <div class="col-md-6 col-lg-5">
               <div class="input-group">
                  <input type="text" name="search" placeholder="Search products..." class="form-control" required>
                  <button type="submit" name="submit" class="btn btn-primary">Search</button>
               </div>
            </div>
         </form>

         <div class="row g-4">
            <?php
            if (isset($_POST['submit'])) {
               $search_item = $_POST['search'];
               $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('query failed');
               if (mysqli_num_rows($select_products) > 0) {
                  while ($fetch_product = mysqli_fetch_assoc($select_products)) {
            ?>
                     <div class="col-md-4 col-sm-6">
                        <form action="" method="post" class="card w-75 shadow">
                           <img class="card-img-top p-3" src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="">
                           <div class="card-body d-flex flex-column">
                              <div class="mb-2 fw-bold fs-5"><?php echo $fetch_product['name']; ?></div>
                              <div class="mb-2 text-danger fs-5 fw-bold">$<?php echo number_format($fetch_product['price'], 0, ',', '.'); ?></div>
                              <input type="number" min="1" name="product_quantity" value="1" class="form-control mb-2" style="max-width:120px;">
                              <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                              <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                              <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                              <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                              <div class="d-flex align-items-center mt-2">
                                 <button type="submit" name="add_to_cart" class="btn btn-primary">Add to cart</button>
                                 <button type="submit" name="more_detail" class="btn btn-warning ms-3">More detail</button>
                              </div>
                           </div>
                        </form>
                     </div>
            <?php
                  }
               } else {
                  echo '<div class="col-12"><div class="alert alert-warning text-center">No result found!</div></div>';
               }
            } else {
               echo '<div class="col-12"><div class="alert alert-info text-center">Search something!</div></div>';
            }
            ?>
         </div>
      </section>

      <?php include 'footer.php'; ?>

      <!-- Bootstrap 5.3.x JS Bundle -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>


</html>