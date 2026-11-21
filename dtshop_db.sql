-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 18, 2026 lúc 08:44 AM
-- Phiên bản máy phục vụ: 10.11.16-MariaDB
-- Phiên bản PHP: 8.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dtshop_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `job_ref` varchar(5) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `nickname` varchar(20) DEFAULT NULL,
  `dob` varchar(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `street_address` varchar(40) NOT NULL,
  `Ward` varchar(40) NOT NULL,
  `province_city` varchar(50) NOT NULL,
  `postcode` varchar(5) NOT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `other_skills` text DEFAULT NULL,
  `status` enum('New','Current','Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `job_ref`, `first_name`, `last_name`, `nickname`, `dob`, `gender`, `email`, `phone`, `street_address`, `Ward`, `province_city`, `postcode`, `skills`, `other_skills`, `status`) VALUES
(1, 'FE001', 'John', 'Smith', 'Johnny', '15/08/2004', 'Male', 'john.smith@example.com', '0912345678', '123 Nguyen Trai', 'Ben Thanh', 'Ho Chi Minh', '70000', 'HTML, CSS, PHP', 'Python', 'New'),
(2, 'UX001', 'Anh', 'Nguyen', 'Tiger', '12/11/1999', 'Male', 'anhnguyen630@gmail.com', '0963980909', 'phạm văn bạch', 'Tân Bình', 'Ho Chi Minh', '70000', 'HTML, CSS, PHP, SQL', 'Python', 'New');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;









-- ---------------------------------------------------------------
-- Jobs: one row per vacancy (list columns hold one item per line)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS jobs (
    job_ref VARCHAR(5) PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    salary VARCHAR(60) NOT NULL,
    reports_to VARCHAR(60) NOT NULL,
    location VARCHAR(60) NOT NULL,
    responsibilities TEXT NOT NULL,
    essential TEXT NOT NULL,
    preferable TEXT NOT NULL
) DEFAULT CHARSET=utf8mb4;

-- Lists are stored one item per line; render_list() in functions.php turns each line into an <li>.

INSERT IGNORE INTO jobs VALUES
('FE001', 'Front-End Web Developer',
 'Build and improve the product listing pages, search and checkout screens that thousands of PC builders use every day.',
 'AUD $80,000-$95,000 per year', 'Head of Digital Engineering', 'Ho Chi Minh City, hybrid',
 'Develop responsive product listing and product detail pages in HTML5, CSS3 and JavaScript.
Turn UX designs into accessible, fast-loading interfaces.
Improve page speed and Core Web Vitals scores across the store.
Review code with teammates and fix defects found in testing.',
 'Strong HTML5 and CSS3, including Flexbox and Grid.
Working knowledge of JavaScript.
Understanding of WCAG accessibility standards.
Experience using Git and GitHub.',
 'Experience with an e-commerce platform.
Familiarity with a front-end framework such as React.
Basic knowledge of SEO and web analytics.'),

('UX001', 'UX Designer',
 'Shape how customers find, compare and buy PC components, from first search to final checkout.',
 'AUD $75,000-$90,000 per year', 'Head of Digital Experience', 'Ho Chi Minh City, hybrid',
 'Run user research and usability tests on shopping and checkout flows.
Create wireframes, prototypes and design specifications for developers.
Maintain the DTShop design system and component library.
Work with developers to check designs are built as intended.',
 'Portfolio showing user-centred design work.
Proficiency in Figma or a similar design tool.
Knowledge of accessible design and colour contrast.
Clear communication with technical and non-technical colleagues.',
 'Experience designing for online retail.
Basic HTML and CSS knowledge.
Experience running A/B tests.'),

('BE001', 'Back-End Developer',
 'Build the server-side systems behind DTShop, from product catalogue and search to orders and payments.',
 'AUD $85,000-$100,000 per year', 'Head of Digital Engineering', 'Ho Chi Minh City, hybrid',
 'Design and maintain APIs for product listings, search, cart and checkout.
Manage the product, customer and order databases, including query performance.
Integrate payment, shipping and inventory services securely.
Monitor system uptime, fix production issues and write automated tests.',
 'Experience with a server-side language such as Python, PHP, Node.js or Java.
Solid SQL and relational database design (MySQL or PostgreSQL).
Experience building and consuming REST APIs.
Understanding of web security basics, including authentication and input validation.',
 'Experience with an e-commerce platform or payment gateway.
Familiarity with cloud hosting and Docker.
Experience with caching tools such as Redis.');


-- ---------------------------------------------------------------
-- About: team members, contributions to Project 1 and Project 2, quotes
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS about (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    student_id VARCHAR(20) NOT NULL,
    role VARCHAR(100) NOT NULL,
    contribution_part1 TEXT NOT NULL,
    contribution_part2 TEXT NOT NULL,
    quote_vi VARCHAR(255) NOT NULL,
    quote_en VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO about (id, name, student_id, role, contribution_part1, contribution_part2, quote_vi, quote_en) VALUES
(1, 'Dao Gia Khang', '106391302', 'Team Leader', 'Responsible for validation, accessibility and final testing, and coordinating the final submission of the static website.', 'Created settings.php and the shared include files (header, nav, footer), built manage.php so managers can list, delete and update EOIs, and checked and submitted the final project.', 'Có công mài sắt, có ngày nên kim.', 'Hard work and persistence lead to success.'),
(2, 'Huynh Phan Yuki', '106394628', 'Jira Manager', 'Managed the Jira project board, tasks and sprint planning, and built the jobs page.', 'Built jobs.php, which loads job listings from the jobs table, created the jobs table, and made search.php with the search boxes on the home and jobs pages.', 'Học đi đôi với hành.', 'Learning goes hand in hand with practice.'),
(3, 'Nguyen Dinh Anh', '106727091', 'Aboriginal and Torres Strait Islander Content Lead', 'Responsible for the Aboriginal and Torres Strait Islander element of the project, and built the apply page and its form.', 'Built apply.php and process_eoi.php with server-side validation of every field, created the eoi table, and showed the EOI number after a successful submission.', 'Thất bại là mẹ thành công.', 'Failure is the mother of success.'),
(4, 'Dao Ba Hung', '106726072', 'Tutor Liaison', 'Communicated with the tutor on behalf of the group, and built the about page.', 'Created the about table and about.php, which loads the member contributions from the database, and built login.php with the users table to protect the manage page.', 'Kiến tha lâu cũng đầy tổ.', 'Small efforts add up over time.');

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    failed_attempts INT NOT NULL DEFAULT 0,
    locked_until DATETIME NULL DEFAULT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO users (id, username, password) VALUES
(1, 'admin', '$2y$10$NGyAbubNAtEbQsBSL/UX9OdGo6QXqe91CjQ7RxPGp74VKjRlbP8HO');

