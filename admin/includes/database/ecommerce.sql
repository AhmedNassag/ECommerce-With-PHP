-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2020 at 01:06 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `visibility` tinyint(4) NOT NULL DEFAULT '0',
  `allow_comments` tinyint(4) NOT NULL DEFAULT '0',
  `allow_ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `parent`, `ordering`, `visibility`, `allow_comments`, `allow_ads`) VALUES
(1, 'Shorts', '', 0, 1, 1, 1, 1),
(2, 'Shoes', '', 0, 2, 0, 1, 0),
(3, 'Hand Made', '', 0, 3, 0, 0, 0),
(4, 'Boxes', 'Boxes Hand Made', 3, 5, 0, 0, 0),
(5, 'games', 'hand made games', 3, 4, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(1, 'awel comment', 1, '2020-05-14', 4, 3),
(2, 'tani comment', 1, '2020-05-14', 4, 2),
(20, 'tani comment', 0, '2020-05-14', 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(255) NOT NULL,
  `add_date` date NOT NULL,
  `country_made` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `rating` smallint(6) NOT NULL,
  `approve` tinyint(4) NOT NULL DEFAULT '0',
  `cat_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `name`, `description`, `price`, `add_date`, `country_made`, `image`, `status`, `rating`, `approve`, `cat_id`, `member_id`, `tags`) VALUES
(1, 'Polo', 'original type', '100$', '2020-04-28', 'France', '', '1', 0, 1, 1, 2, ''),
(2, 'Lacot', 'T-Shirt', '200$', '2020-04-28', 'Italy', '', '1', 0, 1, 1, 2, ''),
(3, 'X-Box Game', 'New Games For X-Box', '120', '2020-05-13', 'America', '', '1', 0, 1, 3, 2, ''),
(4, 'X-Box Game', 'New Games For X-Box', '120', '2020-05-13', 'America', '', '1', 0, 1, 3, 2, ''),
(5, 'not approved item', 'test function', '100', '2020-05-15', 'Egypt', '', '2', 0, 1, 1, 2, 'Discount');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `groupId` int(11) NOT NULL DEFAULT '0',
  `trustStatus` int(11) NOT NULL DEFAULT '0',
  `regStatus` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `password`, `email`, `fullName`, `groupId`, `trustStatus`, `regStatus`, `date`, `avatar`) VALUES
(1, 'ahmednassag', '5471976d6d609a263558a933a495ccdf67386e14', 'ahmednassag@gmail.com', 'Ahmed Nabil', 1, 0, 1, '1993-11-20', ''),
(2, 'marwaahmed', '2c511b27b78ef7244312db7ac221cb8d0d1788e4', 'marwaahmed@gmail.com', 'Marwa Ahmed', 0, 0, 1, '1993-08-01', ''),
(3, 'esraatarek', '011c945f30ce2cbafc452f39840f025693339c42', 'esraatarek@gmail.com', 'Esraa Tarek', 0, 0, 1, '2020-04-24', ''),
(4, 'AbouTrika', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'aboutrika@heart.com', 'Abou Trika', 0, 0, 1, '2020-05-23', ''),
(5, 'hvjhj bkjb', '7c9c63e2667ddbfdf562aab34c8436800be5dbc8', 'kjbjkb@vjhb.vhvh', 'nklnlkn;', 0, 0, 1, '2020-05-23', ''),
(6, 'hv  hnm', '51688f34201c49a9559a4b21aab957f29c9568d7', 'kjbjkb@vjhb.vhvh', 'nklnlkn;', 0, 0, 1, '2020-05-23', ''),
(7, 'Ahly FC', '011c945f30ce2cbafc452f39840f025693339c42', 'ahly@fc.com', 'Ahly Football Club', 0, 0, 1, '2020-05-23', '38874_Ahly Logo.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `item_comment` (`item_id`),
  ADD KEY `user_comment` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `member_1` (`member_id`),
  ADD KEY `category_1` (`cat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `item_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comment` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `category_1` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`member_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
