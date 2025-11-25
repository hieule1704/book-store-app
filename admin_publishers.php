<?php

include 'config.php';

// remove direct session_start(); and include the secure session config instead
include_once __DIR__ . '/session_config.php';

$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
if (!$admin_id) {
    header('Location: login.php');
    exit;
}

// Add publisher
if (isset($_POST['add_publisher'])) {
    $publisher_name = mysqli_real_escape_string($conn, $_POST['publisher_name']);
    $profile_image = $_FILES['profile_image']['name'];
    $profile_image_tmp = $_FILES['profile_image']['tmp_name'];
    $profile_image_folder = 'uploaded_img/' . $profile_image;

    $select_publisher = mysqli_query($conn, "SELECT publisher_name FROM `publisher` WHERE publisher_name = '$publisher_name'") or die('query failed');
    if (mysqli_num_rows($select_publisher) > 0) {
        $message[] = 'Publisher already exists!';
    } else {
        $add_publisher = mysqli_query($conn, "INSERT INTO `publisher`(publisher_name, profile_image) VALUES('$publisher_name', '$profile_image')") or die('query failed');
        if ($add_publisher) {
            move_uploaded_file($profile_image_tmp, $profile_image_folder);
            $message[] = 'Publisher added successfully!';
        } else {
            $message[] = 'Failed to add publisher!';
        }
    }
}

// Delete publisher
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $delete_img_query = mysqli_query($conn, "SELECT profile_image FROM `publisher` WHERE id = '$delete_id'") or die('query failed');
    $fetch_img = mysqli_fetch_assoc($delete_img_query);
    if ($fetch_img && $fetch_img['profile_image']) {
        @unlink('uploaded_img/' . $fetch_img['profile_image']);
    }
    mysqli_query($conn, "DELETE FROM `publisher` WHERE id = $delete_id") or die('query failed');
    header('Location: admin_publishers.php');
    exit;
}

// Update publisher
if (isset($_POST['update_publisher'])) {
    $update_id = $_POST['update_id'];
    $update_publisher_name = mysqli_real_escape_string($conn, $_POST['update_publisher_name']);

    mysqli_query($conn, "UPDATE `publisher` SET publisher_name = '$update_publisher_name' WHERE id = '$update_id'") or die('query failed');

    $update_profile_image = $_FILES['update_profile_image']['name'];
    $update_profile_image_tmp = $_FILES['update_profile_image']['tmp_name'];
    $update_old_image = $_POST['update_old_image'];
    $update_folder = 'uploaded_img/' . $update_profile_image;

    if (!empty($update_profile_image)) {
        mysqli_query($conn, "UPDATE `publisher` SET profile_image = '$update_profile_image' WHERE id = '$update_id'") or die('query failed');
        move_uploaded_file($update_profile_image_tmp, $update_folder);
        @unlink('uploaded_img/' . $update_old_image);
    }

    header('location:admin_publishers.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publishers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">

    <?php include 'admin_header.php'; ?>

    <section class="container my-5">
        <h1 class="text-center text-uppercase mb-4">Manage Publishers</h1>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <h3 class="mb-4 text-center text-uppercase">Add Publisher</h3>
                            <div class="mb-3">
                                <input type="text" name="publisher_name" class="form-control" placeholder="Enter publisher name" required>
                            </div>
                            <div class="mb-3">
                                <input type="file" name="profile_image" accept="image/jpg, image/jpeg, image/png" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <input type="submit" value="Add Publisher" name="add_publisher" class="btn btn-primary">
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
            $select_publishers = mysqli_query($conn, "SELECT * FROM `publisher`") or die('query failed');
            if (mysqli_num_rows($select_publishers) > 0) {
                while ($publisher = mysqli_fetch_assoc($select_publishers)) {
            ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100 text-center shadow">
                            <div class="pt-4">
                                <img src="uploaded_img/<?php echo htmlspecialchars($publisher['profile_image']); ?>" class="rounded-circle shadow" style="width:100px; height:100px; object-fit:cover;" alt="">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($publisher['publisher_name']); ?></h5>
                                <a href="admin_publishers.php?update=<?php echo $publisher['id']; ?>" class="btn btn-warning me-2">Update</a>
                                <a href="admin_publishers.php?delete=<?php echo $publisher['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this publisher?');">Delete</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="col-12"><div class="alert alert-info text-center">No publishers added yet!</div></div>';
            }
            ?>
        </div>
    </section>

    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $update_query = mysqli_query($conn, "SELECT * FROM `publisher` WHERE id = '$update_id'") or die('query failed');
        if (mysqli_num_rows($update_query) > 0) {
            $fetch_update = mysqli_fetch_assoc($update_query);
    ?>
            <div class="modal fade show" id="editPublisherModal" tabindex="-1" aria-modal="true" style="display:block; background:rgba(0,0,0,0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Publisher</h5>
                                <a href="admin_publishers.php" class="btn-close"></a>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="update_id" value="<?php echo $fetch_update['id']; ?>">
                                <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['profile_image']; ?>">
                                <div class="mb-3 text-center">
                                    <img src="uploaded_img/<?php echo htmlspecialchars($fetch_update['profile_image']); ?>" alt="" class="img-fluid mb-2 rounded-circle shadow" style="max-height:120px;">
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="update_publisher_name" value="<?php echo htmlspecialchars($fetch_update['publisher_name']); ?>" class="form-control" required placeholder="Enter publisher name">
                                </div>
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="update_profile_image" accept="image/jpg, image/jpeg, image/png">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" value="Update" name="update_publisher" class="btn btn-success">
                                <a href="admin_publishers.php" class="btn btn-secondary">Cancel</a>
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
                        window.location.href = 'admin_publishers.php';
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