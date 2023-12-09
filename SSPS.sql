-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th12 09, 2023 lúc 05:30 AM
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
-- Cơ sở dữ liệu: `SSPS`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `accepted_file_types`
--

CREATE TABLE `accepted_file_types` (
  `File_Type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `accepted_file_types`
--

INSERT INTO `accepted_file_types` (`File_Type`) VALUES
('.jpeg'),
('.php');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bpp_order`
--

CREATE TABLE `bpp_order` (
  `Order_ID` int(11) NOT NULL,
  `Order_Creation_Date` datetime NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Payment_Status` tinyint(4) NOT NULL,
  `Owner_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bpp_order`
--

INSERT INTO `bpp_order` (`Order_ID`, `Order_Creation_Date`, `Quantity`, `Payment_Status`, `Owner_ID`) VALUES
(1, '2023-12-06 22:19:10', 45, 1, 13),
(2, '2023-12-06 22:19:53', 12, 1, 13),
(45, '2023-12-09 10:52:29', 35, 0, 1),
(46, '2023-12-09 10:59:35', 45, 0, 1),
(47, '2023-12-09 10:59:42', 34, 0, 1),
(48, '2023-12-09 11:28:28', 12, 1, 13);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `campus_building`
--

CREATE TABLE `campus_building` (
  `printer_campusloc` char(1) NOT NULL CHECK (`printer_campusloc` in ('1','2')),
  `printer_buildingloc` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `campus_building`
--

INSERT INTO `campus_building` (`printer_campusloc`, `printer_buildingloc`) VALUES
('1', 'A2'),
('1', 'A3'),
('1', 'B1'),
('1', 'B2'),
('1', 'B4'),
('1', 'C4'),
('1', 'C6'),
('2', 'H1'),
('2', 'H2'),
('2', 'H3'),
('2', 'H6');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `configuration`
--

CREATE TABLE `configuration` (
  `ID` int(11) NOT NULL,
  `Default_Number_Of_Pages` int(11) NOT NULL,
  `Paper_Price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `configuration`
--

INSERT INTO `configuration` (`ID`, `Default_Number_Of_Pages`, `Paper_Price`) VALUES
(0, 60, 400);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `file`
--

CREATE TABLE `file` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `File_Link` varchar(100) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Upload_Date` datetime NOT NULL,
  `Number_Of_Pages` int(11) NOT NULL,
  `User_ID` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `perform`
--

CREATE TABLE `perform` (
  `Request_ID` int(11) NOT NULL,
  `Printer_ID` int(11) NOT NULL,
  `Start_Time` datetime DEFAULT NULL,
  `End_Time` datetime DEFAULT NULL,
  `Completed_Printing_Pages` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `printer_list`
--

CREATE TABLE `printer_list` (
  `printer_id` varchar(8) NOT NULL,
  `printer_name` varchar(20) NOT NULL,
  `printer_desc` varchar(100) DEFAULT NULL,
  `printer_avai` char(1) DEFAULT NULL CHECK (`printer_avai` in ('Y','N')),
  `printer_campusloc` char(1) DEFAULT NULL,
  `printer_buildingloc` char(2) DEFAULT NULL,
  `printer_room` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `printer_list`
--

INSERT INTO `printer_list` (`printer_id`, `printer_name`, `printer_desc`, `printer_avai`, `printer_campusloc`, `printer_buildingloc`, `printer_room`) VALUES
('1A21011', 'Canon 1', 'Lorem Ipsum', 'Y', '1', 'A2', '101'),
('1A21012', 'Canon 2', 'Lorem Ipsum', 'N', '1', 'A2', '101'),
('1A33051', 'Canon 3', 'Lorem Ipsum', 'N', '1', 'A3', '305'),
('1A33052', 'Canon 3', 'Lorem Ipsum', 'N', '1', 'A3', '305'),
('1A33053', 'Canon 3', 'Lorem Ipsum', 'N', '1', 'A3', '305'),
('2H11031', 'Canon 1', 'Lorem Ipsum', 'Y', '2', 'H1', '103'),
('2H22021', 'Canon 2', 'Lorem Ipsum', 'N', '2', 'H2', '202'),
('2H62011', 'Canon 2', 'Lorem Ipsum', 'N', '2', 'H6', '201'),
('2H62012', 'Canon 2', 'Lorem Ipsum', 'N', '2', 'H6', '201'),
('2H62013', 'Canon 2', 'Lorem Ipsum', 'N', '2', 'H6', '201');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `print_request`
--

CREATE TABLE `print_request` (
  `ID` int(11) NOT NULL,
  `Creation_Date` datetime NOT NULL,
  `Pages_Per_Sheet` enum('1','4','9','16') NOT NULL,
  `Number_Of_Copies` int(11) NOT NULL DEFAULT 1,
  `Page_Size` enum('A4','A3','A2','A1','A0') NOT NULL,
  `One/Doubled_Sided` enum('1','2') NOT NULL,
  `Total_Sheet` int(10) DEFAULT NULL,
  `Status` enum('0','1','2','3') DEFAULT '0',
  `File_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `refill_dates`
--

CREATE TABLE `refill_dates` (
  `Refill_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `refill_dates`
--

INSERT INTO `refill_dates` (`Refill_Date`) VALUES
('2023-12-06 22:04:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `requested_page_numbers`
--

CREATE TABLE `requested_page_numbers` (
  `Request_ID` int(11) NOT NULL,
  `Start_Page` int(11) NOT NULL,
  `End_Page` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Fname` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Email` text DEFAULT NULL,
  `Role` varchar(50) NOT NULL DEFAULT 'Student',
  `Sex` tinyint(4) DEFAULT NULL,
  `Date_Of_Birth` date NOT NULL,
  `Balance` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`ID`, `Fname`, `Lname`, `Email`, `Role`, `Sex`, `Date_Of_Birth`, `Balance`) VALUES
(2, 'Ngân', 'Lê Thị Kim', 'kimngan21012003@gmail.com', 'Student', 0, '2003-02-21', 121),
(13, 'Dương', 'Hà Thuỳ', 'duong.hathuy@hcmut.edu.vn', 'Student', 0, '2003-12-12', 129);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `accepted_file_types`
--
ALTER TABLE `accepted_file_types`
  ADD UNIQUE KEY `File_Type` (`File_Type`);

--
-- Chỉ mục cho bảng `bpp_order`
--
ALTER TABLE `bpp_order`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `Owner_ID` (`Owner_ID`);

--
-- Chỉ mục cho bảng `campus_building`
--
ALTER TABLE `campus_building`
  ADD PRIMARY KEY (`printer_campusloc`,`printer_buildingloc`);

--
-- Chỉ mục cho bảng `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_file_userId` (`User_ID`);

--
-- Chỉ mục cho bảng `perform`
--
ALTER TABLE `perform`
  ADD PRIMARY KEY (`Request_ID`,`Printer_ID`),
  ADD KEY `Printer_ID` (`Printer_ID`);

--
-- Chỉ mục cho bảng `printer_list`
--
ALTER TABLE `printer_list`
  ADD PRIMARY KEY (`printer_id`);

--
-- Chỉ mục cho bảng `print_request`
--
ALTER TABLE `print_request`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `File_ID` (`File_ID`);

--
-- Chỉ mục cho bảng `refill_dates`
--
ALTER TABLE `refill_dates`
  ADD PRIMARY KEY (`Refill_Date`);

--
-- Chỉ mục cho bảng `requested_page_numbers`
--
ALTER TABLE `requested_page_numbers`
  ADD PRIMARY KEY (`Request_ID`,`Start_Page`,`End_Page`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bpp_order`
--
ALTER TABLE `bpp_order`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
