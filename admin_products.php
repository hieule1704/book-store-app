<?php

include 'config.php';

// remove direct session_start(); and include the secure session config instead
include_once __DIR__ . '/session_config.php';

$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
if (!$admin_id) {
   header('Location: login.php');
   exit;
}

// Fetch authors and publishers for dropdowns (for both add and filter forms)
$authors_all = mysqli_query($conn, "SELECT id, author_name FROM `author` ORDER BY author_name ASC") or die('Failed to fetch authors');
$publishers_all = mysqli_query($conn, "SELECT id, publisher_name FROM `publisher` ORDER BY publisher_name ASC") or die('Failed to fetch publishers');

// --- Handle product add/delete/update (existing logic) ---

if (isset($_POST['add_product'])) {

   $book_name = mysqli_real_escape_string($conn, $_POST['book_name']);
   $author_id = $_POST['author_id'];
   $publisher_id = $_POST['publisher_id'];
   $book_description = mysqli_real_escape_string($conn, $_POST['book_description']);
   $tag = mysqli_real_escape_string($conn, $_POST['tag']);
   $publish_year = $_POST['publish_year'];
   $total_page = $_POST['total_page'];
   $price = $_POST['price'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_product_name = mysqli_query($conn, "SELECT book_name FROM `products` WHERE book_name = '$book_name'") or die('query failed');

   if (mysqli_num_rows($select_product_name) > 0) {
      $message[] = 'Book name already added';
   } else {
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(book_name, author_id, publisher_id, book_description, tag, publish_year, total_page, price, image) VALUES('$book_name', '$author_id', '$publisher_id', '$book_description', '$tag', '$publish_year', '$total_page', '$price', '$image')") or die('query failed');

      if ($add_product_query) {
         if ($image_size > 2000000) {
            $message[] = 'Image size is too large';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Product added successfully!';
         }
      } else {
         $message[] = 'Product could not be added!';
      }
   }
}

if (isset($_GET['delete'])) {
   $delete_id = intval($_GET['delete']);
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = $delete_id") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = $delete_id") or die('query failed');
   header('Location: admin_products.php');
   exit;
}

if (isset($_POST['update_product'])) {

   $update_p_id = $_POST['update_p_id'];
   $update_book_name = mysqli_real_escape_string($conn, $_POST['update_book_name']);
   $update_author_id = $_POST['update_author_id'];
   $update_publisher_id = $_POST['update_publisher_id'];
   $update_book_description = mysqli_real_escape_string($conn, $_POST['update_book_description']);
   $update_tag = mysqli_real_escape_string($conn, $_POST['update_tag']);
   $update_publish_year = $_POST['update_publish_year'];
   $update_total_page = $_POST['update_total_page'];
   $update_price = $_POST['update_price'];

   mysqli_query($conn, "UPDATE `products` SET book_name = '$update_book_name', author_id = '$update_author_id', publisher_id = '$update_publisher_id', book_description = '$update_book_description', tag = '$update_tag', publish_year = '$update_publish_year', total_page = '$update_total_page', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/' . $update_image;
   $update_old_image = $_POST['update_old_image'];

   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'Image file size is too large';
      } else {
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/' . $update_old_image);
      }
   }

   header('location:admin_products.php');
}

// --- Filtering Logic ---
$filter_book_name = isset($_GET['filter_book_name']) ? mysqli_real_escape_string($conn, $_GET['filter_book_name']) : '';
$filter_author_id = isset($_GET['filter_author_id']) ? intval($_GET['filter_author_id']) : '';
$filter_publisher_id = isset($_GET['filter_publisher_id']) ? intval($_GET['filter_publisher_id']) : '';

$filter_sql_parts = [];
if (!empty($filter_book_name)) {
   $filter_sql_parts[] = "p.book_name LIKE '%$filter_book_name%'";
}
if (!empty($filter_author_id)) {
   $filter_sql_parts[] = "p.author_id = '$filter_author_id'";
}
if (!empty($filter_publisher_id)) {
   $filter_sql_parts[] = "p.publisher_id = '$filter_publisher_id'";
}

$filter_where_clause = '';
if (!empty($filter_sql_parts)) {
   $filter_where_clause = 'WHERE ' . implode(' AND ', $filter_sql_parts);
}

mysqli_data_seek($authors_all, 0);
mysqli_data_seek($publishers_all, 0);

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">

   <?php include 'admin_header.php'; ?>

   <section class="container my-5">
      <h1 class="text-center text-uppercase mb-4">Shop products</h1>
      <div class="row justify-content-center">
         <div class="col-lg-8">
            <div class="card shadow mb-4">
               <div class="card-body">
                  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                     <h3 class="mb-4 text-center text-uppercase">Add product</h3>
                     <div class="mb-3">
                        <input type="text" name="book_name" class="form-control" placeholder="Enter book name" required>
                     </div>
                     <div class="mb-3">
                        <select name="author_id" class="form-select" required>
                           <option value="">Select author</option>
                           <?php
                           // Make sure authors_all is usable again for the add form
                           mysqli_data_seek($authors_all, 0); // Reset pointer
                           while ($row = mysqli_fetch_assoc($authors_all)) {
                           ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['author_name']); ?></option>
                           <?php } ?>
                        </select>
                     </div>
                     <div class="mb-3">
                        <select name="publisher_id" class="form-select" required>
                           <option value="">Select publisher</option>
                           <?php
                           // Make sure publishers_all is usable again for the add form
                           mysqli_data_seek($publishers_all, 0); // Reset pointer
                           while ($row = mysqli_fetch_assoc($publishers_all)) {
                           ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['publisher_name']); ?></option>
                           <?php } ?>
                        </select>
                     </div>
                     <div class="mb-3">
                        <textarea name="book_description" class="form-control" placeholder="Enter book description"></textarea>
                     </div>
                     <div class="mb-3">
                        <input type="text" name="tag" class="form-control" placeholder="Tag (e.g. bestseller, new release, sales)">
                     </div>
                     <div class="mb-3">
                        <input type="number" name="publish_year" class="form-control" placeholder="Publish year">
                     </div>
                     <div class="mb-3">
                        <input type="number" name="total_page" class="form-control" placeholder="Total pages">
                     </div>
                     <div class="mb-3">
                        <input type="number" min="0" name="price" class="form-control" placeholder="Enter product price" required>
                     </div>
                     <div class="mb-3">
                        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="form-control" required>
                     </div>
                     <div class="d-grid">
                        <input type="submit" value="Add product" name="add_product" class="btn btn-primary">
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section class="container my-5">
      <h2 class="text-center text-uppercase mb-4">Filter Products</h2>
      <div class="row justify-content-center">
         <div class="col-lg-8">
            <div class="card shadow mb-4">
               <div class="card-body">
                  <form action="" method="get" class="row g-3 align-items-end">
                     <div class="col-md-4">
                        <label for="filter_book_name" class="form-label">Book Name</label>
                        <input type="text" class="form-control" id="filter_book_name" name="filter_book_name" value="<?php echo htmlspecialchars($filter_book_name); ?>" placeholder="Enter book name">
                     </div>
                     <div class="col-md-4">
                        <label for="filter_author_id" class="form-label">Author</label>
                        <select name="filter_author_id" id="filter_author_id" class="form-select">
                           <option value="">All Authors</option>
                           <?php
                           // Fetch authors for filter dropdown
                           $authors_filter = mysqli_query($conn, "SELECT id, author_name FROM `author` ORDER BY author_name ASC") or die('Failed to fetch authors');
                           while ($row = mysqli_fetch_assoc($authors_filter)) {
                              $selected = ($row['id'] == $filter_author_id) ? 'selected' : '';
                              echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['author_name']) . '</option>';
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-md-4">
                        <label for="filter_publisher_id" class="form-label">Publisher</label>
                        <select name="filter_publisher_id" id="filter_publisher_id" class="form-select">
                           <option value="">All Publishers</option>
                           <?php
                           // Fetch publishers for filter dropdown
                           $publishers_filter = mysqli_query($conn, "SELECT id, publisher_name FROM `publisher` ORDER BY publisher_name ASC") or die('Failed to fetch publishers');
                           while ($row = mysqli_fetch_assoc($publishers_filter)) {
                              $selected = ($row['id'] == $filter_publisher_id) ? 'selected' : '';
                              echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['publisher_name']) . '</option>';
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-info me-2"><i class="fas fa-filter"></i> Filter</button>
                        <a href="admin_products.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear Filter</a>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="container mb-5">
      <div class="row g-4">
         <?php
         $select_products = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
                LEFT JOIN `author` a ON p.author_id = a.id
                LEFT JOIN `publisher` pub ON p.publisher_id = pub.id
                " . $filter_where_clause . " ORDER BY p.book_name ASC") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <div class="col-md-3 col-sm-6">
                  <div class="card h-100 text-center shadow">
                     <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($fetch_products['book_name']); ?>">
                     <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($fetch_products['book_name']); ?></h5>
                        <p class="mb-1"><strong>Author:</strong> <?php echo htmlspecialchars($fetch_products['author_name']); ?></p>
                        <p class="mb-1"><strong>Publisher:</strong> <?php echo htmlspecialchars($fetch_products['publisher_name']); ?></p>
                        <p class="mb-1"><strong>Year:</strong> <?php echo htmlspecialchars($fetch_products['publish_year']); ?></p>
                        <p class="mb-1"><strong>Pages:</strong> <?php echo htmlspecialchars($fetch_products['total_page']); ?></p>
                        <p class="mb-1"><strong>Tag:</strong> <?php echo htmlspecialchars($fetch_products['tag']); ?></p>
                        <p class="card-text text-danger fw-bold">$<?php echo $fetch_products['price']; ?>/-</p>
                        <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="btn btn-warning me-2">Update</a>
                        <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="btn btn-danger" onclick="return confirm('delete this product?');">Delete</a>
                     </div>
                  </div>
               </div>
         <?php
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-info text-center">No products found matching your criteria!</div></div>';
         }
         ?>
      </div>
   </section>

   <?php
   if (isset($_GET['update'])) {
      $update_id = intval($_GET['update']);
      $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $update_id") or die('query failed');
      if (mysqli_num_rows($update_query) > 0) {
         $fetch_update = mysqli_fetch_assoc($update_query);

         // Fetch authors and publishers again for dropdowns in modal
         $authors_modal = mysqli_query($conn, "SELECT id, author_name FROM `author` ORDER BY author_name ASC") or die('Failed to fetch authors');
         $publishers_modal = mysqli_query($conn, "SELECT id, publisher_name FROM `publisher` ORDER BY publisher_name ASC") or die('Failed to fetch publishers');
   ?>
         <div class="modal fade show" id="editProductModal" tabindex="-1" aria-modal="true" style="display:block; background:rgba(0,0,0,0.5);">
            <div class="modal-dialog">
               <div class="modal-content">
                  <form action="" method="post" enctype="multipart/form-data">
                     <div class="modal-header">
                        <h5 class="modal-title">Update Product</h5>
                        <a href="admin_products.php" class="btn-close"></a>
                     </div>
                     <div class="modal-body">
                        <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                        <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                        <div class="mb-3 text-center">
                           <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="" class="img-fluid mb-2" style="max-height:200px;">
                        </div>
                        <div class="mb-3">
                           <input type="text" name="update_book_name" value="<?php echo htmlspecialchars($fetch_update['book_name']); ?>" class="form-control" required placeholder="Enter book name">
                        </div>
                        <div class="mb-3">
                           <select name="update_author_id" class="form-select" required>
                              <option value="">Select author</option>
                              <?php while ($row = mysqli_fetch_assoc($authors_modal)) { ?>
                                 <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $fetch_update['author_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($row['author_name']); ?>
                                 </option>
                              <?php } ?>
                           </select>
                        </div>
                        <div class="mb-3">
                           <select name="update_publisher_id" class="form-select" required>
                              <option value="">Select publisher</option>
                              <?php while ($row = mysqli_fetch_assoc($publishers_modal)) { ?>
                                 <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $fetch_update['publisher_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($row['publisher_name']); ?>
                                 </option>
                              <?php } ?>
                           </select>
                        </div>
                        <div class="mb-3">
                           <textarea name="update_book_description" class="form-control" placeholder="Enter book description"><?php echo htmlspecialchars($fetch_update['book_description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                           <input type="text" name="update_tag" value="<?php echo htmlspecialchars($fetch_update['tag']); ?>" class="form-control" placeholder="Tag (e.g. bestseller, new release, sales)">
                        </div>
                        <div class="mb-3">
                           <input type="number" name="update_publish_year" value="<?php echo htmlspecialchars($fetch_update['publish_year']); ?>" class="form-control" placeholder="Publish year">
                        </div>
                        <div class="mb-3">
                           <input type="number" name="update_total_page" value="<?php echo htmlspecialchars($fetch_update['total_page']); ?>" class="form-control" placeholder="Total pages">
                        </div>
                        <div class="mb-3">
                           <input type="number" name="update_price" value="<?php echo htmlspecialchars($fetch_update['price']); ?>" min="0" class="form-control" required placeholder="Enter product price">
                        </div>
                        <div class="mb-3">
                           <input type="file" class="form-control" name="update_image" accept="image/jpg, image/jpeg, image/png">
                        </div>
                     </div>
                     <div class="modal-footer">
                        <input type="submit" value="Update" name="update_product" class="btn btn-success">
                        <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <script>
            // Auto-focus modal and close on background click
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
            document.addEventListener('click', function(e) {
               // Check if the click was on the modal backdrop, not inside the modal content
               if (e.target.classList.contains('modal') && e.target.id === 'editProductModal') {
                  window.location.href = 'admin_products.php';
               }
            });
         </script>
   <?php
      }
   }
   ?>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>