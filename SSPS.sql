-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2023 at 11:15 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ssps`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `displayLog` (IN `id_argu` INT(11))   BEGIN
  select
    perform.Request_ID as requestid, 
    perform.Start_Time as starttime, 
    perform.End_Time as endtime, file.Name as filename, 
    file.Number_Of_Pages as totalpage, 
    print_request.`One/Doubled_Sided` as numbersides,
     print_request.Number_Of_Copies as numbercopies, print_request.Pages_Per_Sheet as paper_per_sheet, 
     print_request.Page_Size as papersize, print_request.Total_Sheet as total_sheet,
     printer_list.printer_id as printer_model,print_request.Status as state_requestprint 
     from perform join print_request on perform.Request_ID =print_request.ID
      join printer_list on perform.Printer_ID = printer_list.printer_id join 
      file on print_request.File_ID = file.ID join users 
      where file.User_ID = users.ID and file.User_ID = id_argu order by End_Time asc;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertfile` (IN `userID` INT(11))   BEGIN
set @random = FLOOR(1+ RAND()*100);
set @name = CONCAT("exfile", @random); 
set @filelink = CONCAT("exfile",@random,"link"); 

    insert into file(Name, File_Link, Type, Upload_Date, Number_Of_Pages, User_ID) 
VALUES(@name,@filelink, 'Pdf',NOW(), @random, userID);
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `Count_Balance` (`balance` INT(11), `total_sheet` INT(11)) RETURNS INT(11)  BEGIN
   DECLARE res INT(11) DEFAULT 0;
set res = balance - total_sheet;
RETURN res;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `Count_Totalsheet` (`requestid_argu` INT) RETURNS INT(11) DETERMINISTIC BEGIN
   DECLARE total INT(11) DEFAULT 0;
   select file.Number_Of_Pages, print_request.Pages_Per_Sheet, print_request.`One/Doubled_Sided`,print_request.Number_Of_Copies, print_request.Page_Size into @totalpage, @paper_per_sheet, @numbersides, @numbercopies, @papersize from file join print_request on print_request.File_ID = file.ID where print_request.ID = requestid_argu;
   set total = ceil((@totalpage) / (@paper_per_sheet *  @numbersides)) * @numbercopies;
   IF @papersize LIKE 'A4' THEN
   SET total = total * 1;
   ELSEIF @papersize LIKE 'A3' THEN
   SET total = total * 2;
   ELSEIF @papersize LIKE 'A2' THEN
   SET total = total * 3;
   ELSEIF @papersize LIKE 'A1' THEN
   SET total = total * 4;
   ELSEIF @papersize LIKE 'A0' THEN
   SET total = total * 5;
   ELSE
   SET total = total * 1;
   END IF;
   RETURN total;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `accepted_file_types`
--

CREATE TABLE `accepted_file_types` (
  `File_Type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accepted_file_types`
--

INSERT INTO `accepted_file_types` (`File_Type`) VALUES
('.jpeg'),
('.php');

-- --------------------------------------------------------

--
-- Table structure for table `bpp_order`
--

CREATE TABLE `bpp_order` (
  `Order_ID` int(11) NOT NULL,
  `Order_Creation_Date` datetime NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Payment_Status` tinyint(4) NOT NULL,
  `Owner_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpp_order`
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
-- Table structure for table `campus_building`
--

CREATE TABLE `campus_building` (
  `printer_campusloc` char(1) NOT NULL CHECK (`printer_campusloc` in ('1','2')),
  `printer_buildingloc` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campus_building`
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
-- Table structure for table `configuration`
--

CREATE TABLE `configuration` (
  `ID` int(11) NOT NULL,
  `Default_Number_Of_Pages` int(11) NOT NULL,
  `Paper_Price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`ID`, `Default_Number_Of_Pages`, `Paper_Price`) VALUES
(0, 60, 400);

-- --------------------------------------------------------

--
-- Table structure for table `file`
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

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`ID`, `Name`, `File_Link`, `Type`, `Upload_Date`, `Number_Of_Pages`, `User_ID`) VALUES
(1, 'Báo cáo thực tập điện', 'bcttd.docx', 'Word', '2023-11-01 15:31:26', 12, 5),
(2, 'Bài tập lớn lịch sử đảng', 'lsđ.pdf', 'Pdf', '2023-11-02 16:22:35', 25, 6),
(3, 'Bài tập lớn phương pháp tính', 'ppt.xlsx', 'Excel', '2023-11-01 23:39:40', 74, 7),
(4, 'Bài tập lớn tư tưởng Hồ Chí Minh', 'tthcm.pdf', 'Pdf', '2023-11-05 05:39:44', 4, 8),
(5, 'Báo cáo polyme', 'plm.docx', 'Word', '2023-11-03 14:17:50', 7, 9),
(6, 'Bài tập lớn triết', 'triết.pdf', 'Pdf', '2023-11-02 05:39:56', 2, 3),
(7, 'Báo cáo thực tập điện', 'tt.docx', 'Word', '2023-11-06 10:40:01', 16, 10),
(8, 'Bài tập lớn giải tích', 'gt.pdf', 'Pdf', '2023-11-05 18:19:07', 40, 5),
(9, 'Xác suất thống kê', 'xstk.xlsx', 'Excel', '2023-11-04 17:00:14', 35, 6),
(10, 'Bài tập lớn kinh tế', 'kt.pdf', 'Pdf', '2023-11-01 10:40:19', 31, 7),
(104, 'BTL MMT ', './BTLMMT/ASS1', 'Pdf', '2023-11-30 09:45:14', 28, 8),
(106, 'CNPM', './CNPM/CNPM.pdf', 'Pdf', '2023-11-30 09:52:56', 12, 9),
(107, 'report.pdf', './report.pdf', 'Pdf', '2023-11-30 10:10:56', 15, 3),
(108, '48231-51841-1-PB.pdf', './48231-51841-1-PB.pdf', 'Pdf', '2023-11-30 10:11:41', 28, 3),
(109, '06 - UML Sequence Diagrams.pdf', './CNPM/file', 'Pdf', '2023-11-30 10:12:12', 15, 3),
(110, 'Lab_8_Wireshark_SSL_v8.0.pdf', './Lab_8_Wireshark_SSL_v8.0.pdf', 'Pdf', '2023-11-30 10:13:08', 30, 3),
(111, 'Lab_4b_Wireshark_DHCP_v8.0.pdf', './Lab_4b_Wireshark_DHCP_v8.0.pdf', 'Pdf', '2023-11-30 10:13:31', 15, 3),
(112, 'Lab_4a_Wireshark_IP_v8.0.pdf', './Lab_4a_Wireshark_IP_v8.0.pdf', 'Pdf', '2023-11-30 10:15:56', 50, 3),
(113, 'PacketSwitching-CaseStudy-v1-c.pdf', './PacketSwitching-CaseStudy-v1-c.pdf', 'Pdf', '2023-11-30 10:16:31', 28, 3),
(114, 'BTL1-Network-Application-P2P-File-Sharing.pdf', './BTL1-Network-Application-P2P-File-Sharing.pdf', 'Pdf', '2023-11-30 10:17:57', 28, 3),
(115, 'BTL2-Network-Design-For-A-Company.pdf', './BTL2-Network-Design-For-A-Company.pdf', 'Pdf', '2023-11-30 10:18:28', 30, 3),
(116, '6_SQL.pdf', './6_SQL.pdf', 'Pdf', '2023-11-30 10:19:09', 43, 3),
(122, 'CNPM', 'CNPM', 'Pdf', '2023-12-06 21:01:28', 50, 1),
(124, 'btlMMT', 'rrr', 'Pdf', '2023-12-06 21:27:47', 28, 1),
(125, 'btlMMT', 'jhh,h', 'Pdf', '2023-12-06 21:30:04', 15, 1),
(126, 'CNPM', 'CNPM', 'Pdf', '2023-12-06 21:31:19', 15, 1),
(127, 'BTL MMT ', 'hhhh', 'Pdf', '2023-12-06 21:36:05', 12, 1),
(129, 'exfile53', 'exfile53link', 'Pdf', '2023-12-07 15:58:38', 53, 1),
(130, 'exfile89', 'exfile89link', 'Pdf', '2023-12-07 15:58:44', 89, 1),
(131, 'exfile84', 'exfile84link', 'Pdf', '2023-12-07 15:58:48', 84, 1),
(133, 'Báo cáo thực tập điện', 'bcttd.docx', 'Word', '2023-11-01 15:31:26', 12, 11),
(135, 'Bài tập lớn phương pháp tính', 'ppt.xlsx', 'Excel', '2023-11-01 23:39:40', 74, 13),
(136, 'Bài tập lớn tư tưởng Hồ Chí Minh', 'tthcm.pdf', 'Pdf', '2023-11-05 05:39:44', 4, 14),
(137, 'Báo cáo polyme', 'plm.docx', 'Word', '2023-11-03 14:17:50', 7, 15),
(138, 'Bài tập lớn triết', 'triết.pdf', 'Pdf', '2023-11-02 05:39:56', 2, 16),
(139, 'Báo cáo thực tập điện', 'tt.docx', 'Word', '2023-11-06 10:40:01', 16, 17),
(140, 'Bài tập lớn giải tích', 'gt.pdf', 'Pdf', '2023-11-05 18:19:07', 40, 8),
(141, 'Xác suất thống kê', 'xstk.xlsx', 'Excel', '2023-11-04 17:00:14', 35, 9),
(143, 'BTL MMT ', './BTLMMT/ASS1', 'Pdf', '2023-11-30 09:45:14', 28, 6),
(144, 'CNPM', './CNPM/CNPM.pdf', 'Pdf', '2023-11-30 09:52:56', 12, 5),
(145, 'report.pdf', './report.pdf', 'Pdf', '2023-11-30 10:10:56', 15, 20),
(146, '48231-51841-1-PB.pdf', './48231-51841-1-PB.pdf', 'Pdf', '2023-11-30 10:11:41', 28, 21),
(147, '06 - UML Sequence Diagrams.pdf', './CNPM/file', 'Pdf', '2023-11-30 10:12:12', 15, 22),
(148, 'Lab_8_Wireshark_SSL_v8.0.pdf', './Lab_8_Wireshark_SSL_v8.0.pdf', 'Pdf', '2023-11-30 10:13:08', 30, 23),
(149, 'Lab_4b_Wireshark_DHCP_v8.0.pdf', './Lab_4b_Wireshark_DHCP_v8.0.pdf', 'Pdf', '2023-11-30 10:13:31', 15, 24),
(150, 'Lab_4a_Wireshark_IP_v8.0.pdf', './Lab_4a_Wireshark_IP_v8.0.pdf', 'Pdf', '2023-11-30 10:15:56', 50, 25),
(152, 'BTL1-Network-Application-P2P-File-Sharing.pdf', './BTL1-Network-Application-P2P-File-Sharing.pdf', 'Pdf', '2023-11-30 10:17:57', 28, 25),
(155, 'SSPS.pdf', './SSPS.pdf', 'Pdf', '2023-11-30 10:19:31', 12, 11),
(156, '05_Ch5 System Modeling.pdf', './05_Ch5 System Modeling.pdf', 'Pdf', '2023-11-30 10:20:07', 28, 8),
(157, 'HK1_2324_BTL2-Network-Design-For-A-Company', './HK1_2324_BTL2-Network-Design-For-A-Company', 'Pdf', '2023-12-08 10:08:02', 15, 9),
(158, '05_Ch5_Introduction_OOP_2023.pdf', './05_Ch5_Introduction_OOP_2023.pdf', 'Pdf', '2023-12-08 10:49:32', 28, 16),
(159, '06_Ch6 Architectural Design.pdf', './06_Ch6 Architectural Design.pdf', 'Pdf', '2023-12-08 10:52:58', 12, 17),
(160, 'Báo cáo thực tập điện', 'bcttd.docx', 'Word', '2023-11-01 15:31:26', 12, 3),
(162, 'Bài tập lớn phương pháp tính', 'ppt.xlsx', 'Excel', '2023-11-01 23:39:40', 74, 13),
(163, 'Bài tập lớn tư tưởng Hồ Chí Minh', 'tthcm.pdf', 'Pdf', '2023-11-05 05:39:44', 4, 14),
(164, 'Báo cáo polyme', 'plm.docx', 'Word', '2023-11-03 14:17:50', 7, 15),
(165, 'Bài tập lớn triết', 'triết.pdf', 'Pdf', '2023-11-02 05:39:56', 2, 16),
(166, 'Báo cáo thực tập điện', 'tt.docx', 'Word', '2023-11-06 10:40:01', 16, 17),
(167, 'Bài tập lớn giải tích', 'gt.pdf', 'Pdf', '2023-11-05 18:19:07', 40, 8),
(168, 'Xác suất thống kê', 'xstk.xlsx', 'Excel', '2023-11-04 17:00:14', 35, 9),
(169, 'Bài tập lớn kinh tế', 'kt.pdf', 'Pdf', '2023-11-01 10:40:19', 31, 7),
(170, 'BTL MMT ', './BTLMMT/ASS1', 'Pdf', '2023-11-30 09:45:14', 28, 6),
(171, 'CNPM', './CNPM/CNPM.pdf', 'Pdf', '2023-11-30 09:52:56', 12, 5),
(172, 'report.pdf', './report.pdf', 'Pdf', '2023-11-30 10:10:56', 15, 20),
(173, '48231-51841-1-PB.pdf', './48231-51841-1-PB.pdf', 'Pdf', '2023-11-30 10:11:41', 28, 21),
(174, '06 - UML Sequence Diagrams.pdf', './CNPM/file', 'Pdf', '2023-11-30 10:12:12', 15, 22),
(175, 'Lab_8_Wireshark_SSL_v8.0.pdf', './Lab_8_Wireshark_SSL_v8.0.pdf', 'Pdf', '2023-11-30 10:13:08', 30, 23),
(176, 'Lab_4b_Wireshark_DHCP_v8.0.pdf', './Lab_4b_Wireshark_DHCP_v8.0.pdf', 'Pdf', '2023-11-30 10:13:31', 15, 24),
(177, 'Lab_4a_Wireshark_IP_v8.0.pdf', './Lab_4a_Wireshark_IP_v8.0.pdf', 'Pdf', '2023-11-30 10:15:56', 50, 25),
(179, 'BTL1-Network-Application-P2P-File-Sharing.pdf', './BTL1-Network-Application-P2P-File-Sharing.pdf', 'Pdf', '2023-11-30 10:17:57', 28, 25),
(182, 'SSPS.pdf', './SSPS.pdf', 'Pdf', '2023-11-30 10:19:31', 12, 3),
(183, '05_Ch5 System Modeling.pdf', './05_Ch5 System Modeling.pdf', 'Pdf', '2023-11-30 10:20:07', 28, 8),
(184, 'HK1_2324_BTL2-Network-Design-For-A-Company', './HK1_2324_BTL2-Network-Design-For-A-Company', 'Pdf', '2023-12-08 10:08:02', 15, 9),
(185, '05_Ch5_Introduction_OOP_2023.pdf', './05_Ch5_Introduction_OOP_2023.pdf', 'Pdf', '2023-12-08 10:49:32', 28, 16),
(186, '06_Ch6 Architectural Design.pdf', './06_Ch6 Architectural Design.pdf', 'Pdf', '2023-12-08 10:52:58', 12, 17),
(187, 'Báo cáo thực tập điện', 'bcttd.docx', 'Word', '2023-11-01 15:31:26', 12, 27),
(188, 'Bài tập lớn lịch sử đảng', 'lsđ.pdf', 'Pdf', '2023-11-02 16:22:35', 25, 28),
(189, 'Bài tập lớn phương pháp tính', 'ppt.xlsx', 'Excel', '2023-11-01 23:39:40', 74, 29),
(190, 'Bài tập lớn tư tưởng Hồ Chí Minh', 'tthcm.pdf', 'Pdf', '2023-11-05 05:39:44', 4, 30),
(193, 'Báo cáo thực tập điện', 'tt.docx', 'Word', '2023-11-06 10:40:01', 16, 33),
(194, 'Bài tập lớn giải tích', 'gt.pdf', 'Pdf', '2023-11-05 18:19:07', 40, 34),
(196, 'Bài tập lớn kinh tế', 'kt.pdf', 'Pdf', '2023-11-01 10:40:19', 31, 38),
(197, 'BTL MMT ', './BTLMMT/ASS1', 'Pdf', '2023-11-30 09:45:14', 28, 37),
(198, 'CNPM', './CNPM/CNPM.pdf', 'Pdf', '2023-11-30 09:52:56', 12, 36),
(199, 'report.pdf', './report.pdf', 'Pdf', '2023-11-30 10:10:56', 15, 20),
(200, '48231-51841-1-PB.pdf', './48231-51841-1-PB.pdf', 'Pdf', '2023-11-30 10:11:41', 28, 21),
(201, '06 - UML Sequence Diagrams.pdf', './CNPM/file', 'Pdf', '2023-11-30 10:12:12', 15, 22),
(202, 'Lab_8_Wireshark_SSL_v8.0.pdf', './Lab_8_Wireshark_SSL_v8.0.pdf', 'Pdf', '2023-11-30 10:13:08', 30, 23),
(203, 'Lab_4b_Wireshark_DHCP_v8.0.pdf', './Lab_4b_Wireshark_DHCP_v8.0.pdf', 'Pdf', '2023-11-30 10:13:31', 15, 24),
(204, 'Lab_4a_Wireshark_IP_v8.0.pdf', './Lab_4a_Wireshark_IP_v8.0.pdf', 'Pdf', '2023-11-30 10:15:56', 50, 25),
(206, 'BTL1-Network-Application-P2P-File-Sharing.pdf', './BTL1-Network-Application-P2P-File-Sharing.pdf', 'Pdf', '2023-11-30 10:17:57', 28, 25),
(208, '6_SQL.pdf', './6_SQL.pdf', 'Pdf', '2023-11-30 10:19:09', 43, 28),
(209, 'SSPS.pdf', './SSPS.pdf', 'Pdf', '2023-11-30 10:19:31', 12, 27),
(210, '05_Ch5 System Modeling.pdf', './05_Ch5 System Modeling.pdf', 'Pdf', '2023-11-30 10:20:07', 28, 34),
(213, '06_Ch6 Architectural Design.pdf', './06_Ch6 Architectural Design.pdf', 'Pdf', '2023-12-08 10:52:58', 12, 33),
(214, 'Báo cáo thực tập điện', 'bcttd.docx', 'Word', '2023-11-01 15:31:26', 12, 53),
(215, 'Bài tập lớn lịch sử đảng', 'lsđ.pdf', 'Pdf', '2023-11-02 16:22:35', 25, 53),
(216, 'Bài tập lớn phương pháp tính', 'ppt.xlsx', 'Excel', '2023-11-01 23:39:40', 74, 53),
(217, 'Bài tập lớn tư tưởng Hồ Chí Minh', 'tthcm.pdf', 'Pdf', '2023-11-05 05:39:44', 4, 53),
(218, 'Báo cáo polyme', 'plm.docx', 'Word', '2023-11-03 14:17:50', 7, 53),
(219, 'Bài tập lớn triết', 'triết.pdf', 'Pdf', '2023-11-02 05:39:56', 2, 53),
(220, 'Báo cáo thực tập điện', 'tt.docx', 'Word', '2023-11-06 10:40:01', 16, 53),
(221, 'Bài tập lớn giải tích', 'gt.pdf', 'Pdf', '2023-11-05 18:19:07', 40, 53),
(222, 'Xác suất thống kê', 'xstk.xlsx', 'Excel', '2023-11-04 17:00:14', 35, 53),
(223, 'Bài tập lớn kinh tế', 'kt.pdf', 'Pdf', '2023-11-01 10:40:19', 31, 53),
(224, 'BTL MMT ', './BTLMMT/ASS1', 'Pdf', '2023-11-30 09:45:14', 28, 53),
(225, 'CNPM', './CNPM/CNPM.pdf', 'Pdf', '2023-11-30 09:52:56', 12, 53),
(226, 'report.pdf', './report.pdf', 'Pdf', '2023-11-30 10:10:56', 15, 53),
(227, '48231-51841-1-PB.pdf', './48231-51841-1-PB.pdf', 'Pdf', '2023-11-30 10:11:41', 28, 53),
(228, '06 - UML Sequence Diagrams.pdf', './CNPM/file', 'Pdf', '2023-11-30 10:12:12', 15, 53),
(229, 'Lab_8_Wireshark_SSL_v8.0.pdf', './Lab_8_Wireshark_SSL_v8.0.pdf', 'Pdf', '2023-11-30 10:13:08', 30, 53),
(230, 'Lab_4b_Wireshark_DHCP_v8.0.pdf', './Lab_4b_Wireshark_DHCP_v8.0.pdf', 'Pdf', '2023-11-30 10:13:31', 15, 53),
(231, 'Lab_4a_Wireshark_IP_v8.0.pdf', './Lab_4a_Wireshark_IP_v8.0.pdf', 'Pdf', '2023-11-30 10:15:56', 50, 53),
(232, 'PacketSwitching-CaseStudy-v1-c.pdf', './PacketSwitching-CaseStudy-v1-c.pdf', 'Pdf', '2023-11-30 10:16:31', 28, 53),
(233, 'BTL1-Network-Application-P2P-File-Sharing.pdf', './BTL1-Network-Application-P2P-File-Sharing.pdf', 'Pdf', '2023-11-30 10:17:57', 28, 53),
(234, 'BTL2-Network-Design-For-A-Company.pdf', './BTL2-Network-Design-For-A-Company.pdf', 'Pdf', '2023-11-30 10:18:28', 30, 53),
(235, '6_SQL.pdf', './6_SQL.pdf', 'Pdf', '2023-11-30 10:19:09', 43, 53),
(236, 'SSPS.pdf', './SSPS.pdf', 'Pdf', '2023-11-30 10:19:31', 12, 53),
(237, '05_Ch5 System Modeling.pdf', './05_Ch5 System Modeling.pdf', 'Pdf', '2023-11-30 10:20:07', 28, 53),
(238, 'HK1_2324_BTL2-Network-Design-For-A-Company', './HK1_2324_BTL2-Network-Design-For-A-Company', 'Pdf', '2023-12-08 10:08:02', 15, 53),
(239, '05_Ch5_Introduction_OOP_2023.pdf', './05_Ch5_Introduction_OOP_2023.pdf', 'Pdf', '2023-12-08 10:49:32', 28, 53),
(240, '06_Ch6 Architectural Design.pdf', './06_Ch6 Architectural Design.pdf', 'Pdf', '2023-12-08 10:52:58', 12, 53),
(251, 'HK1_2324_BTL2-Network-Design-For-A-Company.pdf', './HK1_2324_BTL2-Network-Design-For-A-Company.pdf', 'Pdf', '2023-12-09 10:18:21', 0, 1),
(252, '48231-51841-1-PB.pdf', './48231-51841-1-PB.pdf', 'Pdf', '2023-12-09 10:21:46', 0, 1),
(253, '6.5.2 Ch7 HW 1-2 PERT Calcs.pdf', './6.5.2 Ch7 HW 1-2 PERT Calcs.pdf', 'Pdf', '2023-12-09 10:38:14', 0, 1),
(265, '6.5.2 Ch7 HW 1-2 PERT Calcs.pdf', './6.5.2 Ch7 HW 1-2 PERT Calcs.pdf', 'Pdf', '2023-12-09 17:07:09', 3, 53);

-- --------------------------------------------------------

--
-- Table structure for table `perform`
--

CREATE TABLE `perform` (
  `Request_ID` int(11) NOT NULL,
  `Printer_ID` varchar(8) NOT NULL,
  `Start_Time` datetime DEFAULT NULL,
  `End_Time` datetime DEFAULT NULL,
  `Completed_Printing_Pages` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perform`
--

INSERT INTO `perform` (`Request_ID`, `Printer_ID`, `Start_Time`, `End_Time`, `Completed_Printing_Pages`) VALUES
(150, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(152, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', 28),
(153, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', 10),
(154, '2H62013', '2023-12-08 18:33:32', NULL, 9),
(155, '1A21012', '2023-12-08 18:33:32', NULL, 5),
(158, '1A36042', '2023-12-08 18:33:32', NULL, NULL),
(159, '1A36042', '2023-12-08 18:33:32', NULL, NULL),
(160, '1A21012', '2023-12-08 18:33:32', NULL, NULL),
(163, '1A36042', '2023-12-08 18:33:32', NULL, NULL),
(164, '1A36042', '2023-12-08 18:33:32', NULL, NULL),
(165, '2H62012', NULL, NULL, NULL),
(166, '1A33052', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(216, '2H62012', '2023-12-08 18:33:32', NULL, NULL),
(240, '2H62012', '2023-12-08 18:33:32', NULL, NULL),
(241, '1A36042', '2023-12-08 18:33:32', NULL, NULL),
(242, '2H62013', '2023-12-08 18:33:32', NULL, NULL),
(243, '1A36042', '2023-12-08 18:33:32', NULL, NULL),
(244, '2H62013', '2023-12-08 18:33:32', NULL, NULL),
(245, '1A33051', NULL, NULL, NULL),
(246, '1A21012', '2023-12-08 18:33:32', NULL, NULL),
(247, '1A36042', '2023-12-08 18:33:32', NULL, NULL),
(248, '1A33052', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(249, '1A33053', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(250, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(251, '1A36042', NULL, NULL, NULL),
(263, '1A33052', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(264, '2H62013', '2023-12-08 18:33:32', NULL, NULL),
(265, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(266, '1A33051', NULL, NULL, NULL),
(267, '1A36042', '2023-12-08 18:33:32', NULL, NULL),
(280, '2H62013', NULL, NULL, NULL),
(281, '1A36042', '2023-12-08 18:33:32', NULL, NULL),
(282, '1A28014', '2023-12-08 18:33:32', NULL, NULL),
(283, '1A33053', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(290, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(292, '2H62012', '2023-12-08 18:33:32', NULL, NULL),
(293, '2H62012', '2023-12-08 18:33:32', NULL, NULL),
(294, '2H62012', '2023-12-08 18:33:32', NULL, NULL),
(295, '1A28014', '2023-12-08 18:33:32', NULL, NULL),
(296, '2H62013', '2023-12-08 18:33:32', NULL, NULL),
(297, '1A33052', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(298, '2H62012', '2023-12-08 18:33:32', NULL, NULL),
(300, '1A33051', NULL, NULL, NULL),
(301, '2H62013', '2023-12-08 18:33:32', NULL, NULL),
(302, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(303, '1A33052', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(304, '1A33051', NULL, NULL, NULL),
(305, '2H62012', '2023-12-08 18:33:32', NULL, NULL),
(306, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(307, '1A36042', NULL, NULL, NULL),
(309, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(312, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(313, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(314, '2H62013', '2023-12-08 18:33:32', NULL, NULL),
(315, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(316, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(317, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(319, '1A33051', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(320, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(321, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(322, '1A33051', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(323, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(324, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(325, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(326, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(327, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(328, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(329, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(330, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(331, '1A28014', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(332, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(333, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(334, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(336, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(339, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(340, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(341, '1A33053', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(342, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(343, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(344, '1A28014', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(345, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(346, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(347, '1A21012', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(350, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(351, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(353, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(354, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(355, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(356, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(357, '1A33051', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(358, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(359, '1A21012', '2023-08-15 17:08:32', '2023-08-15 17:10:32', NULL),
(360, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(361, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(363, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(365, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(366, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(367, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(370, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(372, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(373, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(375, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(376, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(377, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(378, '2H62013', '2023-12-09 17:12:36', NULL, NULL),
(379, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(380, '1A28014', '2023-12-09 10:56:49', NULL, NULL),
(381, '1A36042', '2023-12-08 19:15:00', NULL, NULL),
(382, '2H62012', '2023-12-08 19:15:00', NULL, NULL),
(384, '1A33051', '2023-12-08 21:58:46', NULL, NULL),
(385, '1A36042', '2023-12-09 16:43:28', NULL, NULL),
(386, '2H62013', '2023-10-17 15:09:52', '2023-10-17 15:11:52', NULL),
(387, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(388, '2H62012', '2023-12-08 21:34:12', NULL, NULL),
(389, '1A36042', '2023-11-19 13:14:32', '2023-11-19 13:16:32', NULL),
(391, '1A33052', NULL, NULL, NULL),
(392, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(396, '2H62012', '2023-09-17 09:08:32', '2023-09-17 09:10:32', NULL),
(400, '1A36042', '2023-12-08 22:00:00', NULL, NULL),
(445, '1A36042', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `printer_list`
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
-- Dumping data for table `printer_list`
--

INSERT INTO `printer_list` (`printer_id`, `printer_name`, `printer_desc`, `printer_avai`, `printer_campusloc`, `printer_buildingloc`, `printer_room`) VALUES
('1A21011', 'Canon 1', 'Lorem Ipsum', 'Y', '1', 'A2', '101'),
('1A21012', 'Canon 2', 'Lorem Ipsum', 'N', '1', 'A2', '101'),
('1A28014', 'canon LE', '.', 'N', '1', 'A2', NULL),
('1A33051', 'Canon 3', 'Lorem Ipsum', 'N', '1', 'A3', '305'),
('1A33052', 'Canon 3', 'Lorem Ipsum', 'N', '1', 'A3', '305'),
('1A33053', 'Canon 3', 'Lorem Ipsum', 'N', '1', 'A3', '305'),
('1A36042', 'XP98', '.', 'N', '1', 'A3', NULL),
('1B25042', 'Canon SHP', '.', 'N', '1', 'B2', NULL),
('1C42013', 'XP37', '.', 'N', '1', 'C4', NULL),
('1C43021', 'XP7', '.', 'Y', '1', 'C4', NULL),
('2H11031', 'Canon 1', 'Lorem Ipsum', 'Y', '2', 'H1', '103'),
('2H22021', 'Canon 2', 'Lorem Ipsum', 'N', '2', 'H2', '202'),
('2H32013', 'XP34', '.', 'N', '2', 'H3', NULL),
('2H36042', 'XP1', '.', 'N', '2', 'H3', NULL),
('2H62011', 'Canon 2', 'Lorem Ipsum', 'N', '2', 'H6', '201'),
('2H62012', 'Canon 2', 'Lorem Ipsum', 'N', '2', 'H6', '201'),
('2H62013', 'Canon 2', 'Lorem Ipsum', 'N', '2', 'H6', '201');

-- --------------------------------------------------------

--
-- Table structure for table `print_request`
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
  `File_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `print_request`
--

INSERT INTO `print_request` (`ID`, `Creation_Date`, `Pages_Per_Sheet`, `Number_Of_Copies`, `Page_Size`, `One/Doubled_Sided`, `Total_Sheet`, `Status`, `File_ID`) VALUES
(150, '2023-12-06 21:01:43', '9', 5, 'A0', '2', 75, '2', 122),
(152, '2023-12-06 21:28:00', '1', 4, 'A2', '2', 168, '2', 124),
(153, '2023-12-06 21:30:15', '4', 3, 'A0', '2', 30, '2', 125),
(154, '2023-12-06 21:31:33', '4', 4, 'A3', '2', 16, '1', 126),
(155, '2023-12-06 21:36:20', '16', 4, 'A4', '2', 4, '1', 127),
(158, '2023-12-07 12:00:39', '1', 4, 'A2', '2', 168, '1', 124),
(159, '2023-12-07 12:00:47', '1', 4, 'A2', '2', 168, '1', 124),
(160, '2023-12-07 12:01:06', '16', 4, 'A4', '2', 4, '1', 127),
(163, '2023-12-07 12:06:35', '1', 4, 'A2', '2', 168, '1', 124),
(164, '2023-11-01 15:58:38', '1', 3, 'A4', '2', 168, '1', 129),
(165, '2023-10-03 15:58:44', '9', 1, 'A2', '1', 81, '0', 130),
(166, '2023-09-01 15:58:48', '16', 5, 'A1', '2', 30, '2', 131),
(216, '2023-12-08 18:14:24', '9', 5, 'A4', '1', 200, '1', 1),
(240, '2023-12-08 18:15:15', '9', 4, 'A4', '2', 10, '1', 2),
(241, '2023-12-08 18:15:40', '1', 4, 'A4', '2', 8, '1', 3),
(242, '2023-12-08 18:15:40', '4', 4, 'A4', '2', 148, '1', 4),
(243, '2023-12-08 18:15:55', '1', 1, 'A3', '1', 4, '1', 5),
(244, '2023-12-08 18:15:55', '4', 5, 'A4', '2', 14, '1', 6),
(245, '2023-12-08 18:15:55', '16', 1, 'A2', '1', 5, '0', 7),
(246, '2023-12-08 18:15:55', '16', 2, 'A4', '2', 3, '1', 8),
(247, '2023-12-08 18:16:22', '1', 3, 'A4', '1', 4, '1', 9),
(248, '2023-12-08 18:16:22', '16', 1, 'A1', '2', 105, '2', 10),
(249, '2023-12-08 18:16:22', '16', 1, 'A0', '1', 4, '2', 104),
(250, '2023-12-08 18:16:22', '1', 3, 'A0', '2', 10, '2', 106),
(251, '2023-12-08 18:16:22', '1', 4, 'A2', '1', 90, '0', 107),
(263, '2023-12-08 18:17:02', '16', 1, 'A1', '1', 180, '2', 108),
(264, '2023-12-08 18:17:02', '4', 1, 'A3', '1', 8, '1', 109),
(265, '2023-12-08 18:17:02', '9', 5, 'A0', '1', 8, '2', 110),
(266, '2023-12-08 18:17:02', '16', 1, 'A2', '2', 100, '0', 111),
(267, '2023-12-08 18:17:02', '1', 3, 'A4', '1', 3, '1', 112),
(280, '2023-12-08 18:17:36', '4', 3, 'A2', '1', 150, '0', 113),
(281, '2023-12-08 18:17:36', '1', 3, 'A3', '1', 63, '1', 114),
(282, '2023-12-08 18:17:36', '16', 3, 'A3', '1', 168, '1', 115),
(283, '2023-12-08 18:17:36', '16', 4, 'A0', '2', 12, '2', 116),
(290, '2023-12-08 18:24:13', '9', 1, 'A1', '1', 40, '2', 133),
(292, '2023-12-08 18:24:13', '9', 4, 'A4', '1', 20, '1', 135),
(293, '2023-12-08 18:24:13', '9', 3, 'A3', '1', 36, '1', 136),
(294, '2023-12-08 18:24:13', '9', 2, 'A4', '1', 6, '1', 137),
(295, '2023-12-08 18:24:13', '16', 2, 'A3', '2', 2, '1', 138),
(296, '2023-12-08 18:24:13', '4', 5, 'A4', '2', 4, '1', 139),
(297, '2023-12-08 18:24:13', '16', 2, 'A1', '2', 10, '2', 140),
(298, '2023-12-08 18:24:13', '9', 3, 'A3', '2', 16, '1', 141),
(300, '2023-12-08 18:24:13', '16', 5, 'A2', '1', 32, '0', 143),
(301, '2023-12-08 18:24:13', '4', 5, 'A3', '2', 30, '1', 144),
(302, '2023-12-08 18:24:13', '4', 1, 'A0', '2', 20, '2', 145),
(303, '2023-12-08 18:24:13', '16', 3, 'A1', '1', 10, '2', 146),
(304, '2023-12-08 18:24:13', '16', 5, 'A2', '1', 24, '0', 147),
(305, '2023-12-08 18:24:13', '9', 5, 'A3', '2', 15, '1', 148),
(306, '2023-12-08 18:24:13', '9', 1, 'A1', '2', 20, '2', 149),
(307, '2023-12-08 18:24:13', '1', 4, 'A2', '2', 4, '0', 150),
(309, '2023-12-08 18:24:13', '1', 4, 'A0', '1', 8, '2', 152),
(312, '2023-12-08 18:24:13', '1', 2, 'A0', '1', 440, '2', 155),
(313, '2023-12-08 18:24:13', '4', 4, 'A0', '1', 120, '2', 156),
(314, '2023-12-08 18:24:13', '4', 1, 'A4', '1', 140, '1', 157),
(315, '2023-12-08 18:24:13', '1', 3, 'A1', '1', 4, '2', 158),
(316, '2023-12-08 18:24:13', '1', 3, 'A1', '1', 336, '2', 159),
(317, '2023-12-08 19:05:21', '9', 3, 'A0', '1', 144, '2', 160),
(319, '2023-12-08 19:05:21', '16', 2, 'A2', '1', 20, '2', 162),
(320, '2023-12-08 19:05:21', '1', 4, 'A0', '2', 30, '2', 163),
(321, '2023-12-08 19:05:21', '4', 5, 'A3', '2', 40, '2', 164),
(322, '2023-12-08 19:05:21', '16', 4, 'A2', '2', 10, '2', 165),
(323, '2023-12-08 19:05:21', '9', 4, 'A0', '2', 12, '2', 166),
(324, '2023-12-08 19:05:21', '9', 3, 'A3', '1', 20, '2', 167),
(325, '2023-12-08 19:05:21', '4', 1, 'A0', '2', 30, '2', 168),
(326, '2023-12-08 19:05:21', '1', 5, 'A0', '2', 25, '2', 169),
(327, '2023-12-08 19:05:21', '9', 2, 'A4', '1', 400, '2', 170),
(328, '2023-12-08 19:05:21', '9', 2, 'A2', '2', 8, '2', 171),
(329, '2023-12-08 19:05:21', '9', 4, 'A3', '2', 6, '2', 172),
(330, '2023-12-08 19:05:21', '1', 3, 'A2', '2', 8, '2', 173),
(331, '2023-12-08 19:05:21', '16', 1, 'A3', '2', 126, '2', 174),
(332, '2023-12-08 19:05:21', '4', 1, 'A1', '2', 2, '2', 175),
(333, '2023-12-08 19:05:21', '1', 1, 'A0', '2', 16, '2', 176),
(334, '2023-12-08 19:05:21', '1', 4, 'A3', '1', 40, '2', 177),
(336, '2023-12-08 19:05:21', '1', 2, 'A4', '2', 700, '2', 179),
(339, '2023-12-08 19:05:21', '9', 3, 'A2', '2', 176, '2', 182),
(340, '2023-12-08 19:05:21', '9', 2, 'A2', '2', 9, '2', 183),
(341, '2023-12-08 19:05:21', '16', 2, 'A0', '2', 12, '2', 184),
(342, '2023-12-08 19:05:21', '9', 2, 'A3', '2', 10, '2', 185),
(343, '2023-12-08 19:05:21', '1', 5, 'A1', '2', 8, '2', 186),
(344, '2023-12-08 19:10:36', '16', 3, 'A3', '1', 120, '2', 187),
(345, '2023-12-08 19:10:36', '9', 1, 'A1', '2', 6, '2', 188),
(346, '2023-12-08 19:10:36', '4', 5, 'A1', '2', 8, '2', 189),
(347, '2023-12-08 19:10:36', '16', 5, 'A4', '1', 200, '2', 190),
(350, '2023-12-08 19:10:36', '9', 1, 'A2', '2', 4, '2', 193),
(351, '2023-12-08 19:10:36', '9', 1, 'A1', '1', 3, '2', 194),
(353, '2023-12-08 19:10:36', '9', 4, 'A0', '2', 6, '2', 196),
(354, '2023-12-08 19:10:36', '1', 4, 'A0', '2', 40, '2', 197),
(355, '2023-12-08 19:10:36', '9', 4, 'A4', '2', 280, '2', 198),
(356, '2023-12-08 19:10:36', '9', 1, 'A4', '1', 4, '2', 199),
(357, '2023-12-08 19:10:36', '16', 5, 'A2', '2', 2, '2', 200),
(358, '2023-12-08 19:10:36', '9', 5, 'A0', '2', 15, '2', 201),
(359, '2023-12-08 19:10:36', '16', 1, 'A4', '1', 25, '2', 202),
(360, '2023-12-08 19:10:36', '1', 3, 'A2', '1', 2, '2', 203),
(361, '2023-12-08 19:10:36', '4', 4, 'A3', '1', 135, '2', 204),
(363, '2023-12-08 19:10:36', '1', 5, 'A4', '2', 8, '2', 206),
(365, '2023-12-08 19:10:36', '1', 4, 'A0', '1', 12, '2', 208),
(366, '2023-12-08 19:10:36', '1', 1, 'A1', '2', 860, '2', 209),
(367, '2023-12-08 19:10:36', '9', 2, 'A0', '2', 24, '2', 210),
(370, '2023-12-08 19:10:36', '4', 5, 'A2', '2', 30, '2', 213),
(372, '2023-12-08 19:15:00', '4', 2, 'A2', '1', 240, '2', 215),
(373, '2023-12-08 19:15:00', '1', 4, 'A2', '1', 42, '2', 216),
(375, '2023-12-08 19:15:00', '4', 2, 'A1', '2', 6, '2', 218),
(376, '2023-12-08 19:15:00', '9', 3, 'A2', '1', 8, '2', 219),
(377, '2023-12-08 19:15:00', '4', 2, 'A4', '1', 9, '2', 220),
(378, '2023-12-08 19:15:00', '4', 3, 'A0', '2', 8, '1', 221),
(379, '2023-12-08 19:15:00', '9', 3, 'A3', '2', 75, '2', 222),
(380, '2023-12-08 19:15:00', '16', 5, 'A3', '2', 12, '1', 223),
(381, '2023-12-08 19:15:00', '1', 2, 'A2', '2', 10, '1', 224),
(382, '2023-12-08 19:15:00', '9', 1, 'A2', '2', 84, '1', 225),
(384, '2023-12-08 19:15:00', '16', 5, 'A2', '2', 15, '1', 227),
(385, '2023-12-08 19:15:00', '1', 4, 'A2', '1', 15, '1', 228),
(386, '2023-12-08 19:15:00', '4', 4, 'A2', '2', 180, '2', 229),
(387, '2023-12-08 19:15:00', '9', 3, 'A4', '2', 48, '2', 230),
(388, '2023-12-08 19:15:00', '9', 1, 'A1', '2', 3, '1', 231),
(389, '2023-12-08 19:15:00', '1', 5, 'A1', '1', 12, '2', 232),
(391, '2023-12-08 19:15:00', '16', 3, 'A1', '2', 20, '0', 234),
(392, '2023-12-08 19:15:00', '9', 1, 'A4', '2', 12, '2', 235),
(396, '2023-12-08 19:15:00', '9', 5, 'A4', '2', 20, '2', 239),
(399, '2023-12-08 20:59:39', '1', 4, 'A2', '1', 42, '0', 216),
(400, '2023-12-08 22:00:00', '1', 4, 'A2', '1', 42, '1', 216),
(445, '2023-12-09 17:07:09', '1', 2, 'A4', '1', 6, '0', 265);

--
-- Triggers `print_request`
--
DELIMITER $$
CREATE TRIGGER `UpdateStarttime` AFTER UPDATE ON `print_request` FOR EACH ROW BEGIN
    select print_request.Status, print_request.ID INTO @status, @id from print_request where print_request.ID = NEW.ID;

    IF @status LIKE '0' THEN
     UPDATE perform 
    SET Start_Time = NULL, End_Time = NULL 
    where perform.Request_ID = @id; 
    ELSEIF @status LIKE '1' THEN
    UPDATE perform 
    SET Start_Time = NOW() 
    where perform.Request_ID = @id;
    ELSE 
    UPDATE perform 
    SET Start_Time = NOW(), End_Time = DATE_ADD(NOW(), INTERVAL 2 MINUTE) 
    where perform.Request_ID = @id;
 END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertPerform` AFTER INSERT ON `print_request` FOR EACH ROW BEGIN
SELECT print_request.Status INTO @status from print_request where print_request.ID = NEW.ID;
IF @status LIKE '1' THEN
	INSERT INTO perform(Request_ID, Printer_ID, Start_Time, End_Time, Completed_Printing_Pages) VALUES(NEW.ID, '1A21011',NOW(),NULL,NULL);
    ELSEIF @status LIKE '2' THEN
    INSERT INTO perform(Request_ID, Printer_ID, Start_Time, End_Time, Completed_Printing_Pages) VALUES(NEW.ID, '1A21011',NOW(),DATE_ADD(NOW(),INTERVAL 3 MINUTE),NULL);
    ELSE 
    INSERT INTO perform(Request_ID, Printer_ID, Start_Time, End_Time, Completed_Printing_Pages) VALUES(NEW.ID, '1A21011',NULL,NULL,NULL);
     END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `refill_dates`
--

CREATE TABLE `refill_dates` (
  `Refill_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `refill_dates`
--

INSERT INTO `refill_dates` (`Refill_Date`) VALUES
('2023-12-06 22:04:25');

-- --------------------------------------------------------

--
-- Table structure for table `requested_page_numbers`
--

CREATE TABLE `requested_page_numbers` (
  `Request_ID` int(11) NOT NULL,
  `Start_Page` int(11) NOT NULL,
  `End_Page` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requested_page_numbers`
--

INSERT INTO `requested_page_numbers` (`Request_ID`, `Start_Page`, `End_Page`) VALUES
(150, 5, 23),
(152, 1, 11),
(153, 9, 10),
(154, 4, 9),
(155, 3, 5),
(158, 3, 20),
(159, 11, 27),
(160, 5, 11),
(163, 2, 10),
(164, 15, 36),
(165, 29, 51),
(166, 11, 31),
(216, 3, 11),
(240, 9, 24),
(241, 62, 67),
(242, 2, 3),
(243, 6, 7),
(244, 1, 2),
(245, 5, 8),
(246, 7, 14),
(247, 18, 31),
(248, 16, 28),
(249, 14, 22),
(250, 7, 11),
(251, 8, 9),
(263, 6, 25),
(264, 6, 15),
(265, 8, 15),
(266, 1, 4),
(267, 9, 27),
(280, 17, 22),
(281, 2, 14),
(282, 1, 2),
(283, 9, 42),
(290, 8, 12),
(292, 9, 15),
(293, 1, 2),
(294, 4, 7),
(295, 1, 2),
(296, 1, 7),
(297, 17, 40),
(298, 3, 14),
(300, 12, 25),
(301, 7, 9),
(302, 2, 4),
(303, 10, 16),
(304, 10, 13),
(305, 4, 29),
(306, 3, 14),
(307, 4, 8),
(309, 13, 28),
(312, 4, 8),
(313, 6, 11),
(314, 8, 14),
(315, 2, 2),
(316, 2, 5),
(317, 3, 5),
(319, 1, 72),
(320, 3, 4),
(321, 7, 7),
(322, 2, 2),
(323, 4, 12),
(324, 19, 38),
(325, 18, 29),
(326, 21, 23),
(327, 6, 19),
(328, 9, 10),
(329, 5, 7),
(330, 12, 12),
(331, 7, 15),
(332, 1, 30),
(333, 3, 8),
(334, 7, 25),
(336, 26, 26),
(339, 1, 7),
(340, 9, 27),
(341, 1, 2),
(342, 13, 19),
(343, 1, 9),
(344, 5, 6),
(345, 4, 4),
(346, 57, 63),
(347, 2, 3),
(350, 1, 14),
(351, 16, 26),
(353, 5, 28),
(354, 4, 10),
(355, 3, 6),
(356, 4, 6),
(357, 20, 26),
(358, 1, 15),
(359, 4, 14),
(360, 6, 8),
(361, 22, 41),
(363, 2, 22),
(365, 12, 17),
(366, 7, 8),
(367, 16, 23),
(370, 2, 11),
(372, 15, 23),
(373, 32, 41),
(375, 7, 7),
(376, 2, 2),
(377, 14, 15),
(378, 3, 23),
(379, 11, 20),
(380, 7, 31),
(381, 18, 24),
(382, 2, 8),
(384, 11, 19),
(385, 12, 14),
(386, 13, 14),
(387, 8, 11),
(388, 2, 50),
(389, 3, 16),
(391, 8, 27),
(392, 5, 16),
(396, 15, 19),
(399, 28, 38),
(445, 1, 3);

--
-- Triggers `requested_page_numbers`
--
DELIMITER $$
CREATE TRIGGER `Totalsheet` AFTER INSERT ON `requested_page_numbers` FOR EACH ROW BEGIN
select Request_ID INTO @requestid from requested_page_numbers where requested_page_numbers.Request_ID = NEW.Request_ID;
UPDATE print_request set print_request.Total_Sheet = Count_Totalsheet(@requestid) where print_request.ID = @requestid;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Fname`, `Lname`, `Email`, `Role`, `Sex`, `Date_Of_Birth`, `Balance`) VALUES
(1, 'Dương', 'Hà Thuỳ', 'duong.hathuy@hcmut.edu.vn', 'Student', 0, '2003-12-12', 600),
(3, 'Công', 'Bùi', 'buichiencung@gmail.com', 'Student', 1, '0000-00-00', 600),
(4, 'Bình', 'Bùi', 'buithanhbinh@gmail.com', 'SPSO', 1, '0000-00-00', 600),
(5, 'Tâm', 'Bùi', 'buithimytam@gmail.com', 'Student', 0, '0000-00-00', 600),
(6, 'Vĩnh', 'Đặng', 'dangvanvinh@gmail.com', 'Student', 1, '0000-00-00', 600),
(7, 'Bình', 'Danh', 'danhbinh@gmail.com', 'Student', 1, '0000-00-00', 600),
(8, 'Hoàng', 'Đinh', 'dinhtienhoang@gmail.com', 'Student', 1, '0000-00-00', 600),
(9, 'Hiếu', 'Đoàn', 'doanngochieu@gmail.com', 'Student', 1, '0000-00-00', 600),
(10, 'Minh', 'Đoàn', 'doanthiminh@gmail.com', 'Student', 0, '0000-00-00', 600),
(11, 'Phát', 'Đoàn', 'doanthuanphat@gmail.com', 'Student', 1, '0000-00-00', 600),
(12, 'Hùng', 'Đỗ', 'doquochung @gmail.com', 'SPSO', 0, '0000-00-00', 600),
(13, 'Hào', 'Hà', 'haphuchao @gmail.com', 'Student', 1, '0000-00-00', 600),
(14, 'Khánh', 'Hoàng', 'hoangmanhkhanh@gmail.com', 'Student', 1, '0000-00-00', 600),
(15, 'Nghĩa', 'Hoàng', 'hoangminhnghia@gmail.com', 'Student', 1, '0000-00-00', 600),
(16, 'Vũ', 'Hùng', 'hungnguyenvu@gmail.com', 'Student', 1, '0000-00-00', 600),
(17, 'Ngân', 'Huỳnh', 'huynhthingan@gmail.com', 'Student', 0, '0000-00-00', 600),
(18, 'Mạnh', 'Huỳnh', 'huynhvanmanh@gmail.com', 'Student', 1, '0000-00-00', 600),
(19, 'Hiếu', 'Lê', 'ledinhhieu@gmail.com', 'Student', 1, '0000-00-00', 600),
(20, 'An', 'Lê', 'lehoangan@gmail.com', 'Student', 0, '0000-00-00', 600),
(21, 'Duyên', 'Lê', 'lemyduyen@gmail.com', 'Student', 0, '0000-00-00', 600),
(22, 'Sơn', 'Lê', 'lethanhson@gmail.com', 'Student', 1, '0000-00-00', 600),
(23, 'Quân', 'Lê', 'letranminhquan @gmail.com', 'Student', 1, '0000-00-00', 600),
(24, 'Cường', 'Lê', 'levietcuong@gmail.com', 'Student', 1, '0000-00-00', 600),
(25, 'Bằng', 'Nguyễn', 'nguyencaobang@gmail.com', 'Student', 1, '0000-00-00', 600),
(26, 'Sơn', 'Nguyễn', 'nguyencongson@gmail.com', 'SPSO', 1, '0000-00-00', 600),
(27, 'Huy', 'Nguyễn', 'nguyengiahuy@gmail.com', 'Student', 1, '0000-00-00', 600),
(28, 'Khánh', 'Nguyễn', 'nguyengiakhanh@gmail.com', 'Student', 1, '0000-00-00', 600),
(29, 'Thọ', 'Nguyễn', 'nguyenhoangtho@gmail.com', 'Student', 1, '0000-00-00', 600),
(30, 'Hiếu', 'Nguyễn', 'nguyenhuuhieu@gmail.com', 'Student', 1, '0000-00-00', 600),
(31, 'Ly', 'Nguyễn', 'nguyenkhanhly@gmail.com', 'SPSO', 0, '0000-00-00', 600),
(32, 'Tài', 'Nguyễn', 'nguyenlytai@gmail.com', 'SPSO', 1, '0000-00-00', 600),
(33, 'Việt', 'Nguyễn', 'nguyenthaiviet @gmail.com', 'Student', 1, '0000-00-00', 600),
(34, 'Bằng', 'Nguyễn Thế', 'nguyenthebang@gmail.com', 'Student', 1, '0000-00-00', 600),
(35, 'Trâm', 'Nguyễn', 'nguyentram@gmail.com', 'SPSO', 0, '0000-00-00', 600),
(36, 'Hưng', 'Nguyễn', 'nguyentrihung @gmail.com', 'Student', 1, '0000-00-00', 600),
(37, 'Sơn', 'Nguyễn', 'nguyentuanson @gmail.com', 'Student', 1, '0000-00-00', 600),
(38, 'Huy', 'Nguyễn', 'nguyenvuhuy @gmail.com', 'Student', 1, '0000-00-00', 600),
(39, 'Hiển', 'Nguyễn', 'nguyenxuanhien @gmail.com', 'Student', 1, '0000-00-00', 600),
(40, 'Nhi', 'Nguyễn', 'nguyenynhi@gmail.com', 'Student', 0, '0000-00-00', 600),
(41, 'Minh', 'Phạm', 'phamthiminh@gmail.com', 'Student', 0, '0000-00-00', 600),
(42, 'Hiếu', 'Phạm', 'phamtienhieu@gmail.com', 'Student', 1, '0000-00-00', 600),
(43, 'Nguyên', 'Phan', 'phantannguyen@gmail.com', 'Student', 1, '0000-00-00', 600),
(44, 'Phát', 'Phan', 'phantanphat@gmail.com', 'Student', 1, '0000-00-00', 600),
(45, 'Lộc', 'Phan', 'phantienloc@gmail.com', 'Student', 1, '0000-00-00', 600),
(46, 'Huy', 'Trần', 'trandinhhuy @gmail.com', 'Student', 1, '0000-00-00', 600),
(47, 'Thịnh', 'Trần', 'tranquocthinh @gmail.com', 'SPSO', 1, '0000-00-00', 600),
(48, 'Nhân', 'Trần', 'tranthenhan@gmail.com', 'Student', 1, '0000-00-00', 600),
(49, 'Hạnh', 'Trần', 'tranthimyhanh@gmail.com', 'Student', 0, '0000-00-00', 600),
(50, 'Tâm', 'Trần Thị', 'tranthithanhtam @gmail.com', 'SPSO', 0, '0000-00-00', 600),
(51, 'Thành', 'Võ', 'votrithanh@gmail.com', 'SPSO', 1, '0000-00-00', 600),
(52, 'Diện', 'Vũ', 'vutoandien@gmail.com', 'SPSO', 1, '0000-00-00', 600),
(53, 'THI', 'PHAN PHẠM', 'thi.phanpham@hcmut.edu.vn', 'Student', 0, '2003-10-17', 600),
(54, 'Ngân', 'Lê Thị Kim', 'ngan.lengan2003@hcmut.edu.vn', 'SPSO', 0, '2003-02-21', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accepted_file_types`
--
ALTER TABLE `accepted_file_types`
  ADD UNIQUE KEY `File_Type` (`File_Type`);

--
-- Indexes for table `bpp_order`
--
ALTER TABLE `bpp_order`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `Owner_ID` (`Owner_ID`);

--
-- Indexes for table `campus_building`
--
ALTER TABLE `campus_building`
  ADD PRIMARY KEY (`printer_campusloc`,`printer_buildingloc`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_file_userId` (`User_ID`);

--
-- Indexes for table `perform`
--
ALTER TABLE `perform`
  ADD PRIMARY KEY (`Request_ID`,`Printer_ID`);

--
-- Indexes for table `printer_list`
--
ALTER TABLE `printer_list`
  ADD PRIMARY KEY (`printer_id`),
  ADD KEY `printer_list_campus_building_ibfk_1` (`printer_campusloc`,`printer_buildingloc`);

--
-- Indexes for table `print_request`
--
ALTER TABLE `print_request`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `File_ID` (`File_ID`);

--
-- Indexes for table `refill_dates`
--
ALTER TABLE `refill_dates`
  ADD PRIMARY KEY (`Refill_Date`);

--
-- Indexes for table `requested_page_numbers`
--
ALTER TABLE `requested_page_numbers`
  ADD PRIMARY KEY (`Request_ID`,`Start_Page`,`End_Page`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bpp_order`
--
ALTER TABLE `bpp_order`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

--
-- AUTO_INCREMENT for table `print_request`
--
ALTER TABLE `print_request`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=446;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `FK_file_userId` FOREIGN KEY (`User_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `perform`
--
ALTER TABLE `perform`
  ADD CONSTRAINT `perform_ibfk_1` FOREIGN KEY (`Request_ID`) REFERENCES `print_request` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `printer_list`
--
ALTER TABLE `printer_list`
  ADD CONSTRAINT `printer_list_campus_building_ibfk_1` FOREIGN KEY (`printer_campusloc`,`printer_buildingloc`) REFERENCES `campus_building` (`printer_campusloc`, `printer_buildingloc`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `print_request`
--
ALTER TABLE `print_request`
  ADD CONSTRAINT `print_request_ibfk_1` FOREIGN KEY (`File_ID`) REFERENCES `file` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requested_page_numbers`
--
ALTER TABLE `requested_page_numbers`
  ADD CONSTRAINT `requested_page_numbers_ibfk_1` FOREIGN KEY (`Request_ID`) REFERENCES `print_request` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
