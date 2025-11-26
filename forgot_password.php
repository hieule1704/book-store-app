<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

include 'config.php';
session_start();

$message = [];

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if user exists
    $check = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'");

    if (mysqli_num_rows($check) > 0) {
        $token = bin2hex(random_bytes(16));
        $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes")); // Token valid for 30 mins

        // Save token to DB
        mysqli_query($conn, "UPDATE `users` SET reset_token = '$token', reset_expiry = '$expiry' WHERE email = '$email'");

        // Send Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
            $mail->Port       = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';

            $link = "http://localhost/project/reset_password.php?email=$email&token=$token";
            $mail->Body = "Click here to reset your password: <a href='$link'>$link</a>. This link expires in 30 minutes.";

            $mail->send();
            $message[] = 'Reset link sent to your email.';
        } catch (Exception $e) {
            $message[] = "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message[] = 'Email not found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-3">Forgot Password</h3>
        <?php if ($message) {
            foreach ($message as $msg) echo "<div class='alert alert-info'>$msg</div>";
        } ?>
        <form method="post">
            <div class="mb-3">
                <label>Enter your email</label>
                <input type="email" name="email" required class="form-control">
            </div>
            <div class="d-grid">
                <input type="submit" name="submit" value="Send Reset Link" class="btn btn-primary">
            </div>
            <div class="text-center mt-3">
                <a href="login.php">Back to Login</a>
            </div>
        </form>
    </div>
</body>

</html>