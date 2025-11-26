<?php
include 'config.php';
include_once __DIR__ . '/session_config.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$blog_id) {
    header('location:blog.php');
    exit;
}

// INCREMENT VIEWS
mysqli_query($conn, "UPDATE blogs SET views = views + 1 WHERE id = $blog_id");

// FETCH BLOG DETAILS
$sql = "SELECT b.*, a.author_name, a.profile_picture, c.name as category_name, c.id as cat_id
        FROM `blogs` b 
        LEFT JOIN `author` a ON b.author_id = a.id 
        LEFT JOIN `categories` c ON b.category_id = c.id 
        WHERE b.id = $blog_id";
$result = mysqli_query($conn, $sql);
$blog = mysqli_fetch_assoc($result);

if (!$blog) {
    echo "Blog not found.";
    exit;
}

// FETCH RELATED POSTS
$cat_id = $blog['cat_id'];
$related_sql = "SELECT * FROM blogs WHERE category_id = '$cat_id' AND id != '$blog_id' LIMIT 3";
$related = mysqli_query($conn, $related_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reading Progress Bar */
        .progress-container {
            width: 100%;
            height: 5px;
            background: #f1f1f1;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
        }

        .progress-bar {
            height: 5px;
            background: #0d6efd;
            width: 0%;
        }

        .blog-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }

        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 20px 0;
        }

        .author-box {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
        }
    </style>
</head>

<body>

    <div class="progress-container">
        <div class="progress-bar" id="myBar"></div>
    </div>

    <?php include 'header.php'; ?>

    <div class="container py-5" style="max-width: 800px;">
        <!-- CATEGORY & DATE -->
        <div class="text-center mb-3">
            <a href="blog.php?category=<?php echo $blog['cat_id']; ?>" class="badge bg-primary text-decoration-none rounded-pill px-3">
                <?php echo htmlspecialchars($blog['category_name'] ?? 'General'); ?>
            </a>
            <span class="text-muted ms-2 small"><?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
        </div>

        <!-- TITLE -->
        <h1 class="display-5 fw-bold text-center mb-4"><?php echo htmlspecialchars($blog['title']); ?></h1>

        <!-- AUTHOR -->
        <div class="d-flex justify-content-center align-items-center mb-5">
            <img src="uploaded_img/<?php echo htmlspecialchars($blog['profile_picture']); ?>" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
            <div class="ms-2 text-start">
                <div class="fw-bold small"><?php echo htmlspecialchars($blog['author_name']); ?></div>
                <div class="text-muted small" style="font-size: 0.75rem;">Author</div>
            </div>
        </div>

        <!-- COVER IMAGE -->
        <img src="uploaded_img/<?php echo htmlspecialchars($blog['image']); ?>" class="img-fluid rounded-4 shadow-sm w-100 mb-5">

        <!-- CONTENT -->
        <div class="blog-content">
            <?php echo $blog['content']; ?>
        </div>

        <!-- SHARE & TAGS -->
        <div class="border-top mt-5 pt-4 d-flex justify-content-between">
            <div class="text-muted small"><i class="far fa-eye"></i> <?php echo $blog['views']; ?> Views</div>
            <div>
                <button class="btn btn-sm btn-outline-primary rounded-circle"><i class="fab fa-facebook-f"></i></button>
                <button class="btn btn-sm btn-outline-info rounded-circle"><i class="fab fa-twitter"></i></button>
                <button class="btn btn-sm btn-outline-success rounded-circle"><i class="fab fa-whatsapp"></i></button>
            </div>
        </div>

        <!-- RELATED POSTS -->
        <?php if (mysqli_num_rows($related) > 0): ?>
            <div class="mt-5">
                <h4 class="fw-bold mb-4">You might also like</h4>
                <div class="row g-4">
                    <?php while ($rel = mysqli_fetch_assoc($related)): ?>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="uploaded_img/<?php echo $rel['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold"><a href="blog_detail.php?id=<?php echo $rel['id']; ?>" class="text-decoration-none text-dark"><?php echo $rel['title']; ?></a></h6>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Reading Progress Bar Logic
        window.onscroll = function() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("myBar").style.width = scrolled + "%";
        };
    </script>
</body>

</html>