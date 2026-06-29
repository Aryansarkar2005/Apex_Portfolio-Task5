<?php
require_once('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = isset($_GET['course_id']) ? (int)$GET['course_id'] : (isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0);

if ($course_id > 0) {
    // Check if already enrolled
    $chk_stmt = mysqli_prepare($conn, "SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    mysqli_stmt_bind_param($chk_stmt, "ii", $user_id, $course_id);
    mysqli_stmt_execute($chk_stmt);
    $chk_result = mysqli_stmt_get_result($chk_stmt);

    if (mysqli_num_rows($chk_result) === 0) {
        // Create enrollment
        $ins_stmt = mysqli_prepare($conn, "INSERT INTO enrollments (user_id, course_id, progress_percent) VALUES (?, ?, 15)");
        mysqli_stmt_bind_param($ins_stmt, "ii", $user_id, $course_id);
        mysqli_stmt_execute($ins_stmt);
    }
    
    $_SESSION['enroll_success'] = "You have been successfully enrolled in the course! Begin your lessons below.";
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: courses.php");
    exit();
}
?>
