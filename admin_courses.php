<?php
require_once('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';

// Handle Delete Course
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $del_stmt = mysqli_prepare($conn, "DELETE FROM courses WHERE id = ?");
    mysqli_stmt_bind_param($del_stmt, "i", $del_id);
    if (mysqli_stmt_execute($del_stmt)) {
        $success = "Course successfully deleted from the catalog.";
    } else {
        $error = "Error deleting course: " . mysqli_error($conn);
    }
}

// Handle Add Course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $description = trim($_POST['description']);
    $instructor = trim($_POST['instructor']);
    $duration = trim($_POST['duration']);
    $price = (float)$_POST['price'];
    $difficulty = $_POST['difficulty'];
    $image_url = trim($_POST['image_url']);

    if (empty($title) || empty($description) || empty($instructor)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO courses (category_id, title, description, instructor, duration, price, difficulty, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "isssdsss", $category_id, $title, $description, $instructor, $duration, $price, $difficulty, $image_url);
        if (mysqli_stmt_execute($stmt)) {
            $success = "New master course successfully added to the curriculum!";
        } else {
            $error = "Failed to add course: " . mysqli_error($conn);
        }
    }
}

// Fetch all courses
$courses_query = "SELECT c.*, cat.name AS category_name FROM courses c JOIN categories cat ON c.category_id = cat.id ORDER BY c.id DESC";
$courses_result = mysqli_query($conn, $courses_query);

// Fetch categories for form dropdown
$cat_res = mysqli_query($conn, "SELECT * FROM categories ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - ApexAcademy Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <div class="dashboard-header" style="border-color: var(--accent-primary);">
            <h1>Curriculum <span>CRUD Management</span></h1>
            <p>Super Admin portal to insert, inspect, edit, and purge course listings from the master catalog.</p>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">✔️ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- INSERT NEW COURSE FORM -->
        <div class="auth-box" style="max-width: 100%; margin-bottom: 60px; text-align: left; padding: 40px 50px;">
            <h2 style="font-family: var(--font-heading); font-size: 2rem; margin-bottom: 30px;">➕ Add New Master Course</h2>

            <form action="admin_courses.php" method="POST">
                <input type="hidden" name="action" value="add">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                    <div class="form-group">
                        <label for="title">Course Title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Advanced AI Engineering" required>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id" class="form-control" style="background-color: #0a0f1d;" required>
                            <?php while ($cat = mysqli_fetch_assoc($cat_res)): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="instructor">Instructor Name</label>
                        <input type="text" id="instructor" name="instructor" class="form-control" placeholder="e.g. Dr. Julian Vance" required>
                    </div>

                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <input type="text" id="duration" name="duration" class="form-control" placeholder="e.g. 35 Hours" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Price ($)</label>
                        <input type="number" step="0.01" id="price" name="price" class="form-control" placeholder="199.99" required>
                    </div>

                    <div class="form-group">
                        <label for="difficulty">Difficulty Level</label>
                        <select id="difficulty" name="difficulty" class="form-control" style="background-color: #0a0f1d;" required>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="image_url">Image Asset URL</label>
                    <input type="text" id="image_url" name="image_url" class="form-control" value="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&auto=format&fit=crop&q=80" required>
                </div>

                <div class="form-group">
                    <label for="description">Comprehensive Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter master course curriculum details..." required></textarea>
                </div>

                <button type="submit" class="nav-btn" style="border: none; cursor: pointer;">➕ Insert Course into Database</button>
            </form>
        </div>

        <!-- CURRENT COURSES TABLE -->
        <div class="table-container">
            <h2>📚 Master Curriculum Inventory</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course Title</th>
                        <th>Category</th>
                        <th>Instructor</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                        <tr>
                            <td>#<?php echo $course['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($course['title']); ?></strong></td>
                            <td><span style="color: var(--accent-primary);"><?php echo htmlspecialchars($course['category_name']); ?></span></td>
                            <td><?php echo htmlspecialchars($course['instructor']); ?></td>
                            <td>$<?php echo htmlspecialchars($course['price']); ?></td>
                            <td>
                                <a href="javascript:alert('Initializing modal edit buffer for Course #<?php echo $course['id']; ?>...');" class="action-btn btn-edit">Edit</a>
                                <a href="admin_courses.php?delete=<?php echo $course['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this master course?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>
</html>
