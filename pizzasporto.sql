-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 04, 2021 at 04:37 PM
-- Server version: 5.7.29-0ubuntu0.18.04.1-log
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pizzasporto`
--
DROP DATABASE IF EXISTS `pizzasporto`;
CREATE DATABASE IF NOT EXISTS `pizzasporto` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pizzasporto`;

-- --------------------------------------------------------

--
-- Table structure for table `pizze`
--
-- Creation: Jun 04, 2021 at 04:22 PM
-- Last update: Jun 04, 2021 at 04:22 PM
--

DROP TABLE IF EXISTS `pizze`;
CREATE TABLE `pizze` (
  `id` int(10) UNSIGNED NOT NULL,
  `ingredienti` varchar(60) NOT NULL,
  `nome` varchar(32) NOT NULL,
  `tipo` enum('veggy','vegan') DEFAULT NULL,
  `prezzo` int(10) UNSIGNED NOT NULL,
  `qty` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Lista e descrizione delle pizze';

--
-- Dumping data for table `pizze`
--

INSERT INTO `pizze` (`id`, `ingredienti`, `nome`, `tipo`, `prezzo`, `qty`) VALUES
(1, 'mozzarella, polpa di pomodoro, basilico', 'Margherita', 'veggy', 650, 100),
(2, 'mozzarella di bufala, polpa di pomodoro, basilico', 'Bufala', 'veggy', 1000, 30),
(3, 'polpa di pomodoro, aglio, origano', 'Marinara', 'veggy', 550, 40),
(4, 'pomodoro, mozzarella, acciughe', 'Romana', NULL, 750, 30),
(5, 'pomodoro, mozzarella, formaggi misti', 'Quattro Formaggi', 'veggy', 850, 60),
(6, 'crema di zucca, insalata di spinacino, funghi e cipolla', 'Zucchetta', 'vegan', 950, 13),
(7, 'crema di cipolle, ceci, scarola e alga wakame', 'Wakamolle', 'vegan', 900, 8),
(8, 'pomodoro, mozzarella, wurstel', 'Viennese', NULL, 1300, 24);

-- --------------------------------------------------------

--
-- Table structure for table `utenti`
--
-- Creation: Jun 04, 2021 at 04:19 PM
-- Last update: Jun 04, 2021 at 04:19 PM
--

DROP TABLE IF EXISTS `utenti`;
CREATE TABLE `utenti` (
  `nome` varchar(25) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `data` date DEFAULT NULL,
  `indirizzo` varchar(50) NOT NULL,
  `username` varchar(8) NOT NULL,
  `pwd` varchar(12) NOT NULL,
  `credito` smallint(5) UNSIGNED NOT NULL,
  `gestore` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utenti`
--

INSERT INTO `utenti` (`nome`, `cognome`, `data`, `indirizzo`, `username`, `pwd`, `credito`, `gestore`) VALUES
('Corrado Augusto', 'Calia', '2001-01-01', 'Piazza Giovanni dalle Bande Nere 177', 'coral3', '!!Pwd123!!', 18000, 0),
('Maurizio', 'Murgia', '1987-07-30', 'Vicolo Corto 1', 'maumu1', 'Vegan,Pi,314', 65535, 1),
('Maria', 'De Magistris', '2009-10-29', 'Corso Duca degli Abruzzi 24', 'mdb', '20MySQL??', 65535, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pizze`
--
ALTER TABLE `pizze`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pizze`
--
ALTER TABLE `pizze`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Permessi DB user: uWeak; pwd: posso_leggere? (solo SELECT)
--

GRANT USAGE ON `pizzasporto`.* TO 'uWeak'@'%' IDENTIFIED BY PASSWORD '*BB4DF10CAFBE8E060CB11B1BAEA48369CEDCAF6C';
GRANT SELECT ON `pizzasporto`.* TO 'uWeak'@'%';


--
-- Permessi DB user: uStrong; pwd: SuperPippo!!! (solo SELECT, INSERT, UPDATE)
--

GRANT USAGE ON `pizzasporto`.* TO 'uStrong'@'%' IDENTIFIED BY PASSWORD '*400BF58DFE90766AF20296B3D89A670FC66BEAEC';
GRANT SELECT, INSERT, UPDATE ON `pizzasporto`.* TO 'uStrong'@'%';
