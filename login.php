<?php
require_once('config.php');
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, password_hash, role, is_verified FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password_hash'])) {
                if ($user['is_verified'] == 0) {
                    $_SESSION['unverified_email'] = $email;
                    header("Location: verify_otp.php");
                    exit();
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $email;
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit();
                }
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ApexAcademy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="auth-container">
        <div class="auth-box">
            <h2>Welcome Back</h2>
            <p>Log in to access your course curriculum</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. aryan@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="auth-btn">Log In to Account</button>
            </form>

            <div class="auth-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
            
            <div class="auth-link" style="margin-top: 15px; font-size: 0.85rem; color: var(--text-muted);">
                <strong>[Admin Login]:</strong> admin@apexacademy.com | <strong>Pass:</strong> ApexAdmin@2026
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>
</html>
