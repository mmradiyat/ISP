-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2025 at 11:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isp`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `id` varchar(50) NOT NULL,
  `connection_id` varchar(50) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `state` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `payment_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`id`, `connection_id`, `amount`, `state`, `due_date`, `payment_id`) VALUES
('bi2504242348440046e', 'co2504242335500085d', 1200, 'Paid', '2025-04-29', 'py25042500194300206'),
('bi25042423491400afb', 'co2504242334540027c', 5500, 'Paid', '2025-04-29', 'py25042500194300206'),
('bi250424234915005af', 'co25042423352700b3f', 1000, 'Paid', '2025-04-29', 'py25042500194300206'),
('bi25042423491600947', 'co250424233607002f9', 2000, 'Paid', '2025-04-29', 'py25042500194300206'),
('bi250930162223006dd', 'co25042423434400cd7', 3000, 'Paid', '2025-10-05', 'py2509301633320067a'),
('bi25093016234500898', 'co250424234302004a8', 2000, 'Paid', '2025-10-05', 'py25093019390200ba6'),
('bi250930164056000c5', 'co250929152435008db', 3000, 'Paid', '2025-10-05', 'py25100223381200f99'),
('bi250930231504005d0', 'co25093023050600cd2', 2000, 'Paid', '2025-10-05', 'py25100223525100bd4'),
('bi25100216063100937', 'co250930230552000cf', 2000, 'Paid', '2025-10-07', 'py25100223525100bd4'),
('bi25100216063200e76', 'co25100216041800e38', 5500, 'Paid', '2025-10-07', 'py25100223493900a4c'),
('bi251002201719007e7', 'co25100220113700225', 1200, 'Paid', '2025-10-07', 'py25100223381200f99'),
('bi25100300294600002', 'co25100219063200bbb', 1000, 'Paid', '2025-10-08', 'py2510030032130014a'),
('bi251003002947000e1', 'co25100219084300fa0', 1200, 'Paid', '2025-10-08', 'py2510030032130014a'),
('bi251003002948005fb', 'co251002191211008b0', 2000, 'Paid', '2025-10-08', 'py2510030032130014a'),
('bi251003034038007d2', 'co25100303374200ddc', 1000, 'Paid', '2025-10-08', 'py25100314053400447');

--
-- Triggers `bill`
--
DELIMITER $$
CREATE TRIGGER `before_insert_payment` BEFORE INSERT ON `bill` FOR EACH ROW BEGIN
    -- Generate unique payment ID
    SET NEW.id = CONCAT(
        'bi', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );

    -- Set due_date to 5 days after the insertion date
    SET NEW.due_date = DATE_ADD(NOW(), INTERVAL 5 DAY);

    -- Set state to Unpaid
    SET NEW.state = 'Unpaid';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `id` varchar(50) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `state` varchar(10) DEFAULT NULL,
  `complaining_date` datetime DEFAULT NULL,
  `customer_id` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`id`, `title`, `type`, `state`, `complaining_date`, `customer_id`, `details`, `comments`) VALUES
('com25042500235400923', 'Low speed', 'Speed', 'Replied', '2025-04-25 00:23:54', 'cu2504140208430063b', 'Low speed in my home', NULL),
('com25100300142800436', 'Slow Speed', 'Speed', 'Replied', '2025-10-03 00:14:28', 'cu2504140208430063b', 'adfsdfd', NULL),
('com2510031441390025e', 'Low Speed', 'Speed', 'Replied', '2025-10-03 14:41:39', 'cu25092914570500fb8', 'Do', NULL),
('com25100314454100a58', 'Lost Connection', 'Other', 'Replied', '2025-10-03 14:45:41', 'cu25092914570500fb8', 'Someone cutoff my line', NULL),
('com251003145541006a6', 'Low Speed', 'Speed', 'Replied', '2025-10-03 14:55:41', 'cu2509291431460022f', 'I can not browse any thing', NULL),
('com25100314561600f55', 'Low Speed', 'Speed', 'Replied', '2025-10-03 14:56:16', 'cu2509291431460022f', 'Work fast', NULL),
('com25100314572100609', 'Low Speed', 'Speed', 'Replied', '2025-10-03 14:57:21', 'cu25041402151500309', 'Low Speed', NULL),
('com2510031457300006c', 'Low Speed', 'Speed', 'Replied', '2025-10-03 14:57:30', 'cu25041402151500309', 'Low Speed', NULL),
('com25100314594100f1c', 'Low Speed', 'Speed', 'Replied', '2025-10-03 14:59:41', 'cu25093023000200f40', 'Low Speed', NULL),
('com2510031459500068e', 'Low Speed', 'Speed', 'Replied', '2025-10-03 14:59:50', 'cu25093023000200f40', 'Low Speed', NULL);

--
-- Triggers `complaint`
--
DELIMITER $$
CREATE TRIGGER `before_insert_complaint` BEFORE INSERT ON `complaint` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'com', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE `connections` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `customer_id` varchar(50) DEFAULT NULL,
  `plan_id` varchar(50) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `starting_date` date NOT NULL,
  `submission_date` date DEFAULT NULL,
  `req_plan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `connections`
--

INSERT INTO `connections` (`id`, `name`, `address`, `type`, `customer_id`, `plan_id`, `state`, `starting_date`, `submission_date`, `req_plan`) VALUES
('co250424233403009e7', '2nd Biasness ', 'Dhaka, Bangladesh', 'organizational_plans', 'cu2504140208430063b', 'pl25041414513300316', 'Active', '2025-05-01', NULL, NULL),
('co2504242334540027c', 'Office 1', 'Dhaka, Bangladesh', 'organizational_plans', 'cu2504140208430063b', 'pl25041414513300316', 'Active', '2025-05-02', NULL, NULL),
('co25042423352700b3f', 'Sweet Home', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl2504141448140029b', 'Active', '2025-05-01', NULL, NULL),
('co2504242335500085d', 'My hostel', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl2504141448140029b', 'Active', '2025-04-20', NULL, NULL),
('co250424233607002f9', 'Go life go', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl25041414453500b5b', 'Active', '2025-05-05', NULL, NULL),
('co250424234302004a8', '@nd home', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl2504141448140029b', 'Active', '2025-05-06', NULL, NULL),
('co25042423434400cd7', '@Future plan', 'Dhaka, Bangladesh', 'organizational_plans', 'cu25041402151500309', 'pl25041414513300316', 'Active', '2025-05-29', NULL, NULL),
('co25092522001400d0f', 'Adiyat', 'Mirpur, Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl25041414453500b5b', 'Active', '2025-10-02', '2025-09-25', NULL),
('co25092914584700e09', 'Personal Connection', 'Uttara, Dhaka 1230', 'residential_plans', 'cu25092914570500fb8', 'pl25041414463200f6f', 'Active', '2025-10-10', '2025-09-29', ''),
('co250929152435008db', 'MMR Connection', 'Diabari, Dhaka 1216', 'organizational_plans', 'cu2509291431460022f', 'pl25041414513300316', 'Active', '2025-10-10', '2025-09-29', NULL),
('co25093023050600cd2', 'Kalam Residential Connection', 'Banani', 'residential_plans', 'cu25093023000200f40', 'pl2504141448140029b', 'Active', '2025-10-05', '2025-09-30', NULL),
('co250930230552000cf', 'Kalam Residential Connection 2', 'Banani', 'residential_plans', 'cu25093023000200f40', 'pl2504141448140029b', 'Active', '2025-10-05', '2025-09-30', NULL),
('co25100216041800e38', 'My Home Connection', 'Uttara, Dhaka 1230', 'residential_plans', 'cu25092914570500fb8', 'pl2509302310450049b', 'Active', '2025-10-05', '2025-10-02', NULL),
('co25100219063200bbb', 'Test 10', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl25041414453500b5b', 'Active', '2025-10-01', '2025-10-02', NULL),
('co25100219084300fa0', 'test11', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl25041414463200f6f', 'Active', '2025-10-01', '2025-10-02', NULL),
('co251002191211008b0', 'Test 12', 'Dhaka, Bangladesh', 'organizational_plans', 'cu2504140208430063b', 'pl2504141450000053c', 'Active', '2025-10-05', '2025-10-02', NULL),
('co25100220113700225', 'Test 123', 'Mirpur, Dhaka 1216', 'residential_plans', 'cu2509291431460022f', 'pl25041414463200f6f', 'Active', '2025-10-31', '2025-10-02', NULL),
('co25100303374200ddc', 'Test 100', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl25041414453500b5b', 'Active', '2025-10-05', '2025-10-03', NULL);

--
-- Triggers `connections`
--
DELIMITER $$
CREATE TRIGGER `before_insert_connections` BEFORE INSERT ON `connections` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'co', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `nid` varchar(15) DEFAULT NULL,
  `gender` text NOT NULL,
  `photo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `email`, `phone`, `address`, `password`, `nid`, `gender`, `photo`) VALUES
('cu2504140208430063b', 'Kobir Khan', 'customer1@fisp.com', '01693674298', 'Dhaka, Bangladesh', 'c1', '8653567689', 'male', 'files/customer/profile_pic_file/8653567689_photo.jpg'),
('cu25041402151500309', 'Hanbe Errcel', 'customer2@fisp.com', '01575398478', 'Dhaka, Bangladesh', 'c2', '7568236769', 'male', 'files/customer/profile_pic_file/7568236769_photo.jpg'),
('cu2509291431460022f', 'Md Mashud Rana', 'personal.adiyat@gmail.com', '01812703580', 'Mirpur, Dhaka 1216', 'mmr', '5088067599', 'male', 'files/customer/profile_pic_file/5088067599_photo.jpg'),
('cu25092914570500fb8', 'Rana', 'rana@gmail.com', '01956125879', 'Uttara, Dhaka 1230', 'r', '1030456879', 'male', 'files/customer/profile_pic_file/1030456879_photo.jpg'),
('cu25093023000200f40', 'Kalam', 'kl@gmail.com', '01621589768', 'Banani', 'kl', '1569874586', 'male', 'files/customer/profile_pic_file/1569874586_photo.png');

--
-- Triggers `customer`
--
DELIMITER $$
CREATE TRIGGER `before_insert_customer` BEFORE INSERT ON `customer` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'cu', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `post` varchar(50) DEFAULT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `nid` bigint(20) DEFAULT NULL,
  `nid_file` varchar(100) DEFAULT NULL,
  `certificate_file` varchar(100) DEFAULT NULL,
  `resume_file` varchar(100) DEFAULT NULL,
  `photo_file` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_sup_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `name`, `post`, `phone`, `email`, `password`, `address`, `gender`, `salary`, `nid`, `nid_file`, `certificate_file`, `resume_file`, `photo_file`, `is_admin`, `is_sup_admin`) VALUES
('em25041400001500724', 'Abdullah Md Jahid Hassan', 'Admin', 17654536373, 'abdullahmdjahidhassan@gmail.com', 'F.s.56564545', 'Uttara, Dhaka, Bangladesh', 'Male', 15000, 1234567890, 'files/employee/nid_file/em25041400001500724_nid.pdf', 'files/employee/certificate_file/em25041400001500724_certificate.pdf', 'files/employee/resume_file/em25041400001500724_resume.pdf', 'files/employee/profile_pic_file/em25041400001500724_photo.jpg', 1, 1),
('em25041400064300dc2', 'Rato Talukdar', 'Line Man', 1564346536, 'employee4@fisp.com', 'e4', 'Dhaka, Bangladesh', 'male', 25000, 5363927493, 'files/employee/nid_file/em25041400064300dc2_nid.pdf', 'files/employee/certificate_file/em25041400064300dc2_certificate.pdf', 'files/employee/resume_file/em25041400064300dc2_resume.pdf', 'files/employee/profile_pic_file/em25041400064300dc2_photo.jpg', 0, 0),
('em25041400095800752', 'Rased Alom', 'Sarver Oparator', 1646863674, 'employee3@fisp.com', 'e3', 'Dhaka, Bangladash', 'male', 40000, 2567354677, 'files/employee/nid_file/em25041400095800752_nid.pdf', 'files/employee/certificate_file/em25041400095800752_certificate.pdf', 'files/employee/resume_file/em25041400095800752_resume.pdf', 'files/employee/profile_pic_file/em25041400095800752_photo.jpg', 0, 0),
('em25041400140500f5d', 'Kobir Khan', 'Manager', 1357546842, 'admin@fisp.com', 'a', 'Dhaka, Bangladash', 'male', 80000, 6541956837, 'files/employee/nid_file/em25041400140500f5d_nid.pdf', 'files/employee/certificate_file/em25041400140500f5d_certificate.pdf', 'files/employee/resume_file/em25041400140500f5d_resume.pdf', 'files/employee/profile_pic_file/em25041400140500f5d_photo.jpg', 1, 0),
('em25092914491800142', 'Mr Adiyat', 'Manager', 1721703580, 'personal.adiyat@gmail.com', 'mmr', 'Mirpur, Dhaka 1216', 'male', 30000, 5088067599, 'files/employee/nid_file/em25092914491800142_nid.pdf', 'files/employee/certificate_file/em25092914491800142_certificate.pdf', 'files/employee/resume_file/em25092914491800142_resume.pdf', 'files/employee/profile_pic_file/em25092914491800142_photo.jpg', 1, 0),
('em2509291508570039d', 'Kevin', 'Line Man', 1689754789, 'k@gmail.com', 'k', 'Uttara 10', 'male', 12000, 1265897835, 'files/employee/nid_file/em2509291508570039d_nid.pdf', 'files/employee/certificate_file/em2509291508570039d_certificate.pdf', 'files/employee/resume_file/em2509291508570039d_resume.pdf', 'files/employee/profile_pic_file/em2509291508570039d_photo.jpg', 0, 0),
('em2509291511010073e', 'Peter', 'Line Man', 1548796874, 'p@gmail.com', 'p', 'Banani', 'male', 12000, 2356987849, 'files/employee/nid_file/em2509291511010073e_nid.pdf', 'files/employee/certificate_file/em2509291511010073e_certificate.pdf', 'files/employee/resume_file/em2509291511010073e_resume.pdf', 'files/employee/profile_pic_file/em2509291511010073e_photo.png', 0, 0);

--
-- Triggers `employee`
--
DELIMITER $$
CREATE TRIGGER `before_insert_employee` BEFORE INSERT ON `employee` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'em', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `footer_data`
--

CREATE TABLE `footer_data` (
  `id` int(11) NOT NULL,
  `address_text` varchar(100) DEFAULT NULL,
  `address_link` varchar(255) DEFAULT NULL,
  `phone_text` varchar(20) DEFAULT NULL,
  `phone_link` varchar(255) DEFAULT NULL,
  `mail_text` varchar(50) DEFAULT NULL,
  `mail_link` varchar(255) DEFAULT NULL,
  `fb_link` varchar(255) DEFAULT NULL,
  `ms_link` varchar(255) DEFAULT NULL,
  `wh_link` varchar(255) DEFAULT NULL,
  `in_link` varchar(255) DEFAULT NULL,
  `yt_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer_data`
--

INSERT INTO `footer_data` (`id`, `address_text`, `address_link`, `phone_text`, `phone_link`, `mail_text`, `mail_link`, `fb_link`, `ms_link`, `wh_link`, `in_link`, `yt_link`) VALUES
(1, 'House 9, Road 23, Block D, Mirpur 11, Dhaka-1216', 'https://www.google.com/maps/search/House+9,+Road+23,+Block+D,+Mirpur+11,+Dhaka+1216/@23.8223475,90.3654215,15z/data=!3m1!4b1?entry=ttu&g_ep=EgoyMDI1MDkzMC4wIKXMDSoASAFQAw%3D%3D', '+8801812703580', 'Reach Us Anytime', 'info@fmisp.com', 'mailto:info@fmisp.com', 'https://www.facebook.com/mmradiyat', 'https://m.me/mmradiyat', 'https://wa.me/qr/SCY3CD2PK635G1', 'https://www.instagram.com/mmr.adiyat', 'https://www.youtube.com');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` varchar(50) NOT NULL,
  `customer_id` varchar(50) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `state` varchar(10) DEFAULT NULL,
  `tran_id` varchar(50) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'BDT',
  `pay_date` datetime DEFAULT NULL,
  `val_id` varchar(27) DEFAULT NULL,
  `card_type` varchar(50) DEFAULT NULL,
  `store_amount` decimal(10,2) DEFAULT NULL,
  `bank_tran_id` varchar(27) DEFAULT NULL,
  `tran_status` varchar(10) DEFAULT NULL,
  `card_issuer` varchar(50) DEFAULT NULL,
  `card_brand` varchar(50) DEFAULT NULL,
  `card_sub_brand` varchar(50) DEFAULT NULL,
  `card_issuer_country` varchar(50) DEFAULT NULL,
  `card_issuer_country_code` varchar(5) DEFAULT NULL,
  `store_id` varchar(18) DEFAULT NULL,
  `verify_sign` varchar(32) DEFAULT NULL,
  `verify_sign_sha2` varchar(64) DEFAULT NULL,
  `risk_level` int(11) DEFAULT NULL,
  `risk_title` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `customer_id`, `amount`, `state`, `tran_id`, `currency`, `pay_date`, `val_id`, `card_type`, `store_amount`, `bank_tran_id`, `tran_status`, `card_issuer`, `card_brand`, `card_sub_brand`, `card_issuer_country`, `card_issuer_country_code`, `store_id`, `verify_sign`, `verify_sign_sha2`, `risk_level`, `risk_title`) VALUES
('py25042500182300f42', 'cu2504140208430063b', 1200, 'Paid', 'py25042500182300f42680a806ff000d', 'BDT', '2025-04-25 00:18:24', '25042501845s8Mf8jTFym2XkfK', 'BKASH-BKash', 1170.00, '25042501845wbFQheIVoRpiX6U', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', 'd296f3ae35814971f198b50a8a7b7eb3', '58363d7f4717d941b4051e301e40d4f28d3d285441b398455bb54f10f8c61b84', 0, 'Safe'),
('py25042500194300206', 'cu2504140208430063b', 9700, 'Paid', 'py25042500194300206680a80bf6c547', 'BDT', '2025-04-25 00:19:43', '250425020010WBp2Qa9IRRw2QX', 'BKASH-BKash', 9457.50, '250425020018HDftodo2Mbscm6', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', 'f54f5470ec80ab88142d3c0615adfb31', '8a76e5da4b51cdb6ff288dcc6289c0565eb4417a030c189bf24c17cb3424ffcb', 0, 'Safe'),
('py25042500251800990', 'cu25041402151500309', 1000, 'Paid', 'py25042500251800990680a820e66f1b', 'BDT', '2025-04-25 00:25:21', '250425025330BW4CZUZcd99ato', 'BKASH-BKash', 975.00, '2504250253305uubJx5svNjLcs', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '0c82f5a4ffdf8cb6c9976fcc38ba2876', 'a7a4a948d672d2cd2cf0ef52029ab47d6ac8f92b25d94bd426e4d8494540f2b7', 0, 'Safe'),
('py25042500255200fff', 'cu25041402151500309', 1200, 'Paid', 'py25042500255200fff680a82304c95f', 'BDT', '2025-04-25 00:25:56', '250425026080hNNSSu5BwR3GVC', 'BKASH-BKash', 1170.00, '25042502608bLGPXDRzBm4kTPC', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '9c8bd33873e9336dde7c524fe772982b', '33e2e48a8bf6486c65e4e5ef59302dce866135d26363e6f60c1dad87f88ec7cd', 0, 'Safe'),
('py2509301633320067a', 'cu25041402151500309', 8000, 'Paid', 'py2509301633320067a68dbb1fcb57b3', 'BDT', '2025-09-30 16:33:32', '2509301634041dx0SDxhbJMIE6A', 'BKASH-BKash', 7800.00, '250930163404m6IFoyzuzEBOYkx', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '33a356a29ac6df53681047cf6c5786c3', '64f6c075b810a32c67e6743b7c71434d768ade4b342b318b1b6f465f3829db42', 0, 'Safe'),
('py25093019390200ba6', 'cu2504140208430063b', 2000, 'Paid', 'py25093019390200ba668dbdd766f6f8', 'BDT', '2025-09-30 19:39:02', '250930193914sNurHtDlgidpgk2', 'BKASH-BKash', 1950.00, '2509301939140HHr1JLuVLWsYiR', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '9036170884ee14642eea40e062cacc70', 'b524f330c73afe73278df66114a4b5f485a8295d2e71e889bba6b740ad4f1775', 0, 'Safe'),
('py25100223381200f99', 'cu2509291431460022f', 4200, 'Paid', 'py25100223381200f9968deb884c10f5', 'BDT', '2025-10-02 23:38:14', '2510022348071Zv5DOU3GdGQ8HJ', 'BKASH-BKash', 4095.00, '2510022348074dRMHR0wLoYAkMy', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '03c620e8bd9752b032fa63b570e724f9', '7d33f5cf5d94ebe3bc47b695f967ef7dfbc2ac487cc430786e2025a112f86c2a', 0, 'Safe'),
('py25100223493900a4c', 'cu25092914570500fb8', 5500, 'Paid', 'py25100223493900a4c68debb33b57c8', 'BDT', '2025-10-02 23:49:39', '2510022350060VfBka4D5bwf4sA', 'BKASH-BKash', 5362.50, '2510022350061ssGDi3PNINrSlJ', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '2cb82b6f0ecd8cf2be46d3d1b76339c0', 'e68ff00c25a79bf25d1e6a24f39b911216f55968eed9e5319d3688d2605bc1b5', 0, 'Safe'),
('py25100223525100bd4', 'cu25093023000200f40', 4000, 'Paid', 'py25100223525100bd468debbf30a766', 'BDT', '2025-10-02 23:52:51', '251002235335kcF5p2amcQwlbB0', 'NAGAD-Nagad', 3900.00, '2510022353353Sk3oalmoxaqEyg', 'VALID', 'Nagad', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '1a7c8db9a32fa32143858731fa3a8b4b', 'd7bd785427806e0ee6d762fd18bb8e07b8e7db0736dd851e216d6fae68de4549', 0, 'Safe'),
('py2510030032130014a', 'cu2504140208430063b', 4200, 'Paid', 'py2510030032130014a68dec52d7703c', 'BDT', '2025-10-03 00:32:14', '251003032281mcs1ApqM0vyvaE', 'UPay-UPay', 4095.00, '251003032280RElOw3fQqQUw1J', 'VALID', 'UPay', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '7804166ab2ff0737b97391084b97444b', '8328e235befce8f87a1628067d74d5d87de48ea0e9a324fbbe3d63376209d957', 0, 'Safe'),
('py25100314053400447', 'cu2504140208430063b', 1000, 'Paid', 'py2510031405340044768df83ce32404', 'BDT', '2025-10-03 14:05:34', '2510031406001j6g61r02gC0DPH', 'ABBANKIB-AB Bank', 975.00, '251003140600ONPVT0goqXlY9N5', 'VALID', 'AB Bank Limited', 'IB', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '0286a7e66c758dd25ea0119ab26d9ef3', 'd06b89f5dd4aa18a5c0506123722d15095d84fdd2c3e133cc03697b5c12bccd1', 0, 'Safe');

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `before_insert_payments` BEFORE INSERT ON `payments` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'py', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` varchar(50) NOT NULL,
  `type` text DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  `realip` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `type`, `name`, `speed`, `realip`, `price`) VALUES
('pl25041414453500b5b', 'residential_plans', 'A good start 🏠', 5, 'No', 500),
('pl25041414463200f6f', 'residential_plans', 'Hello Speed 🏠⚡', 10, 'No', 700),
('pl2504141448140029b', 'residential_plans', 'Speedy Life 🏃‍♂️‍➡️', 20, 'Yes', 1500),
('pl2504141450000053c', 'organizational_plans', 'Bootstrap ⭐', 20, 'No', 1500),
('pl25041414513300316', 'organizational_plans', 'Grow With Speed ⚡', 30, 'Yes', 2500),
('pl25041414525000129', 'organizational_plans', 'Sky is the Limit 🪂', 50, 'Yes', 5000),
('pl2509302310450049b', 'residential_plans', 'Bizli', 50, 'No', 5000);

--
-- Triggers `plans`
--
DELIMITER $$
CREATE TRIGGER `before_insert_residential_plans` BEFORE INSERT ON `plans` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'pl', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `residential_plan` int(11) NOT NULL,
  `organizational_plan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenue`
--

INSERT INTO `revenue` (`id`, `date`, `residential_plan`, `organizational_plan`) VALUES
(13, '2025-04-01', 50000, 45000),
(14, '2025-05-01', 45000, 55000),
(15, '2025-06-01', 35000, 40000),
(16, '2025-07-01', 20000, 25000),
(17, '2025-08-01', 35000, 45000),
(18, '2025-09-01', 45000, 55000);

--
-- Triggers `revenue`
--
DELIMITER $$
CREATE TRIGGER `before_insert_revenue` BEFORE INSERT ON `revenue` FOR EACH ROW BEGIN
    SET NEW.date = LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `state` text DEFAULT NULL,
  `employee_id` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `task_ref` varchar(50) DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `name`, `address`, `start`, `end`, `state`, `employee_id`, `details`, `task_ref`, `completed_at`) VALUES
('ta25042423383800af3', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-01', 'Completed', 'em25041400095800752', 'cc', 'co25042423273600e34', '2025-04-24 23:48:39'),
('ta25042423385500383', 'New connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-20', 'Late', 'em25041400064300dc2', 'cc', 'co25042423285600ee3', '2025-04-24 23:49:12'),
('ta250424233925000cd', 'New connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-21', 'Late', 'em25041400095800752', 'cc', 'co250424232938007a8', '2025-04-24 23:48:41'),
('ta25042423393400a65', 'New connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-01', 'Completed', 'em25041400064300dc2', 'cc', 'co25042423302600250', '2025-04-24 23:49:13'),
('ta25042423400300d73', 'New connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-03', 'Completed', 'em25041400095800752', 'cc', 'co25042423304900df3', '2025-04-24 23:48:42'),
('ta2504242340170018c', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-05', 'Completed', 'em25041400064300dc2', 'cc', 'co250424233126007cc', '2025-04-24 23:49:14'),
('ta25042423403500b6d', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-19', 'Late', 'em25041400095800752', 'cc', 'co2504242333330009f', '2025-04-24 23:48:42'),
('ta25042423404800b3b', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-02', 'Completed', 'em25041400064300dc2', 'cc', 'co2504242334540027c', '2025-04-24 23:49:14'),
('ta2504242341000050b', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-07', 'Completed', 'em25041400095800752', 'cc', 'co25042423350800007', '2025-04-24 23:48:43'),
('ta25042423411800f74', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-01', 'Completed', 'em25041400064300dc2', 'cc', 'co25042423352700b3f', '2025-04-24 23:49:15'),
('ta25042423413100b05', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-20', 'Late', 'em25041400095800752', 'cc', 'co2504242335500085d', '2025-04-24 23:48:44'),
('ta25042423414300bb2', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-05', 'Completed', 'em25041400064300dc2', 'cc', 'co250424233607002f9', '2025-04-24 23:49:16'),
('ta25042423441200134', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-06', 'Late', 'em25041400064300dc2', 'cc', 'co250424234302004a8', '2025-09-30 16:23:45'),
('ta2504242344200072c', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-29', 'Late', 'em25041400095800752', 'cc', 'co25042423434400cd7', '2025-09-30 16:22:23'),
('ta25042423463500c7b', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-06-30', 'Completed', 'em25041400064300dc2', 'cc', 'co2504242345030025a', '2025-04-25 00:00:59'),
('ta25042423570900738', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-22', 'Late', 'em25041400064300dc2', 'uu', 'co25042423273600e34', '2025-04-25 00:01:00'),
('ta25042423572500821', 'Update connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-02', 'Completed', 'em25041400095800752', 'uu', 'co25042423285600ee3', '2025-04-25 00:00:27'),
('ta25042423574400221', 'Update connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-02', 'Completed', 'em25041400064300dc2', 'uu', 'co250424232938007a8', '2025-04-25 00:01:01'),
('ta25042423580500370', 'Update connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-21', 'Late', 'em25041400095800752', 'uu', 'co25042423302600250', '2025-04-25 00:00:28'),
('ta25042423582100859', 'Update connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-19', 'Late', 'em25041400064300dc2', 'uu', 'co25042423304900df3', '2025-04-25 00:01:02'),
('ta250424235832006cb', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-02', 'Completed', 'em25041400095800752', 'uu', 'co250424233126007cc', '2025-04-25 00:00:28'),
('ta25042423584800fea', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-01', 'Completed', 'em25041400095800752', 'uu', 'co2504242333330009f', '2025-04-25 00:00:29'),
('ta25042423590400e4b', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-27', 'Completed', 'em25041400095800752', 'uu', 'co2504242334540027c', '2025-04-25 00:00:31'),
('ta250424235932002cb', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-08', 'Late', 'em25041400095800752', 'uu', 'co25042423352700b3f', '2025-09-30 16:22:25'),
('ta25042500112300ae8', 'Disconnect connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-23', 'Late', 'em25041400095800752', 'dd', 'co25042423285600ee3', '2025-04-25 00:12:54'),
('ta25042500114700a3f', 'Disconnect connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-23', 'Late', 'em25041400064300dc2', 'dd', 'co25042423350800007', '2025-04-25 00:13:22'),
('ta25042500121700b4f', 'Disconnect connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-02', 'Late', 'em25041400095800752', 'dd', 'co250424232938007a8', '2025-09-30 16:22:26'),
('ta25042500123600d53', 'Disconnect connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-03', 'Late', 'em25041400064300dc2', 'dd', 'co250424233126007cc', '2025-09-30 16:23:50'),
('ta25092522023500783', 'New connection in Mirpur, Dhaka, Bangladesh', 'Mirpur, Dhaka, Bangladesh', '2025-09-25', '2025-10-01', 'Completed', 'em25041400064300dc2', 'Soon', 'co25092522001400d0f', '2025-09-30 16:23:53'),
('ta25092915013700fe9', 'New connection in Uttara, Dhaka 1230', 'Uttara, Dhaka 1230', '2025-09-29', '2025-10-09', 'Completed', 'em25041400064300dc2', 'You have to complete it before 10th October.', 'co25092914584700e09', '2025-09-30 19:21:57'),
('ta250929152821007c0', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-09-29', '2025-10-05', 'Completed', 'em2509291508570039d', 'Complete in time', 'co250424233403009e7', '2025-09-29 15:32:36'),
('ta25092915290800632', 'New connection in Diabari, Dhaka 1216', 'Diabari, Dhaka 1216', '2025-09-29', '2025-10-09', 'Completed', 'em2509291511010073e', 'Do it', 'co250929152435008db', '2025-09-30 16:40:56'),
('ta25093016313300810', 'Disconnect connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-09-30', '2025-10-03', 'Completed', 'em2509291508570039d', 'Do it', 'co25042423304900df3', '2025-09-30 16:39:54'),
('ta25093016315900c5a', 'Disconnect connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-09-30', '2025-10-05', 'Completed', 'em2509291508570039d', 'do it', 'co2504242345030025a', '2025-09-30 16:39:56'),
('ta250930163640008c4', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-09-30', '2025-10-05', 'Completed', 'em25041400095800752', 'Do It', 'co2504242335500085d', '2025-09-30 16:38:33'),
('ta25093016370500c8f', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-09-30', '2025-10-05', 'Completed', 'em25041400095800752', 'Do it', 'co250424233607002f9', '2025-09-30 16:38:34'),
('ta2509302308240054b', 'New connection in Banani', 'Banani', '2025-09-30', '2025-10-04', 'Completed', 'em2509291511010073e', 'Do it in time', 'co25093023050600cd2', '2025-09-30 23:15:04'),
('ta25100215554700c89', 'Disconnect connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-10-02', '2025-10-05', 'Completed', 'em2509291511010073e', 'Do', 'co2504242328140041d', '2025-10-02 15:59:47'),
('ta25100215573200250', 'Disconnect connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-10-02', '2025-10-05', 'Completed', 'em2509291511010073e', 'do', 'co25042423273600e34', '2025-10-02 15:59:50'),
('ta25100216053600a43', 'New connection in Banani', 'Banani', '2025-10-02', '2025-10-05', 'Completed', 'em2509291508570039d', 'do', 'co250930230552000cf', '2025-10-02 16:06:31'),
('ta251002160556002c6', 'New connection in Uttara, Dhaka 1230', 'Uttara, Dhaka 1230', '2025-10-02', '2025-10-05', 'Completed', 'em2509291508570039d', 'do', 'co25100216041800e38', '2025-10-02 16:06:32'),
('ta25100220123100776', 'New connection in Mirpur, Dhaka 1216', 'Mirpur, Dhaka 1216', '2025-10-02', '2025-10-31', 'Completed', 'em25041400064300dc2', 'ddd', 'co25100220113700225', '2025-10-02 20:17:19'),
('ta25100220243000e52', 'Disconnect connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-10-02', '2025-10-04', 'Completed', 'em25041400064300dc2', 'gg', 'co25042423302600250', '2025-10-03 00:00:56'),
('ta25100300280000d50', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-10-02', '2025-10-05', 'Completed', 'em2509291508570039d', 'dd', 'co25100219063200bbb', '2025-10-03 00:29:46'),
('ta2510030028420037a', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-10-02', '2025-10-06', 'Completed', 'em2509291508570039d', 'df', 'co25100219084300fa0', '2025-10-03 00:29:47'),
('ta2510030029080050e', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-10-02', '2025-10-05', 'Completed', 'em2509291508570039d', 'lkj', 'co251002191211008b0', '2025-10-03 00:29:48'),
('ta25100303390900c84', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-10-05', '2025-10-10', 'Completed', 'em2509291511010073e', 'klkl', 'co25100303374200ddc', '2025-10-03 03:40:38'),
('ta25100304024400e78', 'Disconnect connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-10-05', '2025-10-08', 'Completed', 'em2509291511010073e', 'lkj', 'co2504242333330009f', '2025-10-03 04:03:30');

--
-- Triggers `task`
--
DELIMITER $$
CREATE TRIGGER `before_insert_task` BEFORE INSERT ON `task` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'ta', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `connections`
--
ALTER TABLE `connections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `footer_data`
--
ALTER TABLE `footer_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revenue`
--
ALTER TABLE `revenue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `footer_data`
--
ALTER TABLE `footer_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `generate_monthly_bills` ON SCHEDULE EVERY 1 MONTH STARTS '2024-06-05 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO `payment` (`connection_id`, `amount`, `state`)
SELECT 
    c.id AS connection_id,
    p.price AS amount,
    'Unpaid' AS state
FROM `connections` c
JOIN `plans` p ON c.plan_id = p.id
WHERE c.state != 'Pending'$$

CREATE DEFINER=`root`@`localhost` EVENT `late_monthly_bills` ON SCHEDULE EVERY 1 SECOND STARTS '2024-06-11 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE `payment` 
SET 
    `state` = 'Late',
    `amount` = `amount` * 1.10
WHERE `state` = 'Unpaid'$$

CREATE DEFINER=`root`@`localhost` EVENT `generate_monthly_revenue` ON SCHEDULE EVERY 1 MONTH STARTS '2024-06-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO -- Insert revenue for the previous month
INSERT INTO revenue (date, organizational_plan, residential_plan)
SELECT 
    -- Use the first day of the previous month as the record date
    DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01') AS revenue_date,

    -- Sum of organizational payments
    COALESCE(SUM(CASE 
        WHEN connections.type = 'organizational_plans' THEN payment.amount 
        ELSE 0 
    END), 0) AS organizational_plan,

    -- Sum of residential payments
    COALESCE(SUM(CASE 
        WHEN connections.type = 'residential_plans' THEN payment.amount 
        ELSE 0 
    END), 0) AS residential_plan

FROM payment
JOIN connections ON payment.connection_id = connections.id
WHERE payment.state = 'Paid'
  AND YEAR(payment.pay_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
  AND MONTH(payment.pay_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
