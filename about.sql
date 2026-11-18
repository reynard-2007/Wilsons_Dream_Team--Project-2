-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th10 18, 2026 lúc 06:32 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

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
-- Cấu trúc bảng cho bảng `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `role` varchar(100) NOT NULL,
  `contribution_part1` text NOT NULL,
  `contribution_part2` text NOT NULL,
  `quote_vi` varchar(255) NOT NULL,
  `quote_en` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `about`
--

INSERT INTO `about` (`id`, `name`, `student_id`, `role`, `contribution_part1`, `contribution_part2`, `quote_vi`, `quote_en`) VALUES
(1, 'Dao Gia Khang', '106391302', 'Team Leader', 'Responsible for validation, accessibility and final testing, and coordinating the final submission of the static website.', 'Created settings.php and the shared include files (header, nav, footer), built manage.php so managers can list, delete and update EOIs, and checked and submitted the final project.', 'Có công mài sắt, có ngày nên kim.', 'Hard work and persistence lead to success.'),
(2, 'Huynh Phan Yuki', '106394628', 'Jira Manager', 'Managed the Jira project board, tasks and sprint planning, and built the jobs page.', 'Built jobs.php, which loads job listings from the jobs table, created the jobs table, and made search.php with the search boxes on the home and jobs pages.', 'Học đi đôi với hành.', 'Learning goes hand in hand with practice.'),
(3, 'Nguyen Dinh Anh', '106727091', 'Aboriginal and Torres Strait Islander Content Lead', 'Responsible for the Aboriginal and Torres Strait Islander element of the project, and built the apply page and its form.', 'Built apply.php and process_eoi.php with server-side validation of every field, created the eoi table, and showed the EOI number after a successful submission.', 'Thất bại là mẹ thành công.', 'Failure is the mother of success.'),
(4, 'Dao Ba Hung', '106726072', 'Tutor Liaison', 'Communicated with the tutor on behalf of the group, and built the about page.', 'Created the about table and about.php, which loads the member contributions from the database, and built login.php with the users table to protect the manage page.', 'Kiến tha lâu cũng đầy tổ.', 'Small efforts add up over time.');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
