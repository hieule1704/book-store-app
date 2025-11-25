<?php

include 'config.php';

// remove direct session_start(); and include the secure session config instead
include_once __DIR__ . '/session_config.php';

$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
if (!$admin_id) {
    header('Location: login.php');
    exit;
}

// Fetch authors for dropdown
$authors = mysqli_query($conn, "SELECT id, author_name FROM `author`") or die('Failed to fetch authors');

// Add blog
if (isset($_POST['add_blog'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = $_POST['author_id'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    if ($content != "") {
        $add_blog = mysqli_query($conn, "INSERT INTO `blogs`(title, content, image, author_id) VALUES('$title', '$content', '$image', '$author_id')") or die('query failed');
        if ($add_blog) {
            if (!empty($image)) {
                move_uploaded_file($image_tmp, $image_folder);
            }
            $message[] = 'Blog added successfully!';
        } else {
            $message[] = 'Failed to add blog!';
        }
    } else {
        $message[] = "$content";
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
    $update_author_id = $_POST['update_author_id'];

    mysqli_query($conn, "UPDATE `blogs` SET title = '$update_title', content = '$update_content', author_id = '$update_author_id' WHERE id = '$update_id'") or die('query failed');

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
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <h3 class="mb-4 text-center text-uppercase">Add Blog</h3>
                            <div class="mb-3">
                                <input type="text" name="title" class="form-control" placeholder="Enter blog title" required>
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
                                <textarea id="editor" name="content" class="form-control" rows="5" placeholder="Enter blog content"></textarea>
                            </div>
                            <div class="mb-3">
                                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="form-control">
                            </div>
                            <div class="d-grid">
                                <input type="submit" value="Add Blog" name="add_blog" class="btn btn-primary">
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
            $select_blogs = mysqli_query($conn, "SELECT b.*, a.author_name FROM `blogs` b LEFT JOIN `author` a ON b.author_id = a.id ORDER BY b.created_at DESC") or die('query failed');
            if (mysqli_num_rows($select_blogs) > 0) {
                while ($blog = mysqli_fetch_assoc($select_blogs)) {
            ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100 shadow">
                            <?php
                            $defaultImage = "uploaded_img/default_blog_img.jpg";
                            $imageSrc = !empty($blog['image']) ? "uploaded_img/" . htmlspecialchars($blog['image']) : $defaultImage;
                            ?>
                            <img src="<?php echo $imageSrc; ?>" class="card-img-top img-fluid    object-fit-cover" alt="Blog Image" style="height:150px">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h5>
                                <p class="mb-1"><strong>Author:</strong> <?php echo htmlspecialchars($blog['author_name']); ?></p>
                                <p class="card-text" style="max-height: 80px; overflow: hidden;"><?php echo nl2br(htmlspecialchars(mb_substr(strip_tags($blog['content']), 0, 100))); ?>...</p>
                                <a href="admin_blogs.php?update=<?php echo $blog['id']; ?>" class="btn btn-warning me-2">Update</a>
                                <a href="admin_blogs.php?delete=<?php echo $blog['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this blog?');">Delete</a>
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

    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $update_query = mysqli_query($conn, "SELECT * FROM `blogs` WHERE id = '$update_id'") or die('query failed');
        if (mysqli_num_rows($update_query) > 0) {
            $fetch_update = mysqli_fetch_assoc($update_query);
            $authors_modal = mysqli_query($conn, "SELECT id, author_name FROM `author`") or die('Failed to fetch authors');
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
                                <?php if (!empty($fetch_update['image'])) { ?>
                                    <div class="mb-3 text-center">
                                        <img src="uploaded_img/<?php echo htmlspecialchars($fetch_update['image']); ?>" alt="" class="img-fluid mb-2" style="max-height:180px;">
                                    </div>
                                <?php } ?>
                                <div class="mb-3">
                                    <input type="text" name="update_title" value="<?php echo htmlspecialchars($fetch_update['title']); ?>" class="form-control" required placeholder="Enter blog title">
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
                                    <textarea id="editorUpdate" name="update_content" class="form-control" rows="5" required><?php echo htmlspecialchars($fetch_update['content']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="update_image" accept="image/jpg, image/jpeg, image/png">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" value="Update" name="update_blog" class="btn btn-success">
                                <a href="admin_blogs.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
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
</body>

<script>
    // Add blog
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });

    // Edit blog
    const editorUpdate = document.querySelector('#editorUpdate');
    if (editorUpdate) {
        ClassicEditor
            .create(editorUpdate)
            .catch(error => {
                console.error(error);
            });
    }
</script>

</body>


</html>