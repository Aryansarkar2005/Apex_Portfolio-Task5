-- ApexAcademy E-Learning Portal - Master Database Schema
-- Database: apex_academy_db

CREATE DATABASE IF NOT EXISTS `apex_academy_db`;
USE `apex_academy_db`;

-- 1. Users Table (Stores Students and Super Admins with OTP Fields)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('student', 'admin') DEFAULT 'student',
    `is_verified` TINYINT(1) DEFAULT 0,
    `otp_code` VARCHAR(10) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default Super Admin (Password: ApexAdmin@2026)
INSERT INTO `users` (`full_name`, `email`, `password_hash`, `role`, `is_verified`, `otp_code`) 
VALUES ('Super Admin', 'admin@apexacademy.com', '$2y$10$Qj2o1jKx2Lg5uN/qP6E6L.lBmVl/lCj1yq.4Qe0H/0b.uQh.rW.eG', 'admin', 1, NULL)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 2. Categories Table (For AJAX Real-time Filtering)
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert initial categories
INSERT INTO `categories` (`id`, `name`, `slug`) VALUES
(1, 'Web Development', 'web-development'),
(2, 'Artificial Intelligence', 'artificial-intelligence'),
(3, 'Data Science', 'data-science'),
(4, 'Cyber Security', 'cyber-security'),
(5, 'UI/UX Design', 'ui-ux-design')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 3. Courses Table (Course details, pricing, instructor, and category relation)
CREATE TABLE IF NOT EXISTS `courses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NOT NULL,
    `instructor` VARCHAR(100) NOT NULL,
    `duration` VARCHAR(50) NOT NULL,
    `price` DECIMAL(10, 2) DEFAULT 0.00,
    `difficulty` ENUM('Beginner', 'Intermediate', 'Advanced') DEFAULT 'Beginner',
    `image_url` VARCHAR(255) DEFAULT 'assets/course_default.png',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert premium sample courses
INSERT INTO `courses` (`id`, `category_id`, `title`, `description`, `instructor`, `duration`, `price`, `difficulty`, `image_url`) VALUES
(1, 1, 'Full-Stack Modern Web Engineering', 'Master front-end foundations with HTML5, CSS3 Grid, and advanced backend API architectures using PHP 8 and MySQL.', 'Aryan Sarkar', '42 Hours', 199.99, 'Intermediate', 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&auto=format&fit=crop&q=80'),
(2, 2, 'Applied AI & Large Language Models', 'Explore generative AI architectures, prompt design, and fine-tuning neural networks for commercial SaaS products.', 'Dr. Julian Vance', '35 Hours', 249.99, 'Advanced', 'https://images.unsplash.com/photo-1677442136019-21780efad99a?w=600&auto=format&fit=crop&q=80'),
(3, 3, 'Python for Data Science & Machine Learning', 'Comprehensive data visualization, statistical analysis, and machine learning models utilizing Pandas, NumPy, and Scikit-Learn.', 'Sarah Chen, M.S.', '28 Hours', 149.99, 'Beginner', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80'),
(4, 4, 'Cyber Security & Ethical Hacking', 'Advanced penetration testing, vulnerability assessment, and enterprise cryptographic engineering methodologies.', 'Marcus Thorne', '50 Hours', 299.99, 'Advanced', 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=600&auto=format&fit=crop&q=80'),
(5, 5, 'High-Fidelity UI/UX Design & Wireframing', 'From wireframe foundations to pixel-perfect Figma prototypes. Build stunning design systems with micro-animations.', 'Elena Rostova', '20 Hours', 129.99, 'Beginner', 'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?w=600&auto=format&fit=crop&q=80'),
(6, 1, 'Advanced Next.js & React Ecosystems', 'Architect scalable, server-side rendered web applications featuring Next.js 14, App Router, and complex state management.', 'David Miller', '32 Hours', 179.99, 'Intermediate', 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&auto=format&fit=crop&q=80')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 4. Enrollments Table (Tracks student course registrations and lesson progress)
CREATE TABLE IF NOT EXISTS `enrollments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `course_id` INT NOT NULL,
    `enrollment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `progress_percent` INT DEFAULT 0,
    `is_completed` TINYINT(1) DEFAULT 0,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_enrollment` (`user_id`, `course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
