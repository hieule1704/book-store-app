<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

include 'config.php';

if (!isset($_GET['email']) || !isset($_GET['code'])) {
    die('Invalid verification link.');
}

$email = mysqli_real_escape_string($conn, $_GET['email']);
$code  = mysqli_real_escape_string($conn, $_GET['code']);

$check = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND verification_code = '$code' AND is_verified = 0") or die('Query failed: ' . mysqli_error($conn));

if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "UPDATE `users` SET is_verified = 1, verification_code = NULL WHERE email = '$email'") or die('Query failed: ' . mysqli_error($conn));
    // Redirect to login with a success message or show confirmation
    header('Location: login.php');
    exit;
} else {
    echo 'Verification failed or account already verified.';
    exit;
}
