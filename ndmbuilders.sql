-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2017 at 10:59 AM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `himirror-qa20161110`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_tbl`
--

CREATE TABLE `attendance_tbl` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `shift` varchar(255) NOT NULL,
  `dates` varchar(255) NOT NULL,
  `time_in` varchar(255) NOT NULL,
  `time_out` varchar(255) NOT NULL,
  `lateness` varchar(255) NOT NULL,
  `early_leave` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `abnormal_report` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bk_csv_history`
--

CREATE TABLE `bk_csv_history` (
  `id` int(11) NOT NULL,
  `type_of_file` varchar(255) DEFAULT NULL,
  `editor_name` varchar(255) DEFAULT NULL,
  `db_name` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `dated` varchar(255) DEFAULT NULL,
  `timed` varchar(255) DEFAULT NULL,
  `sn_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `scpid` varchar(255) NOT NULL,
  `raw_scp_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bk_csv_history`
--

INSERT INTO `bk_csv_history` (`id`, `type_of_file`, `editor_name`, `db_name`, `file_name`, `dated`, `timed`, `sn_number`, `status`, `scpid`, `raw_scp_id`) VALUES
(1, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skinprod_test_doc.xlsx', '2017/09/06', '01:30:23pm', 'Clinique-00043', 'Success', '6087', ''),
(2, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skinprod_test_doc.xlsx', '2017/09/06', '01:30:46pm', 'Clinique-00043', 'Success', '6088', ''),
(3, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skinprod_test_doc.xlsx', '2017/09/06', '01:32:14pm', 'Clinique-00043', 'Success', '6089', ''),
(4, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skinprod_test_doc.xlsx', '2017/09/06', '05:16:28pm', 'Clinique-00043', 'Success', '6090', ''),
(5, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skinprod_test_doc.xlsx', '2017/09/06', '05:38:28pm', 'Clinique-00043', 'Success', '6091', ''),
(6, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:04pm', 'uriage_us_20170905_01', 'Success', '', '6102'),
(7, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:05pm', 'greatbarrierisland_us_20170905_01', 'Success', '', '6103'),
(8, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:05pm', 'pierresapothecary_us_20170905_01', 'Success', '', '6104'),
(9, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:06pm', 'palmers_us_20170907_01', 'Success', '', '6105'),
(10, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:06pm', 'pierresapothecary_us_20170907_01', 'Success', '', '6106'),
(11, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:07pm', 'pierresapothecary_us_20170907_02', 'Success', '', '6107'),
(12, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:08pm', 'pierresapothecary_us_20170907_03', 'Success', '', '6108'),
(13, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:08pm', 'pierresapothecary_us_20170907_04', 'Success', '', '6109'),
(14, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:09pm', 'pierresapothecary_us_20170907_05', 'Success', '', '6110'),
(15, 'By Excel file', 'IC-Manager', 'himirror-QA20161110', '/var/www/uploads/skuncare_product_list_20170905.xlsx', '2017/09/11', '04:50:10pm', 'pierresapothecary_us_20170907_06', 'Success', '', '6111');

-- --------------------------------------------------------

--
-- Table structure for table `bk_login_history`
--

CREATE TABLE `bk_login_history` (
  `id` int(19) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `time` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bk_user`
--

CREATE TABLE `bk_user` (
  `userID` int(19) NOT NULL,
  `Username` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `User_Type` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bk_user`
--

INSERT INTO `bk_user` (`userID`, `Username`, `Password`, `User_Type`) VALUES
(1, 'Jisel', '1234', 'Admin'),
(2, 'admin', '123456', 'Engneer');

-- --------------------------------------------------------

--
-- Table structure for table `bk_user_access`
--

CREATE TABLE `bk_user_access` (
  `accessID` int(19) NOT NULL,
  `userID` int(19) NOT NULL,
  `member_info` int(11) DEFAULT NULL,
  `attendance` int(11) DEFAULT NULL,
  `schedule` int(11) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bk_user_access`
--

INSERT INTO `bk_user_access` (`accessID`, `userID`, `member_info`, `attendance`, `schedule`, `salary`) VALUES
(1, 1, 1, 1, 1, 1),
(2, 2, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bk_user_profile`
--

CREATE TABLE `bk_user_profile` (
  `profileID` int(19) NOT NULL,
  `userID` int(19) NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bk_user_profile`
--

INSERT INTO `bk_user_profile` (`profileID`, `userID`, `first_name`, `last_name`, `email`, `password`) VALUES
(1, 1, 'Jessele', 'Del Mundo', 'jesseledm@gmail.com', '1234'),
(2, 2, 'John', 'Doe', 'johnd@mail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `member_info`
--

CREATE TABLE `member_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `usertype` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `age` varchar(11) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `nationality` varchar(255) NOT NULL,
  `home_address` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `sss_number` varchar(255) NOT NULL,
  `tin_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `salary_tbl`
--

CREATE TABLE `salary_tbl` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `shift` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `overtime` varchar(255) NOT NULL,
  `overtime_special` varchar(255) NOT NULL,
  `lateness` varchar(255) NOT NULL,
  `early_leave` varchar(255) NOT NULL,
  `tax_deduct` varchar(255) NOT NULL,
  `salary_deduct` varchar(255) NOT NULL,
  `allowance` varchar(255) NOT NULL,
  `night_diff` varchar(255) NOT NULL,
  `salary_adjustment` varchar(255) NOT NULL,
  `pay_date` varchar(255) NOT NULL,
  `net_pay` varchar(255) NOT NULL,
  `gross_pay` varchar(255) NOT NULL,
  `total_deduct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_tbl`
--

CREATE TABLE `schedule_tbl` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `usertype` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `shift` varchar(255) NOT NULL,
  `dates` varchar(255) NOT NULL,
  `timed` varchar(255) NOT NULL,
  `weeks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shift_tbl`
--

CREATE TABLE `shift_tbl` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `weeks` varchar(255) NOT NULL,
  `shifts` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skincareproduct`
--

CREATE TABLE `skincareproduct` (
  `SCP_id` int(19) NOT NULL,
  `barcodeEAN13` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `barcodeGTIN` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `barcodeGTIN14` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `barcodeUPC` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `barcodeUPCA` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `brand_names` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `country_that_product_sell_in` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `del_flag` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `effective_period` datetime DEFAULT NULL,
  `features` text CHARACTER SET utf8,
  `gender` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `ingrediant` text CHARACTER SET utf8,
  `instruction` text CHARACTER SET utf8,
  `manufactured_location` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `photos` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `product_link_web` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `product_names` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `product_names_sub` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `size_ml` double DEFAULT NULL,
  `size_oz` double DEFAULT NULL,
  `size_pces` double DEFAULT NULL,
  `product_categories_id` decimal(19,0) DEFAULT NULL,
  `product_textures_id` decimal(19,0) DEFAULT NULL,
  `product_type_id` decimal(19,0) DEFAULT NULL,
  `price_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `price_web` double DEFAULT NULL,
  `Approval_flag` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `size_g` double DEFAULT NULL,
  `time_to_use` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `asin` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `step_id` decimal(19,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `usertype` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bk_csv_history`
--
ALTER TABLE `bk_csv_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bk_login_history`
--
ALTER TABLE `bk_login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bk_user`
--
ALTER TABLE `bk_user`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `bk_user_access`
--
ALTER TABLE `bk_user_access`
  ADD PRIMARY KEY (`accessID`);

--
-- Indexes for table `bk_user_profile`
--
ALTER TABLE `bk_user_profile`
  ADD PRIMARY KEY (`profileID`);

--
-- Indexes for table `member_info`
--
ALTER TABLE `member_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_tbl`
--
ALTER TABLE `salary_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_tbl`
--
ALTER TABLE `schedule_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_tbl`
--
ALTER TABLE `shift_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skincareproduct`
--
ALTER TABLE `skincareproduct`
  ADD PRIMARY KEY (`SCP_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bk_csv_history`
--
ALTER TABLE `bk_csv_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `bk_login_history`
--
ALTER TABLE `bk_login_history`
  MODIFY `id` int(19) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bk_user`
--
ALTER TABLE `bk_user`
  MODIFY `userID` int(19) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `bk_user_access`
--
ALTER TABLE `bk_user_access`
  MODIFY `accessID` int(19) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `bk_user_profile`
--
ALTER TABLE `bk_user_profile`
  MODIFY `profileID` int(19) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `member_info`
--
ALTER TABLE `member_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `salary_tbl`
--
ALTER TABLE `salary_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `schedule_tbl`
--
ALTER TABLE `schedule_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shift_tbl`
--
ALTER TABLE `shift_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
