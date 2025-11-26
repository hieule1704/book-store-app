<?php
include 'config.php';

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if already subscribed
        $check = mysqli_query($conn, "SELECT * FROM subscribers WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            echo "You are already subscribed!";
        } else {
            $insert = mysqli_query($conn, "INSERT INTO subscribers(email) VALUES('$email')");
            if ($insert) {
                echo "success";
            } else {
                echo "Failed to subscribe. Try again.";
            }
        }
    } else {
        echo "Invalid email format.";
    }
}
