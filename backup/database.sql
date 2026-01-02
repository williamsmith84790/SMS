-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2026 at 09:55 PM
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
-- Database: `edu_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `permissions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `permissions`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2023-01-01 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `batch` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `achievement` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `category_id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_categories`
--

CREATE TABLE `document_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(50) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `date`, `time`, `location`, `description`, `image`, `created_at`) VALUES
(2, 'demo', '2026-01-04', '12PM', 'At College', '<p>demo event</p>', 'media/events/1767385607_IMG-20240318-WA0049.jpg', '2026-01-03 01:26:47');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_members`
--

CREATE TABLE `faculty_members` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `designation` varchar(200) NOT NULL,
  `department` varchar(200) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `bio` longtext DEFAULT NULL,
  `order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_albums`
--

CREATE TABLE `gallery_albums` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_albums`
--

INSERT INTO `gallery_albums` (`id`, `title`, `description`, `cover_image`, `created_at`) VALUES
(2, 'demo', '', 'media/gallery/covers/1767385856_IMG-20240318-WA0050-768x512.jpg', '2026-01-03');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(200) DEFAULT NULL,
  `media_type` enum('image','video') NOT NULL DEFAULT 'image',
  `video_embed` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `album_id`, `image`, `caption`, `media_type`, `video_embed`) VALUES
(4, 2, 'media/gallery/images/1767385872_0_IMG-20240318-WA0056-300x200.jpg', '', 'image', NULL),
(5, 2, 'media/gallery/images/1767385872_1_IMG-20240318-WA0056-768x512.jpg', '', 'image', NULL),
(6, 2, 'media/gallery/images/1767385872_2_IMG-20240318-WA0056-1024x682.jpg', '', 'image', NULL),
(7, 2, 'media/gallery/images/1767385872_3_IMG-20240318-WA0056-1536x1023.jpg', '', 'image', NULL),
(8, 2, 'media/gallery/images/1767385872_4_IMG-20240318-WA0057.jpg', '', 'image', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `label` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `location` enum('header','footer') NOT NULL DEFAULT 'header'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `label`, `link`, `parent_id`, `sort_order`, `location`) VALUES
(1, 'Home', 'index.php', NULL, 1, 'header'),
(2, 'About us', '#', NULL, 2, 'header'),
(3, 'Vision & Mission', 'page.php?slug=vision-mission', 2, 1, 'header'),
(4, 'History', 'page.php?slug=history', 2, 2, 'header'),
(5, 'Principal\'s Message', 'page.php?slug=principal-message', 2, 3, 'header'),
(6, 'Core Team', 'page.php?slug=core-team', 2, 4, 'header'),
(7, 'Administration', '#', NULL, 3, 'header'),
(8, 'Principal Office', 'page.php?slug=principal-office', 7, 1, 'header'),
(9, 'Vice Principal Office', 'page.php?slug=vice-principal-office', 7, 2, 'header'),
(10, 'Controller Office', 'page.php?slug=controller-office', 7, 3, 'header'),
(11, 'Student Affairs', 'page.php?slug=student-affairs', 7, 4, 'header'),
(12, 'Academics', '#', NULL, 4, 'header'),
(13, 'Programs', '#', 12, 1, 'header'),
(14, 'Intermediate', 'page.php?slug=program-intermediate', 13, 1, 'header'),
(15, 'BS-4YDP', 'page.php?slug=program-bs-4ydp', 13, 2, 'header'),
(16, 'Faculty', 'faculty.php', 12, 2, 'header'),
(17, 'Admissions', '#', NULL, 5, 'header'),
(18, 'Intermediate (Prospectus)', 'downloads.php', 17, 1, 'header'),
(19, 'BS-4YDP (Prospectus)', 'downloads.php', 17, 2, 'header'),
(20, 'Life at Campus', '#', NULL, 6, 'header'),
(21, 'Facilities', 'page.php?slug=facilities', 20, 1, 'header'),
(22, 'Societies', '#', 20, 2, 'header'),
(23, 'College Societies', 'page.php?slug=college-societies', 22, 1, 'header'),
(24, 'Girls Guide Association', 'page.php?slug=girls-guide', 22, 2, 'header'),
(25, 'Co-curricular Activities', 'page.php?slug=co-curricular', 20, 3, 'header'),
(26, 'Hostel', 'page.php?slug=hostel', 20, 4, 'header'),
(27, 'Library', 'page.php?slug=library', 20, 5, 'header'),
(28, 'Career Counselling', 'page.php?slug=career-counselling', 20, 6, 'header'),
(29, 'Alumni', '#', NULL, 7, 'header'),
(30, 'Our Best Graduates', 'alumni.php', 29, 1, 'header'),
(31, 'Success Stories', 'page.php?slug=success-stories', 29, 2, 'header'),
(32, 'Donate', 'page.php?slug=donate', 29, 3, 'header'),
(33, 'Events', 'events.php', 20, 8, 'header'),
(34, 'Contact Us', 'contact.php', NULL, 9, 'header'),
(35, 'Results', 'results.php', 12, 10, 'header'),
(36, 'Home', 'index.php', NULL, 1, 'footer'),
(37, 'Vision & Mission', 'page.php?slug=vision-mission', NULL, 2, 'footer'),
(38, 'Our Faculty', 'faculty.php', NULL, 3, 'footer'),
(39, 'Downloads', 'downloads.php', NULL, 4, 'footer'),
(40, 'Check Results', 'results.php', NULL, 5, 'footer'),
(41, 'Contact Us', 'contact.php', NULL, 6, 'footer');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `date_posted` date NOT NULL DEFAULT current_timestamp(),
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `title`, `content`) VALUES
(1, 'vision-mission', 'Vision & Mission', '<p><strong>Our Vision:</strong> To be a leading institution of learning...</p><p><strong>Our Mission:</strong> To empower students...</p>'),
(2, 'history', 'Our History', '<p>Established in 1990, our college has a rich history...</p>'),
(3, 'principal-message', 'Principal\'s Message', '<p>Welcome to our college. We strive for excellence...</p>'),
(4, 'core-team', 'Core Team', '<p>Meet our dedicated leadership team...</p>'),
(5, 'principal-office', 'Principal Office', '<p>Information about the Principal Office...</p>'),
(6, 'vice-principal-office', 'Vice Principal Office', '<p>Information about the Vice Principal Office...</p>'),
(7, 'controller-office', 'Controller of Examinations', '<p>Details about examination schedules and results...</p>'),
(8, 'student-affairs', 'Student Affairs', '<p>We are here to support student life...</p>'),
(9, 'program-intermediate', 'Intermediate Programs', '<p>We offer FA, FSc, ICS, and I.Com programs...</p>'),
(10, 'program-bs-4ydp', 'BS (4-Year) Programs', '<p>Our BS programs include Computer Science, English, and more...</p>'),
(11, 'facilities', 'Campus Facilities', '<p>We provide state-of-the-art labs, library, and sports grounds...</p>'),
(12, 'college-societies', 'College Societies', '<p>Join our Debating, Dramatic, and Science societies...</p>'),
(13, 'girls-guide', 'Girls Guide Association', '<p>Information about the Girls Guide activities...</p>'),
(14, 'co-curricular', 'Co-curricular Activities', '<p>Sports, debates, and competitions...</p>'),
(15, 'hostel', 'Hostel Facilities', '<p>Secure and comfortable accommodation for students...</p>'),
(16, 'library', 'Library', '<p>Our library houses over 50,000 books...</p>'),
(17, 'career-counselling', 'Career Counselling', '<p>Guidance for your future career paths...</p>'),
(18, 'success-stories', 'Success Stories', '<p>Read about our alumni achievements...</p>'),
(19, 'donate', 'Donate to Alumni Fund', '<p>Support the next generation of students...</p>');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'site_logo', 'media/settings/logo_1767367083_CooperRoad.png'),
(2, 'site_name', 'My School'),
(3, 'contact_email', 'info@myschool.com'),
(4, 'contact_phone', '+123 456 7890'),
(5, 'contact_address', '123 Education Street, Knowledge City'),
(6, 'social_facebook', '#'),
(7, 'social_twitter', '#'),
(8, 'social_instagram', '#'),
(9, 'social_linkedin', '#'),
(10, 'footer_about_text', 'We are dedicated to providing quality education and fostering an environment of academic excellence and personal growth for all our students.'),
(11, 'header_apply_link', 'downloads.php'),
(12, 'footer_copyright_text', 'All Rights Reserved'),
(13, 'feature_1_title', 'Intermediate Program'),
(14, 'feature_1_text', 'Comprehensive F.Sc, F.A, and I.Com programs designed to build a strong academic foundation.'),
(15, 'feature_1_link', 'page.php?slug=program-intermediate'),
(16, 'feature_1_image', 'media/settings/f1_1767386115_course-img-300x198.jpg'),
(17, 'feature_2_title', 'BS-4YDP Program'),
(18, 'feature_2_text', 'Four-year degree programs offering in-depth specialization and research opportunities.'),
(19, 'feature_2_link', 'page.php?slug=program-bs-4ydp'),
(20, 'feature_2_image', 'media/settings/f2_1767386115_course-img1-150x150.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `site_stats`
--

CREATE TABLE `site_stats` (
  `id` int(11) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `number` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_stats`
--

INSERT INTO `site_stats` (`id`, `icon`, `number`, `label`, `sort_order`) VALUES
(1, 'fas fa-calendar-alt', '34', 'The Year Founded', 1),
(2, 'fas fa-user-graduate', '9000', 'Students In 2022', 2),
(3, 'fas fa-chalkboard-teacher', '1500', 'Staff', 3),
(4, 'fas fa-graduation-cap', '300000', 'Alumni', 4),
(5, 'fas fa-handshake', '600', 'Partner', 5);

-- --------------------------------------------------------

--
-- Table structure for table `slider_images`
--

CREATE TABLE `slider_images` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slider_images`
--

INSERT INTO `slider_images` (`id`, `title`, `image`, `order`, `is_active`) VALUES
(3, '', 'media/slider/1767386202_about-video-bg-1536x422.jpg', 0, 1),
(4, '', 'media/slider/1767386239_banner3-1536x453.jpg', 0, 1),
(5, '', 'media/slider/1767386272_cources-bg-1536x484.jpg', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_results`
--

CREATE TABLE `student_results` (
  `id` int(11) NOT NULL,
  `roll_number` varchar(50) NOT NULL,
  `session` varchar(50) NOT NULL,
  `student_name` varchar(200) NOT NULL,
  `father_name` varchar(200) DEFAULT NULL,
  `result_data` text DEFAULT NULL,
  `total_marks` varchar(20) DEFAULT NULL,
  `obtained_marks` varchar(20) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `result_file` varchar(255) DEFAULT NULL,
  `class` varchar(100) DEFAULT NULL,
  `part` varchar(50) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `registration_number` varchar(100) DEFAULT NULL,
  `result_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticker_items`
--

CREATE TABLE `ticker_items` (
  `id` int(11) NOT NULL,
  `content` varchar(500) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urgent_alerts`
--

CREATE TABLE `urgent_alerts` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `urgent_alerts`
--

INSERT INTO `urgent_alerts` (`id`, `title`, `message`, `is_active`, `created_at`) VALUES
(2, 'welcome', 'welcome', 1, '2026-01-03 01:25:30'),
(3, 'demo 1', 'demo 1', 0, '2026-01-03 01:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `document_categories`
--
ALTER TABLE `document_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty_members`
--
ALTER TABLE `faculty_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `site_stats`
--
ALTER TABLE `site_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider_images`
--
ALTER TABLE `slider_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_results`
--
ALTER TABLE `student_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_result` (`roll_number`,`session`);

--
-- Indexes for table `ticker_items`
--
ALTER TABLE `ticker_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `urgent_alerts`
--
ALTER TABLE `urgent_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_categories`
--
ALTER TABLE `document_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty_members`
--
ALTER TABLE `faculty_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `site_stats`
--
ALTER TABLE `site_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `slider_images`
--
ALTER TABLE `slider_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_results`
--
ALTER TABLE `student_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticker_items`
--
ALTER TABLE `ticker_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `urgent_alerts`
--
ALTER TABLE `urgent_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `document_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `gallery_images_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
