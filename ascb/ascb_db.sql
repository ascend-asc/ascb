-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2026 at 06:11 AM
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
-- Database: `ascb_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL COMMENT 'Filename only — image must be in the img/ folder',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `image`, `created_at`) VALUES
(6, 'Laptops and Cameras for Student Leaders and Campus Journalists', 'As part of its continuing commitment to student development, the Andres Soriano Colleges of Bislig (ASCB) Management has provided laptops and cameras to the College’s two recognized student organizations—the Supreme Student Government (SSG) and The Valiants, the official student publication.\r\nThese resources will further equip our student leaders and campus journalists with the tools they need to lead, serve, inform, and inspire the ASCB community with excellence and integrity.\r\nAt ASCB, we remain committed to empowering our students by investing in their growth, leadership, and future', '2026-07-28', 'event.jpg', '2026-07-28 17:51:13'),
(7, '🔥 𝐅𝐑𝐎𝐌 𝐓𝐇𝐄 𝐀𝐒𝐇𝐄𝐒, 𝐖𝐄 𝐑𝐎𝐒𝐄 𝐒𝐓𝐑𝐎𝐍𝐆𝐄𝐑. 🔥', 'On 𝐉𝐮𝐥𝐲 𝟏𝟗, 𝟐𝟎𝟏𝟒, 𝐀𝐧𝐝𝐫𝐞𝐬 𝐒𝐨𝐫𝐢𝐚𝐧𝐨 𝐂𝐨𝐥𝐥𝐞𝐠𝐞𝐬 𝐨𝐟 𝐁𝐢𝐬𝐥𝐢𝐠 faced a devastating fire that tested the strength of our institution and the unity of our community.\r\n\r\nWhat was lost that day became the foundation of renewed 𝒄𝒐𝒖𝒓𝒂𝒈𝒆, 𝒖𝒏𝒊𝒕𝒚, and 𝒉𝒐𝒑𝒆.\r\n\r\nTwelve years later, ASCB stands rebuilt—not only in structure, but also in spirit. We remember the loss, honor the resilience of everyone who helped us rise, and celebrate our continuing journey toward excellence.\r\n\r\n𝑨 𝒅𝒂𝒚 𝒘𝒆 𝒘𝒊𝒍𝒍 𝒏𝒆𝒗𝒆𝒓 𝒇𝒐𝒓𝒈𝒆𝒕.\r\n𝑨 𝒍𝒆𝒈𝒂𝒄𝒚 𝒕𝒉𝒂𝒕 𝒄𝒐𝒏𝒕𝒊𝒏𝒖𝒆𝒔 𝒕𝒐 𝒓𝒊𝒔𝒆.\r\n\r\n💙 𝐀𝐒𝐂𝐁, 𝐀𝐒𝐂𝐄𝐍𝐃𝐈𝐍𝐆! 💙\r\n\r\n✨ 𝑪𝒓𝒆𝒂𝒕𝒆 𝑴𝒆𝒎𝒐𝒓𝒊𝒆𝒔. 𝑷𝒖𝒓𝒔𝒖𝒆 𝑬𝒙𝒄𝒆𝒍𝒍𝒆𝒏𝒄𝒆. ✨', '2026-07-18', 'event2.jpg', '2026-07-28 18:19:56'),
(8, 'ASCENDANCE Dance Squad', 'College Division earned 4th Placer at the 1st Inter-Collegiate Varsity Edition of the Mindanao-Wide Open Hip Hop Dance Competition during the 27th T’nalak Festival in Koronadal City!\r\nThank you to our coaches, school administration, supporters, and everyone who believed in us. This is not the end—it’s another milestone that will fuel us to come back stronger.\r\nCongratulations, ASCENDANCE! 💙🔥\r\nThe climb continues. The best is yet to come.', '2026-07-16', 'event3.jpg', '2026-07-28 18:22:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
