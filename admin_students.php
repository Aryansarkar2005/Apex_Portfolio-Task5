<?php
require_once('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';

// Handle Delete Student
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $del_stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'student'");
    mysqli_stmt_bind_param($del_stmt, "i", $del_id);
    if (mysqli_stmt_execute($del_stmt)) {
        $success = "Student account successfully expunged from the database.";
    } else {
        $error = "Error removing student: " . mysqli_error($conn);
    }
}

// Fetch all students with enrollment count
$std_query = "SELECT u.*, COUNT(e.id) AS enrollment_count 
              FROM users u 
              LEFT JOIN enrollments e ON u.id = e.user_id 
              WHERE u.role = 'student' 
              GROUP BY u.id 
              ORDER BY u.id DESC";
$std_result = mysqli_query($conn, $std_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - ApexAcademy Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <div class="dashboard-header" style="border-color: var(--accent-primary);">
            <h1>Student Account <span>Audit & Monitoring</span></h1>
            <p>Admin panel to inspect registered student accounts, track OTP verification statuses, and monitor enrollments.</p>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">✔️ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- STUDENTS TABLE -->
        <div class="table-container">
            <h2>🎓 Registered Student Demographics</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Email Address</th>
                        <th>Verification Status</th>
                        <th>Active Enrollments</th>
                        <th>Registered Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($std_result) > 0): ?>
                        <?php while ($std = mysqli_fetch_assoc($std_result)): ?>
                            <tr>
                                <td>#<?php echo $std['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($std['full_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($std['email']); ?></td>
                                <td>
                                    <?php if ($std['is_verified'] == 1): ?>
                                        <span style="display:inline-block; padding: 4px 12px; background: rgba(16, 185, 129, 0.2); color: #34d399; border-radius: 12px; font-size:0.85rem; font-weight:600;">✔️ OTP Verified</span>
                                    <?php else: ?>
                                        <span style="display:inline-block; padding: 4px 12px; background: rgba(239, 68, 68, 0.2); color: #f87171; border-radius: 12px; font-size:0.85rem; font-weight:600;">⚠️ Pending OTP</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong style="color: var(--accent-primary);"><?php echo $std['enrollment_count']; ?> Courses</strong></td>
                                <td><?php echo date("M d, Y", strtotime($std['created_at'])); ?></td>
                                <td>
                                    <a href="admin_students.php?delete=<?php echo $std['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this student account?');">Expunge</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">No student accounts registered yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>
</html>
