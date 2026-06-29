<?php
require_once('config.php');
session_start();

// Fetch featured courses with category names
$courses_query = "SELECT c.*, cat.name AS category_name 
                  FROM courses c 
                  JOIN categories cat ON c.category_id = cat.id 
                  ORDER BY c.id ASC LIMIT 6";
$courses_result = mysqli_query($conn, $courses_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexAcademy - Premium E-Learning Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <!-- HERO SECTION -->
    <main class="hero">
        <div class="hero-content">
            <div class="tagline">✦ Task 5 Capstone Project</div>
            <h1>Elite Technical Education for <span>Future Engineers</span></h1>
            <p>Master cutting-edge full-stack engineering, applied artificial intelligence, and enterprise cryptographic systems through professional-grade curriculum designed by industry architects.</p>
            <div class="hero-btns">
                <a href="courses.php" class="nav-btn">Explore Course Catalog</a>
                <a href="register.php" class="nav-btn-outline">Join as Student</a>
            </div>
        </div>
        
        <div class="hero-image-container">
            <div class="hero-bg-glow"></div>
            <div class="hero-image"></div>
        </div>
    </main>

    <!-- VALUE PROPOSITIONS -->
    <section class="values-section">
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">🛡️</div>
                <h3>Secure OTP Gateway</h3>
                <p>Enterprise-grade authentication equipped with real-time One-Time Password (OTP) validation sent directly to student email inboxes.</p>
            </div>

            <div class="value-card">
                <div class="value-icon">⚡</div>
                <h3>Zero-Reload AJAX Search</h3>
                <p>Seamless asynchronous course filtering powered by modern JavaScript fetch APIs, allowing lightning-fast catalog exploration.</p>
            </div>

            <div class="value-card">
                <div class="value-icon">📈</div>
                <h3>Interactive Analytics</h3>
                <p>Advanced Super Admin command center featuring responsive Chart.js data visualization monitoring active revenues and enrollments.</p>
            </div>
        </div>
    </section>

    <!-- FEATURED COURSES GRID -->
    <section class="courses-section">
        <div class="section-header">
            <h2>Our Featured Curriculum</h2>
            <p>Explore our masterfully curated technical courses designed to equip you with enterprise-level programming capabilities.</p>
        </div>

        <div class="courses-grid">
            <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                <div class="course-card">
                    <div class="course-img-box" style="background-image: url('<?php echo htmlspecialchars($course['image_url']); ?>');">
                        <div class="course-badge"><?php echo htmlspecialchars($course['category_name']); ?></div>
                    </div>
                    <div class="course-content">
                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p><?php echo htmlspecialchars($course['description']); ?></p>
                        <div class="course-meta">
                            <span>🎓 <?php echo htmlspecialchars($course['instructor']); ?></span>
                            <span>⏱️ <?php echo htmlspecialchars($course['duration']); ?></span>
                        </div>
                        <div class="course-meta" style="border-top: none; padding-top: 10px;">
                            <span style="color: var(--accent-primary); font-weight: 600;">⚡ <?php echo htmlspecialchars($course['difficulty']); ?></span>
                            <div class="course-price">$<?php echo htmlspecialchars($course['price']); ?></div>
                        </div>
                    </div>
                    <a href="courses.php?course_id=<?php echo $course['id']; ?>" class="course-enroll-btn">View Details & Enroll</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <?php include('footer.php'); ?>

</body>
</html>
