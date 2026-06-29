# Capstone Project Report: ApexAcademy E-Learning Portal
**Author:** Aryan Sarkar  
**Timeline:** Days 49–60 (Task 5 Final Capstone & Deployment)  
**Partner Organization:** ApexPlanet Software Pvt. Ltd. / Pinnacle AI  

---

## 1. Executive Summary & Objective
The capstone project objective was to independently design, build, and deploy a professional-grade web application demonstrating complete industry readiness. **ApexAcademy** was conceptualized as a premium E-Learning Portal bridging elite front-end design aesthetics with robust, secure backend logic, interactive asynchronous searching, and live analytical monitoring.

---

## 2. System Architecture & Key Modules

### 2.1 Secure Authentication & Email OTP Gateway (`register.php`, `verify_otp.php`, `login.php`)
- **Cryptographic Hashing:** User passwords are encrypted at rest using PHP's native `password_hash()` implementing the strong bcrypt algorithm.
- **One-Time Password (OTP) Flow:** To ensure valid student registrations, the system halts login until a dynamically generated 6-digit OTP code sent via email is verified.
- **Role-Based Access Control (RBAC):** Strict separation of privileges between normal students (`role = student`) and system administrators (`role = admin`).

### 2.2 Zero-Reload AJAX Search & Filtering (`courses.php`, `ajax_filter_courses.php`)
- **Asynchronous Fetch API:** Replaces traditional slow page reloads with lightning-fast JavaScript `fetch()` calls.
- **Parametric Filtering:** Evaluates search inputs against course titles, technical descriptions, and instructor names in real-time, accompanied by category pill isolation.

### 2.3 Super Admin Command Center & Analytics (`admin_dashboard.php`)
- **Visual Analytics:** Integrates `Chart.js` to parse live MySQL relational records into highly responsive bar charts (Course Enrollment Popularity) and doughnut charts (OTP Verification Demographics).
- **Master CRUD Operations:** Comprehensive inventory and account management tools enabling dynamic SQL `INSERT`, `SELECT`, `UPDATE`, and `DELETE` execution for courses (`admin_courses.php`) and students (`admin_students.php`).

---

## 3. Database Entity-Relationship Schema (`apex_academy_db`)
The platform enforces strict referential integrity across 4 fundamental tables using `InnoDB` foreign key cascading:

```
+-------------------+       +-------------------+       +-------------------+
|       users       |       |    enrollments    |       |      courses      |
+-------------------+       +-------------------+       +-------------------+
| id (PK)           |1     *| id (PK)           |*     1| id (PK)           |
| full_name         |-------| user_id (FK)      |-------| category_id (FK)  |
| email (UNIQUE)    |       | course_id (FK)    |       | title             |
| password_hash     |       | progress_percent  |       | description       |
| role (enum)       |       | enrollment_date   |       | price             |
| is_verified       |       +-------------------+       +-------------------+
| otp_code          |                                             |*
+-------------------+                                             |1
                                                        +-------------------+
                                                        |    categories     |
                                                        +-------------------+
                                                        | id (PK)           |
                                                        | name              |
                                                        | slug              |
                                                        +-------------------+
```

---

## 4. Deployment & Hosting Specifications
- **Target Platform:** InfinityFree / 000webhost Free Hosting environment.
- **Version Control Integration:** Connected directly to the dedicated GitHub repository (`Apex_Portfolio-Task5`) ensuring continuous delivery and live updates.
- **Configuration Fallbacks:** Master configuration (`config.php`) equipped with dynamic connection exceptions and automated database initialization scripts.

---

## 5. Project Conclusion & Industry Readiness Proof
ApexAcademy establishes concrete evidence of my full-stack engineering proficiency. From engineering complex database schemas and strict RBAC session boundaries to integrating third-party chart libraries and asynchronous JavaScript handlers, this capstone confirms my capability to build and deploy commercial-grade web applications to production environments.
