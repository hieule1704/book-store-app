<?php

include 'config.php';

// remove direct session_start(); and include the secure session config instead
include_once __DIR__ . '/session_config.php';

$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
if (!$admin_id) {
    header('Location: login.php');
    exit;
}

// Add author
if (isset($_POST['add_author'])) {
    $author_name = mysqli_real_escape_string($conn, $_POST['author_name']);
    $profile_picture = $_FILES['profile_picture']['name'];
    $profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];
    $profile_picture_folder = 'uploaded_img/' . $profile_picture;

    $select_author = mysqli_query($conn, "SELECT author_name FROM `author` WHERE author_name = '$author_name'") or die('query failed');
    if (mysqli_num_rows($select_author) > 0) {
        $message[] = 'Author already exists!';
    } else {
        $add_author = mysqli_query($conn, "INSERT INTO `author`(author_name, profile_picture) VALUES('$author_name', '$profile_picture')") or die('query failed');
        if ($add_author) {
            move_uploaded_file($profile_picture_tmp, $profile_picture_folder);
            $message[] = 'Author added successfully!';
        } else {
            $message[] = 'Failed to add author!';
        }
    }
}

// Delete author
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $delete_img_query = mysqli_query($conn, "SELECT profile_picture FROM `author` WHERE id = '$delete_id'") or die('query failed');
    $fetch_img = mysqli_fetch_assoc($delete_img_query);
    if ($fetch_img && $fetch_img['profile_picture']) {
        @unlink('uploaded_img/' . $fetch_img['profile_picture']);
    }
    mysqli_query($conn, "DELETE FROM `author` WHERE id = $delete_id") or die('query failed');
    header('Location: admin_authors.php');
    exit;
}

// Update author
if (isset($_POST['update_author'])) {
    $update_id = $_POST['update_id'];
    $update_author_name = mysqli_real_escape_string($conn, $_POST['update_author_name']);

    mysqli_query($conn, "UPDATE `author` SET author_name = '$update_author_name' WHERE id = '$update_id'") or die('query failed');

    $update_profile_picture = $_FILES['update_profile_picture']['name'];
    $update_profile_picture_tmp = $_FILES['update_profile_picture']['tmp_name'];
    $update_old_picture = $_POST['update_old_picture'];
    $update_folder = 'uploaded_img/' . $update_profile_picture;

    if (!empty($update_profile_picture)) {
        mysqli_query($conn, "UPDATE `author` SET profile_picture = '$update_profile_picture' WHERE id = '$update_id'") or die('query failed');
        move_uploaded_file($update_profile_picture_tmp, $update_folder);
        @unlink('uploaded_img/' . $update_old_picture);
    }

    header('location:admin_authors.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">

    <?php include 'admin_header.php'; ?>

    <section class="container my-5">
        <h1 class="text-center text-uppercase mb-4">Manage Authors</h1>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <h3 class="mb-4 text-center text-uppercase">Add Author</h3>
                            <div class="mb-3">
                                <input type="text" name="author_name" class="form-control" placeholder="Enter author name" required>
                            </div>
                            <div class="mb-3">
                                <input type="file" name="profile_picture" accept="image/jpg, image/jpeg, image/png" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <input type="submit" value="Add Author" name="add_author" class="btn btn-primary">
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
            $select_authors = mysqli_query($conn, "SELECT * FROM `author`") or die('query failed');
            if (mysqli_num_rows($select_authors) > 0) {
                while ($author = mysqli_fetch_assoc($select_authors)) {
            ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100 text-center shadow">
                            <div class="pt-4">
                                <img src="uploaded_img/<?php echo htmlspecialchars($author['profile_picture']); ?>" class="rounded-circle shadow" style="width:100px; height:100px; object-fit:cover;" alt="">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($author['author_name']); ?></h5>
                                <a href="admin_authors.php?update=<?php echo $author['id']; ?>" class="btn btn-warning me-2">Update</a>
                                <a href="admin_authors.php?delete=<?php echo $author['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this author?');">Delete</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="col-12"><div class="alert alert-info text-center">No authors added yet!</div></div>';
            }
            ?>
        </div>
    </section>

    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $update_query = mysqli_query($conn, "SELECT * FROM `author` WHERE id = '$update_id'") or die('query failed');
        if (mysqli_num_rows($update_query) > 0) {
            $fetch_update = mysqli_fetch_assoc($update_query);
    ?>
            <div class="modal fade show" id="editAuthorModal" tabindex="-1" aria-modal="true" style="display:block; background:rgba(0,0,0,0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Author</h5>
                                <a href="admin_authors.php" class="btn-close"></a>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="update_id" value="<?php echo $fetch_update['id']; ?>">
                                <input type="hidden" name="update_old_picture" value="<?php echo $fetch_update['profile_picture']; ?>">
                                <div class="mb-3 text-center">
                                    <img src="uploaded_img/<?php echo htmlspecialchars($fetch_update['profile_picture']); ?>" alt="" class="img-fluid mb-2 rounded-circle shadow" style="max-height:120px;">
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="update_author_name" value="<?php echo htmlspecialchars($fetch_update['author_name']); ?>" class="form-control" required placeholder="Enter author name">
                                </div>
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="update_profile_picture" accept="image/jpg, image/jpeg, image/png">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" value="Update" name="update_author" class="btn btn-success">
                                <a href="admin_authors.php" class="btn btn-secondary">Cancel</a>
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
                        window.location.href = 'admin_authors.php';
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