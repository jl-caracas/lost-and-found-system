-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2026 at 04:57 PM
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
-- Database: `lost_and_found_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `module`, `ip_address`, `created_at`) VALUES
(1, 1, 'Added category: Electronics', 'categories', '::1', '2026-06-17 16:20:39'),
(2, 1, 'Added category: Electronic', 'categories', '::1', '2026-06-17 16:22:07'),
(3, 1, 'Updated category: Personal Items', 'categories', '::1', '2026-06-17 16:30:26'),
(4, 1, 'Updated category: Electronic', 'categories', '::1', '2026-06-17 16:30:48'),
(5, 1, 'Added category: Academic Items', 'categories', '::1', '2026-06-17 16:31:11'),
(6, 1, 'Added item: cat', 'items', '::1', '2026-06-17 16:45:37'),
(7, 1, 'Updated item: Test Item', 'items', '::1', '2026-06-17 16:46:16'),
(8, 1, 'Added item: Bottle', 'items', '::1', '2026-06-17 16:47:05'),
(9, 1, 'Added item: smart watch', 'items', '::1', '2026-06-17 16:48:14'),
(10, 1, 'Added user: john123', 'users', '::1', '2026-06-17 16:53:37'),
(11, 1, 'User logged out: admin', 'auth', '::1', '2026-06-17 16:53:46'),
(12, 2, 'User logged in', 'auth', '::1', '2026-06-17 16:53:54'),
(13, 2, 'Claim submitted for item ID: 3 by john', 'claims', '::1', '2026-06-17 16:54:41'),
(14, 2, 'User logged out: john123', 'auth', '::1', '2026-06-17 16:54:52'),
(15, 1, 'User logged in', 'auth', '::1', '2026-06-17 16:55:00'),
(16, 1, 'User logged in', 'auth', '::1', '2026-06-18 15:49:17'),
(17, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 15:51:34'),
(18, 2, 'User logged in', 'auth', '::1', '2026-06-18 15:51:41'),
(19, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 15:53:09'),
(20, 1, 'User logged in', 'auth', '::1', '2026-06-18 15:53:13'),
(21, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 15:54:00'),
(22, 2, 'User logged in', 'auth', '::1', '2026-06-18 15:54:10'),
(23, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 15:59:06'),
(24, 2, 'User logged in', 'auth', '::1', '2026-06-18 16:01:51'),
(25, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 16:03:08'),
(26, 3, 'User logged in', 'auth', '::1', '2026-06-18 16:03:13'),
(27, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-18 16:11:58'),
(28, 1, 'User logged in', 'auth', '::1', '2026-06-18 16:12:02'),
(29, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 16:23:44'),
(30, 3, 'User logged in', 'auth', '::1', '2026-06-18 16:23:49'),
(31, 3, 'Added item: GoooonMobile', 'items', '::1', '2026-06-18 16:28:58'),
(32, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-18 16:37:47'),
(33, 4, 'User logged in', 'auth', '::1', '2026-06-18 16:37:55'),
(34, 4, 'User logged out: yuann123', 'auth', '::1', '2026-06-18 16:54:33'),
(35, 2, 'User logged in', 'auth', '::1', '2026-06-18 16:54:42'),
(36, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 17:10:14'),
(37, 2, 'User logged in', 'auth', '::1', '2026-06-18 17:10:22'),
(38, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 17:10:26'),
(39, 1, 'User logged in', 'auth', '::1', '2026-06-18 17:15:38'),
(40, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 17:30:59'),
(41, 2, 'User logged in', 'auth', '::1', '2026-06-18 17:31:22'),
(42, 2, 'Updated item: Laptop', 'items', '::1', '2026-06-18 17:35:48'),
(43, 2, 'Updated item: Found Notebook', 'items', '::1', '2026-06-18 17:36:14'),
(44, 2, 'Updated item: Poster of a CyberDroyd', 'items', '::1', '2026-06-18 17:38:20'),
(45, 2, 'Added item: 3D Printed Prime Vandal Gun', 'items', '::1', '2026-06-18 17:45:14'),
(46, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 17:52:53'),
(47, 1, 'User logged in', 'auth', '::1', '2026-06-18 17:52:57'),
(48, 1, 'Added category: Perishable Items', 'categories', '::1', '2026-06-18 17:54:22'),
(49, 1, 'Added category: Pets', 'categories', '::1', '2026-06-18 17:55:47'),
(50, 1, 'Updated category: Perishable Items', 'categories', '::1', '2026-06-18 17:57:31'),
(51, 1, 'Updated category: Perishable Items', 'categories', '::1', '2026-06-18 17:58:47'),
(52, 1, 'Updated category: Pets', 'categories', '::1', '2026-06-18 17:59:17'),
(53, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 18:26:37'),
(54, 2, 'User logged in', 'auth', '::1', '2026-06-18 18:27:10'),
(55, 2, 'Updated item: Netanyahu Plushie', 'items', '::1', '2026-06-18 18:29:29'),
(56, 2, 'Updated item: White Cat', 'items', '::1', '2026-06-18 18:35:11'),
(57, 2, 'Updated item: White Cat', 'items', '::1', '2026-06-18 18:36:08'),
(58, 2, 'Updated item: White Cat', 'items', '::1', '2026-06-18 18:37:37'),
(59, 2, 'Updated item: Netanyahu Plushie', 'items', '::1', '2026-06-18 18:38:41'),
(60, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 18:43:06'),
(61, 2, 'User logged in', 'auth', '::1', '2026-06-18 18:55:49'),
(62, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 18:57:11'),
(63, 2, 'User logged in', 'auth', '::1', '2026-06-18 18:58:14'),
(64, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 19:04:00'),
(65, 2, 'User logged in', 'auth', '::1', '2026-06-18 19:04:23'),
(66, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 19:47:16'),
(67, 1, 'User logged in', 'auth', '::1', '2026-06-18 19:47:21'),
(68, 1, 'Updated item: Missing Jacket', 'items', '::1', '2026-06-18 20:18:50'),
(69, 1, 'Updated item: Missing Jacket', 'items', '::1', '2026-06-18 20:19:12'),
(70, 1, 'Updated item: Missing Jacket', 'items', '::1', '2026-06-18 20:21:53'),
(71, 1, 'Updated item: Phone', 'items', '::1', '2026-06-18 20:23:23'),
(72, 1, 'Updated item: Research Prototype of the NChain AI', 'items', '::1', '2026-06-18 20:25:59'),
(73, 1, 'Updated item: Research Prototype of the NChain AI', 'items', '::1', '2026-06-18 20:28:19'),
(74, 1, 'Updated item: Lost Laptop', 'items', '::1', '2026-06-18 20:29:48'),
(75, 1, 'Updated item: Lost Car', 'items', '::1', '2026-06-18 20:30:56'),
(76, 1, 'Updated item: Mouse 1ms refreshrate', 'items', '::1', '2026-06-18 20:33:02'),
(77, 1, 'Updated item: Mouse 1ms refreshrate', 'items', '::1', '2026-06-18 20:33:18'),
(78, 1, 'Updated item: Quarter Zip Jacket of the EPS', 'items', '::1', '2026-06-18 20:35:42'),
(79, 1, 'Updated item: Cat Painting', 'items', '::1', '2026-06-18 20:36:56'),
(80, 1, 'Deleted item: Found Keys', 'items', '::1', '2026-06-18 20:37:28'),
(81, 1, 'Updated item: Lost Smartwatch', 'items', '::1', '2026-06-18 20:38:10'),
(82, 1, 'Updated item: Aquaflask Bottle', 'items', '::1', '2026-06-18 20:39:30'),
(83, 1, 'Updated item: Lost Car', 'items', '::1', '2026-06-18 20:41:33'),
(84, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 20:42:27'),
(85, 4, 'User logged in', 'auth', '::1', '2026-06-18 20:42:34'),
(86, 4, 'Added item: Chicken Nuggets', 'items', '::1', '2026-06-18 20:58:10'),
(87, 4, 'User logged out: yuann123', 'auth', '::1', '2026-06-18 21:08:24'),
(88, 5, 'User logged in', 'auth', '::1', '2026-06-18 21:08:48'),
(89, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 21:10:27'),
(90, 3, 'User logged in', 'auth', '::1', '2026-06-18 21:10:33'),
(91, 3, 'Marked issue report #1 as fixed', 'issue_reports', '::1', '2026-06-18 21:16:18'),
(92, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-18 21:31:58'),
(93, 5, 'User logged in', 'auth', '::1', '2026-06-18 21:32:07'),
(94, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 21:37:11'),
(95, 4, 'User logged in', 'auth', '::1', '2026-06-18 21:37:16'),
(96, 4, 'User logged out: yuann123', 'auth', '::1', '2026-06-18 21:38:31'),
(97, 4, 'User logged in', 'auth', '::1', '2026-06-18 21:41:30'),
(98, 4, 'User logged out: yuann123', 'auth', '::1', '2026-06-18 21:42:32'),
(99, 4, 'User logged in', 'auth', '::1', '2026-06-18 21:45:26'),
(100, 4, 'User logged out: yuann123', 'auth', '::1', '2026-06-18 21:46:01'),
(101, 6, 'User logged in', 'auth', '::1', '2026-06-18 21:46:14'),
(102, 6, 'Added item: Legoat Jersey', 'items', '::1', '2026-06-18 21:50:14'),
(103, 6, 'Claim submitted for item ID: 6 by Test User Two', 'claims', '::1', '2026-06-18 21:52:23'),
(104, 6, 'User logged out: testuser2', 'auth', '::1', '2026-06-18 21:52:34'),
(105, 2, 'User logged in', 'auth', '::1', '2026-06-18 21:52:48'),
(106, 2, 'Updated claim status to \'approved\' for claim ID: 2', 'claims', '::1', '2026-06-18 21:53:17'),
(107, 2, 'User logged out: john123', 'auth', '::1', '2026-06-18 21:55:34'),
(108, 1, 'User logged in', 'auth', '::1', '2026-06-18 21:55:39'),
(109, 1, 'Updated claim status to \'approved\' for claim ID: 2', 'claims', '::1', '2026-06-18 21:56:05'),
(110, 1, 'Updated claim status to \'approved\' for claim ID: 2', 'claims', '::1', '2026-06-18 22:10:27'),
(111, 1, 'Updated claim status to \'approved\' for claim ID: 2', 'claims', '::1', '2026-06-18 22:34:39'),
(112, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 22:41:17'),
(113, 5, 'User logged in', 'auth', '::1', '2026-06-18 22:41:25'),
(114, 5, 'Sent message on item ID: 21', 'messages', '::1', '2026-06-18 22:42:19'),
(115, 5, 'Sent message on item ID: 21', 'messages', '::1', '2026-06-18 22:42:24'),
(116, 5, 'Sent message on item ID: 21', 'messages', '::1', '2026-06-18 22:42:28'),
(117, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 22:42:32'),
(118, 6, 'User logged in', 'auth', '::1', '2026-06-18 22:42:48'),
(119, 6, 'Sent message on item ID: 21', 'messages', '::1', '2026-06-18 22:43:42'),
(120, 6, 'Marked item as claimed: Legoat Jersey', 'items', '::1', '2026-06-18 22:44:04'),
(121, 6, 'Updated item: Legoat Jersey', 'items', '::1', '2026-06-18 22:44:27'),
(122, 6, 'Marked item as claimed: Legoat Jersey', 'items', '::1', '2026-06-18 22:44:35'),
(123, 6, 'User logged out: testuser2', 'auth', '::1', '2026-06-18 22:44:48'),
(124, 1, 'User logged in', 'auth', '::1', '2026-06-18 22:44:56'),
(125, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 22:49:38'),
(126, 5, 'User logged in', 'auth', '::1', '2026-06-18 22:49:46'),
(127, 5, 'Sent message on item ID: 5', 'messages', '::1', '2026-06-18 22:51:01'),
(128, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 22:51:03'),
(129, 3, 'User logged in', 'auth', '::1', '2026-06-18 22:51:10'),
(130, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-18 22:52:28'),
(131, 5, 'User logged in', 'auth', '::1', '2026-06-18 22:52:39'),
(132, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 22:55:12'),
(133, 4, 'User logged in', 'auth', '::1', '2026-06-18 22:55:17'),
(134, 4, 'Sent message on item ID: 5', 'messages', '::1', '2026-06-18 22:55:35'),
(135, 4, 'User logged out: yuann123', 'auth', '::1', '2026-06-18 22:55:37'),
(136, 3, 'User logged in', 'auth', '::1', '2026-06-18 22:55:42'),
(137, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-18 22:55:52'),
(138, 5, 'User logged in', 'auth', '::1', '2026-06-18 22:55:58'),
(139, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 22:56:10'),
(140, 3, 'User logged in', 'auth', '::1', '2026-06-18 22:56:14'),
(141, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-18 23:03:09'),
(142, 3, 'User logged in', 'auth', '::1', '2026-06-18 23:03:37'),
(143, 3, 'Marked item as claimed: GoooonMobile', 'items', '::1', '2026-06-18 23:04:28'),
(144, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-18 23:04:38'),
(145, 1, 'User logged in', 'auth', '::1', '2026-06-18 23:04:47'),
(146, 1, 'Updated claim status to \'rejected\' for claim ID: 5', 'claims', '::1', '2026-06-18 23:05:05'),
(147, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 23:11:00'),
(148, 3, 'User logged in', 'auth', '::1', '2026-06-18 23:11:06'),
(149, 3, 'Updated claim status to \'claimed\' for claim ID: 5', 'claims', '::1', '2026-06-18 23:11:18'),
(150, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-18 23:11:48'),
(151, 5, 'User logged in', 'auth', '::1', '2026-06-18 23:11:55'),
(152, 5, 'Added item: Balls', 'items', '::1', '2026-06-18 23:13:45'),
(153, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 23:13:50'),
(154, 6, 'User logged in', 'auth', '::1', '2026-06-18 23:13:56'),
(155, 6, 'Sent message on item ID: 22', 'messages', '::1', '2026-06-18 23:14:13'),
(156, 6, 'User logged out: testuser2', 'auth', '::1', '2026-06-18 23:14:19'),
(157, 5, 'User logged in', 'auth', '::1', '2026-06-18 23:14:28'),
(158, 5, 'Marked item as claimed: Balls', 'items', '::1', '2026-06-18 23:15:05'),
(159, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 23:15:08'),
(160, 1, 'User logged in', 'auth', '::1', '2026-06-18 23:15:13'),
(161, 1, 'Updated claim status to \'claimed\' for claim ID: 6', 'claims', '::1', '2026-06-18 23:15:25'),
(162, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 23:15:32'),
(163, 5, 'User logged in', 'auth', '::1', '2026-06-18 23:15:37'),
(164, 5, 'Updated claim status to \'pending\' for claim ID: 6', 'claims', '::1', '2026-06-18 23:16:01'),
(165, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 23:18:52'),
(166, 1, 'User logged in', 'auth', '::1', '2026-06-18 23:18:59'),
(167, 1, 'Updated claim status to \'claimed\' for claim ID: 6', 'claims', '::1', '2026-06-18 23:19:11'),
(168, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 23:19:13'),
(169, 5, 'User logged in', 'auth', '::1', '2026-06-18 23:19:23'),
(170, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-18 23:26:14'),
(171, 1, 'User logged in', 'auth', '::1', '2026-06-18 23:27:26'),
(172, 1, 'User logged out: admin', 'auth', '::1', '2026-06-18 23:28:10'),
(173, 3, 'User logged in', 'auth', '::1', '2026-06-18 23:28:18'),
(174, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-18 23:28:54'),
(175, 5, 'User logged in', 'auth', '::1', '2026-06-19 12:43:13'),
(176, 5, 'User logged out: testuser1', 'auth', '::1', '2026-06-19 12:43:42'),
(177, 3, 'User logged in', 'auth', '::1', '2026-06-19 12:43:50'),
(178, 3, 'Marked issue report #2 as fixed', 'issue_reports', '::1', '2026-06-19 12:47:04'),
(179, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-19 12:47:16'),
(180, NULL, 'New user registered: yeriel123', 'auth', '::1', '2026-06-19 12:49:11'),
(181, 7, 'User logged in', 'auth', '::1', '2026-06-19 12:49:26'),
(182, 5, 'User logged in', 'auth', '::1', '2026-06-19 13:08:57'),
(183, 5, 'Added item: Lost Wallet Test E2E', 'items', '::1', '2026-06-19 13:08:57'),
(184, 6, 'User logged in', 'auth', '::1', '2026-06-19 13:08:57'),
(185, 5, 'User logged in', 'auth', '::1', '2026-06-19 13:08:57'),
(186, 1, 'User logged in', 'auth', '::1', '2026-06-19 13:08:57'),
(187, 5, 'User logged in', 'auth', '::1', '2026-06-19 13:09:22'),
(188, 5, 'Added item: Lost Wallet Test E2E', 'items', '::1', '2026-06-19 13:09:22'),
(189, 6, 'User logged in', 'auth', '::1', '2026-06-19 13:09:22'),
(190, 6, 'Claim submitted for item ID: 24 by Test User Two', 'claims', '::1', '2026-06-19 13:09:22'),
(191, 5, 'User logged in', 'auth', '::1', '2026-06-19 13:09:22'),
(192, 1, 'User logged in', 'auth', '::1', '2026-06-19 13:09:22'),
(193, 1, 'Updated claim status to \'approved\' for claim ID: 7', 'claims', '::1', '2026-06-19 13:09:22'),
(194, 7, 'User logged out: yeriel123', 'auth', '::1', '2026-06-19 13:11:41'),
(195, 3, 'User logged in', 'auth', '::1', '2026-06-19 13:13:26'),
(196, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-19 13:13:30'),
(197, 1, 'User logged in', 'auth', '::1', '2026-06-19 13:14:59'),
(198, 1, 'User logged out: admin', 'auth', '::1', '2026-06-19 13:29:45'),
(199, 1, 'User logged in', 'auth', '::1', '2026-06-19 13:29:56'),
(200, 1, 'User logged out: admin', 'auth', '::1', '2026-06-19 13:31:06'),
(201, 3, 'User logged in', 'auth', '::1', '2026-06-19 13:31:10'),
(202, 3, 'User logged out: jhun123', 'auth', '::1', '2026-06-19 13:31:17'),
(203, 4, 'User logged in', 'auth', '::1', '2026-06-19 13:31:23'),
(204, 4, 'User logged out: yuann123', 'auth', '::1', '2026-06-19 14:17:37'),
(205, NULL, 'New user registered: margie123', 'auth', '::1', '2026-06-19 14:19:41'),
(206, 8, 'User logged in', 'auth', '::1', '2026-06-19 14:19:52'),
(207, 8, 'User logged out: margie123', 'auth', '::1', '2026-06-19 14:48:13'),
(208, 1, 'User logged in', 'auth', '::1', '2026-06-19 14:48:26'),
(209, 1, 'Deleted item: Lost Wallet Test E2E', 'items', '::1', '2026-06-19 14:55:31'),
(210, 1, 'Deleted item: Lost Wallet Test E2E', 'items', '::1', '2026-06-19 14:55:46');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Personal Items', 'Wallets, bags, clothing, accessories, jewelry, and other personal belongings.', '2026-06-17 16:20:39'),
(2, 'Electronic', 'Mobile phones, laptops, tablets, chargers, headphones, and other electronic devices.', '2026-06-17 16:22:07'),
(3, 'Academic Items', 'Books, notebooks, pens, uniforms, IDs, and other school-related items.', '2026-06-17 16:31:11'),
(4, 'Perishable Items', 'Foods: meat & seafood, dairy products, fresh fruits, vegetables, and other ready to eat items.', '2026-06-18 17:54:22'),
(5, 'Pets', 'Dogs, Cats, Fish, and other animals that was missing.', '2026-06-18 17:55:47');

-- --------------------------------------------------------

--
-- Table structure for table `claims`
--

CREATE TABLE `claims` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `claimant_name` varchar(100) NOT NULL,
  `claimant_id_type` enum('pup_id','national_id','faculty_id','other') NOT NULL,
  `claimant_id_number` varchar(50) NOT NULL,
  `claimant_contact` varchar(20) DEFAULT NULL,
  `proof_document` varchar(255) DEFAULT NULL,
  `claim_date` date NOT NULL,
  `status` enum('pending','approved','rejected','claimed') DEFAULT 'pending',
  `admin_remarks` text DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `claims`
--

INSERT INTO `claims` (`id`, `item_id`, `claimant_name`, `claimant_id_type`, `claimant_id_number`, `claimant_contact`, `proof_document`, `claim_date`, `status`, `admin_remarks`, `processed_by`, `created_at`, `updated_at`) VALUES
(1, 3, 'john', 'pup_id', '2099-12345', '09876970697', 'assets/uploads/proofs/6a32d151d4559.jpg', '2026-06-17', 'pending', NULL, NULL, '2026-06-17 16:54:41', '2026-06-17 16:54:41'),
(2, 6, 'Test User Two', 'pup_id', 'TEST-00306-TG-0', '09676767676', 'assets/uploads/proofs/6a34689788102.jpg', '2026-06-18', 'approved', 'Nice', 1, '2026-06-18 21:52:23', '2026-06-18 21:56:05'),
(3, 21, 'Test User Two', '', 'SELF-CLAIMED', 'N/A', '', '2026-06-19', 'pending', NULL, NULL, '2026-06-18 22:44:04', '2026-06-18 22:44:04'),
(4, 21, 'Test User Two', '', 'SELF-CLAIMED', 'N/A', '', '2026-06-19', 'pending', NULL, NULL, '2026-06-18 22:44:35', '2026-06-18 22:44:35'),
(5, 5, 'Jhun Francis Talosig', '', 'SELF-CLAIMED', 'N/A', '', '2026-06-19', 'claimed', 'No Proof', 3, '2026-06-18 23:04:28', '2026-06-18 23:11:18'),
(6, 22, 'Test User One', '', 'SELF-CLAIMED', 'N/A', 'assets/uploads/claims/proof_6a347bf932cbb.jpg', '2026-06-19', 'claimed', '', 1, '2026-06-18 23:15:05', '2026-06-18 23:19:11');

-- --------------------------------------------------------

--
-- Table structure for table `issue_reports`
--

CREATE TABLE `issue_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `issue_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','fixed') DEFAULT 'open',
  `resolved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issue_reports`
--

INSERT INTO `issue_reports` (`id`, `user_id`, `issue_type`, `description`, `status`, `resolved_by`, `created_at`, `updated_at`) VALUES
(1, 5, 'Technical Bug', 'The bug on the messages', 'fixed', 3, '2026-06-18 21:10:16', '2026-06-18 21:16:18'),
(2, 5, 'Feature Request', 'the post feature was lagging', 'fixed', 3, '2026-06-19 12:43:36', '2026-06-19 12:47:04');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `reward` varchar(100) DEFAULT NULL,
  `status` enum('lost','found') NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `specific_location` varchar(255) DEFAULT NULL,
  `date_reported` datetime NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `reported_by` int(11) DEFAULT NULL,
  `status_label` enum('open','found_owner','claimed') DEFAULT 'open',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `category_id`, `item_name`, `description`, `reward`, `status`, `location`, `specific_location`, `date_reported`, `photo`, `reported_by`, `status_label`, `latitude`, `longitude`, `created_at`) VALUES
(1, 1, 'Cat Painting', 'Cat Painting of a Cat Painting', '₱10000', 'lost', 'Kalye I. Lopez, Poblacion, Mandaluyong, Eastern Manila District, Metro Manila, 1551, Philippines', 'in the trash', '2026-06-18 00:00:00', 'assets/uploads/items/cat_painting.png', 1, 'open', 14.58632459, 121.02929592, '2026-06-17 16:40:02'),
(2, 1, 'cat', 'white', NULL, 'lost', 'koisk', NULL, '2026-06-11 00:00:00', 'assets/uploads/items/6a32cf317adc9.jpg', 1, 'open', NULL, NULL, '2026-06-17 16:45:37'),
(3, 1, 'Bottle', 'Green', NULL, 'found', 'Building B', NULL, '2026-06-17 00:00:00', 'assets/uploads/items/6a32cf891978c.jpg', 1, 'open', NULL, NULL, '2026-06-17 16:47:05'),
(4, 2, 'smart watch', 'Beige', NULL, 'found', 'Building C', NULL, '2026-06-17 00:00:00', 'assets/uploads/items/6a32cfcdabc95.png', 1, 'open', NULL, NULL, '2026-06-17 16:48:13'),
(5, 2, 'GoooonMobile', 'Black Car', '5000 dollars', 'lost', 'PUP Taguig', 'Parking Lot', '2026-06-17 00:00:00', 'assets/uploads/items/6a341cca68c51.jpg', 3, 'claimed', 14.48813210, 121.05142530, '2026-06-18 16:28:58'),
(6, 2, 'Laptop', 'This item was found at the Gate 1(near Ortigas Avenue highway)on the sidewalk . Please contact if me belongs to you.', NULL, 'found', 'Gate 1', 'sidewalk', '2026-06-17 19:27:00', 'assets/uploads/items/6a32bc7b64148.jpg', 2, 'open', 14.60048510, 121.04926680, '2026-06-18 17:27:19'),
(8, 2, 'Phone', 'This phone was battery drained and we managed to charge it and open it.', NULL, 'found', 'PUP Main Building', 'Canteen', '2026-06-12 19:27:00', 'assets/uploads/items/6a32bf7b6bf1b.jpg', 1, 'open', 14.59714170, 121.01069940, '2026-06-18 17:27:19'),
(9, 2, 'Mouse 1ms refreshrate', 'solid na mouse, ez Radiant', NULL, 'found', '16, Araneta Avenue, Barangay 79, Zone 7, University Hills, District 1, Caloocan, Northern Manila District, Metro Manila, 1400, Philippines', 'just outside', '2026-06-10 19:27:00', 'assets/uploads/items/6a32bf894a5a2.jpg', 1, 'open', 14.65933637, 120.98066747, '2026-06-18 17:27:19'),
(10, 2, 'Lost Car', 'Car was on Fire', NULL, 'found', 'Katipunan Avenue, Loyola Heights, 3rd District, Quezon City, Eastern Manila District, Metro Manila, 1108, Philippines', 'Swimming Pool', '2026-06-16 19:27:00', 'assets/uploads/items/6a32bf96b566d.jpg', 1, 'open', 14.63956237, 121.07830524, '2026-06-18 17:27:19'),
(11, 5, 'White Cat', 'the cat was rescued from an arson attack on Taguig City.', NULL, 'found', 'Singapore Street, AFPEPVHAI, Pinagsama, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1630, Philippines', 'near on the Blk 15 Lot 3', '2026-06-12 19:27:00', 'assets/uploads/items/6a32cf317adc9.jpg', 2, 'open', 14.51686151, 121.05356991, '2026-06-18 17:27:19'),
(12, 1, 'Missing Jacket', 'Meduim Sized Jacket', 'Free Lunch', 'lost', 'Science and Technology Information Institute, Sibol, Purok Uno, Central Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1713, Philippines', 'DOST Library', '2026-06-15 19:27:00', 'assets/uploads/items/6a3453619a036.jpg', 1, 'open', 14.49079554, 121.04972363, '2026-06-18 17:27:19'),
(13, 1, 'Aquaflask Bottle', 'Nawawalang bote', 'Free Fortnite Battlepass', 'lost', 'Skyway, Landcom Village, 508, Santa Mesa, Sixth District, Manila, Capital District, Metro Manila, 1067, Philippines', 'nasa kalsada', '2026-06-12 19:27:00', 'assets/uploads/items/6a32cf891978c.jpg', 3, 'open', 14.59625056, 121.02527797, '2026-06-18 17:27:19'),
(14, 2, 'Lost Smartwatch', 'This item was found/lost at the specified location. Please contact if it belongs to you.', NULL, 'found', 'Dunwoody Street, University Hills Subdivision, Potrero, University Hills, District 1, Malabon, Northern Manila District, Metro Manila, 1475, Philippines', 'near the parking lot', '2026-06-09 19:27:00', 'assets/uploads/items/6a32cfcdabc95.png', 3, 'open', 14.66222188, 120.97821057, '2026-06-18 17:27:19'),
(15, 2, 'Poster of a CyberDroyd', 'This item was found/lost at the specified location. Please contact if it belongs to you.', NULL, 'found', '47, Santa Teresita Drive, Mañalac Industrial Estate, Bagumbayan, Taguig District 1, Taguig, Southern Manila District, Metro Manila, 1717, Philippines', 'on the plant nearby', '2026-06-13 19:27:00', 'assets/uploads/items/6a32e0acabb4c.png', 2, 'open', 14.47919237, 121.05150461, '2026-06-18 17:27:19'),
(16, 3, 'Research Prototype of the NChain AI', 'This item is a revolutional item that can change the world if it gets to the wrong people.', '$5,000,000', 'lost', '1234, Padas Street, Barangay 20, Zone 2, Kaunlaran, District 2, Caloocan, Northern Manila District, Metro Manila, 1409, Philippines', 'Donk know', '2026-06-12 19:27:00', 'assets/uploads/items/6a3307b5621c6.jpg', 1, 'open', 14.64682862, 120.96713305, '2026-06-18 17:27:19'),
(17, 1, 'Netanyahu Plushie', 'Best Plushie on the world to the point that I dont event want to found the actual owner', NULL, 'found', 'Linear Park', 'near the entrance on the side bench', '2026-06-12 19:27:00', 'assets/uploads/items/6a3308443aeda.jpg', 2, 'open', 14.59214830, 121.00949230, '2026-06-18 17:27:19'),
(18, 1, 'Quarter Zip Jacket of the EPS', 'Please Speed I need this', '1 million Robux', 'lost', 'Arca Boulevard, Arca South, Western Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1630, Philippines', 'ARCA grass near Landers', '2026-06-12 19:27:00', 'assets/uploads/items/6a33138ce8c3c.png', 3, 'open', 14.50569595, 121.04483128, '2026-06-18 17:27:19'),
(19, 1, '3D Printed Prime Vandal Gun', 'a 3D Printed Prime Vandal Gun from the game Valorant, it its kinda heavy and also feels like a cardboard', NULL, 'lost', 'SM Aura', 'SMX convention', '2026-06-15 15:39:00', 'assets/uploads/items/6a342eaaaf471.jpeg', 2, 'open', 14.54563880, 121.05340130, '2026-06-18 17:45:14'),
(20, 4, 'Chicken Nuggets', 'Masarap na nuggets, naiwan ko sa baunan ko sa school', 'Free Lunch kainin natin yung nuggets', 'lost', 'PUP Taguig', 'Building A, 4th floor??', '2026-06-18 20:02:00', 'assets/uploads/items/6a345be2abf2b.jpg', 4, 'open', 14.48813210, 121.05142530, '2026-06-18 20:58:10'),
(21, 1, 'Legoat Jersey', 'Laker\'s Lebron Number 23 Jersey large size', '500 pesos', 'lost', 'Ninoy Aquino Stadium, Kalye M. Adriatico, Agno, Barangay 708, Malate, Fifth District, Manila, Capital District, Metro Manila, 1004, Philippines', 'at the parking lot', '2026-06-18 23:34:00', 'assets/uploads/items/6a346816ab78e.jpg', 6, 'claimed', 14.56448798, 120.99113882, '2026-06-18 21:50:14'),
(22, 1, 'Balls', 'Balls from our game last night at PUP taguig Campus suddenly went missing.', NULL, 'lost', 'PUP Taguig', 'Court', '2026-06-19 01:11:00', 'assets/uploads/items/6a347ba95260f.jpg', 5, 'claimed', 14.48813210, 121.05142530, '2026-06-18 23:13:45');

-- --------------------------------------------------------

--
-- Table structure for table `item_images`
--

CREATE TABLE `item_images` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_images`
--

INSERT INTO `item_images` (`id`, `item_id`, `photo_path`, `created_at`) VALUES
(1, 20, 'assets/uploads/items/add_6a345be2ad5af.jpg', '2026-06-18 20:58:10'),
(2, 20, 'assets/uploads/items/add_6a345be2adae6.jpg', '2026-06-18 20:58:10'),
(3, 20, 'assets/uploads/items/add_6a345be2ae001.jpg', '2026-06-18 20:58:10'),
(4, 20, 'assets/uploads/items/add_6a345be2ae655.jpg', '2026-06-18 20:58:10');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_user_id`, `to_user_id`, `item_id`, `message`, `photo`, `is_read`, `created_at`) VALUES
(1, 5, 6, 21, 'Hello I have what you need', NULL, 1, '2026-06-18 22:42:19'),
(2, 5, 6, 21, '', 'assets/uploads/messages/6a3474509b74e.jpg', 1, '2026-06-18 22:42:24'),
(3, 5, 6, 21, '', 'assets/uploads/messages/6a3474545f7ff.jpg', 1, '2026-06-18 22:42:28'),
(4, 6, 5, 21, 'lets meet up\\', NULL, 1, '2026-06-18 22:43:42'),
(5, 5, 3, 5, 'I got your car homie lets meet', NULL, 1, '2026-06-18 22:51:01'),
(6, 4, 3, 5, 'hi i might have your car\\', NULL, 1, '2026-06-18 22:55:35'),
(7, 6, 5, 22, 'I might have the item you lost', NULL, 1, '2026-06-18 23:14:13'),
(14, 1, 3, 1, 'Testing foreign key', NULL, 1, '2026-06-18 23:22:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_initial` varchar(5) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `id_type` enum('pup_id','national_id','faculty_id','other') NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','user') DEFAULT 'user',
  `status` enum('active','disabled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `middle_initial`, `last_name`, `birthdate`, `age`, `bio`, `profile_picture`, `id_type`, `id_number`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'admin', 'Stelle', '', 'Aronhunter', '2005-12-03', 20, 'I\'m the Admin', '1781820946_adminProfileImage.jpg', 'pup_id', 'ADMIN001', 'admin@foundly.com', '$2y$10$okJQyKpsqYvkdLBcBGZfnOHj0NxSGrY90yX3OCEid.4S6EcQecXZS', 'admin', 'active', '2026-06-17 15:55:39'),
(2, 'john123', 'John Immanuel', 'C.', 'Marondo', '2006-06-19', 19, 'Throughout heaven and earth, I alone am the honored one.', 'john123ProfileImage.jpg', 'faculty_id', '12345678', 'john123@gmail.com', '$2y$10$GJS7ZIJOiCC4sVwrQnzx7.bfaZFeholwzhxIpGWJpYvGP7NBj/xtm', 'staff', 'active', '2026-06-17 16:53:37'),
(3, 'jhun123', 'Jhun Francis', 'M.', 'Talosig', '2005-12-03', 20, 'I might be the GOAT.', 'jhun123ProfileImage.jpg', 'pup_id', '2024-00306-TG-0', 'jhun123@gmail.com', '$2y$10$CBg1NgdML72KK0A0Bu7e7.yP//crukcJXNdCpWk88NP8fWZtTm3Ti', 'staff', 'active', '2026-06-18 16:01:12'),
(4, 'yuann123', 'Yuann Czedriehck', 'D', 'Staff', '2000-01-01', 26, 'Cyberpunk 2067', 'yuann123ProfileImage.jpg', 'pup_id', '2024-00243-TG-0', 'yuann123@gmail.com', '$2y$10$I3y0cGB/EJPVjrydttBpfuZYwDmNCl59oK.f8Fdgv646x.eLNmp7K', 'staff', 'active', '2026-06-18 16:01:12'),
(5, 'testuser1', 'Test', '', 'User One', '2000-01-01', 26, 'I\'m a Tester', '1781818523_testuser1ProfileImage.jpg', 'pup_id', 'TEST-00001-TG-0', 'testuser1@gmail.com', '$2y$10$46v9nc/tgAassvj19mTxFeixp9KpnTtia.1AzqJqvAC4cxl4cAfcq', 'user', 'active', '2026-06-18 18:46:10'),
(6, 'testuser2', 'Test', '', 'User Two', '2000-01-01', 26, 'I\'m a tester', '1781819193_testuser2ProfileImage.jpg', 'pup_id', 'TEST-00002-TG-0', 'testuser2@gmail.com', '$2y$10$82QDm2xy..NGQwA3Xd7pXePGoVl01p4dxNfwy.00HlDbVP3mQawcK', 'user', 'active', '2026-06-18 18:46:10'),
(7, 'yeriel123', 'Yeriel Gyan', 'D', 'Pallada', '2006-04-20', 20, NULL, NULL, 'pup_id', '2024-00067-TG-0', 'yeriel123@gmail.com', '$2y$10$LELTt8sMwNkziVulRKZwWe8FGPL9V6Npxv/Kzg.DBG2OgGABWTP7C', 'user', 'active', '2026-06-19 12:49:11'),
(8, 'margie123', 'Margie', '', 'Fernando', '2006-02-06', 20, NULL, NULL, 'pup_id', '2024-00069-TG-0', 'margie123@gmail.com', '$2y$10$1qO/7nrtVowRZXePXAIrveanotzXm3dfxbrXZMewOda0UlbQUBmMO', 'user', 'active', '2026-06-19 14:19:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `issue_reports`
--
ALTER TABLE `issue_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `resolved_by` (`resolved_by`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `reported_by` (`reported_by`);

--
-- Indexes for table `item_images`
--
ALTER TABLE `item_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_id` (`id_type`,`id_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `claims`
--
ALTER TABLE `claims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `issue_reports`
--
ALTER TABLE `issue_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `item_images`
--
ALTER TABLE `item_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `claims`
--
ALTER TABLE `claims`
  ADD CONSTRAINT `claims_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `claims_ibfk_2` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `issue_reports`
--
ALTER TABLE `issue_reports`
  ADD CONSTRAINT `issue_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `issue_reports_ibfk_2` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_images`
--
ALTER TABLE `item_images`
  ADD CONSTRAINT `item_images_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
