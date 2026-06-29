<?php
// header.php - Dynamic Navigation Bar for ApexAcademy E-Learning Portal
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
    <a href="index.php" class="logo-container">
        <div class="logo-icon">A</div>
        <div class="logo-text">Apex<span>Academy</span></div>
    </a>
    
    <div class="nav-links">
        <a href="index.php" class="nav-link">Home</a>
        <a href="courses.php" class="nav-link">Courses</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" class="nav-link">Admin Panel</a>
                <a href="admin_courses.php" class="nav-link">Manage Courses</a>
                <a href="admin_students.php" class="nav-link">Students</a>
            <?php else: ?>
                <a href="dashboard.php" class="nav-link">My Dashboard</a>
            <?php endif; ?>
            <a href="logout.php" class="nav-btn-outline">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-link">Login</a>
            <a href="register.php" class="nav-btn">Get Started</a>
        <?php endif; ?>
    </div>
</nav>
