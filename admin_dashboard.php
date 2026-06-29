<?php
require_once('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch total metrics
$std_res = mysqli_query($conn, "SELECT COUNT(*) AS total_students FROM users WHERE role='student'");
$std_count = mysqli_fetch_assoc($std_res)['total_students'];

$crs_res = mysqli_query($conn, "SELECT COUNT(*) AS total_courses FROM courses");
$crs_count = mysqli_fetch_assoc($crs_res)['total_courses'];

$rev_res = mysqli_query($conn, "SELECT SUM(c.price) AS total_revenue FROM enrollments e JOIN courses c ON e.course_id = c.id");
$rev_sum = mysqli_fetch_assoc($rev_res)['total_revenue'] ?? 0.00;

// Fetch data for Chart.js (Course popularity)
$chart_query = "SELECT c.title, COUNT(e.id) AS enrollment_count 
                FROM courses c 
                LEFT JOIN enrollments e ON c.id = e.course_id 
                GROUP BY c.id";
$chart_result = mysqli_query($conn, $chart_query);

$course_titles = [];
$enrollment_counts = [];
while ($row = mysqli_fetch_assoc($chart_result)) {
    $course_titles[] = substr($row['title'], 0, 20) . "...";
    $enrollment_counts[] = (int)$row['enrollment_count'];
}

// Fetch verification status for doughnut chart
$ver_query = "SELECT is_verified, COUNT(*) as count FROM users WHERE role='student' GROUP BY is_verified";
$ver_result = mysqli_query($conn, $ver_query);
$ver_data = [0 => 0, 1 => 0];
while ($row = mysqli_fetch_assoc($ver_result)) {
    $ver_data[$row['is_verified']] = (int)$row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ApexAcademy</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 40px;
            margin-bottom: 60px;
        }
        .chart-box {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 40px;
        }
        .chart-box h3 {
            font-family: var(--font-heading);
            font-size: 1.5rem;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <div class="dashboard-header" style="border-color: var(--accent-primary);">
            <h1>Super Admin <span>Command Center</span></h1>
            <p>Advanced statistical monitoring, revenue metrics, and high-fidelity Chart.js data visualization.</p>
        </div>

        <!-- METRICS GRID -->
        <div class="metrics-grid">
            <div class="metric-box">
                <div class="metric-title">Total Registered Students</div>
                <div class="metric-value"><?php echo $std_count; ?> <span>🎓</span></div>
            </div>

            <div class="metric-box">
                <div class="metric-title">Active Master Courses</div>
                <div class="metric-value"><?php echo $crs_count; ?> <span>📚</span></div>
            </div>

            <div class="metric-box">
                <div class="metric-title">Aggregate Calculated Revenue</div>
                <div class="metric-value"><span>$</span><?php echo number_format($rev_sum, 2); ?></div>
            </div>
        </div>

        <!-- CHARTS GRID (Chart.js) -->
        <div class="charts-grid">
            <div class="chart-box">
                <h3>⚡ Course Enrollment Popularity</h3>
                <canvas id="enrollmentChart"></canvas>
            </div>

            <div class="chart-box">
                <h3>🛡️ Student OTP Verification Demographics</h3>
                <canvas id="verificationChart"></canvas>
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 40px;">
            <a href="admin_courses.php" class="nav-btn">Manage Course Curriculum (CRUD)</a>
            <a href="admin_students.php" class="nav-btn-outline">Audit Student Accounts</a>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script>
        // Set Chart.js global dark theme defaults
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.05)';

        // 1. Enrollment Bar Chart
        const ctxEnroll = document.getElementById('enrollmentChart').getContext('2d');
        new Chart(ctxEnroll, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($course_titles); ?>,
                datasets: [{
                    label: 'Active Enrollments',
                    data: <?php echo json_encode($enrollment_counts); ?>,
                    backgroundColor: 'rgba(0, 229, 255, 0.6)',
                    borderColor: '#00e5ff',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });

        // 2. Verification Doughnut Chart
        const ctxVer = document.getElementById('verificationChart').getContext('2d');
        new Chart(ctxVer, {
            type: 'doughnut',
            data: {
                labels: ['Verified Accounts (OTP Validated)', 'Pending OTP Verification'],
                datasets: [{
                    data: [<?php echo $ver_data[1]; ?>, <?php echo $ver_data[0]; ?>],
                    backgroundColor: ['#6366f1', 'rgba(239, 68, 68, 0.6)'],
                    borderColor: ['#818cf8', '#f87171'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</body>
</html>
