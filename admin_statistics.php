<?php
include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) header('location:login.php');

$authors = $conn->query("SELECT id, author_name FROM author")->fetch_all(MYSQLI_ASSOC);
$publishers = $conn->query("SELECT id, publisher_name FROM publisher")->fetch_all(MYSQLI_ASSOC);

$type = $_GET['type'] ?? '';
$value = $_GET['value'] ?? '';

$sql = "SELECT total_products FROM orders WHERE payment_status = 'completed'";
$result = $conn->query($sql);

$productCounts = []; // ['Product Name' => quantity]

while ($row = $result->fetch_assoc()) {
    $items = explode(',', $row['total_products']);
    foreach ($items as $item) {
        if (preg_match('/(.*)\s\((\d+)\)/', trim($item), $matches)) {
            $name = trim($matches[1]);
            $qty = (int)$matches[2];
            if (!isset($productCounts[$name])) $productCounts[$name] = 0;
            $productCounts[$name] += $qty;
        }
    }
}

$filter_id = null;
if ($type == 'author' && $value !== '') {
    $authorResult = $conn->query("SELECT id FROM author WHERE id = " . intval($value));
    $authorRow = $authorResult->fetch_assoc();
    $filter_id = $authorRow['id'] ?? null;
} elseif ($type == 'publisher' && $value !== '') {
    $publisherResult = $conn->query("SELECT id FROM publisher WHERE id = " . intval($value));
    $publisherRow = $publisherResult->fetch_assoc();
    $filter_id = $publisherRow['id'] ?? null;
}

$quantityData = [];

$revenueData = []; // ['product name' => total revenue]
$filteredQuantityData = [];
$priceData = [];

foreach ($productCounts as $name => $qty) {
    $productInfo = $conn->query("SELECT * FROM products WHERE book_name = '" . $conn->real_escape_string($name) . "'")->fetch_assoc();
    if (!$productInfo) continue;

    $quantityData[$name] = $qty;

    if (($type == 'author' && $productInfo['author_id'] != $filter_id) ||
        ($type == 'publisher' && $productInfo['publisher_id'] != $filter_id)
    ) {
        continue;
    }

    $revenue = $productInfo['price'] * $qty;
    $revenueData[$name] = $revenue;
    $filteredQuantityData[$name] = $qty;
    $priceData[$name] = $productInfo['price'];
}

arsort($revenueData);
$labels = array_keys($revenueData);
$data = array_values($revenueData);
$totalRevenue = array_sum($data);

arsort($quantityData);
$top5Labels = array_slice(array_keys($quantityData), 0, 5);
$top5Quantities = array_slice($quantityData, 0, 5);

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

<body>
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
                <h5>Quantity Sold:</h5>
                <table class="table table-bordered table-striped" style="max-width:700px;">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Unit Price (đ)</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($labels as $label) {
                            $qty = $filteredQuantityData[$label] ?? 0;
                            $price = $priceData[$label] ?? 0;
                            echo "<tr>
                                    <td>" . htmlspecialchars($label) . "</td>
                                    <td>" . number_format($price) . "</td>
                                    <td>$qty</td>
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
                    data: <?= json_encode($data) ?>,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: '#0d6efd',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Revenue Chart by Product'
                    }
                },
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
                plugins: {
                    title: {
                        display: true,
                        text: 'Top 5 Best-Selling Books by Quantity'
                    }
                },
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