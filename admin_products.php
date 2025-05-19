<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

// Fetch authors and publishers for dropdowns
$authors = mysqli_query($conn, "SELECT id, author_name FROM `author`") or die('Failed to fetch authors');
$publishers = mysqli_query($conn, "SELECT id, publisher_name FROM `publisher`") or die('Failed to fetch publishers');

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
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">

   <?php include 'admin_header.php'; ?>

   <!-- product CRUD section starts  -->

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
                           <?php while ($row = mysqli_fetch_assoc($authors)) { ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['author_name']); ?></option>
                           <?php } ?>
                        </select>
                     </div>
                     <div class="mb-3">
                        <select name="publisher_id" class="form-select" required>
                           <option value="">Select publisher</option>
                           <?php while ($row = mysqli_fetch_assoc($publishers)) { ?>
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

   <!-- product CRUD section ends -->

   <!-- show products  -->

   <section class="container mb-5">
      <div class="row g-4">
         <?php
         $select_products = mysqli_query($conn, "SELECT p.*, a.author_name, pub.publisher_name FROM `products` p
            LEFT JOIN `author` a ON p.author_id = a.id
            LEFT JOIN `publisher` pub ON p.publisher_id = pub.id
            ") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <div class="col-md-3 col-sm-6">
                  <div class="card h-100 text-center shadow">
                     <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" class="card-img-top" alt="">
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
            echo '<div class="col-12"><div class="alert alert-info text-center">No products added yet!</div></div>';
         }
         ?>
      </div>
   </section>

   <!-- Edit product modal (Bootstrap modal for better UX) -->
   <?php
   if (isset($_GET['update'])) {
      $update_id = $_GET['update'];
      $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
      if (mysqli_num_rows($update_query) > 0) {
         $fetch_update = mysqli_fetch_assoc($update_query);

         // Fetch authors and publishers again for dropdowns in modal
         $authors_modal = mysqli_query($conn, "SELECT id, author_name FROM `author`") or die('Failed to fetch authors');
         $publishers_modal = mysqli_query($conn, "SELECT id, publisher_name FROM `publisher`") or die('Failed to fetch publishers');
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
               if (e.target.classList.contains('modal')) {
                  window.location.href = 'admin_products.php';
               }
            });
         </script>
   <?php
      }
   }
   ?>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>