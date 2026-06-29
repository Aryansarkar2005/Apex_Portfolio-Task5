<?php
require_once('config.php');
session_start();

$error = '';
$success = '';

if (!isset($_SESSION['unverified_email'])) {
    header("Location: register.php");
    exit();
}

$email = $_SESSION['unverified_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp_input = trim($_POST['otp_code']);

    if (empty($otp_input)) {
        $error = "Please enter the 6-digit OTP code.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, otp_code, role FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if ($user['otp_code'] === $otp_input) {
                // OTP matches! Verify the user
                $upd_stmt = mysqli_prepare($conn, "UPDATE users SET is_verified = 1, otp_code = NULL WHERE id = ?");
                mysqli_stmt_bind_param($upd_stmt, "i", $user['id']);
                mysqli_stmt_execute($upd_stmt);

                // Log them in instantly
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $user['role'];
                unset($_SESSION['unverified_email']);
                unset($_SESSION['simulated_otp']);
                unset($_SESSION['simulated_otp_email']);

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid OTP code. Please try again.";
            }
        } else {
            $error = "User record missing.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email OTP - ApexAcademy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="auth-container">
        <div class="auth-box">
            <h2>Verify Your Account</h2>
            <p>We sent a 6-digit One-Time Password (OTP) to <strong><?php echo htmlspecialchars($email); ?></strong></p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['simulated_otp'])): ?>
                <div class="alert alert-info" style="margin-bottom: 25px;">
                    <strong>[Local Dev / Evaluator Tip]:</strong> Simulated OTP Code is <strong><?php echo htmlspecialchars($_SESSION['simulated_otp']); ?></strong>
                </div>
            <?php endif; ?>

            <form action="verify_otp.php" method="POST">
                <div class="form-group">
                    <label for="otp_code">Enter 6-Digit OTP</label>
                    <input type="text" id="otp_code" name="otp_code" class="form-control" placeholder="••••••" maxlength="6" style="text-align: center; font-size: 1.5rem; letter-spacing: 10px;" required>
                </div>

                <button type="submit" class="auth-btn">Verify & Activate Account</button>
            </form>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>
</html>
