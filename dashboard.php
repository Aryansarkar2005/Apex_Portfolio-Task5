<?php
require_once('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user profile info
$stmt = mysqli_prepare($conn, "SELECT full_name, email, role FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user_result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($user_result);

// Fetch enrolled courses
$enroll_query = "SELECT e.*, c.title, c.description, c.instructor, c.duration, c.image_url, cat.name AS category_name 
                 FROM enrollments e 
                 JOIN courses c ON e.course_id = c.id 
                 JOIN categories cat ON c.category_id = cat.id 
                 WHERE e.user_id = ? 
                 ORDER BY e.enrollment_date DESC";
$enroll_stmt = mysqli_prepare($conn, $enroll_query);
mysqli_stmt_bind_param($enroll_stmt, "i", $user_id);
mysqli_stmt_execute($enroll_stmt);
$enroll_result = mysqli_stmt_get_result($enroll_stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - ApexAcademy</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .progress-bar-container {
            width: 100%;
            height: 10px;
            background: rgba(255,255,255,0.1);
            border-radius: 5px;
            overflow: hidden;
            margin: 15px 0;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border-radius: 5px;
            transition: width 0.4s ease;
        }
    </style>
</head>
<body>

    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome to Your Dashboard, <span><?php echo htmlspecialchars($user['full_name']); ?></span>!</h1>
            <p><strong>Account Email:</strong> <?php echo htmlspecialchars($user['email']); ?> | <strong>Role:</strong> <?php echo strtoupper(htmlspecialchars($user['role'])); ?></p>
        </div>

        <?php if (isset($_SESSION['enroll_success'])): ?>
            <div class="alert alert-success">✔️ <?php echo htmlspecialchars($_SESSION['enroll_success']); unset($_SESSION['enroll_success']); ?></div>
        <?php endif; ?>

        <h2 style="font-family: var(--font-heading); font-size: 2rem; margin-bottom: 30px;">My Enrolled Curriculum</h2>

        <div class="courses-grid">
            <?php if (mysqli_num_rows($enroll_result) > 0): ?>
                <?php while ($enroll = mysqli_fetch_assoc($enroll_result)): ?>
                    <div class="course-card">
                        <div class="course-img-box" style="background-image: url('<?php echo htmlspecialchars($enroll['image_url']); ?>');">
                            <div class="course-badge"><?php echo htmlspecialchars($enroll['category_name']); ?></div>
                        </div>
                        <div class="course-content">
                            <h3><?php echo htmlspecialchars($enroll['title']); ?></h3>
                            <p><?php echo htmlspecialchars($enroll['description']); ?></p>
                            
                            <div class="course-meta">
                                <span>🎓 <?php echo htmlspecialchars($enroll['instructor']); ?></span>
                                <span>⏱️ <?php echo htmlspecialchars($enroll['duration']); ?></span>
                            </div>

                            <div style="margin-top: 20px; border-top: 1px solid var(--border-glass); padding-top: 15px;">
                                <div style="display:flex; justify-content:space-between; font-size:0.9rem; color: var(--text-muted);">
                                    <span>Lesson Progress</span>
                                    <span style="color: var(--accent-primary); font-weight:700;"><?php echo htmlspecialchars($enroll['progress_percent']); ?>%</span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: <?php echo htmlspecialchars($enroll['progress_percent']); ?>%;"></div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:alert('Initializing secure video lesson buffer... Welcome to Lecture 1!');" class="course-enroll-btn" style="background: var(--accent-primary); color: #0a0f1d;">Resume Video Lesson ▶</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; background: var(--bg-card); border: 1px solid var(--border-glass); border-radius: 20px; padding: 60px; text-align: center;">
                    <h3 style="font-family: var(--font-heading); font-size: 1.8rem; margin-bottom: 15px;">No Enrolled Courses Yet</h3>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">You haven't enrolled in any engineering programs yet. Head over to our catalog and explore our master curriculum.</p>
                    <a href="courses.php" class="nav-btn">Explore Course Catalog</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>
</html>
