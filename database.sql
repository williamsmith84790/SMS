-- Database Schema for EduPortal (PHP Version)

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
-- Table structure for table `slider_images`
--

CREATE TABLE `slider_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(50) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticker_items`
--

CREATE TABLE `ticker_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(500) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urgent_alerts`
--

CREATE TABLE `urgent_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `date_posted` datetime NOT NULL DEFAULT current_timestamp(),
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculty_members`
--

CREATE TABLE `faculty_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `designation` varchar(200) NOT NULL,
  `department` varchar(200) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `bio` longtext DEFAULT NULL,
  `order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_albums`
--

CREATE TABLE `gallery_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(200) DEFAULT NULL,
  `media_type` enum('image','video') NOT NULL DEFAULT 'image',
  `video_embed` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `album_id` (`album_id`),
  CONSTRAINT `gallery_images_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `batch` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `achievement` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_categories`
--

CREATE TABLE `document_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `category_id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `document_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_results`
--

CREATE TABLE `student_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roll_number` varchar(50) NOT NULL,
  `session` varchar(50) NOT NULL,
  `student_name` varchar(200) NOT NULL,
  `father_name` varchar(200) DEFAULT NULL,
  `result_data` text DEFAULT NULL,
  `total_marks` varchar(20) DEFAULT NULL,
  `obtained_marks` varchar(20) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `result_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_result` (`roll_number`,`session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2023-01-01 00:00:00');
-- Password is 'password'

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('site_logo', ''),
('site_name', 'EduPortal'),
('contact_email', 'info@eduportal.com'),
('contact_phone', '+123 456 7890'),
('contact_address', '123 Education Street, Knowledge City'),
('social_facebook', '#'),
('social_twitter', '#'),
('social_instagram', '#'),
('social_linkedin', '#'),
('footer_about_text', 'We are dedicated to providing quality education and fostering an environment of academic excellence and personal growth for all our students.'),
('header_apply_link', 'downloads.php'),
('footer_copyright_text', 'All Rights Reserved');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `location` enum('header','footer') NOT NULL DEFAULT 'header',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
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
(33, 'Events', 'events.php', NULL, 8, 'header'),
(34, 'Contact', 'contact.php', NULL, 9, 'header'),
(35, 'Results', 'results.php', NULL, 10, 'header'),
(36, 'Home', 'index.php', NULL, 1, 'footer'),
(37, 'Vision & Mission', 'page.php?slug=vision-mission', NULL, 2, 'footer'),
(38, 'Our Faculty', 'faculty.php', NULL, 3, 'footer'),
(39, 'Downloads', 'downloads.php', NULL, 4, 'footer'),
(40, 'Check Results', 'results.php', NULL, 5, 'footer'),
(41, 'Contact Us', 'contact.php', NULL, 6, 'footer');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
