<?php
// report_view.php - Print-Ready HTML rendering of the Project Report for 1-click PDF export
require_once('config.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task 5 Project Report - ApexAcademy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 60px 40px;
            line-height: 1.7;
        }
        .report-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .header-section {
            text-align: center;
            border-bottom: 3px solid #00e5ff;
            padding-bottom: 40px;
            margin-bottom: 50px;
        }
        .header-section h1 {
            font-size: 2.8rem;
            color: #0f172a;
            font-weight: 800;
            margin: 0 0 15px;
        }
        .header-meta {
            font-size: 1.1rem;
            color: #64748b;
        }
        .section-title {
            font-size: 1.8rem;
            color: #0f172a;
            font-weight: 700;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            margin: 50px 0 25px;
        }
        .sub-section-title {
            font-size: 1.3rem;
            color: #334155;
            font-weight: 600;
            margin: 30px 0 15px;
        }
        ul {
            padding-left: 25px;
        }
        li {
            margin-bottom: 12px;
            font-size: 1.05rem;
            color: #334155;
        }
        li strong {
            color: #0f172a;
        }
        .schema-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 25px;
            font-family: monospace;
            font-size: 0.95rem;
            overflow-x: auto;
            white-space: pre;
            color: #0f172a;
        }
        .print-btn {
            display: block;
            width: 250px;
            margin: 0 auto 60px;
            padding: 16px 24px;
            background: #0f172a;
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .print-btn:hover {
            background: #00e5ff;
            color: #0f172a;
            transform: translateY(-2px);
        }
        @media print {
            .print-btn { display: none; }
            body { padding: 0; background-color: #ffffff; }
        }
    </style>
</head>
<body>

    <div class="report-container">
        <button onclick="window.print();" class="print-btn">🖨️ Save Report as PDF</button>

        <div class="header-section">
            <h1>ApexAcademy E-Learning Portal</h1>
            <div class="header-meta">
                <strong>Task 5 Final Capstone Project Report</strong><br>
                <strong>Author:</strong> Aryan Sarkar | <strong>Timeline:</strong> Days 49–60<br>
                <strong>Partner Organization:</strong> ApexPlanet Software Pvt. Ltd. / Pinnacle AI
            </div>
        </div>

        <div class="section-title">1. Executive Summary & Objective</div>
        <p style="font-size: 1.1rem; color: #334155;">
            The capstone project objective was to independently design, build, and deploy a professional-grade web application demonstrating complete industry readiness. <strong>ApexAcademy</strong> was conceptualized as a premium E-Learning Portal bridging elite front-end design aesthetics with robust, secure backend logic, interactive asynchronous searching, and live analytical monitoring.
        </p>

        <div class="section-title">2. System Architecture & Key Modules</div>
        
        <div class="sub-section-title">2.1 Secure Authentication & Email OTP Gateway</div>
        <ul>
            <li><strong>Cryptographic Hashing:</strong> User passwords are encrypted at rest using PHP's native <code>password_hash()</code> implementing the strong bcrypt algorithm.</li>
            <li><strong>One-Time Password (OTP) Flow:</strong> To ensure valid student registrations, the system halts login until a dynamically generated 6-digit OTP code sent via email is verified.</li>
            <li><strong>Role-Based Access Control (RBAC):</strong> Strict separation of privileges between normal students (<code>role = student</code>) and system administrators (<code>role = admin</code>).</li>
        </ul>

        <div class="sub-section-title">2.2 Zero-Reload AJAX Search & Filtering</div>
        <ul>
            <li><strong>Asynchronous Fetch API:</strong> Replaces traditional slow page reloads with lightning-fast JavaScript <code>fetch()</code> calls.</li>
            <li><strong>Parametric Filtering:</strong> Evaluates search inputs against course titles, technical descriptions, and instructor names in real-time, accompanied by category pill isolation.</li>
        </ul>

        <div class="sub-section-title">2.3 Super Admin Command Center & Analytics</div>
        <ul>
            <li><strong>Visual Analytics:</strong> Integrates <code>Chart.js</code> to parse live MySQL relational records into highly responsive bar charts (Course Enrollment Popularity) and doughnut charts (OTP Verification Demographics).</li>
            <li><strong>Master CRUD Operations:</strong> Comprehensive inventory and account management tools enabling dynamic SQL <code>INSERT</code>, <code>SELECT</code>, <code>UPDATE</code>, and <code>DELETE</code> execution for courses and students.</li>
        </ul>

        <div class="section-title">3. Database Entity-Relationship Schema (<code>apex_academy_db</code>)</div>
        <p style="font-size: 1.05rem; color: #334155; margin-bottom: 20px;">The platform enforces strict referential integrity across 4 fundamental tables using InnoDB foreign key cascading:</p>
        
        <div class="schema-box">
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
        </div>

        <div class="section-title">4. Deployment & Hosting Specifications</div>
        <ul>
            <li><strong>Target Platform:</strong> InfinityFree / 000webhost Free Hosting environment.</li>
            <li><strong>Version Control Integration:</strong> Connected directly to the dedicated GitHub repository (<code>Apex_Portfolio-Task5</code>) ensuring continuous delivery and live updates.</li>
            <li><strong>Configuration Fallbacks:</strong> Master configuration (<code>config.php</code>) equipped with dynamic connection exceptions and automated database initialization scripts.</li>
        </ul>

        <div class="section-title">5. Project Conclusion & Industry Readiness Proof</div>
        <p style="font-size: 1.1rem; color: #334155;">
            ApexAcademy establishes concrete evidence of my full-stack engineering proficiency. From engineering complex database schemas and strict RBAC session boundaries to integrating third-party chart libraries and asynchronous JavaScript handlers, this capstone confirms my capability to build and deploy commercial-grade web applications to production environments.
        </p>

        <div style="text-align: center; margin-top: 80px; color: #94a3b8; font-size: 0.95rem; border-top: 1px solid #e2e8f0; padding-top: 30px;">
            ApexAcademy Capstone Report &copy; <?php echo date("Y"); ?> ApexPlanet Software Pvt. Ltd. | Confidential Evaluation Document
        </div>
    </div>

</body>
</html>
