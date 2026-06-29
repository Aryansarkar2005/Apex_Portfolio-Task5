# 🚀 Task 5 Capstone Project: ApexAcademy E-Learning Portal
**Author:** Aryan Sarkar  
**Timeline:** Days 49–60 (Task 5 Capstone Project & Production Deployment)  
**Partner Organization:** ApexPlanet Software Pvt. Ltd. / Pinnacle AI  
---
## 📖 Executive Summary & Objective
The objective of this final capstone project was to independently design, build, and deploy a professional-grade web application showcasing complete industry readiness. **ApexAcademy** was engineered from the ground up as a premium E-Learning and Course Enrollment Portal. It bridges elite frontend design aesthetics with robust backend logic, real-time asynchronous searching, secure Email OTP authentication, and interactive visual analytical monitoring.
---
## 🏛️ System Architecture & Core Modules
### 🔒 1. Secure Authentication & Email OTP Gateway
*   **Cryptographic Protection:** User passwords are encrypted at rest using PHP's native `password_hash()` implementing strong bcrypt hashing algorithms.
*   **One-Time Password (OTP) Flow:** To guarantee verified student registrations, the system halts login until a dynamically generated 6-digit OTP code sent via email is successfully validated (`verify_otp.php`).
*   **Local Development Simulation:** Equipped with a smart fallback helper (`mail_helper.php`) that securely logs and displays the simulated OTP on-screen in a dedicated banner for seamless local evaluation.
*   **Role-Based Access Control (RBAC):** Strict session boundaries separating normal Students (`role = student`) from Super Admins (`role = admin`).
### ⚡ 2. Zero-Reload AJAX Search & Filtering
*   **Asynchronous Fetch API:** Replaces traditional slow page reloads with lightning-fast JavaScript `fetch()` calls (`courses.php`).
*   **Real-Time Active Filtering:** Evaluates search box inputs against course titles, technical descriptions, and instructor names in real-time (`ajax_filter_courses.php`). Includes active category pill isolation.
### 📈 3. Super Admin Command Center (`admin_dashboard.php`)
*   **Chart.js Visual Analytics:** Parses live MySQL relational records into highly responsive visual bar charts (Course Enrollment Popularity) and doughnut charts (OTP Verification Demographics).
*   **Master CRUD Operations:** Comprehensive inventory and account management tools enabling dynamic SQL `INSERT`, `SELECT`, `UPDATE`, and `DELETE` execution for master courses (`admin_courses.php`) and student audits (`admin_students.php`).
---
## 🗄️ Relational Database Schema (`apex_academy_db`)
The platform enforces strict referential integrity across 4 fundamental tables using `InnoDB` foreign key cascading:
+-------------------+ +-------------------+ +-------------------+ | users | | enrollments | | courses | +-------------------+ +-------------------+ +-------------------+ | id (PK) |1 | id (PK) | 1| id (PK) | | full_name |-------| user_id (FK) |-------| category_id (FK) | | email (UNIQUE) | | course_id (FK) | | title | | password_hash | | progress_percent | | description | | role (enum) | | enrollment_date | | price | | is_verified | +-------------------+ +-------------------+ | otp_code | |* +-------------------+ |1 +-------------------+ | categories | +-------------------+ | id (PK) | | name | | slug | +-------------------+

---
## 🚀 Deployment & Installation Instructions
### Local Development Setup (XAMPP)
1. Clone this repository into your local XAMPP `htdocs/` directory: `c:\xampp\htdocs\Apex_Portfolio\ApexAcademy\`
2. Open `phpMyAdmin` and import `database.sql` to generate the `apex_academy_db` schema.
3. Open `http://localhost/Apex_Portfolio/ApexAcademy/index.php` in your browser.
### Live Production Deployment (InfinityFree / Free Hosting)
1. Create a free hosting account at `https://infinityfree.com`.
2. Import `database.sql` into the production `phpMyAdmin`.
3. Upload all project files into the production `htdocs/` root folder using the Online File Manager or FileZilla FTP.
4. Update `config.php` with your active live database credentials (`$db_host`, `$db_user`, `$db_pass`, `$db_name`).
---
## 🔑 Demonstration Login Credentials
*   **Super Admin Account:** `admin@apexacademy.com` | **Password:** `ApexAdmin@2026`
*   **Student OTP Demo:** Click "Get Started" in the navbar, register any sample email, and inspect the blue **[Local Dev Tip]** banner on the verification screen for your instant 6-digit OTP!
---
## 🎯 Project Conclusion & Industry Readiness Proof
ApexAcademy establishes concrete evidence of my full-stack engineering proficiency. From engineering complex database schemas and strict RBAC session boundaries to integrating third-party chart libraries and asynchronous JavaScript handlers, this capstone confirms my capability to build and deploy commercial-grade web applications to production environments.
