<?php
require_once 'vendor/autoload.php';

use Google\Client;
use Google\Service\Oauth2;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

include 'config.php';
// Include the secure session config
include_once __DIR__ . '/session_config.php';

// Google Client Configuration
$clientID = $_ENV['GOOGLE_CLIENT_ID'];
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
$redirectUri = 'http://localhost/project/google_login.php';

$client = new Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        // Use the Oauth2 Service class directly
        $oauth2 = new Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        $email = mysqli_real_escape_string($conn, $userInfo->email);
        $name = mysqli_real_escape_string($conn, $userInfo->name);

        // Check if user exists
        $check_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'") or die('Query failed: ' . mysqli_error($conn));

        if (mysqli_num_rows($check_user) > 0) {
            $user = mysqli_fetch_assoc($check_user);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
        } else {
            // Register new user with default user_type
            // Note: Since they logged in with Google, we can mark them as verified automatically if you want
            // For now, we keep logic simple:
            mysqli_query($conn, "INSERT INTO `users` (name, email, password, user_type, is_verified) VALUES ('$name', '$email', '', 'user', 1)") or die('Query failed: ' . mysqli_error($conn));
            $user_id = mysqli_insert_id($conn);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_type'] = 'user';
        }

        // Redirect to home or admin page based on user_type
        if ($_SESSION['user_type'] == 'admin') {
            header('Location: admin_page.php');
        } else {
            header('Location: home.php');
        }
        exit;
    } else {
        echo "Google Login Failed. Error: " . htmlspecialchars($token['error']);
        exit;
    }
} else {
    // Redirect to Google OAuth consent page
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}
