<?php
require_once('config.php');
session_start();

// Fetch all categories for filter pills
$cat_query = "SELECT * FROM categories ORDER BY id ASC";
$cat_result = mysqli_query($conn, $cat_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Catalog - ApexAcademy</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .catalog-header {
            padding: 140px 5% 60px;
            text-align: center;
            border-bottom: 1px solid var(--border-glass);
            background: radial-gradient(circle at center, #131c31 0%, #0a0f1d 70%);
        }
        .catalog-header h1 {
            font-family: var(--font-heading);
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }
        .catalog-header p {
            color: var(--text-muted);
            font-size: 1.15rem;
            max-width: 700px;
            margin: 0 auto 40px;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto 40px;
            position: relative;
        }
        .search-input {
            width: 100%;
            padding: 20px 30px;
            background: rgba(10, 15, 29, 0.8);
            border: 1px solid var(--accent-primary);
            border-radius: 30px;
            color: var(--text-main);
            font-size: 1.1rem;
            font-family: var(--font-body);
            box-shadow: 0 0 25px rgba(0, 229, 255, 0.2);
            transition: all 0.3s ease;
        }
        .search-input:focus {
            outline: none;
            box-shadow: 0 0 35px rgba(0, 229, 255, 0.4);
            background: #0a0f1d;
        }
        .category-pills {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .pill-btn {
            padding: 10px 24px;
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: 25px;
            color: var(--text-muted);
            font-family: var(--font-heading);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .pill-btn:hover, .pill-btn.active {
            background: var(--accent-primary);
            color: #0a0f1d;
            border-color: var(--accent-primary);
            box-shadow: 0 5px 15px var(--accent-primary-glow);
        }
    </style>
</head>
<body>

    <?php include('header.php'); ?>

    <div class="catalog-header">
        <h1>Explore Master Curriculum</h1>
        <p>Utilize our cutting-edge zero-reload AJAX search to filter through industry-aligned engineering programs instantly.</p>
        
        <div class="search-container">
            <input type="text" id="searchBox" class="search-input" placeholder="🔍 Search by course title, tech stack, or instructor...">
        </div>

        <div class="category-pills">
            <button class="pill-btn active" onclick="filterCategory('all', this)">⚡ All Courses</button>
            <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
                <button class="pill-btn" onclick="filterCategory('<?php echo $cat['id']; ?>', this)">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </button>
            <?php endwhile; ?>
        </div>
    </div>

    <section class="courses-section">
        <div id="coursesGrid" class="courses-grid">
            <!-- AJAX Filtered Courses load here -->
        </div>
    </section>

    <?php include('footer.php'); ?>

    <script>
        let currentCategory = 'all';
        let searchQuery = '';

        const searchBox = document.getElementById('searchBox');
        const coursesGrid = document.getElementById('coursesGrid');

        // Fetch courses via AJAX
        function fetchCourses() {
            coursesGrid.innerHTML = '<div style="text-align:center; grid-column: 1/-1; padding: 50px; font-size: 1.2rem; color: var(--accent-primary);">⚡ Loading Master Curriculum...</div>';
            
            const formData = new FormData();
            formData.append('category', currentCategory);
            formData.append('search', searchQuery);

            fetch('ajax_filter_courses.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                coursesGrid.innerHTML = data;
            })
            .catch(error => {
                coursesGrid.innerHTML = '<div style="text-align:center; grid-column: 1/-1; color: red;">⚠️ Error loading curriculum.</div>';
            });
        }

        // Search Input listener
        searchBox.addEventListener('input', function(e) {
            searchQuery = e.target.value;
            fetchCourses();
        });

        // Category pill click handler
        function filterCategory(catId, element) {
            currentCategory = catId;
            const pills = document.querySelectorAll('.pill-btn');
            pills.forEach(p => p.classList.remove('active'));
            element.classList.add('active');
            fetchCourses();
        }

        // Initial fetch on page load
        document.addEventListener('DOMContentLoaded', fetchCourses);
    </script>
</body>
</html>
