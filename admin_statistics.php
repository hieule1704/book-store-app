<?php
include 'config.php';

// remove direct session_start(); and include the secure session config instead
include __DIR__ . '/session_config.php';

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) header('location:login.php');

$authors = $conn->query("SELECT id, author_name FROM author")->fetch_all(MYSQLI_ASSOC);
$publishers = $conn->query("SELECT id, publisher_name FROM publisher")->fetch_all(MYSQLI_ASSOC);

$type = $_GET['type'] ?? '';
$value = $_GET['value'] ?? '';

// --- NEW LOGIC USING ORDER_ITEMS TABLE ---

// Base SQL parts
$sql_joins = "
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
";

$sql_where = "WHERE o.payment_status = 'completed'";

// Apply Filters
if ($type == 'author' && $value !== '') {
    $sql_where .= " AND p.author_id = " . intval($value);
} elseif ($type == 'publisher' && $value !== '') {
    $sql_where .= " AND p.publisher_id = " . intval($value);
}

// 1. Fetch Data for Charts & Tables
$query = "
    SELECT 
        p.book_name, 
        SUM(oi.quantity) as total_qty, 
        SUM(oi.quantity * oi.price) as total_revenue,
        p.price as current_price
    $sql_joins
    $sql_where
    GROUP BY p.id
    ORDER BY total_revenue DESC
";

$result = $conn->query($query);

$labels = [];
$revenueData = [];
$quantityData = [];
$priceData = [];
$totalRevenue = 0;

while ($row = $result->fetch_assoc()) {
    $name = $row['book_name'];
    $labels[] = $name;
    $revenueData[] = $row['total_revenue'];
    $quantityData[$name] = $row['total_qty']; // Key-value for sorting top 5
    $priceData[] = $row['current_price'];
    $totalRevenue += $row['total_revenue'];
}

// Top 5 Logic
arsort($quantityData);
$top5Labels = array_slice(array_keys($quantityData), 0, 5);
$top5Quantities = array_slice(array_values($quantityData), 0, 5);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Revenue Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">
    <?php include 'admin_header.php'; ?>

    <section class="container my-5">
        <h1 class="text-center text-uppercase mb-4">Revenue Statistics</h1>
    </section>

    <div class="container my-5">
        <div class="card p-4 shadow mx-auto" style="max-width: 900px;">
            <h4 class="text-center mb-4 text-success">Product Revenue Statistics</h4>

            <form method="get" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Filter by --</option>
                        <option value="author" <?= $type == 'author' ? 'selected' : '' ?>>Author</option>
                        <option value="publisher" <?= $type == 'publisher' ? 'selected' : '' ?>>Publisher</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="value" class="form-select" onchange="this.form.submit()" <?= $type == '' ? 'disabled' : '' ?>>
                        <option value="">-- Select --</option>
                        <?php
                        $list = $type == 'author' ? $authors : $publishers;
                        if ($list) {
                            $key_id = 'id';
                            $key_name = $type == 'author' ? 'author_name' : 'publisher_name';
                            foreach ($list as $item) {
                                $v = $item[$key_id];
                                $name = $item[$key_name];
                                echo "<option value='$v' " . ($value == $v ? 'selected' : '') . ">$name</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <a href="admin_statistics.php" class="btn btn-secondary w-100">Reset Filter</a>
                </div>
            </form>

            <canvas id="revenueChart" height="300"></canvas>
            <p class="text-end mt-3 fw-bold text-primary">Total Revenue: $<?= number_format($totalRevenue) ?></p>

            <div class="mt-4">
                <h5>Sales Breakdown:</h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity Sold</th>
                            <th>Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($labels); $i++) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($labels[$i]) . "</td>
                                    <td>" . $quantityData[$labels[$i]] . "</td>
                                    <td>$" . number_format($revenueData[$i]) . "</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card p-4 shadow mx-auto mt-4" style="max-width: 900px;">
            <h4 class="text-center mb-4 text-success">Top 5 Best-Selling Books</h4>
            <canvas id="top5Chart" height="300"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Revenue ($)',
                    data: <?= json_encode($revenueData) ?>,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: '#0d6efd',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('top5Chart').getContext('2d');
        const top5Chart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?= json_encode($top5Labels) ?>,
                datasets: [{
                    label: 'Quantity Sold',
                    data: <?= json_encode($top5Quantities) ?>,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: '#28a745',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>