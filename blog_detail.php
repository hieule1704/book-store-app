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

$blog_id = $_GET['id'] ?? null;

if (!$blog_id) {
    header('location:blog.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM `blogs` WHERE id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card-text img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px auto;
        }
    </style>


<body>

    <?php include 'header.php'; ?>

    <div class="bg-light py-4 mb-4">
        <div class="container">
            <h3 class="mb-1">Blog Detail</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($blog['title']); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="container py-5">
        <?php if ($blog): ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <?php if (!empty($blog['image'])): ?>
                            <img src="uploaded_img/<?php echo htmlspecialchars($blog['image']); ?>" class="card-img-top" alt="">
                        <?php endif; ?>
                        <div class="card-body">
                            <h2 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h2>
                            <p class="text-muted small mb-2">
                                Posted on <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                            </p>
                            <div class="card-text">
                                <?php echo $blog['content']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger text-center">Blog post not found.</div>
        <?php endif; ?>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>