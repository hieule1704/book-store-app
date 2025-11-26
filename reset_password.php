<?php
include 'config.php';
include_once __DIR__ . '/session_config.php';

$message = [];

// helper: safe fetch single row using prepared stmt
function fetch_user_by_email_token($conn, $email, $token)
{
    $sql = "SELECT id, email, reset_token, reset_expiry FROM `users` WHERE email = ? AND reset_token = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, "ss", $email, $token);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res && mysqli_num_rows($res) === 1) return mysqli_fetch_assoc($res);
    return false;
}

// If user arrived via link, verify token first (but do not die with raw HTML)
$show_form = false;
$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

if (!empty($email) && !empty($token)) {
    $email_esc = mysqli_real_escape_string($conn, $email);
    $token_esc = mysqli_real_escape_string($conn, $token);

    $user = fetch_user_by_email_token($conn, $email_esc, $token_esc);
    if (!$user) {
        $message[] = 'Invalid or expired password reset link. <a href="forgot_password.php">Try again</a>';
    } else {
        // validate expiry in PHP (works even if column is varchar or DATETIME)
        $expiry = $user['reset_expiry'] ?? null;
        if (empty($expiry)) {
            $message[] = 'Invalid or expired password reset link. <a href="forgot_password.php">Try again</a>';
        } else {
            $expiry_ts = strtotime($expiry);
            if ($expiry_ts === false || $expiry_ts < time()) {
                $message[] = 'Reset link expired. <a href="forgot_password.php">Request again</a>';
            } else {
                $show_form = true;
            }
        }
    }
} else {
    // no params — redirect to forgot or show friendly message
    header("Location: forgot_password.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $email_post = $_POST['email'] ?? '';
    $token_post = $_POST['token'] ?? '';
    $pass = $_POST['password'] ?? '';
    $cpass = $_POST['cpassword'] ?? '';

    if ($pass === '' || $cpass === '') {
        $message[] = 'Please fill both password fields.';
    } elseif ($pass !== $cpass) {
        $message[] = 'Passwords do not match.';
    } elseif (strlen($pass) < 6) {
        $message[] = 'Password must be at least 6 characters.';
    } else {
        // re-validate token before changing password
        $user = fetch_user_by_email_token($conn, $email_post, $token_post);
        if (!$user) {
            $message[] = 'Invalid or expired reset link.';
        } else {
            $expiry = $user['reset_expiry'] ?? null;
            $expiry_ts = $expiry ? strtotime($expiry) : 0;
            if ($expiry_ts === false || $expiry_ts < time()) {
                $message[] = 'Reset link expired. Please request a new one.';
            } else {
                // Hash password (keep consistent with your app — use password_hash ideally)
                $new_hash = md5($pass); // consider migrating to password_hash()
                $uid = intval($user['id']);
                $update_sql = "UPDATE `users` SET password = ?, reset_token = NULL, reset_expiry = NULL, remember_token = NULL WHERE id = ?";
                $stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($stmt, "si", $new_hash, $uid);
                if (mysqli_stmt_execute($stmt)) {
                    header('Location: login.php?reset=success');
                    exit;
                } else {
                    $message[] = 'Failed to update password. Try again later.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Minimal centered card like forgot_password.php */
        body {
            background: #f8f9fa;
        }

        .center-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            box-sizing: border-box;
        }

        .card-min {
            max-width: 420px;
            width: 100%;
        }
    </style>
</head>

<body class="bg-light">
    <main class="center-wrap">
        <div class="card card-min shadow-sm p-4">
            <h4 class="mb-3 text-center">Reset Password</h4>

            <?php if (!empty($message)) {
                foreach ($message as $m) {
                    echo "<div class='alert alert-info'>" . htmlspecialchars($m) . "</div>";
                }
            } ?>

            <?php if ($show_form): ?>
                <form method="post" novalidate>
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div class="mb-3">
                        <label class="form-label">New password</label>
                        <input type="password" name="password" class="form-control" required minlength="6" placeholder="Enter new password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm password</label>
                        <input type="password" name="cpassword" class="form-control" required minlength="6" placeholder="Confirm new password">
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="reset_password" class="btn btn-primary">Set new password</button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="login.php">Back to login</a>
                    </div>
                </form>
            <?php else: ?>
                <div class="text-center">
                    <a href="forgot_password.php" class="btn btn-secondary">Request new reset link</a>
                    <div class="mt-2"><a href="login.php">Back to login</a></div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>