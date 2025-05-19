<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

// --- FILTER HANDLING ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_author = isset($_GET['author']) ? intval($_GET['author']) : 0;
$filter_publisher = isset($_GET['publisher']) ? intval($_GET['publisher']) : 0;

// --- GET AUTHORS & PUBLISHERS FOR RIBBON ---
$authors = mysqli_query($conn, "SELECT id, author_name, profile_picture FROM `author`") or die('Failed to fetch authors');
$publishers = mysqli_query($conn, "SELECT id, publisher_name, profile_image FROM `publisher`") or die('Failed to fetch publishers');

// --- FILTER FORM HANDLING ---
$where = [];
if ($search) {
   $where[] = "(p.book_name LIKE '%$search%')";
}
if ($filter_author) {
   $where[] = "p.author_id = $filter_author";
}
if ($filter_publisher) {
   $where[] = "p.publisher_id = $filter_publisher";
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

if (isset($_POST['add_to_cart'])) {

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Check if product already in cart for this user
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart!';
   } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="bg-light py-4 mb-4">
      <div class="container">
         <h3 class="mb-1">Our shop</h3>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
               <li class="breadcrumb-item"><a href="home.php">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Shop</li>
            </ol>
         </nav>
      </div>
   </div>

   <!-- SEARCH & FILTER FORM -->
   <section class="container mb-4">
      <form class="row g-2 align-items-end sticky-top bg-white py-2" method="get" action="" style="z-index:10;">
         <div class="col-md-4">
            <label class="form-label mb-1">Search book</label>
            <input type="text" name="search" class="form-control" placeholder="Book name..." value="<?php echo htmlspecialchars($search); ?>">
         </div>
         <div class="col-md-3">
            <label class="form-label mb-1">Author</label>
            <select name="author" class="form-select">
               <option value="0">All authors</option>
               <?php foreach ($authors as $author): ?>
                  <option value="<?php echo $author['id']; ?>" <?php if ($filter_author == $author['id']) echo 'selected'; ?>>
                     <?php echo htmlspecialchars($author['author_name']); ?>
                  </option>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="col-md-3">
            <label class="form-label mb-1">Publisher</label>
            <select name="publisher" class="form-select">
               <option value="0">All publishers</option>
               <?php foreach ($publishers as $publisher): ?>
                  <option value="<?php echo $publisher['id']; ?>" <?php if ($filter_publisher == $publisher['id']) echo 'selected'; ?>>
                     <?php echo htmlspecialchars($publisher['publisher_name']); ?>
                  </option>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <a href="shop.php" class="btn btn-outline-secondary w-100">Reset</a>
         </div>
      </form>
   </section>

   <!-- Featured Authors Ribbon -->
   <section class="container mb-4">
      <h5 class="mb-2 fw-bold text-center">Featured Authors</h5>
      <div id="authorRibbon" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
         <div class="carousel-inner">
            <?php
            $i = 0;
            foreach ($authors as $author):
            ?>
               <div class="carousel-item <?php if ($i++ == 0) echo 'active'; ?>">
                  <div class="d-flex justify-content-center align-items-center" style="height: 220px;">
                     <a href="shop.php?author=<?php echo $author['id']; ?>" class="d-flex flex-column align-items-center text-decoration-none">
                        <div class="rounded-circle overflow-hidden mb-2" style="width:180px; height:180px; border:4px solid #0d6efd; background:#fff;">
                           <img src="uploaded_img/<?php echo htmlspecialchars($author['profile_picture']); ?>" alt="<?php echo htmlspecialchars($author['author_name']); ?>" style="width:100%; height:100%; object-fit:contain; background:#fff;">
                        </div>
                        <span class="fw-semibold text-dark text-center" style="max-width:140px; white-space:normal;"><?php echo htmlspecialchars($author['author_name']); ?></span>
                     </a>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
         <button class="carousel-control-prev" type="button" data-bs-target="#authorRibbon" data-bs-slide="prev" style="filter: invert(1); background: rgba(0,0,0,0.5); border-radius: 50%; width: 48px; height: 48px;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         </button>
         <button class="carousel-control-next" type="button" data-bs-target="#authorRibbon" data-bs-slide="next" style="filter: invert(1); background: rgba(0,0,0,0.5); border-radius: 50%; width: 48px; height: 48px;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
         </button>
      </div>
   </section>

   <!-- Featured Publishers Ribbon -->
   <section class="container mb-4">
      <h5 class="mb-2 fw-bold text-center">Publishers</h5>
      <div id="publisherRibbon" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
         <div class="carousel-inner">
            <?php
            $i = 0;
            foreach ($publishers as $publisher):
            ?>
               <div class="carousel-item <?php if ($i++ == 0) echo 'active'; ?>">
                  <div class="d-flex justify-content-center align-items-center" style="height: 220px;">
                     <a href="shop.php?publisher=<?php echo $publisher['id']; ?>" class="d-flex flex-column align-items-center text-decoration-none">
                        <div class="rounded-circle overflow-hidden mb-2" style="width:180px; height:180px; border:4px solid #ffc107; background:#fff;">
                           <img src="uploaded_img/<?php echo htmlspecialchars($publisher['profile_image']); ?>" alt="<?php echo htmlspecialchars($publisher['publisher_name']); ?>" style="width:100%; height:100%; object-fit:contain; background:#fff;">
                        </div>
                        <span class="fw-semibold text-dark text-center" style="max-width:140px; white-space:normal;"><?php echo htmlspecialchars($publisher['publisher_name']); ?></span>
                     </a>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
         <button class="carousel-control-prev" type="button" data-bs-target="#publisherRibbon" data-bs-slide="prev" style="filter: invert(1); background: rgba(0,0,0,0.5); border-radius: 50%; width: 48px; height: 48px;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         </button>
         <button class="carousel-control-next" type="button" data-bs-target="#publisherRibbon" data-bs-slide="next" style="filter: invert(1); background: rgba(0,0,0,0.5); border-radius: 50%; width: 48px; height: 48px;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
         </button>
      </div>
   </section>

   <section class="container py-5">
      <h1 class="text-center text-uppercase mb-4">Latest Books</h1>
      <div class="row g-4">
         <?php
         $select_products = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
            LEFT JOIN `author` a ON p.author_id = a.id
            LEFT JOIN `publisher` pub ON p.publisher_id = pub.id
            $where_sql
         ") or die('query failed');

         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <div class="col-md-3 col-sm-6 mb-4 align-items-stretch">
                  <form action="" method="post" class="card shadow">
                     <a href="detail.php?id=<?php echo htmlspecialchars($fetch_products['id']); ?>" class="bg-white d-flex justify-content-center align-items-center p-2" style="height: 250px;">
                        <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo htmlspecialchars($fetch_products['book_name']); ?>" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                     </a>
                     <div class="card-body d-flex flex-column text-center">
                        <div title="<?php echo htmlspecialchars($fetch_products['book_name']); ?>" class="mb-2 fw-bold fs-5 line-clamp-2"><?php echo htmlspecialchars($fetch_products['book_name']); ?></div>
                        <div class="mb-2 text-secondary small">
                           <span><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($fetch_products['author_name']); ?></span>
                           <span class="ms-2"><i class="fa-solid fa-building"></i> <?php echo htmlspecialchars($fetch_products['publisher_name']); ?></span>
                        </div>
                        <div class="mb-2 text-danger fs-5 fw-bold">$<?php echo number_format($fetch_products['price'], 0, ',', '.'); ?></div>
                        <div class="mt-auto">
                           <input type="hidden" min="1" name="product_quantity" value="1" class="form-control mb-2" style="max-width:120px;">
                           <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                           <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['book_name']); ?>">
                           <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                           <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                           <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to cart</button>
                        </div>
                     </div>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-info text-center">No books found!</div></div>';
         }
         ?>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>