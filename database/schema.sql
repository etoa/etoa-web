-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2022 at 11:09 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `etoa-web`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `config_id` int(11) NOT NULL,
  `config_name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `config_value` text COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `redirects`
--

CREATE TABLE `redirects` (
  `id` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rounds`
--

CREATE TABLE `rounds` (
  `round_id` int(11) NOT NULL,
  `round_name` varchar(255) NOT NULL,
  `round_url` varchar(255) NOT NULL,
  `round_active` int(1) NOT NULL DEFAULT 1,
  `round_startdate` int(30) NOT NULL DEFAULT 0,
  `round_status_online` int(1) UNSIGNED NOT NULL DEFAULT 1,
  `round_status_ping` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `round_status_checked` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `round_status_changed` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `round_status_ip` varchar(15) NOT NULL DEFAULT '127.0.0.1',
  `round_status_port` int(5) UNSIGNED NOT NULL DEFAULT 80,
  `round_status_error` varchar(250) NOT NULL DEFAULT ' ',
  `round_alturl` varchar(250) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `texts`
--

CREATE TABLE `texts` (
  `text_id` int(11) NOT NULL,
  `text_keyword` varchar(255) NOT NULL,
  `text_text` text NOT NULL,
  `text_last_changes` int(50) NOT NULL DEFAULT 0,
  `text_name` varchar(50) NOT NULL,
  `text_author_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `redirects`
--
ALTER TABLE `redirects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rounds`
--
ALTER TABLE `rounds`
  ADD PRIMARY KEY (`round_id`),
  ADD KEY `round_name` (`round_name`);

--
-- Indexes for table `texts`
--
ALTER TABLE `texts`
  ADD PRIMARY KEY (`text_id`),
  ADD UNIQUE KEY `text_keyword` (`text_keyword`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `redirects`
--
ALTER TABLE `redirects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rounds`
--
ALTER TABLE `rounds`
  MODIFY `round_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `texts`
--
ALTER TABLE `texts`
  MODIFY `text_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
