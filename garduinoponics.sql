-- phpMyAdmin SQL Dump
-- version 4.2.7
-- http://www.phpmyadmin.net
--
-- Host: ---
-- Generation Time: Aug 01, 2014 at 04:16 PM
-- Server version: 5.5.38-MariaDB
-- PHP Version: 5.5.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `garduinoponics`
--

-- --------------------------------------------------------

--
-- Table structure for table `day`
--

CREATE TABLE IF NOT EXISTS `day` (
`id_day` int(11) NOT NULL,
  `date` date NOT NULL,
  `sunrise` datetime DEFAULT NULL,
  `sunset` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `hour`
--

CREATE TABLE IF NOT EXISTS `hour` (
`id_hour` int(11) NOT NULL,
  `id_day` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datetime` datetime NOT NULL,
  `room_temperature` float(3,1) NOT NULL,
  `tank_temperature` float(3,1) NOT NULL,
  `ph` float(1,1) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `live`
--

CREATE TABLE IF NOT EXISTS `live` (
`id_live` int(11) NOT NULL,
  `last_communication` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
`id_log` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` varchar(100) NOT NULL,
  `message` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quarter_hour`
--

CREATE TABLE IF NOT EXISTS `quarter_hour` (
`id_quarter_hour` int(11) NOT NULL,
  `id_day` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datetime` datetime NOT NULL,
  `sunlight` int(4) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `day`
--
ALTER TABLE `day`
 ADD PRIMARY KEY (`id_day`);

--
-- Indexes for table `hour`
--
ALTER TABLE `hour`
 ADD PRIMARY KEY (`id_hour`), ADD KEY `id_day` (`id_day`);

--
-- Indexes for table `live`
--
ALTER TABLE `live`
 ADD PRIMARY KEY (`id_live`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
 ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `quarter_hour`
--
ALTER TABLE `quarter_hour`
 ADD PRIMARY KEY (`id_quarter_hour`), ADD KEY `id_day` (`id_day`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `day`
--
ALTER TABLE `day`
MODIFY `id_day` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `hour`
--
ALTER TABLE `hour`
MODIFY `id_hour` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `live`
--
ALTER TABLE `live`
MODIFY `id_live` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quarter_hour`
--
ALTER TABLE `quarter_hour`
MODIFY `id_quarter_hour` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `hour`
--
ALTER TABLE `hour`
ADD CONSTRAINT `hour_ibfk_1` FOREIGN KEY (`id_day`) REFERENCES `day` (`id_day`);

--
-- Constraints for table `quarter_hour`
--
ALTER TABLE `quarter_hour`
ADD CONSTRAINT `quarter_hour_ibfk_1` FOREIGN KEY (`id_day`) REFERENCES `day` (`id_day`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
