<?php
require_once('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = isset($_POST['category']) ? $_POST['category'] : 'all';
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';

    $where_clauses = [];
    $params = [];
    $types = '';

    if ($category !== 'all') {
        $where_clauses[] = "c.category_id = ?";
        $params[] = (int)$category;
        $types .= 'i';
    }

    if (!empty($search)) {
        $where_clauses[] = "(c.title LIKE ? OR c.description LIKE ? OR c.instructor LIKE ?)";
        $search_wildcard = "%" . $search . "%";
        $params[] = $search_wildcard;
        $params[] = $search_wildcard;
        $params[] = $search_wildcard;
        $types .= 'sss';
    }

    $query = "SELECT c.*, cat.name AS category_name FROM courses c JOIN categories cat ON c.category_id = cat.id";
    if (!empty($where_clauses)) {
        $query .= " WHERE " . implode(" AND ", $where_clauses);
    }
    $query .= " ORDER BY c.id ASC";

    $stmt = mysqli_prepare($conn, $query);
    if (!empty($types)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($course = mysqli_fetch_assoc($result)) {
            ?>
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
                <a href="enroll.php?course_id=<?php echo $course['id']; ?>" class="course-enroll-btn">Instantly Enroll in Course</a>
            </div>
            <?php
        }
    } else {
        echo '<div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: var(--text-muted); font-size: 1.2rem;">⚠️ No master courses matching your search criteria were found.</div>';
    }
}
?>
