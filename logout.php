<?php

include 'config.php';

// use include_once to avoid double session start
include_once __DIR__ . '/session_config.php';

// If user is logged in, clear their remember_token in DB and delete cookie
if (!empty($_SESSION['user_id']) || !empty($_SESSION['admin_id'])) {
    $id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : intval($_SESSION['admin_id']);
    // clear token in DB
    mysqli_query($conn, "UPDATE `users` SET remember_token = NULL WHERE id = $id");
    // delete cookie (set expired)
    setcookie('remember_token', '', time() - 3600, '/');
}

// now clear session
session_unset();
session_destroy();

header('location:login.php');
exit();
