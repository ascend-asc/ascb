-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2026 at 01:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ascend_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `admin_user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `target` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('superadmin','editor') DEFAULT 'editor',
  `last_login` datetime DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `full_name`, `email`, `password_hash`, `role`, `last_login`, `status`, `created_at`) VALUES
(1, 'Super Admin', 'admin@123', '$2y$10$7aYx5uHFNKwP7B/QKTQ3gOdyLxOq1kopKtCbwWcLss0eQanqT/B4W', 'superadmin', '2026-08-05 19:16:40', 'active', '2026-07-30 01:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `cta_label` varchar(50) DEFAULT NULL,
  `cta_link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `image_path`, `cta_label`, `cta_link`, `sort_order`, `is_active`, `created_at`) VALUES
(6, 'Welcome to Andres Soriano Colleges of Bislig', 'ASCB, Ascending! — Shaping competent professionals and responsible leaders since 1952', 'uploads/slides/slide_6a7320e8ece1e.jpg', 'Learn More', '/ascend_website/about', 0, 1, '2026-07-30 03:16:32'),
(7, 'Enrollment is Now Open', 'Freshmen, transferees, and returnees — start your application today', 'uploads/slides/slide_6a7320899b698.jpg', 'Apply Now', '/ascend_website/admissions', 0, 1, '2026-07-30 03:26:16'),
(10, 'Invest in Your Future', 'CHED, TES, TDP, LGU, and Alay ng Probinsya scholarships available for qualified students', 'uploads/slides/slide_6a73207f10181.jpg', 'Check Scholarships', '/ascend_website/scholarships', 0, 1, '2026-07-30 03:41:03');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `form_type` varchar(50) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `message`, `form_type`, `submitted_at`, `is_read`) VALUES
(1, 'johnlloyd', 'john@gmail.com', '09383196687', 'scholarship', 'General Inquiry', '2026-07-31 06:07:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `media_library`
--

CREATE TABLE `media_library` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_events`
--

CREATE TABLE `news_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_events`
--

INSERT INTO `news_events` (`id`, `title`, `slug`, `excerpt`, `body`, `cover_image`, `category`, `published_at`, `status`, `created_at`) VALUES
(1, 'Student Leaders and Campus Journalists', 'laptops-cameras-ssg-valiants', 'Laptops and Cameras for Student Leaders and Campus Journalists', 'As part of its continuing commitment to student development, the Andres Soriano Colleges of Bislig (ASCB) Management has provided laptops and cameras to the College’s two recognized student organizations—the Supreme Student Government (SSG) and The Valiants, the official student publication.\r\nThese resources will further equip our student leaders and campus journalists with the tools they need to lead, serve, inform, and inspire the ASCB community with excellence and integrity.\r\nAt ASCB, we remain committed to empowering our students by investing in their growth, leadership, and future.\r\n💙 ASCB, Ascending!', 'uploads/news/news_6a6ae656e3d2a.jpg', 'Announcements', '2026-07-30 07:42:20', 'published', '2026-07-30 05:42:20'),
(4, 'WE ARE HIRING! CANTEEN MANAGER', 'HIRING!!-Canteen-Manager', 'Andres Soriano Colleges of Bislig, Inc. (ASCB) is seeking a dynamic, responsible, and service-oriented Canteen Manager', 'WE ARE HIRING!\r\nCANTEEN MANAGER\r\nAndres Soriano Colleges of Bislig, Inc. (ASCB) is seeking a dynamic, responsible, and service-oriented Canteen Manager to oversee the daily operations of the school canteen and ensure the delivery of high-quality, safe, and affordable food services to our students, employees, and visitors.\r\nQualifications:\r\n• Bachelor\'s degree in hospitality management, Business Administration, Food Service Management, Nutrition, or any related field (preferred)\r\n• Experience in food service, restaurant, or canteen management is an advantage\r\n• Knowledgeable in food safety, sanitation, and inventory management\r\n• Strong leadership, organizational, and customer service skills\r\n• Computer literate and proficient in basic record-keeping and reporting\r\n• Honest, trustworthy, and capable of handling cash and financial transactions\r\n• Physically fit and willing to work in a fast-paced environment\r\n• Excellent communication and interpersonal skills\r\nKey Responsibilities:\r\n• Supervise the daily operations of the school canteen.\r\n• Ensure food quality, cleanliness, and compliance with health and safety standards.\r\n• Manage inventory, purchasing, and stock control.\r\n• Prepare sales, expense, and inventory reports.\r\n• Supervise and evaluate canteen personnel.\r\n• Promote excellent customer service and efficient operations.\r\nApplication Requirements:\r\n• Application Letter\r\n• Updated Resume/Curriculum Vitae\r\n• Photocopy of Transcript of Records and Diploma\r\n• Certificates of Employment and Training (if applicable)\r\n• Valid Government-issued ID\r\nSubmit your application to:\r\nHuman Resource Office, \r\nNew Building, Second Floor\r\nAt the back of NB 202 Classroom\r\nApplication Deadline: July 24\r\nJoin our team and help us provide a healthy, efficient, and student-friendly dining experience for the ASCB community!\r\nApply now and become part of the ASCB family!', 'uploads/news/news_6a6ae88779560.jpg', 'Announcements', '2026-07-30 08:00:39', 'published', '2026-07-30 06:00:39'),
(5, '𝐅𝐑𝐎𝐌 𝐓𝐇𝐄 𝐀𝐒𝐇𝐄𝐒, 𝐖𝐄 𝐑𝐎𝐒𝐄 𝐒𝐓𝐑𝐎𝐍𝐆𝐄𝐑.', 'To-ashes-we-arise-stronger', '𝑨 𝒅𝒂𝒚 𝒘𝒆 𝒘𝒊𝒍𝒍 𝒏𝒆𝒗𝒆𝒓 𝒇𝒐𝒓𝒈𝒆𝒕.  \r\n𝑨 𝒍𝒆𝒈𝒂𝒄𝒚 𝒕𝒉𝒂𝒕 𝒄𝒐𝒏𝒕𝒊𝒏𝒖𝒆𝒔 𝒕𝒐 𝒓𝒊𝒔𝒆.\r\n💙 𝐀𝐒𝐂𝐁, 𝐀𝐒𝐂𝐄𝐍𝐃𝐈𝐍𝐆! 💙', 'On 𝐉𝐮𝐥𝐲 𝟏𝟗, 𝟐𝟎𝟏𝟒, 𝐀𝐧𝐝𝐫𝐞𝐬 𝐒𝐨𝐫𝐢𝐚𝐧𝐨 𝐂𝐨𝐥𝐥𝐞𝐠𝐞𝐬 𝐨𝐟 𝐁𝐢𝐬𝐥𝐢𝐠 faced a devastating fire that tested the strength of our institution and the unity of our community.\r\nWhat was lost that day became the foundation of renewed 𝒄𝒐𝒖𝒓𝒂𝒈𝒆, 𝒖𝒏𝒊𝒕𝒚, and 𝒉𝒐𝒑𝒆.\r\nTwelve years later, ASCB stands rebuilt—not only in structure, but also in spirit. We remember the loss, honor the resilience of everyone who helped us rise, and celebrate our continuing journey toward excellence.\r\n𝑨 𝒅𝒂𝒚 𝒘𝒆 𝒘𝒊𝒍𝒍 𝒏𝒆𝒗𝒆𝒓 𝒇𝒐𝒓𝒈𝒆𝒕.  \r\n𝑨 𝒍𝒆𝒈𝒂𝒄𝒚 𝒕𝒉𝒂𝒕 𝒄𝒐𝒏𝒕𝒊𝒏𝒖𝒆𝒔 𝒕𝒐 𝒓𝒊𝒔𝒆.\r\n💙 𝐀𝐒𝐂𝐁, 𝐀𝐒𝐂𝐄𝐍𝐃𝐈𝐍𝐆! 💙\r\n✨ 𝑪𝒓𝒆𝒂𝒕𝒆 𝑴𝒆𝒎𝒐𝒓𝒊𝒆𝒔. 𝑷𝒖𝒓𝒔𝒖𝒆 𝑬𝒙𝒄𝒆𝒍𝒍𝒆𝒏𝒄𝒆. ✨', 'uploads/news/news_6a6ae8f4648ab.jpg', 'Announcements', '2026-07-30 08:02:28', 'published', '2026-07-30 06:02:28');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` longtext DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `title`, `body`, `meta_title`, `meta_description`, `is_published`, `created_at`, `updated_at`) VALUES
(4, 'tuition-fees', 'Tuition Fees', '<h2>About Andres Soriano Colleges of Bislig</h2>\r\n<p>Andres Soriano Colleges of Bislig (ASCB) traces its roots back to 1952, when civic-spirited citizens formed the \"South East Pacific Institute.\" Over the decades it grew into Andres Soriano Institute (1954), Andres Soriano Junior College (1967), and finally Andres Soriano Colleges, Incorporated in 1971. Today, ASCB stands as a leading private educational institution offering basic education, technical-vocational, and higher education programs.</p>\r\n\r\n<h3>Our Vision</h3>\r\n<p>ASCB envisions itself as a leading private educational institution in the region and beyond, fostering an empowering and transformative education that develops globally competent, values-driven, and socially engaged individuals.</p>\r\n\r\n<h3>Our Mission</h3>\r\n<p>Guided by a commitment to excellence, inclusivity, and service, Andres Soriano Colleges of Bislig provides holistic, accessible, and quality basic, technical-vocational, and higher education programs that cultivate lifelong learning, critical thinking, and innovation; uphold integrity, social responsibility, and cultural heritage; equip graduates with 21st-century competencies for local and global relevance; and strengthen linkages with industry, government, and civil society to advance sustainable development.</p>\r\n\r\n<h3>Our Core Values — ASCB</h3>\r\n<ul>\r\n  <li><strong>Accountability</strong> — Acting with integrity and responsibility in all roles and decisions.</li>\r\n  <li><strong>Stewardship</strong> — Caring for people, resources, and the environment with purpose and respect.</li>\r\n  <li><strong>Compassion</strong> — Demonstrating empathy, inclusivity, and a genuine concern for others.</li>\r\n  <li><strong>Brilliance</strong> — Pursuing excellence, innovation, and meaningful impact in every endeavor.</li>\r\n</ul>\r\n\r\n<h3>Our Motto</h3>\r\n<p><em>\"ASCB, Ascending!\"</em> — capturing the spirit of a dynamic and visionary institution continually striving for greater heights, honoring our legacy while embracing the future as we move toward our centennial in 2052.</p>\r\n\r\n<h3>Accreditation</h3>\r\n<p>ASCB is officially recognized by the Commission on Higher Education (CHED) as a Higher Education Institution offering undergraduate and graduate degrees, and is authorized by TESDA to provide nationally certified vocational training programs.</p>', '', '', 1, '2026-07-30 06:37:48', '2026-07-30 06:38:53'),
(5, 'cultural-events', 'Cultural Events', '<p class=\"lead text-muted mb-4\">Join us in celebrating diversity and culture at ASCB.</p><p>Throughout the academic year, Andres Soriano Colleges of Bislig hosts a vibrant array of cultural events that showcase the talents and heritage of our student body. From traditional dances to modern arts festivals, these events are a cornerstone of our vibrant campus life.</p><div class=\"mt-4\"><a href=\"http://localhost/ascend_website/student-life\" class=\"btn btn-outline-primary\">← Back to Student Life</a></div>', 'Cultural Events | Andres Soriano Colleges of Bislig', 'Explore ASCB&#39;s cultural events — traditional dances, arts festivals, and celebrations that showcase student talent and Filipino heritage throughout the school year.', 1, '2026-08-05 10:59:42', '2026-08-05 11:30:47'),
(6, 'sports', 'Sports & Athletics', '<p class=\"lead text-muted mb-4\">Discover our sports programs and athletic teams.</p><p>We believe in the holistic development of our students, which includes physical fitness and competitive sportsmanship. Our sports programs offer a wide range of activities, inter-departmental leagues, and varsity teams that represent ASCB with pride.</p><div class=\"mt-4\"><a href=\"http://localhost/ascend_website/student-life\" class=\"btn btn-outline-primary\">&larr; Back to Student Life</a></div>', NULL, NULL, 1, '2026-08-05 10:59:42', '2026-08-05 11:20:52'),
(7, 'community-service', 'Community Service', '<p class=\"lead text-muted mb-4\">Engaging our students in meaningful community outreach.</p><p>ASCB is deeply committed to serving the local community of Bislig. Through various student-led initiatives, outreach programs, and volunteer opportunities, our students learn the value of empathy, social responsibility, and active citizenship.</p><div class=\"mt-4\"><a href=\"http://localhost/ascend_website/student-life\" class=\"btn btn-outline-primary\">&larr; Back to Student Life</a></div>', NULL, NULL, 1, '2026-08-05 11:20:52', '2026-08-05 11:20:52'),
(8, 'academic-clubs', 'Academic Clubs', '<p class=\"lead text-muted mb-4\">Join our academic clubs to enhance your learning experience.</p><p>Our academic clubs provide students with a platform to dive deeper into their fields of interest. Whether you are passionate about science, literature, business, or technology, there is a club where you can collaborate, innovate, and grow alongside your peers.</p><div class=\"mt-4\"><a href=\"http://localhost/ascend_website/student-life\" class=\"btn btn-outline-primary\">&larr; Back to Student Life</a></div>', NULL, NULL, 1, '2026-08-05 11:20:52', '2026-08-05 11:20:52');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `department` enum('Basic Ed','Diploma','College') NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `brochure_pdf` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `department`, `name`, `description`, `brochure_pdf`, `is_active`, `created_at`) VALUES
(1, 'Basic Ed', 'Elementary Department', '(est. 1968)', NULL, 1, '2026-07-30 05:28:14'),
(2, 'Basic Ed', 'Junior High School', '', NULL, 1, '2026-07-30 05:28:37'),
(3, 'Basic Ed', 'Senior High School', '(with dedicated 3-storey building)', NULL, 1, '2026-07-30 05:29:14'),
(4, 'Diploma', 'Diploma Business of Operation Technology ', 'Ladderized program leading to Bachelor of Science in Business Administration major in Marketing Management.', NULL, 1, '2026-07-30 05:31:03'),
(5, 'Diploma', 'Diploma of Security Operation Technology', 'DSOT → ladders to BS Criminology', NULL, 1, '2026-07-30 05:34:01'),
(6, 'Diploma', 'Diploma Information System Technology', 'DIST → ladders to BS Information Systems', NULL, 1, '2026-07-30 05:34:53'),
(7, 'Diploma', 'Diploma Information Technology', 'DIT → ladders to BS Information Technology', NULL, 1, '2026-07-30 05:35:23'),
(8, 'College', 'College of Criminal Justice Education (CCJE)', 'Bachelor of Science in Criminology (BSCrim)', NULL, 1, '2026-07-30 05:37:21'),
(9, 'College', 'College of Computer Education (CCE)', 'Bachelor of Science in Information Systems (BSIS)\r\nBachelor of Science in Information Technology (BSIT)\r\nBachelor of Science in Computer Science (BSCS) — with Associate program', NULL, 1, '2026-07-30 05:38:04'),
(10, 'College', 'College of Accountancy Education (CAE)', 'Bachelor of Science in Accountancy (BSA)', NULL, 1, '2026-07-30 05:38:22'),
(11, 'College', 'College of Business Administration and Education (CBAE)', 'Bachelor of Science in Commerce (BSC)\r\nBachelor of Science in Accounting Technology\r\nBachelor of Science in Business Administration (BSBA)\r\n- Financial Management\r\n- Marketing Management\r\n- Human Resource Development Management\r\n2-year Secretarial Course', NULL, 1, '2026-07-30 05:38:49'),
(12, 'College', 'College of Teacher Education (CTE)', 'Bachelor of Science in Elementary Education (BSEEd)\r\n(Secondary Education / other teacher-ed tracks, if offered — confirm with the Dean&#39;s office)', NULL, 1, '2026-07-30 05:39:04'),
(13, 'College', 'Graduate School', 'Master&#39;s/Doctoral programs (opened 1982 to serve professionals in private and public schools)', NULL, 1, '2026-07-30 05:39:26');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_identity`
--

CREATE TABLE `site_identity` (
  `id` int(11) NOT NULL,
  `vision` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `motto` varchar(255) DEFAULT NULL,
  `core_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`core_values`)),
  `logo_path` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_identity`
--

INSERT INTO `site_identity` (`id`, `vision`, `mission`, `motto`, `core_values`, `logo_path`, `updated_at`) VALUES
(1, 'ASCB envisions itself as a leading private educational institution in the region and beyond, fostering an empowering and transformative education that develops globally competent, values-driven, and socially engaged individuals.', 'Guided by a commitment to excellence, inclusivity, and service, Andres Soriano Colleges of Bislig provides holistic, accessible, and quality basic, technical-vocational, and higher education programs that cultivate lifelong learning, critical thinking, and innovation; uphold integrity, social responsibility, and cultural heritage; equip graduates with 21st-century competencies for local and global relevance; and strengthen linkages with industry, government, and civil society to advance sustainable development.', 'ASCB, Ascending!', '{\"A\":{\"label\":\"Accountability\",\"desc\":\"Acting with integrity and responsibility in all roles and decisions.\"},\"S\":{\"label\":\"Stewardship\",\"desc\":\"Caring for people, resources, and the environment with purpose and respect.\"},\"C\":{\"label\":\"Compassion\",\"desc\":\"Demonstrating empathy, inclusivity, and a genuine concern for others.\"},\"B\":{\"label\":\"Brilliance\",\"desc\":\"Pursuing excellence, innovation, and meaningful impact in every endeavor.\"}}', NULL, '2026-07-30 02:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'site_name', 'Andres Soriano Colleges of Bislig', '2026-07-30 06:12:07'),
(2, 'site_tagline', 'Ascending to Excellence', '2026-07-30 06:12:07'),
(3, 'contact_email', 'info@ascb.edu.ph', '2026-07-30 06:12:07'),
(4, 'contact_phone', '(086) 853-2222', '2026-07-30 06:12:07'),
(5, 'contact_address', 'Andres Soriano Ave, Mangagoy, Bislig City', '2026-07-30 06:12:07'),
(6, 'facebook_url', 'https://www.facebook.com/AndresSorianoCollege', '2026-07-30 06:12:07'),
(7, 'maintenance_mode', '1', '2026-07-30 06:14:58');

-- --------------------------------------------------------

--
-- Table structure for table `staff_directory`
--

CREATE TABLE `staff_directory` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_directory`
--

INSERT INTO `staff_directory` (`id`, `name`, `position`, `department`, `photo`, `sort_order`, `created_at`) VALUES
(2, 'Anrey G. Antiquina, PhD', 'Acting School President', 'Administration', 'uploads/staff/staff_6a6aed193347b.jpg', 0, '2026-07-30 06:20:09'),
(3, 'Rio S. Consigna', 'Vice-President for Administration', 'Administration', NULL, 0, '2026-08-05 11:20:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_user_id` (`admin_user_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media_library`
--
ALTER TABLE `media_library`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `news_events`
--
ALTER TABLE `news_events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `site_identity`
--
ALTER TABLE `site_identity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `staff_directory`
--
ALTER TABLE `staff_directory`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `media_library`
--
ALTER TABLE `media_library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_events`
--
ALTER TABLE `news_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_identity`
--
ALTER TABLE `site_identity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `staff_directory`
--
ALTER TABLE `staff_directory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_user_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `media_library`
--
ALTER TABLE `media_library`
  ADD CONSTRAINT `media_library_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
