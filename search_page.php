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

if (isset($_POST['add_to_cart'])) {
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed');

   if (mysqli_num_rows($check_cart) > 0) {
      $message[] = 'Already added to cart!';
   } else {
      mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, quantity, image) VALUES ('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('Query failed');
      $message[] = 'Product added to cart!';
   }
}

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
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="style.css">

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>

<body>
   <?php include 'header.php'; ?>

   <div class="bg-light py-4 mb-4">
      <div class="container">
         <h3 class="mb-1">Search Page</h3>
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

      <div class="row g-4">
         <?php
         if (isset($_POST['submit'])) {
            $search_item = $_POST['search'];
            $select_products = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
            LEFT JOIN `author` a ON p.author_id = a.id
            LEFT JOIN `publisher` pub ON p.publisher_id = pub.id WHERE book_name LIKE '%{$search_item}%'") or die('Query failed');

            if (mysqli_num_rows($select_products) > 0) {
               while ($fetch_product = mysqli_fetch_assoc($select_products)) {
         ?>
                  <div class="col-md-3 col-sm-6 mb-4 align-items-stretch">
                     <form action="" method="post" class="card shadow product-card">
                        <a href="detail.php?id=<?php echo htmlspecialchars($fetch_product['id']); ?>" class="bg-white d-flex justify-content-center align-items-center p-2" style="height: 250px;">
                           <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="<?php echo htmlspecialchars($fetch_product['book_name']); ?>" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        </a>
                        <div class="card-body d-flex flex-column text-center">
                           <div title="<?php echo htmlspecialchars($fetch_product['book_name']); ?>" class="mb-2 fw-bold fs-5 line-clamp-2"><?php echo htmlspecialchars($fetch_product['book_name']); ?></div>
                           <div class="mb-2 text-secondary small">
                              <span><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($fetch_product['author_name']); ?></span>
                              <span class="ms-2"><i class="fa-solid fa-building"></i> <?php echo htmlspecialchars($fetch_product['publisher_name']); ?></span>
                           </div>
                           <div class="mb-2 text-danger fs-5 fw-bold">$<?php echo number_format($fetch_product['price'], 0, ',', '.'); ?></div>
                           <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                           <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_product['book_name']); ?>">
                           <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                           <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                           <input type="hidden" name="product_quantity" value="1">
                           <div class="mt-auto d-flex gap-2">
                              <button type="submit" name="add_to_cart" class="btn btn-primary w-50">Add to cart</button>
                              <button type="submit" name="buy_now" formaction="checkout.php" formmethod="post" class="btn btn-success w-50">Buy now</button>
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