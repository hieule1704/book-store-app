<?php

include 'config.php';

// remove direct session_start(); and include the secure session config instead
include_once __DIR__ . '/session_config.php';

$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
if (!$admin_id) {
    header('Location: login.php');
    exit;
}

// Fetch authors and categories for dropdowns
$authors = mysqli_query($conn, "SELECT id, author_name FROM `author`") or die('Failed to fetch authors');
$categories = mysqli_query($conn, "SELECT * FROM `categories`") or die('Failed to fetch categories');

// Add blog
if (isset($_POST['add_blog'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = intval($_POST['author_id']);
    $category_id = intval($_POST['category_id']); // NEW
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    if ($content != "") {
        $add_blog = mysqli_query($conn, "INSERT INTO `blogs`(title, content, image, author_id, category_id) VALUES('$title', '$content', '$image', '$author_id', '$category_id')") or die('query failed');
        if ($add_blog) {
            if (!empty($image)) {
                move_uploaded_file($image_tmp, $image_folder);
            }
            $message[] = 'Blog added successfully!';
        } else {
            $message[] = 'Failed to add blog!';
        }
    } else {
        $message[] = "Content cannot be empty!";
    }
}

// Delete blog
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $delete_img_query = mysqli_query($conn, "SELECT image FROM `blogs` WHERE id = '$delete_id'") or die('query failed');
    $fetch_img = mysqli_fetch_assoc($delete_img_query);
    if ($fetch_img && $fetch_img['image']) {
        @unlink('uploaded_img/' . $fetch_img['image']);
    }
    mysqli_query($conn, "DELETE FROM `blogs` WHERE id = $delete_id") or die('query failed');
    header('Location: admin_blogs.php');
    exit;
}

// Update blog
if (isset($_POST['update_blog'])) {
    $update_id = $_POST['update_id'];
    $update_title = mysqli_real_escape_string($conn, $_POST['update_title']);
    $update_content = mysqli_real_escape_string($conn, $_POST['update_content']);
    $update_author_id = intval($_POST['update_author_id']);
    $update_category_id = intval($_POST['update_category_id']); // NEW

    mysqli_query($conn, "UPDATE `blogs` SET title = '$update_title', content = '$update_content', author_id = '$update_author_id', category_id = '$update_category_id' WHERE id = '$update_id'") or die('query failed');

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp = $_FILES['update_image']['tmp_name'];
    $update_old_image = $_POST['update_old_image'];
    $update_folder = 'uploaded_img/' . $update_image;

    if (!empty($update_image)) {
        mysqli_query($conn, "UPDATE `blogs` SET image = '$update_image' WHERE id = '$update_id'") or die('query failed');
        move_uploaded_file($update_image_tmp, $update_folder);
        @unlink('uploaded_img/' . $update_old_image);
    }

    header('location:admin_blogs.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>

<body class="bg-light">

    <?php include 'admin_header.php'; ?>

    <section class="container my-5">
        <h1 class="text-center text-uppercase mb-4">Manage Blogs</h1>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <h3 class="mb-4 text-center text-uppercase">Create New Post</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Author</label>
                                    <select name="author_id" class="form-select" required>
                                        <option value="">Select author</option>
                                        <?php
                                        mysqli_data_seek($authors, 0);
                                        while ($row = mysqli_fetch_assoc($authors)) { ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['author_name']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <?php
                                        mysqli_data_seek($categories, 0);
                                        while ($row = mysqli_fetch_assoc($categories)) { ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea id="editor" name="content" class="form-control" rows="5" placeholder="Start writing..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cover Image</label>
                                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="form-control">
                            </div>
                            <div class="d-grid">
                                <input type="submit" value="Publish Post" name="add_blog" class="btn btn-primary">
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
            // Modified query to join Categories
            $select_blogs = mysqli_query($conn, "
                SELECT b.*, a.author_name, c.name as category_name 
                FROM `blogs` b 
                LEFT JOIN `author` a ON b.author_id = a.id 
                LEFT JOIN `categories` c ON b.category_id = c.id 
                ORDER BY b.created_at DESC
            ") or die('query failed');

            if (mysqli_num_rows($select_blogs) > 0) {
                while ($blog = mysqli_fetch_assoc($select_blogs)) {
            ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100 shadow">
                            <?php
                            $defaultImage = "uploaded_img/default_blog_img.jpg";
                            $imageSrc = !empty($blog['image']) ? "uploaded_img/" . htmlspecialchars($blog['image']) : $defaultImage;
                            ?>
                            <img src="<?php echo $imageSrc; ?>" class="card-img-top object-fit-cover" alt="Blog Image" style="height:200px">
                            <div class="card-body">
                                <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($blog['category_name'] ?? 'Uncategorized'); ?></span>
                                <h5 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h5>
                                <p class="text-muted small">By <?php echo htmlspecialchars($blog['author_name']); ?></p>
                                <div class="d-flex gap-2 mt-3">
                                    <a href="admin_blogs.php?update=<?php echo $blog['id']; ?>" class="btn btn-warning flex-fill">Edit</a>
                                    <a href="admin_blogs.php?delete=<?php echo $blog['id']; ?>" class="btn btn-danger flex-fill" onclick="return confirm('Delete this blog?');">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="col-12"><div class="alert alert-info text-center">No blogs added yet!</div></div>';
            }
            ?>
        </div>
    </section>

    <!-- UPDATE MODAL -->
    <?php
    if (isset($_GET['update'])) {
        $update_id = intval($_GET['update']);
        $update_query = mysqli_query($conn, "SELECT * FROM `blogs` WHERE id = '$update_id'") or die('query failed');
        if (mysqli_num_rows($update_query) > 0) {
            $fetch_update = mysqli_fetch_assoc($update_query);

            // Reset pointers for modal dropdowns
            mysqli_data_seek($authors, 0);
            mysqli_data_seek($categories, 0);
    ?>
            <div class="modal fade show" id="editBlogModal" tabindex="-1" aria-modal="true" style="display:block; background:rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Blog</h5>
                                <a href="admin_blogs.php" class="btn-close"></a>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="update_id" value="<?php echo $fetch_update['id']; ?>">
                                <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label>Title</label>
                                            <input type="text" name="update_title" value="<?php echo htmlspecialchars($fetch_update['title']); ?>" class="form-control" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Author</label>
                                                <select name="update_author_id" class="form-select" required>
                                                    <?php while ($row = mysqli_fetch_assoc($authors)) { ?>
                                                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $fetch_update['author_id']) echo 'selected'; ?>>
                                                            <?php echo htmlspecialchars($row['author_name']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Category</label>
                                                <select name="update_category_id" class="form-select" required>
                                                    <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                                                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $fetch_update['category_id']) echo 'selected'; ?>>
                                                            <?php echo htmlspecialchars($row['name']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label>Content</label>
                                            <textarea id="editorUpdate" name="update_content" class="form-control" rows="8"><?php echo htmlspecialchars($fetch_update['content']); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cover Image</label>
                                        <?php if (!empty($fetch_update['image'])) { ?>
                                            <img src="uploaded_img/<?php echo htmlspecialchars($fetch_update['image']); ?>" class="img-fluid rounded mb-3 d-block border">
                                        <?php } ?>
                                        <input type="file" class="form-control" name="update_image" accept="image/jpg, image/jpeg, image/png">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" value="Save Changes" name="update_blog" class="btn btn-success">
                                <a href="admin_blogs.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                document.body.classList.add('modal-open');
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('modal')) {
                        window.location.href = 'admin_blogs.php';
                    }
                });
            </script>
    <?php
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#editor')).catch(error => console.error(error));
        const editorUpdate = document.querySelector('#editorUpdate');
        if (editorUpdate) {
            ClassicEditor.create(editorUpdate).catch(error => console.error(error));
        }
    </script>
</body>

</html>