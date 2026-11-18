-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 18, 2026 lúc 05:58 AM
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
(2, 'UX001', 'Anh', 'Nguyen', 'Tiger', '12/10/2008', 'Male', 'dinhanhn630@gmail.com', '0962937979', 'số 1 Trần Văn Danh', 'Tân Bình', 'Ho Chi Minh', '70000', 'HTML, CSS, PHP, SQL', 'Python', 'New');

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
