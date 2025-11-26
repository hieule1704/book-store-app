<?php
include 'config.php';
include_once __DIR__ . '/session_config.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM `orders` WHERE id = '$order_id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Transfer Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'header.php'; ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Bank Transfer Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <p>Please transfer the exact amount to the following account. Your order will be processed once the payment is verified manually.</p>

                        <ul class="list-group mb-4">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Bank Name:</span> <strong>MB Bank</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Account Number:</span> <strong>0000123456789</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Account Name:</span> <strong>NGUYEN VAN A</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <span>Amount:</span> <strong class="text-danger">$<?php echo number_format($order['total_price']); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <span>Transfer Content:</span> <strong>ORDER <?php echo $order_id; ?></strong>
                            </li>
                        </ul>

                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-circle"></i> <strong>Important:</strong> Please write the correct Transfer Content so we can verify your payment.
                        </div>

                        <div class="d-grid">
                            <a href="orders.php" class="btn btn-primary">I Have Transferred</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>