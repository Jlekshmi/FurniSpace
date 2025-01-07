-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2024 at 07:03 PM
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
-- Database: `db_furnispace`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_furniture`
--

CREATE TABLE `tbl_furniture` (
  `furniture_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `furniture_name` varchar(255) NOT NULL,
  `furniture_description` varchar(255) NOT NULL,
  `furniture_quantity_available` varchar(20) NOT NULL,
  `furniture_price` varchar(20) NOT NULL,
  `furniture_image` varchar(255) NOT NULL,
  `furniture_added_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_furniture`
--

INSERT INTO `tbl_furniture` (`furniture_id`, `user_id`, `furniture_name`, `furniture_description`, `furniture_quantity_available`, `furniture_price`, `furniture_image`, `furniture_added_by`) VALUES
(4, 1, 'Skiftebo dark gray and red', 'The sofa-bed has a compact design that allows you to fit both an extra seat and overnight guests in a small area.\r\n\r\nThe seat cushions made of pocket springs high-resilience foam and polyester fibre mould to your body and provide firm support and comforta', '600', '1300.88', './assets/images/image-upload/sofa.jpg', 'Jayalekshmi Vrindakumari Jayachandran'),
(8, 3, 'Jeanette Dining Table', 'With its fashion-forward take on farmhouse styling the Jeanette dining room table is a feast for the senses. A dry vintage black finish infuses the table with wonderfully weathered charm. The tables clean-lined Parsons styling is a timeless classic choice', '856', '799.99', './assets/images/image-upload/harry-dining-1.jpg', 'Harry Potter'),
(9, 2, 'Neilsville Full Platform Bed', 'Part beach chic part urban hip the Neilsville full platform bed is everything you dreamed of at a comfortably cool price. The butcher block whitewash finish over replicated pine grain lends a richly relaxed aesthetic that suits your sensibility. What an e', '561', '799', './assets/images/image-upload/bed.jpg', 'Lekshmi Jayachandran');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_firstname` varchar(255) NOT NULL,
  `user_lastname` varchar(255) NOT NULL,
  `user_gender` varchar(10) NOT NULL,
  `user_dob` varchar(10) NOT NULL,
  `user_address` varchar(1000) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_firstname`, `user_lastname`, `user_gender`, `user_dob`, `user_address`, `user_email`, `user_phone`, `user_password`) VALUES
(1, 'Jayalekshmi', 'Vrindakumari Jayachandran', 'Female', '1995-05-07', '1425 blockline rd,Kitchener,ON,N2C0B9,', 'jvrindakumarija1620@conestogac.on.ca', '2345677888', 'Passw0rd!'),
(2, 'Lekshmi', 'Jayachandran', 'Female', '1995-05-07', '1425 blockline rd,Kitchener,ON,N2C0B9,', 'jlekshmi@gmail.com', '2345677888', 'Passw0rd!'),
(3, 'Harry', 'Potter', 'Male', '2006-06-12', 'hogwarts,school,AB,a1a1a1,', 'harry@gmail.com', '9876543210', 'Harrypotter@1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_furniture`
--
ALTER TABLE `tbl_furniture`
  ADD PRIMARY KEY (`furniture_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_furniture`
--
ALTER TABLE `tbl_furniture`
  MODIFY `furniture_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_furniture`
--
ALTER TABLE `tbl_furniture`
  ADD CONSTRAINT `tbl_furniture_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
