<?php
require_once('config.php');
require_once('mail_helper.php');
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check if email already exists
        $stmt = mysqli_prepare($conn, "SELECT id, is_verified FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($existing_user = mysqli_fetch_assoc($result)) {
            if ($existing_user['is_verified'] == 1) {
                $error = "This email address is already registered and verified. Please login.";
            } else {
                // User exists but not verified. Update OTP and resend
                $otp_code = (string)random_int(100000, 999999);
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $upd_stmt = mysqli_prepare($conn, "UPDATE users SET password_hash = ?, otp_code = ? WHERE email = ?");
                mysqli_stmt_bind_param($upd_stmt, "sss", $password_hash, $otp_code, $email);
                mysqli_stmt_execute($upd_stmt);

                send_otp_email($email, $otp_code);
                $_SESSION['unverified_email'] = $email;
                header("Location: verify_otp.php");
                exit();
            }
        } else {
            // New user registration
            $otp_code = (string)random_int(100000, 999999);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $ins_stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, email, password_hash, otp_code) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($ins_stmt, "ssss", $full_name, $email, $password_hash, $otp_code);
            
            if (mysqli_stmt_execute($ins_stmt)) {
                send_otp_email($email, $otp_code);
                $_SESSION['unverified_email'] = $email;
                header("Location: verify_otp.php");
                exit();
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply as Student - ApexAcademy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="auth-container">
        <div class="auth-box">
            <h2>Apply as Student</h2>
            <p>Begin your elite technical education with ApexAcademy</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" placeholder="e.g. Aryan Sarkar" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. aryan@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="auth-btn">Register & Send OTP</button>
            </form>

            <div class="auth-link">
                Already have an account? <a href="login.php">Log in here</a>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>
</html>
