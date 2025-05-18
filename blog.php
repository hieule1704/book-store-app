<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>

    <!-- Bootstrap 5.3.x CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="bg-light py-4 mb-4">
        <div class="container">
            <h3 class="mb-1">Blog</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Blog</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="container py-5">
        <h1 class="text-center text-uppercase mb-4">Latest Posts</h1>
        <div class="row g-4">
            <?php
            $select_blog = mysqli_query($conn, "SELECT * FROM `blogs` ORDER BY created_at DESC") or die('query failed');
            if (mysqli_num_rows($select_blog) > 0) {
                while ($blog = mysqli_fetch_assoc($select_blog)) {
            ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow h-100">
                            <?php if (!empty($blog['image'])): ?>
                                <img src="uploaded_img/<?php echo htmlspecialchars($blog['image']); ?>" class="card-img-top" alt="">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars(substr($blog['content'], 0, 150))); ?>...</p>
                                <div class="mt-auto">
                                    <a href="blog_detail.php?id=<?php echo $blog['id']; ?>" class="btn btn-primary btn-sm">Read More</a>
                                </div>
                            </div>
                            <div class="card-footer text-muted small">
                                Posted on <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="col-12"><div class="alert alert-info text-center">No blog posts found.</div></div>';
            }
            ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap 5.3.x JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>