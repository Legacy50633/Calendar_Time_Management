-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2024 at 01:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timebase_sys`
--

-- --------------------------------------------------------

--
-- Table structure for table `gpio_setting`
--

CREATE TABLE `gpio_setting` (
  `ID` int(11) NOT NULL,
  `start_audio` varchar(150) NOT NULL,
  `end_audio` varchar(150) NOT NULL,
  `button_status` varchar(10) NOT NULL,
  `time_interval` int(11) NOT NULL,
  `selftest` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gpio_setting`
--

INSERT INTO `gpio_setting` (`ID`, `start_audio`, `end_audio`, `button_status`, `time_interval`, `selftest`) VALUES
(1, 'audioBell/6662f1217afd5.mp3', 'audioBell/6662f1217afe6.mp3', '0', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `taskmanager`
--

CREATE TABLE `taskmanager` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `message` varchar(250) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `notallowed` varchar(150) NOT NULL,
  `timing` time NOT NULL,
  `colour` varchar(30) NOT NULL,
  `days` varchar(150) NOT NULL,
  `audio` varchar(250) NOT NULL,
  `audioname` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gpio_setting`
--
ALTER TABLE `gpio_setting`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `taskmanager`
--
ALTER TABLE `taskmanager`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gpio_setting`
--
ALTER TABLE `gpio_setting`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `taskmanager`
--
ALTER TABLE `taskmanager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2204;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
