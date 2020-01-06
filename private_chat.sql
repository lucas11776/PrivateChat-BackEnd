-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2020 at 03:33 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `private_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `user_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_seen` int(11) NOT NULL,
  `profile_picture` text NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_valid` int(1) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`user_id`, `created`, `last_seen`, `profile_picture`, `username`, `email`, `email_valid`, `password`) VALUES
(1, '2019-12-10 05:35:19', 1577816863, 'http://localhost:81/uploads/profile/PROFILE-5e0af3e414e0d.png', 'themba', 'thembangubeni04@gmail.com', 0, 'ae9da09ac085e533ddb233889a1fc9b7e9ad0c5fc4635056d7b192072446816ba9656b3742c4943095ebfa6e6d03fdd41ccfea5ab810a7a90d51427338b2f41cgar4EpPS1+zd4wLOyniyiR4IiScrY3Hy4OZbL1MLRsQ='),
(2, '2019-12-10 06:42:13', 1575660605, 'http://localhost:81/assets/defualt/profile_picture.png', 'lucas', 'lucas@gmail.com', 0, '0923033fe28268fab4ba69193f7f57adcf91c392f087e1c9b2684f4d944cced23d41c034f9d3e8a7b7321a6e53c282736a91b585b919e797df850e940923ecc8xlNFhnXx/tVdqraqFCsY6HSUKAukgd83S/s6IUoBetE='),
(3, '2019-12-10 06:42:38', 1577816847, 'http://localhost:81/uploads/profile/PROFILE-5e0b6cfeecb2f.png', 'musa', 'musa@gmail.com', 0, 'c3c30427599da9549d41079bae9625d6a097af8c9de430fe3bc0865ed5def2720335d9610bcf77e3d14542a3dacbbd204194bc0ef5fc3e122108a2ec9a218b2chQk+vycZFMHd2S95NRvyCQHFWjSvomhXrk2uVxJi/uA='),
(4, '2019-12-10 06:43:33', 1576767099, 'http://localhost:81/assets/defualt/profile_picture.png', 'peter', 'peter@gmail.com', 0, '91587a3b6add36ccc1b280dc9e7235207c6006c6368166c634d98cafa71ffac29f0cffbb22f64045ab7204d29143a801a0e335f9a47a5e9e364931137033fbc2OEp6RUcyAC8GTlDnLW+vMOXXzwttu4bOuZI0dTEn+Pg='),
(5, '2019-12-10 06:45:13', 1577814222, 'http://localhost:81/uploads/profile/PROFILE-5e0b8290db018.jpg', 'ntombi', 'ntombi@gmail.com', 0, 'c8e0b9492c866f3ee4655310fded27d2810db96be31bd720ad86c752cb09f87eba3b8137251a8143eca6e5f337009faf3dabdb256fa049a8d761d72b1339c20feBgp9smXBbtsNRKOq3gRiF7r/PosZJTV0MBJqRgc42c='),
(6, '2019-12-10 06:50:05', 1575960605, 'http://localhost:81/assets/defualt/profile_picture.png', 'james', 'james@gmail.com', 0, '6c3e556f6372deffed8d4906c0b2221f3cbfad172b8e6994bb2a3d51d811f3dc1cc602aec6c129b794c56bf632c55fdff6ea9e40e4fc300c1742250cd9bac32bccQP2WXrGnhCqRLnP4GF7SZ1/TGUDdu9oNtC3cnhFRQ='),
(7, '2019-12-11 20:36:58', 1577466301, 'http://localhost:81/assets/defualt/profile_picture.png', 'sbu', 'sbu@gmail.com', 0, 'd6f6674905afe1855ea405db7f431edf0d1c2675c4f9a5e4ceea85d593828ba79b4bb361c50b25fa1fc11f13783e95f7838f5b6fc146218258c54dddfe91b6fdhlhuMgOuYgf7KODbw+9xJN/DvFzVoKokqRt3KjjmK7w='),
(8, '2019-12-22 05:31:56', 1576995377, 'http://localhost:81/assets/defualt/profile_picture.png', 'victor', 'victor@gmail.com', 0, '567cf38a83b1a0f02196321a88264e415cd5b1cbde6fae5118a4ba29a1b4f24215233f882d8b792227dbbfa54767d6851225000909c95296b9403fe79d8f0a0biFbLtyfAOsNMzULw/7RmvP5P6hlRBGDfKXvT7d0dHNM='),
(9, '2019-12-23 17:00:15', 1577369057, 'http://localhost:81/assets/defualt/profile_picture.png', 'nandi', 'nandi@gmail.com', 0, 'fe3714ce2f9b82294cff53d51044efe5fabd048a5b9923b113b99cfd6b224c22e1b3fc6066e190e23fd9e301fb449354d1ce564fabf99a7da151cbf0ead4532cO0LB896yMgMwQRxoxa7ox/VjBGtRKyl+E9ivS6Oehqg=');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen` int(1) NOT NULL,
  `from_user` int(11) NOT NULL,
  `to_user` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`chat_id`, `created`, `seen`, `from_user`, `to_user`, `type`, `content`) VALUES
(189, '2019-12-31 18:08:14', 1, 3, 1, 'text', 'Sending Message.'),
(190, '2019-12-31 18:08:34', 1, 3, 1, 'text', 'Sending second message.'),
(191, '2019-12-31 18:08:46', 1, 3, 1, 'text', 'Message.'),
(192, '2019-12-31 18:17:46', 1, 1, 3, 'text', 'Test Message.');

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `friends_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `from_user` int(11) NOT NULL,
  `to_user` int(11) NOT NULL,
  `block_from_user` int(1) NOT NULL,
  `block_to_user` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`friends_id`, `created`, `from_user`, `to_user`, `block_from_user`, `block_to_user`) VALUES
(3, '2019-12-14 14:55:00', 3, 1, 0, 0),
(9, '2019-12-16 10:01:40', 5, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `friends_requests`
--

CREATE TABLE `friends_requests` (
  `friend_request_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `from_user` int(11) NOT NULL,
  `to_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`friends_id`);

--
-- Indexes for table `friends_requests`
--
ALTER TABLE `friends_requests`
  ADD PRIMARY KEY (`friend_request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `friends_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `friends_requests`
--
ALTER TABLE `friends_requests`
  MODIFY `friend_request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
