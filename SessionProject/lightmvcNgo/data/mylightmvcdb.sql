-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2019 at 09:15 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mylightmvcdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `firstname`, `lastname`) VALUES
(1, 'Paul', 'George'),
(2, 'Jill', 'Lewis'),
(3, 'Jack', 'Brown'),
(4, 'Bill', 'Wright'),
(5, 'John', 'Bernstein');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_date` varchar(32) NOT NULL,
  `order_status` varchar(16) NOT NULL,
  `amount` int(11) NOT NULL,
  `description` text NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_date`, `order_status`, `amount`, `description`, `customer_id`) VALUES
(1, '1520308800', 'open', 560, 'Coffee Table Books', 4),
(2, '1520222400', 'open', 9800, 'JavaScript Books', 3),
(3, '1520136000', 'complete', 300, 'Web Development Books', 2),
(4, '1520136000', 'invoiced', 500, 'PHP Books', 5),
(5, '1520308800', 'open', 50, 'Newspapers', 3),
(6, '1520308800', 'held', 300, 'Candy', 3),
(7, '1520222400', 'invoiced', 1200, 'Smart Phones', 5);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` varchar(100) NOT NULL,
  `image` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`) VALUES
(1, 'Apple pie', '14.99', '16 inch apple pie', 'apple_pie.png'),
(2, 'pear pie', '1.99', '16 inch pear pie', 'pear_pie.png'),
(3, 'Chocolate pie', '17.99', '18 inch chocolate pie', 'chocolate_pie.png'),
(4, 'Lemon pie', '15.00', '16 inch lemon pie', 'lemon_pie.png'),
(5, 'jardiniere', '25.00', '20 inch jardienere pie', 'jardiniere_pie.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
