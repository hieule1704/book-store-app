<?php
include 'config.php';
include_once __DIR__ . '/session_config.php';

// --- 1. PAYMENT CONFIGURATION (EDIT THIS) ---
$bank_id      = 'MB';           // Example: MB, VCB, ACB, TPB, VPB (Look up your bank code on vietqr.io)
$account_no   = '0000123456789'; // YOUR REAL BANK ACCOUNT NUMBER
$account_name = 'NGUYEN VAN A'; // YOUR REAL ACCOUNT NAME (Uppercase, No Accents)
$template     = 'compact2';     // Style: 'compact', 'compact2', 'qr_only', 'print'

// --- 2. SECURITY CHECKS ---
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id == 0) {
    header('Location: home.php');
    exit;
}

// --- 3. FETCH ORDER DETAILS ---
$query = "SELECT * FROM `orders` WHERE id = '$order_id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $order = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Order not found!'); window.location.href='home.php';</script>";
    exit;
}

// --- 4. GENERATE QR DATA ---
// Description format: "PAY ORDER 123" (Keep it short for banking apps)
$description = "PAY ORDER $order_id";
$amount = intval($order['total_price']);

// VietQR API URL
$qr_url = "https://img.vietqr.io/image/$bank_id-$account_no-$template.png?amount=$amount&addInfo=$description&accountName=$account_name";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Order #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .payment-card {
            max-width: 900px;
            margin: 50px auto;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .qr-container {
            background: #fff;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 10px;
            display: inline-block;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            background-color: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <div class="card payment-card bg-white">
            <div class="row g-0">
                <!-- Left Side: Instructions -->
                <div class="col-lg-7 p-5">
                    <h3 class="fw-bold mb-4 text-primary">Complete Your Payment</h3>
                    <p class="text-muted mb-4">Please scan the QR code using your banking app to finalize your order.</p>

                    <div class="d-flex align-items-center mb-4">
                        <div class="step-circle">1</div>
                        <div>Open your <strong>Mobile Banking App</strong></div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="step-circle">2</div>
                        <div>Select <strong>"Scan QR"</strong> feature</div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="step-circle">3</div>
                        <div>Confirm amount & Note: <strong><?php echo $description; ?></strong></div>
                    </div>

                    <div class="alert alert-warning mt-4 shadow-sm border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> The system will verify your payment automatically within 5-10 minutes.
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <a href="orders.php" class="btn btn-outline-secondary btn-lg px-4">I'll Pay Later</a>
                        <a href="orders.php" class="btn btn-success btn-lg px-4"><i class="fas fa-check me-2"></i>I Have Paid</a>
                    </div>
                </div>

                <!-- Right Side: QR Code -->
                <div class="col-lg-5 bg-gradient-primary p-5 text-center text-white d-flex flex-column justify-content-center align-items-center">
                    <h5 class="text-uppercase letter-spacing-2 mb-4 opacity-75">Scan to Pay</h5>

                    <div class="qr-container shadow-lg mb-4">
                        <img src="<?php echo $qr_url; ?>" alt="Payment QR Code" class="img-fluid" style="max-width: 100%; height: auto;">
                    </div>

                    <h2 class="fw-bold mb-1">$<?php echo number_format($amount); ?></h2>
                    <p class="opacity-75 mb-0">Total Amount</p>

                    <div class="mt-4 pt-4 border-top border-white border-opacity-25 w-100">
                        <div class="d-flex justify-content-between small opacity-75 mb-2">
                            <span>Order ID:</span>
                            <span class="fw-bold">#<?php echo $order_id; ?></span>
                        </div>
                        <div class="d-flex justify-content-between small opacity-75">
                            <span>Beneficiary:</span>
                            <span class="fw-bold"><?php echo $account_name; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>