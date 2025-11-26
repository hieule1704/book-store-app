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

// --- FILTER HANDLING ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_author = isset($_GET['author']) ? intval($_GET['author']) : 0;
$filter_publisher = isset($_GET['publisher']) ? intval($_GET['publisher']) : 0;
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'newest'; // New Sorting Feature

// --- GET AUTHORS & PUBLISHERS FOR SIDEBAR ---
$authors = mysqli_query($conn, "SELECT id, author_name FROM `author` ORDER BY author_name ASC") or die('Failed to fetch authors');
$publishers = mysqli_query($conn, "SELECT id, publisher_name FROM `publisher` ORDER BY publisher_name ASC") or die('Failed to fetch publishers');

// --- FILTER QUERY BUILDING ---
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

// --- SORTING LOGIC ---
$order_sql = "ORDER BY p.id DESC"; // Default
if ($sort_by == 'price_low') $order_sql = "ORDER BY p.price ASC";
if ($sort_by == 'price_high') $order_sql = "ORDER BY p.price DESC";
if ($sort_by == 'oldest') $order_sql = "ORDER BY p.id ASC";


// --- CART ACTIONS ---
if (isset($_POST['add_to_cart'])) {
   $product_id = $_POST['product_id'];
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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">

   <style>
      .product-card {
         transition: transform 0.2s, box-shadow 0.2s;
         border: 1px solid rgba(0, 0, 0, 0.05);
      }

      .product-card:hover {
         transform: translateY(-5px);
         box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      }

      .filter-sidebar {
         background: #fff;
         border-radius: 8px;
         padding: 20px;
         box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
         position: sticky;
         top: 100px;
         /* Stays visible when scrolling */
      }

      .form-check-input:checked {
         background-color: #0d6efd;
         border-color: #0d6efd;
      }

      .card-img-container {
         height: 220px;
         display: flex;
         align-items: center;
         justify-content: center;
         background: #f8f9fa;
         position: relative;
      }

      .card-img-top {
         max-height: 180px;
         width: auto;
         object-fit: contain;
      }

      .badge-stock {
         position: absolute;
         bottom: 10px;
         right: 10px;
         font-size: 0.7rem;
      }
   </style>
</head>

<body class="bg-light">

   <?php include 'header.php'; ?>

   <!-- HEADER BANNER -->
   <div class="bg-primary py-5 mb-5 text-white text-center" style="background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
      <div class="container">
         <h1 class="fw-bold display-5">Browse Our Collection</h1>
         <p class="lead">Find your next favorite book from our extensive catalog.</p>
      </div>
   </div>

   <div class="container mb-5">
      <div class="row">

         <!-- SIDEBAR FILTERS -->
         <div class="col-lg-3 mb-4">
            <div class="filter-sidebar">
               <form action="" method="get">
                  <h5 class="fw-bold mb-3"><i class="fas fa-filter text-primary"></i> Filter By</h5>

                  <!-- Search -->
                  <div class="mb-4">
                     <label class="form-label fw-bold small text-muted text-uppercase">Search</label>
                     <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Book title..." value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
                     </div>
                  </div>

                  <!-- Authors -->
                  <div class="mb-4">
                     <label class="form-label fw-bold small text-muted text-uppercase">Authors</label>
                     <select name="author" class="form-select" onchange="this.form.submit()">
                        <option value="0">All Authors</option>
                        <?php
                        mysqli_data_seek($authors, 0);
                        while ($row = mysqli_fetch_assoc($authors)): ?>
                           <option value="<?php echo $row['id']; ?>" <?php if ($filter_author == $row['id']) echo 'selected'; ?>>
                              <?php echo htmlspecialchars($row['author_name']); ?>
                           </option>
                        <?php endwhile; ?>
                     </select>
                  </div>

                  <!-- Publishers -->
                  <div class="mb-4">
                     <label class="form-label fw-bold small text-muted text-uppercase">Publishers</label>
                     <select name="publisher" class="form-select" onchange="this.form.submit()">
                        <option value="0">All Publishers</option>
                        <?php
                        mysqli_data_seek($publishers, 0);
                        while ($row = mysqli_fetch_assoc($publishers)): ?>
                           <option value="<?php echo $row['id']; ?>" <?php if ($filter_publisher == $row['id']) echo 'selected'; ?>>
                              <?php echo htmlspecialchars($row['publisher_name']); ?>
                           </option>
                        <?php endwhile; ?>
                     </select>
                  </div>

                  <div class="d-grid gap-2">
                     <a href="shop.php" class="btn btn-outline-secondary btn-sm">Reset Filters</a>
                  </div>

                  <!-- Hidden Sort Input to keep sort when filtering -->
                  <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort_by); ?>">
               </form>
            </div>
         </div>

         <!-- MAIN PRODUCT GRID -->
         <div class="col-lg-9">

            <!-- Sort Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
               <span class="text-muted">Showing results...</span>
               <form method="get" class="d-flex align-items-center">
                  <!-- Keep current filters hidden so sorting doesn't reset them -->
                  <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                  <input type="hidden" name="author" value="<?php echo $filter_author; ?>">
                  <input type="hidden" name="publisher" value="<?php echo $filter_publisher; ?>">

                  <label class="me-2 small text-muted text-nowrap">Sort by:</label>
                  <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                     <option value="newest" <?php if ($sort_by == 'newest') echo 'selected'; ?>>Newest Arrivals</option>
                     <option value="price_low" <?php if ($sort_by == 'price_low') echo 'selected'; ?>>Price: Low to High</option>
                     <option value="price_high" <?php if ($sort_by == 'price_high') echo 'selected'; ?>>Price: High to Low</option>
                     <option value="oldest" <?php if ($sort_by == 'oldest') echo 'selected'; ?>>Oldest</option>
                  </select>
               </form>
            </div>

            <div class="row g-3">
               <?php
               $select_products = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
                  LEFT JOIN `author` a ON p.author_id = a.id
                  LEFT JOIN `publisher` pub ON p.publisher_id = pub.id
                  $where_sql
                  $order_sql
               ") or die('query failed');

               if (mysqli_num_rows($select_products) > 0) {
                  while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                     // Stock Logic
                     $stock = $fetch_products['stock_quantity'];
                     $isDisabled = $stock == 0 ? 'disabled' : '';
                     $stockBadge = $stock == 0
                        ? '<span class="badge bg-danger badge-stock">Out of Stock</span>'
                        : ($stock < 5 ? '<span class="badge bg-warning text-dark badge-stock">Low Stock</span>' : '');
               ?>
                     <div class="col-md-4 col-sm-6">
                        <form action="" method="post" class="card h-100 product-card border-0 shadow-sm">
                           <div class="card-img-container">
                              <a href="detail.php?id=<?php echo $fetch_products['id']; ?>">
                                 <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" class="card-img-top" alt="">
                              </a>
                              <?php echo $stockBadge; ?>
                           </div>

                           <div class="card-body d-flex flex-column">
                              <div class="mb-1 text-muted small text-uppercase">
                                 <a href="view_publisher.php?id=<?php echo $fetch_products['publisher_id']; ?>" class="text-decoration-none text-muted">
                                    <?php echo htmlspecialchars($fetch_products['publisher_name']); ?>
                                 </a>
                              </div>
                              <h6 class="card-title fw-bold mb-1 text-truncate">
                                 <a href="detail.php?id=<?php echo $fetch_products['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($fetch_products['book_name']); ?>
                                 </a>
                              </h6>
                              <p class="card-text small text-secondary mb-2">by
                                 <a href="view_author.php?id=<?php echo $fetch_products['author_id']; ?>" class="text-decoration-none text-secondary fw-bold">
                                    <?php echo htmlspecialchars($fetch_products['author_name']); ?>
                                 </a>
                              </p>

                              <div class="mt-auto">
                                 <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fs-5 fw-bold text-primary">$<?php echo $fetch_products['price']; ?></span>
                                    <div class="text-warning small">
                                       <i class="fas fa-star"></i>
                                       <i class="fas fa-star"></i>
                                       <i class="fas fa-star"></i>
                                       <i class="fas fa-star"></i>
                                       <i class="fas fa-star-half-alt"></i>
                                    </div>
                                 </div>

                                 <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                                 <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['book_name']); ?>">
                                 <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                                 <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

                                 <div class="d-flex gap-2">
                                    <input type="number" min="1" name="product_quantity" value="1" class="form-control form-control-sm" style="width: 60px;">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary btn-sm w-100" <?php echo $isDisabled; ?>>
                                       <i class="fas fa-shopping-cart me-1"></i> Add
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
               <?php
                  }
               } else {
                  echo '<div class="col-12 text-center py-5 text-muted">
                     <i class="fas fa-search fa-3x mb-3"></i>
                     <h4>No books found matching your criteria.</h4>
                     <a href="shop.php" class="btn btn-link">Clear all filters</a>
                  </div>';
               }
               ?>
            </div>
         </div>
      </div>
   </div>

   <?php include 'footer.php'; ?>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>