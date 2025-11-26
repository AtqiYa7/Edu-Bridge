SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Admin table
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(250) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `admin` (`id`, `fullname`, `email`, `password`) VALUES
(1, 'Anjum', 'admin@tutor.com', '21232f297a57a5a743894a0e4a801fc3');

-- Applied posts
DROP TABLE IF EXISTS `applied_post`;
CREATE TABLE `applied_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `post_by` int(11) NOT NULL,
  `applied_by` int(11) NOT NULL,
  `applied_to` int(11) NOT NULL,
  `applied_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `student_ck` varchar(3) NOT NULL DEFAULT 'no',
  `tutor_ck` varchar(3) NOT NULL DEFAULT 'no',
  `tutor_cf` tinyint(4) NOT NULL DEFAULT '0',
  `tution_cf` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

INSERT INTO `applied_post` (`id`, `post_id`, `post_by`, `applied_by`, `applied_to`, `applied_time`, `student_ck`, `tutor_ck`, `tutor_cf`, `tution_cf`) VALUES
(13, 8, 1, 5, 1, '2025-11-30 08:26:35', 'yes', 'yes', 0, 0),
(14, 9, 10, 9, 10, '2025-11-30 09:05:48', 'yes', 'yes', 0, 1);

-- Post table
DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postby_id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `class` text NOT NULL,
  `medium` varchar(20) NOT NULL,
  `salary` varchar(50) NOT NULL,
  `location` text NOT NULL,
  `p_university` text NOT NULL,
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deadline` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO `post` (`id`, `postby_id`, `subject`, `class`, `medium`, `salary`, `location`, `p_university`, `post_time`, `deadline`) VALUES
(2, 1, 'ICT, Computer Science', 'College/University', 'English', 'None', 'Mirabajar, Sylhet', 'North East University, Sylhet University', '2025-01-09 11:11:44', '01/17/2025'),
(3, 2, 'English, Religion, ICT, Physics, Higher Math, Statistics', 'Eleven-Twelve, College/University', 'Bangla', '10000-15000', 'Dakshin Surma, Sylhet', 'North East University, RTM University', '2025-01-09 17:36:07', '01/07/2025'),
(4, 1, 'Bangla, ICT, Computer Science', 'Six-Seven, Eleven-Twelve', 'Bangla', '2000-5000', 'Mirabajar, Sylhet', 'North East University, Sylhet University', '2025-01-10 04:28:42', '01/17/2025'),
(5, 6, 'Bangla', 'One-Three', 'Bangla', '1000-1500', 'Bondor Bazar, Sylhet', 'North East University, Sylhet University', '2025-01-11 05:17:25', '01/19/2025'),
(6, 9, 'Bangla', 'One-Three', 'Bangla', 'None', 'Lamabajar, Sylhet', 'North East University, RTM University', '2025-01-10 05:24:41', '02/14/2025'),
(7, 1, 'Bangla', 'One-Three', 'Bangla', '5000-10000', 'Sylhet Sadar', 'North East University, Sylhet University, RTM University', '2025-06-28 10:23:31', '06/30/2025'),
(8, 5, 'ICT', 'Eleven-Twelve, College/University', 'Any', '2000-2500', 'Lamabajar, Sylhet', 'North East University, RTM University, Sylhet University', '2025-11-30 05:03:02', '12/19/2025');

-- Tutor table
DROP TABLE IF EXISTS `tutor`;
CREATE TABLE `tutor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `t_id` int(11) NOT NULL,
  `inst_name` varchar(150) NOT NULL,
  `prefer_sub` text NOT NULL,
  `class` text NOT NULL,
  `medium` text NOT NULL,
  `prefer_location` text NOT NULL,
  `salary` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

INSERT INTO `tutor` (`id`, `t_id`, `inst_name`, `prefer_sub`, `class`, `medium`, `prefer_location`, `salary`) VALUES
(5, 2, 'North East University, Sylhet University', 'Bangla, Math, ICT, Computer Science', 'One-Three, Nine-Ten, Eleven-Twelve, College/University', 'Bangla, Any', 'Mirabajar, Sylhet', '1000-2000'),
(11, 6, 'North East University, RTM University', 'Bangla, English, Religion, ICT, Physics, Sociology, Economics, Civics, Computer Science', 'Six-Seven, Nine-Ten, Eleven-Twelve', 'Bangla', 'Lamabajar, Sylhet', '2000-5000'),
(15, 5, 'North East University, Sylhet University', 'Bangla, Math, General Science, Religion, ICT, Physics, Higher Math, Computer Science', 'Nine-Ten, Eleven-Twelve, College/University', 'English, Any', 'Dakshin Surma, Sylhet', '1000-2000'),
(17, 9, 'North East University, RTM University, Sylhet University', 'ICT, Physics, Higher Math, Computer Science', 'Nine-Ten, Eleven-Twelve, College/University', 'Any', 'Bondor Bazar, Lamabajar, Mirabajar, Sylhet', '5000-10000');

-- User table
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(150) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `email` varchar(40) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(200) NOT NULL DEFAULT '',
  `pass` varchar(50) NOT NULL,
  `confirmcode` varchar(7) NOT NULL,
  `activation` varchar(3) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL,
  `user_pic` text,
  `last_logout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `online` varchar(5) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO `user` (`id`, `fullname`, `gender`, `email`, `phone`, `address`, `pass`, `confirmcode`, `activation`, `type`, `user_pic`, `last_logout`, `online`) VALUES
(1, 'Rashed Ahmed', 'male', 'rashed@gmail.com', '015976432566', 'Mirabajar, Sylhet', 'e10adc3949ba59abbe56e057f20f883e', '205575', '', 'student', '1543554432.png', '2018-11-30 06:11:19', 'no'),
(2, 'Tariq Hossain', 'male', 'tariq@gmail.com', '014976432566', 'Lamabajar, Sylhet', '8d788385431273d11e8b43bb78f3aa41', '901358', '', 'teacher', '1515505450.jpg', '2018-11-30 05:35:16', 'yes'),
(5, 'Sumaiya Khatun', 'female', 'sumaiya@gmail.com', '014976432566', 'Bondor Bazar, Sylhet', '8d788385431273d11e8b43bb78f3aa41', '495196', '', 'teacher', '', '2018-11-30 08:45:02', 'no'),
(6, 'Imran Ali', 'male', 'imran@gmail.com', '014976432566', 'Mirabajar, Sylhet', '8d788385431273d11e8b43bb78f3aa41', '292470', '', 'teacher', '1515558340.jpeg', '2018-09-04 02:39:17', 'no'),
(9, 'Tanisha Rahman', 'female', 'tanisha@gmail.com', '01899761551', 'Dakshin Surma, Sylhet', 'e10adc3949ba59abbe56e057f20f883e', '214114', '', 'teacher', '1543568429.jpg', '2018-11-30 09:00:29', 'yes'),
(10, 'Anika Mir', 'female', 'anika@gmail.com', '01788651991', 'Lamabajar, Sylhet', 'e10adc3949ba59abbe56e057f20f883e', '946363', '', 'student', '1543568644.png', '2018-11-30 09:13:40', 'no');

COMMIT;
