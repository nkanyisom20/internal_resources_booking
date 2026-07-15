-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2026 at 05:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `company_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(20) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `purpose` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `resource_id`, `booking_date`, `start_time`, `end_time`, `purpose`, `status`) VALUES
(1, 2, 1, '2026-04-01', '10:30:00', '14:30:00', 'Studio', 'rejected'),
(2, 2, 2, '0000-00-00', '12:30:00', '16:50:00', '', 'approved'),
(3, 2, 1, '2026-05-29', '20:00:00', '21:00:00', 'Soccer Tournament', 'approved'),
(4, 12, 2, '0000-00-00', '20:50:00', '22:00:00', 'Community Meeting', 'rejected'),
(5, 12, 9, '2026-05-21', '12:30:00', '18:00:00', 'Board Meeting', 'cancelled'),
(6, 12, 11, '2026-03-16', '20:00:00', '22:00:00', 'Student Meeting', 'rejected'),
(7, 12, 11, '2025-08-29', '00:00:00', '05:00:00', 'Student Meeting', 'pending'),
(8, 12, 2, '2025-10-19', '02:00:00', '08:00:00', 'jjj', 'rejected'),
(9, 12, 9, '2025-10-19', '01:00:00', '02:00:00', '000', 'approved'),
(10, 12, 14, '2025-10-19', '00:21:00', '02:00:00', 'n', 'approved'),
(11, 16, 11, '2027-01-01', '00:00:00', '02:00:00', 'S', 'cancelled'),
(12, 16, 2, '2026-01-01', '00:00:00', '02:00:00', 'A', 'rejected'),
(13, 16, 2, '2027-01-01', '00:00:00', '01:00:00', 'M', 'approved'),
(14, 16, 2, '2026-04-03', '19:00:00', '22:00:00', ',l', 'cancelled'),
(15, 16, 2, '2026-03-31', '12:00:00', '14:00:00', 'fdg', 'cancelled'),
(16, 16, 1, '2027-01-15', '15:00:00', '17:00:00', 'Soccer Tournament', 'cancelled'),
(17, 16, 1, '2026-04-04', '10:00:00', '12:00:00', 'Sports day', 'pending'),
(18, 16, 1, '2026-04-04', '14:00:00', '16:00:00', 'Young Summit', 'cancelled'),
(19, 16, 2, '2026-04-10', '12:00:00', '14:00:00', 'kl', 'cancelled'),
(20, 16, 7, '2026-04-04', '08:00:00', '10:00:00', ',.', 'cancelled'),
(21, 16, 2, '2026-04-03', '12:00:00', '14:00:00', 'hv', 'cancelled'),
(22, 16, 2, '2026-03-31', '20:00:00', '22:00:00', 'nj', 'cancelled'),
(23, 16, 1, '2026-04-04', '01:02:00', '03:02:00', 'nb', 'cancelled'),
(24, 16, 1, '2026-04-09', '10:01:00', '12:01:00', 'soccer tournament', 'pending'),
(25, 16, 1, '2026-04-11', '12:00:00', '14:00:00', 'ghnfjh', 'approved'),
(26, 16, 2, '2026-04-17', '12:00:00', '14:00:00', 'dfhzgn', 'pending'),
(27, 16, 1, '2026-04-18', '12:00:00', '14:00:00', 'xfdhzg', 'approved'),
(28, 16, 1, '2026-04-17', '12:00:00', '14:00:00', 'cv', 'pending'),
(29, 16, 1, '2026-04-16', '12:00:00', '14:00:00', 'cgn', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL,
  `resource_name` varchar(30) NOT NULL,
  `description` varchar(200) NOT NULL,
  `location` varchar(50) NOT NULL,
  `capacity` int(10) NOT NULL,
  `picture` varchar(50) NOT NULL,
  `status` enum('Inactive','Active') DEFAULT 'Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`resource_id`, `resource_name`, `description`, `location`, `capacity`, `picture`, `status`) VALUES
(1, 'Isiqalo Sports Ground', 'Isiqalo Sports Ground unizulu ', 'Isiqalo Residents', 300, 'esiqalo_sports_grounds.jpeg', 'Active'),
(2, 'Student Centre Hall', 'Student Centre Hall Unizulu Main Campus', 'Student Centre', 1200, 'student_centre_hall.jpeg', 'Active'),
(7, 'Guest House', 'Guest House @ Staff Resident', 'Staff Resident', 10, 'guest_house.jpg', 'Active'),
(9, 'Conference Center', 'Unizulu Conference Center Main Campus', 'Main Library', 100, 'conference_center.jpg', 'Active'),
(11, 'Work Facility', 'Work Facility for Indoor ', 'Bhekuzulu Hall', 194, 'workout_facility.jpg', 'Active'),
(20, 'Gymnasium Centre ', 'Gymnasium (Main Training Center) for Students', 'Main Training Centre', 603, 'gymnasium.jpg', 'Active'),
(23, 'Bhekuzulu Hall', 'Bhekuzulu Hall Unizulu Main Campus', 'Unizulu Main Campus', 3000, 'img_69db5bddd86550.09108111.jpeg', 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `employee_id` int(11) NOT NULL,
  `employee_no` varchar(20) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `fullnames` varchar(30) NOT NULL,
  `password` varchar(80) NOT NULL,
  `role` enum('Clerk','Admin') DEFAULT 'Clerk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`employee_id`, `employee_no`, `surname`, `fullnames`, `password`, `role`) VALUES
(2, '2014502891', 'Ngunezi', 'Nkanyiso', '$2y$10$QwnS5r6ptsVQZ1bOHJ/.6uwWFri1Trxst3BsydcsBvWldu4nu6j4q', 'Clerk'),
(3, '2013502892', 'Mina', 'Mkhize AF', '$2y$10$wdY7bQjRQWzIWfbLpNDBMu716FSSvaZADHlsky.e0SLLu5Y2wa2FK', 'Clerk'),
(6, '2015502893', 'Mina', 'Mkhize AFF', '$2y$10$QwnS5r6ptsVQZ1bOHJ/.6uwWFri1Trxst3BsydcsBvWldu4nu6j4q', 'Clerk'),
(11, '20145028904', 'kk', 'jj.,', '$2y$10$QwnS5r6ptsVQZ1bOHJ/.6uwWFri1Trxst3BsydcsBvWldu4nu6j4q', 'Clerk'),
(12, '20147147147', '', '', '$2y$10$CbK3IzMO0Gq/v.EKuztEzuQP625QYfakGcWg4kImtX0QKkuA25nrG', 'Clerk'),
(13, '201350289333', '', '', '$2y$10$IoSqFOGYfP.X7X9vfX9b8uNHN/q8pQejEuiITJ4Dxpwl8pf5R4XZi', 'Clerk'),
(14, '201450289', 'Nqubeko', 'Mkhize', '$2y$10$NxNCNUibQd3b5jHldKByoeeEEbMXVTxo9zfBl4Hpp1G0u3zmGSxdO', 'Admin'),
(16, '201450285', '', '', '$2y$10$IDqOtoHtbnfXpXxUi98Kzurfreu8vR4Oz1syJv3sXnTwqmF3IPnc2', 'Clerk'),
(17, '20000000', '', '', '$2y$10$ybIY5zmchCOuCSYPD3BFXeY71a/H3BCTbfocvNduZtQczwNIOcQri', 'Clerk'),
(18, '20135028912', '', '', '$2y$10$ogwiYlESNojYhs440WgvquJAvlQeB6z3peXzRZBtATlh4ZaOwMqVO', 'Clerk'),
(19, '8545525', '', '', '$2y$10$BXg2CADgMAcdeZ/ANyg4zuLsM7NNWEfYwJmg7uQVYbAcWmwkCITqu', 'Admin'),
(20, '20135028956', '', '', '$2y$10$lDTzpX1I/hE3QBcbjQ8rUOBlhpsnUR0ZKSf/sOLb0F0WjhdoMPKeG', 'Admin'),
(21, '201350289knm', 'KNM', 'M', '$2y$10$JWRmu9p7Zjk/UcKzmhw7ee2YbPMFgyNKX3Fq0.Rf25b5ql5nuW52e', 'Clerk'),
(22, '2014502894', '', '', '$2y$10$Zfb8qp4Lv2SVm4q4qv.NU.GOhD6OaMgwh8/ossCmUgc5B0oFYodcC', 'Clerk'),
(23, '20145028923', 'Gambu', 'T', '$2y$10$5mjC5bZxdEn69KHnOmtzi.ViXLG7vwhg2YtH4GpR2kvbK4M8Laps2', 'Clerk'),
(43, '2013502898', 'Asibonge b', 'Mkhize', '$2y$10$xhZ55gvPhlEwAW.RIImqxuNbA9zUFY4NJCdrruEXINsOQmGdVJyqa', 'Admin'),
(45, '201250289', 'Mina', 'Mkhize', '$2y$10$82jNIu6csDwhbfpYXx7jbeU6gwStjkq4fnq7p/Sv6KO2UemXBX1ny', 'Clerk'),
(46, '201350289', 'Asibonge', 'Nqubeko', '$2y$10$ATZGIJ0luxwhlkb8cVvRxOMOIYv6hwemRrgSGXlsrLLCRxq3iDI3G', 'Clerk');

-- --------------------------------------------------------

--
-- Table structure for table `users_update`
--

CREATE TABLE `users_update` (
  `update_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `user_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_update`
--

INSERT INTO `users_update` (`update_id`, `name`, `email`, `status`, `user_id`) VALUES
(1, 'Mina', 'nmkka@gmail.com', 'active', 2),
(2, '', 'mn@kl.com', 'active', NULL),
(3, '', 'mn@gmail.como', 'active', NULL),
(4, '', 'mn@gmail.com5', 'active', NULL),
(5, '', 'mn@gmail.com53', 'active', 14);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`resource_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `username` (`employee_no`);

--
-- Indexes for table `users_update`
--
ALTER TABLE `users_update`
  ADD PRIMARY KEY (`update_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users_update`
--
ALTER TABLE `users_update`
  MODIFY `update_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_update`
--
ALTER TABLE `users_update`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
