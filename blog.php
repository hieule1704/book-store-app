<?php
include 'config.php';
include_once __DIR__ . '/session_config.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

// --- FILTERS ---
$search = $_GET['search'] ?? '';
$cat_id = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Base Query
$sql = "SELECT b.*, a.author_name, a.profile_picture, c.name as category_name 
        FROM `blogs` b 
        LEFT JOIN `author` a ON b.author_id = a.id 
        LEFT JOIN `categories` c ON b.category_id = c.id 
        WHERE 1=1";

if ($search) {
    $sql .= " AND (b.title LIKE '%" . mysqli_real_escape_string($conn, $search) . "%')";
}
if ($cat_id) {
    $sql .= " AND b.category_id = $cat_id";
}

$sql .= " ORDER BY b.created_at DESC";
$result = mysqli_query($conn, $sql);

// Fetch Sidebar Data
$categories = mysqli_query($conn, "SELECT * FROM categories");
$popular_posts = mysqli_query($conn, "SELECT id, title, image, created_at FROM blogs ORDER BY views DESC LIMIT 4");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .blog-card {
            transition: transform 0.2s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .blog-img {
            height: 200px;
            object-fit: cover;
        }

        .featured-img {
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
        }

        .author-img {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
        }

        .sidebar-link {
            text-decoration: none;
            color: #555;
            display: block;
            padding: 5px 0;
            transition: 0.2s;
        }

        .sidebar-link:hover {
            color: #0d6efd;
            padding-left: 5px;
        }

        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <!-- HERO SECTION (Only show on main page without filters) -->
    <?php if (!$search && !$cat_id && mysqli_num_rows($result) > 0):
        $featured = mysqli_fetch_assoc($result); // Take the first one as featured
        // Don't reset pointer, we will display the rest in the grid
    ?>
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-7 position-relative">
                    <a href="blog.php?category=<?php echo $featured['category_id']; ?>" class="badge bg-primary mb-2 text-decoration-none">
                        <?php echo htmlspecialchars($featured['category_name'] ?? 'General'); ?>
                    </a>
                    <h1 class="display-4 fw-bold mb-3"><?php echo htmlspecialchars($featured['title']); ?></h1>
                    <p class="lead text-muted"><?php echo mb_substr(strip_tags($featured['content']), 0, 150); ?>...</p>

                    <div class="d-flex align-items-center mt-4">
                        <img src="uploaded_img/<?php echo htmlspecialchars($featured['profile_picture']); ?>" class="author-img me-2">
                        <small class="text-muted fw-bold"><?php echo htmlspecialchars($featured['author_name']); ?></small>
                        <span class="mx-2 text-muted">•</span>
                        <small class="text-muted"><?php echo date('M d, Y', strtotime($featured['created_at'])); ?></small>
                    </div>
                    <a href="blog_detail.php?id=<?php echo $featured['id']; ?>" class="btn btn-outline-dark mt-4 rounded-pill px-4">Read Article</a>
                </div>
                <div class="col-lg-5 mt-4 mt-lg-0">
                    <a href="blog_detail.php?id=<?php echo $featured['id']; ?>">
                        <img src="uploaded_img/<?php echo htmlspecialchars($featured['image']); ?>" class="img-fluid featured-img shadow w-100" alt="">
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container pb-5">
        <div class="row">
            <!-- MAIN CONTENT -->
            <div class="col-lg-8">
                <h3 class="fw-bold mb-4 border-bottom pb-2">Latest Articles</h3>
                <div class="row g-4">
                    <?php
                    // If we showed a featured post, the pointer is at 1. 
                    // If filtered, pointer is at 0.
                    // We continue fetching from current pointer.
                    if (mysqli_num_rows($result) > 0) {
                        while ($blog = mysqli_fetch_assoc($result)) {
                    ?>
                            <div class="col-md-6">
                                <div class="card blog-card h-100 shadow-sm">
                                    <div class="position-relative">
                                        <img src="uploaded_img/<?php echo htmlspecialchars($blog['image']); ?>" class="card-img-top blog-img" alt="">
                                        <a href="blog.php?category=<?php echo $blog['category_id']; ?>" class="category-badge">
                                            <?php echo htmlspecialchars($blog['category_name'] ?? 'General'); ?>
                                        </a>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold">
                                            <a href="blog_detail.php?id=<?php echo $blog['id']; ?>" class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($blog['title']); ?>
                                            </a>
                                        </h5>
                                        <p class="card-text text-muted small flex-grow-1">
                                            <?php echo mb_substr(strip_tags($blog['content']), 0, 90); ?>...
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-3">
                                            <div class="d-flex align-items-center">
                                                <img src="uploaded_img/<?php echo htmlspecialchars($blog['profile_picture']); ?>" class="author-img me-2">
                                                <small class="text-muted"><?php echo htmlspecialchars($blog['author_name']); ?></small>
                                            </div>
                                            <small class="text-muted"><i class="far fa-eye me-1"></i><?php echo $blog['views']; ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12 alert alert-warning">No stories found matching your criteria.</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4 ps-lg-5 mt-5 mt-lg-0">

                <!-- Search Widget -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Search</h5>
                        <form action="blog.php" method="get">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search topics..." value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Categories Widget -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Categories</h5>
                        <div class="d-flex flex-column gap-1">
                            <a href="blog.php" class="sidebar-link <?php echo ($cat_id == 0) ? 'fw-bold text-primary' : ''; ?>">All Topics</a>
                            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                                <a href="blog.php?category=<?php echo $cat['id']; ?>" class="sidebar-link <?php echo ($cat_id == $cat['id']) ? 'fw-bold text-primary' : ''; ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>

                <!-- Popular Posts Widget -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Popular Now</h5>
                        <?php while ($pop = mysqli_fetch_assoc($popular_posts)): ?>
                            <div class="d-flex mb-3 align-items-center">
                                <img src="uploaded_img/<?php echo htmlspecialchars($pop['image']); ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                <div class="ms-3">
                                    <a href="blog_detail.php?id=<?php echo $pop['id']; ?>" class="text-decoration-none text-dark fw-semibold d-block lh-sm">
                                        <?php echo htmlspecialchars($pop['title']); ?>
                                    </a>
                                    <small class="text-muted"><?php echo date('M d', strtotime($pop['created_at'])); ?></small>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>