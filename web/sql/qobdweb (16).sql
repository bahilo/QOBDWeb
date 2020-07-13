-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 13, 2020 at 02:44 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qobdweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `action`
--

INSERT INTO `action` (`id`, `name`, `display_name`) VALUES
(1, 'ACTION_BILL', 'Facturation'),
(2, 'ACTION_PDF', 'Génération PDF'),
(4, 'ACTION_QUOTE', 'Devis'),
(5, 'ACTION_ORDER', 'Commande'),
(6, 'ACTION_PREORDER', 'Pré-commande'),
(7, 'ACTION_PREREFUND', 'Pré-avoir'),
(8, 'ACTION_VALID', 'Revalidation client'),
(9, 'ACTION_REFUND', 'Avoir'),
(10, 'ACTION_REFUND_BILLED', 'Avoir facturé'),
(11, 'ACTION_ORDER_CLOSED', 'Commande clôturée'),
(12, 'ACTION_REFUND_CLOSED', 'Avoir clôturé'),
(13, 'ACTION_DASHBORD', 'Tableau de bord'),
(14, 'ACTION_AGENT', 'Profile commercial'),
(15, 'ACTION_SETTING', 'Configuration'),
(16, 'ACTION_CATALOGUE', 'Catalogue produits'),
(17, 'ACTION_CLIENT', 'CLients'),
(18, 'ACTION_SECURITY', 'Gestion de la sécurité'),
(19, 'ACTION_STATISTIC', 'Statistiques et graphiques'),
(20, 'ACTION_BLOG', 'Blog'),
(21, 'ACTION_SENSIBLE', 'Données sensible (ex: marge, commentaire admin, etc...)');

-- --------------------------------------------------------

--
-- Table structure for table `action_role`
--

DROP TABLE IF EXISTS `action_role`;
CREATE TABLE IF NOT EXISTS `action_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `privilege_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_218831649D32F035` (`action_id`),
  KEY `IDX_21883164D60322AC` (`role_id`),
  KEY `IDX_2188316432FB8AEA` (`privilege_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `action_role`
--

INSERT INTO `action_role` (`id`, `action_id`, `role_id`, `privilege_id`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 2),
(3, 4, 1, 3),
(4, 5, 1, 4),
(5, 6, 1, 5),
(6, 7, 1, 6),
(7, 8, 1, 7),
(8, 9, 1, 8),
(9, 10, 1, 9),
(10, 11, 1, 10),
(11, 12, 1, 11),
(12, 13, 1, 12),
(13, 14, 1, 13),
(14, 15, 1, 14),
(15, 16, 1, 15),
(16, 17, 1, 16),
(17, 18, 1, 17),
(18, 1, 8, 18),
(19, 1, 7, 19),
(20, 20, 1, 20),
(21, 19, 1, 21),
(22, 2, 7, 22),
(23, 4, 7, 23),
(24, 5, 7, 24),
(25, 6, 7, 25),
(26, 7, 7, 26),
(27, 8, 7, 27),
(28, 9, 7, 28),
(29, 10, 7, 29),
(30, 11, 7, 30),
(31, 12, 7, 31),
(32, 13, 7, 32),
(33, 14, 7, 33),
(34, 15, 7, 34),
(35, 16, 7, 35),
(36, 17, 7, 36),
(37, 18, 7, 37),
(38, 19, 7, 38),
(39, 20, 7, 39),
(40, 21, 1, 40);

-- --------------------------------------------------------

--
-- Table structure for table `action_tracker`
--

DROP TABLE IF EXISTS `action_tracker`;
CREATE TABLE IF NOT EXISTS `action_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_76C1A24E9D32F035` (`action_id`),
  KEY `IDX_76C1A24E3414710B` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_principal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D4E6F81F8697D13` (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

DROP TABLE IF EXISTS `agent`;
CREATE TABLE IF NOT EXISTS `agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `is_online` tinyint(1) DEFAULT NULL,
  `list_size` int(11) DEFAULT NULL,
  `is_activated` tinyint(1) NOT NULL,
  `ipaddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logged_at` datetime DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_268B9C9DF8697D13` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`id`, `comment_id`, `first_name`, `last_name`, `phone`, `fax`, `email`, `user_name`, `password`, `picture`, `is_admin`, `is_online`, `list_size`, `is_activated`, `ipaddress`, `logged_at`, `token`) VALUES
(1, NULL, 'JOEL', 'DAGO', '+33618319489', NULL, 'Joel.dago@yahoo.fr', 'bahilo', '$2y$13$U492bfnvE.g8Wow6H/KokubxRMWiggin69N.p68FBlVI/ik6EhWRi', 'bahilo-5e0a373680906.png', 1, 1, NULL, 1, NULL, '2020-07-13 13:20:33', 'ewU8wZYxe6sTmN7SoLPWonGD5IBmORzOj1Cg8FsXy18'),
(2, NULL, 'client 3', 'client 3', NULL, NULL, 'sisi.bahilo@gmail.com', 'danseur', '$2y$13$QM4mW.6v4WprHhY9lwDS0uEjKqmHiji2YhJWgZ3xkw8vgHNIjIrWa', NULL, 0, 0, NULL, 1, NULL, '2020-04-13 13:24:13', NULL),
(3, NULL, 'Ruth', 'Lago', NULL, NULL, 'lago_ruth@yahoo.fr', 'ruth225', '$2y$13$2.Tituel1ZGvJrEWKadhuuZI12owKJgEOPHhn8JMZyub6z2UpyCQC', NULL, 0, 0, NULL, 0, NULL, '2020-01-04 18:16:31', NULL),
(4, NULL, 'toto', 'toto', NULL, NULL, 'Joel.dago@yahoo.fr', 'toto', '$2y$13$KUe3iVlHY0XPl.1RVUIyEOB0JiML3GOjJhkfW05gQBRWiO/g5JEYy', NULL, 0, NULL, NULL, 0, NULL, NULL, NULL),
(6, NULL, 'toto', 'toto', NULL, NULL, 'joel.dago@yahoo.fr', 'toto3', '$2y$13$0TVNmP.O3pzoc3ZFkrlOHehdQ3/opCje3lyiMbRYR4t9WYCBs8cbO', NULL, 0, NULL, NULL, 0, NULL, NULL, NULL),
(7, NULL, 'toto', 'toto', NULL, NULL, 'joel.dago@yahoo.fr', 'toto4', '$2y$13$WfwnjlOIUbw5SIn8tx5Nuu/bWCK76EDRkGDqSRRoUJZxuGrTeEEIO', NULL, 0, NULL, NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `agent_discussion`
--

DROP TABLE IF EXISTS `agent_discussion`;
CREATE TABLE IF NOT EXISTS `agent_discussion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) DEFAULT NULL,
  `discussion_id` int(11) DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL,
  `is_owner` tinyint(1) DEFAULT NULL,
  `unread` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B1FF28003414710B` (`agent_id`),
  KEY `IDX_B1FF28001ADED311` (`discussion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agent_role`
--

DROP TABLE IF EXISTS `agent_role`;
CREATE TABLE IF NOT EXISTS `agent_role` (
  `agent_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`agent_id`,`role_id`),
  KEY `IDX_FAF230893414710B` (`agent_id`),
  KEY `IDX_FAF23089D60322AC` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agent_role`
--

INSERT INTO `agent_role` (`agent_id`, `role_id`) VALUES
(1, 1),
(1, 7),
(1, 10),
(2, 7),
(2, 9),
(2, 10),
(3, 1),
(3, 7),
(3, 10),
(4, 10),
(6, 10),
(7, 10);

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

DROP TABLE IF EXISTS `alert`;
CREATE TABLE IF NOT EXISTS `alert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reminder_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alert_bill`
--

DROP TABLE IF EXISTS `alert_bill`;
CREATE TABLE IF NOT EXISTS `alert_bill` (
  `alert_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  PRIMARY KEY (`alert_id`,`bill_id`),
  KEY `IDX_F591CB6793035F72` (`alert_id`),
  KEY `IDX_F591CB671A8C12F5` (`bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short` varchar(3000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(10000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_at` datetime DEFAULT NULL,
  `publish_end_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_center_of_interest` tinyint(1) DEFAULT NULL,
  `is_product` tinyint(1) DEFAULT NULL,
  `is_partenaire` tinyint(1) DEFAULT NULL,
  `is_team` tinyint(1) DEFAULT NULL,
  `is_testimony` tinyint(1) DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_23A0E66F675F31B` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

DROP TABLE IF EXISTS `bill`;
CREATE TABLE IF NOT EXISTS `bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `private_comment_id` int(11) DEFAULT NULL,
  `public_comment_id` int(11) DEFAULT NULL,
  `income_statistic_id` int(11) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay` double NOT NULL,
  `pay_received` double DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `limit_date_at` datetime DEFAULT NULL,
  `payed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7A2119E3F5ACD764` (`private_comment_id`),
  UNIQUE KEY `UNIQ_7A2119E362FBE97E` (`public_comment_id`),
  KEY `IDX_7A2119E3B622266F` (`income_statistic_id`),
  KEY `IDX_7A2119E3E7A1254A` (`contact_id`),
  KEY `IDX_7A2119E319EB6921` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_setting`
--

DROP TABLE IF EXISTS `blog_setting`;
CREATE TABLE IF NOT EXISTS `blog_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_file` tinyint(1) DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rib` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_delay` int(11) DEFAULT NULL,
  `max_credit` double DEFAULT NULL,
  `is_activated` tinyint(1) NOT NULL,
  `is_prospect` tinyint(1) NOT NULL,
  `denomination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C7440455F8697D13` (`comment_id`),
  KEY `IDX_C74404553414710B` (`agent_id`),
  KEY `IDX_C74404551A8C12F5` (`bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(3000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_principal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4C62E638F8697D13` (`comment_id`),
  UNIQUE KEY `UNIQ_4C62E638F5B7AF75` (`address_id`),
  KEY `IDX_4C62E63819EB6921` (`client_id`),
  KEY `IDX_4C62E638A53A8AA` (`provider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `culture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `culture`) VALUES
(1, 'Afghanistan', 'AF'),
(2, 'Albania', 'AL'),
(3, 'Algeria', 'DZ'),
(4, 'American Samoa', 'AS'),
(5, 'Andorra', 'AD'),
(6, 'Angola', 'AO'),
(7, 'Anguilla', 'AI'),
(8, 'Antarctica', 'AQ'),
(9, 'Antigua and Barbuda', 'AG'),
(10, 'Argentina', 'AR'),
(11, 'Armenia', 'AM'),
(12, 'Aruba', 'AW'),
(13, 'Australia', 'AU'),
(14, 'Austria', 'AT'),
(15, 'Azerbaijan', 'AZ'),
(16, 'Bahamas', 'BS'),
(17, 'Bahrain', 'BH'),
(18, 'Bangladesh', 'BD'),
(19, 'Barbados', 'BB'),
(20, 'Belarus', 'BY'),
(21, 'Belgium', 'BE'),
(22, 'Belize', 'BZ'),
(23, 'Benin', 'BJ'),
(24, 'Bermuda', 'BM'),
(25, 'Bhutan', 'BT'),
(26, 'Bolivia', 'BO'),
(27, 'Bosnia and Herzegovina', 'BA'),
(28, 'Botswana', 'BW'),
(29, 'Bouvet Island', 'BV'),
(30, 'Brazil', 'BR'),
(31, 'British Indian Ocean Territory', 'IO'),
(32, 'Brunei Darussalam', 'BN'),
(33, 'Bulgaria', 'BG'),
(34, 'Burkina Faso', 'BF'),
(35, 'Burundi', 'BI'),
(36, 'Cambodia', 'KH'),
(37, 'Cameroon', 'CM'),
(38, 'Canada', 'CA'),
(39, 'Cape Verde', 'CV'),
(40, 'Cayman Islands', 'KY'),
(41, 'Central African Republic', 'CF'),
(42, 'Chad', 'TD'),
(43, 'Chile', 'CL'),
(44, 'China', 'CN'),
(45, 'Christmas Island', 'CX'),
(46, 'Cocos (Keeling) Islands', 'CC'),
(47, 'Colombia', 'CO'),
(48, 'Comoros', 'KM'),
(49, 'Congo', 'CG'),
(50, 'Congo, the Democratic Republic of the', 'CD'),
(51, 'Cook Islands', 'CK'),
(52, 'Costa Rica', 'CR'),
(53, 'Cote D\'Ivoire', 'CI'),
(54, 'Croatia', 'HR'),
(55, 'Cuba', 'CU'),
(56, 'Cyprus', 'CY'),
(57, 'Czech Republic', 'CZ'),
(58, 'Denmark', 'DK'),
(59, 'Djibouti', 'DJ'),
(60, 'Dominica', 'DM'),
(61, 'Dominican Republic', 'DO'),
(62, 'Ecuador', 'EC'),
(63, 'Egypt', 'EG'),
(64, 'El Salvador', 'SV'),
(65, 'Equatorial Guinea', 'GQ'),
(66, 'Eritrea', 'ER'),
(67, 'Estonia', 'EE'),
(68, 'Ethiopia', 'ET'),
(69, 'Falkland Islands (Malvinas)', 'FK'),
(70, 'Faroe Islands', 'FO'),
(71, 'Fiji', 'FJ'),
(72, 'Finland', 'FI'),
(73, 'France', 'FR'),
(74, 'French Guiana', 'GF'),
(75, 'French Polynesia', 'PF'),
(76, 'French Southern Territories', 'TF'),
(77, 'Gabon', 'GA'),
(78, 'Gambia', 'GM'),
(79, 'Georgia', 'GE'),
(80, 'Germany', 'DE'),
(81, 'Ghana', 'GH'),
(82, 'Gibraltar', 'GI'),
(83, 'Greece', 'GR'),
(84, 'Greenland', 'GL'),
(85, 'Grenada', 'GD'),
(86, 'Guadeloupe', 'GP'),
(87, 'Guam', 'GU'),
(88, 'Guatemala', 'GT'),
(89, 'Guinea', 'GN'),
(90, 'Guinea-Bissau', 'GW'),
(91, 'Guyana', 'GY'),
(92, 'Haiti', 'HT'),
(93, 'Heard Island and Mcdonald Islands', 'HM'),
(94, 'Holy See (Vatican City State)', 'VA'),
(95, 'Honduras', 'HN'),
(96, 'Hong Kong', 'HK'),
(97, 'Hungary', 'HU'),
(98, 'Iceland', 'IS'),
(99, 'India', 'IN'),
(100, 'Indonesia', 'ID'),
(101, 'Iran, Islamic Republic of', 'IR'),
(102, 'Iraq', 'IQ'),
(103, 'Ireland', 'IE'),
(104, 'Israel', 'IL'),
(105, 'Italy', 'IT'),
(106, 'Jamaica', 'JM'),
(107, 'Japan', 'JP'),
(108, 'Jordan', 'JO'),
(109, 'Kazakhstan', 'KZ'),
(110, 'Kenya', 'KE'),
(111, 'Kiribati', 'KI'),
(112, 'Korea, Democratic People\'s Republic of', 'KP'),
(113, 'Korea, Republic of', 'KR'),
(114, 'Kuwait', 'KW'),
(115, 'Kyrgyzstan', 'KG'),
(116, 'Lao People\'s Democratic Republic', 'LA'),
(117, 'Latvia', 'LV'),
(118, 'Lebanon', 'LB'),
(119, 'Lesotho', 'LS'),
(120, 'Liberia', 'LR'),
(121, 'Libyan Arab Jamahiriya', 'LY'),
(122, 'Liechtenstein', 'LI'),
(123, 'Lithuania', 'LT'),
(124, 'Luxembourg', 'LU'),
(125, 'Macao', 'MO'),
(126, 'Macedonia, the Former Yugoslav Republic of', 'MK'),
(127, 'Madagascar', 'MG'),
(128, 'Malawi', 'MW'),
(129, 'Malaysia', 'MY'),
(130, 'Maldives', 'MV'),
(131, 'Mali', 'ML'),
(132, 'Malta', 'MT'),
(133, 'Marshall Islands', 'MH'),
(134, 'Martinique', 'MQ'),
(135, 'Mauritania', 'MR'),
(136, 'Mauritius', 'MU'),
(137, 'Mayotte', 'YT'),
(138, 'Mexico', 'MX'),
(139, 'Micronesia, Federated States of', 'FM'),
(140, 'Moldova, Republic of', 'MD'),
(141, 'Monaco', 'MC'),
(142, 'Mongolia', 'MN'),
(143, 'Montserrat', 'MS'),
(144, 'Morocco', 'MA'),
(145, 'Mozambique', 'MZ'),
(146, 'Myanmar', 'MM'),
(147, 'Namibia', 'NA'),
(148, 'Nauru', 'NR'),
(149, 'Nepal', 'NP'),
(150, 'Netherlands', 'NL'),
(151, 'Netherlands Antilles', 'AN'),
(152, 'New Caledonia', 'NC'),
(153, 'New Zealand', 'NZ'),
(154, 'Nicaragua', 'NI'),
(155, 'Niger', 'NE'),
(156, 'Nigeria', 'NG'),
(157, 'Niue', 'NU'),
(158, 'Norfolk Island', 'NF'),
(159, 'Northern Mariana Islands', 'MP'),
(160, 'Norway', 'NO'),
(161, 'Oman', 'OM'),
(162, 'Pakistan', 'PK'),
(163, 'Palau', 'PW'),
(164, 'Palestinian Territory, Occupied', 'PS'),
(165, 'Panama', 'PA'),
(166, 'Papua New Guinea', 'PG'),
(167, 'Paraguay', 'PY'),
(168, 'Peru', 'PE'),
(169, 'Philippines', 'PH'),
(170, 'Pitcairn', 'PN'),
(171, 'Poland', 'PL'),
(172, 'Portugal', 'PT'),
(173, 'Puerto Rico', 'PR'),
(174, 'Qatar', 'QA'),
(175, 'Reunion', 'RE'),
(176, 'Romania', 'RO'),
(177, 'Russian Federation', 'RU'),
(178, 'Rwanda', 'RW'),
(179, 'Saint Helena', 'SH'),
(180, 'Saint Kitts and Nevis', 'KN'),
(181, 'Saint Lucia', 'LC'),
(182, 'Saint Pierre and Miquelon', 'PM'),
(183, 'Saint Vincent and the Grenadines', 'VC'),
(184, 'Samoa', 'WS'),
(185, 'San Marino', 'SM'),
(186, 'Sao Tome and Principe', 'ST'),
(187, 'Saudi Arabia', 'SA'),
(188, 'Senegal', 'SN'),
(189, 'Serbia and Montenegro', 'CS'),
(190, 'Seychelles', 'SC'),
(191, 'Sierra Leone', 'SL'),
(192, 'Singapore', 'SG'),
(193, 'Slovakia', 'SK'),
(194, 'Slovenia', 'SI'),
(195, 'Solomon Islands', 'SB'),
(196, 'Somalia', 'SO'),
(197, 'South Africa', 'ZA'),
(198, 'South Georgia and the South Sandwich Islands', 'GS'),
(199, 'Spain', 'ES'),
(200, 'Sri Lanka', 'LK'),
(201, 'Sudan', 'SD'),
(202, 'Suriname', 'SR'),
(203, 'Svalbard and Jan Mayen', 'SJ'),
(204, 'Swaziland', 'SZ'),
(205, 'Sweden', 'SE'),
(206, 'Switzerland', 'CH'),
(207, 'Syrian Arab Republic', 'SY'),
(208, 'Taiwan, Province of China', 'TW'),
(209, 'Tajikistan', 'TJ'),
(210, 'Tanzania, United Republic of', 'TZ'),
(211, 'Thailand', 'TH'),
(212, 'Timor-Leste', 'TL'),
(213, 'Togo', 'TG'),
(214, 'Tokelau', 'TK'),
(215, 'Tonga', 'TO'),
(216, 'Trinidad and Tobago', 'TT'),
(217, 'Tunisia', 'TN'),
(218, 'Turkey', 'TR'),
(219, 'Turkmenistan', 'TM'),
(220, 'Turks and Caicos Islands', 'TC'),
(221, 'Tuvalu', 'TV'),
(222, 'Uganda', 'UG'),
(223, 'Ukraine', 'UA'),
(224, 'United Arab Emirates', 'AE'),
(225, 'United Kingdom', 'GB'),
(226, 'United States', 'US'),
(227, 'United States Minor Outlying Islands', 'UM'),
(228, 'Uruguay', 'UY'),
(229, 'Uzbekistan', 'UZ'),
(230, 'Vanuatu', 'VU'),
(231, 'Venezuela', 'VE'),
(232, 'Viet Nam', 'VN'),
(233, 'Virgin Islands, British', 'VG'),
(234, 'Virgin Islands, U.s.', 'VI'),
(235, 'Wallis and Futuna', 'WF'),
(236, 'Western Sahara', 'EH'),
(237, 'Yemen', 'YE'),
(238, 'Zambia', 'ZM'),
(239, 'Zimbabwe', 'ZW');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double NOT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `name`, `symbol`, `rate`, `country_code`, `country`, `is_default`, `created_at`) VALUES
(1, 'AED', 'AED', 0, 'AED', 'United Arab Emirates Dirham', 0, '2019-11-08 00:00:00'),
(2, 'AFN', 'AFN', 0, 'AFN', 'Afghan Afghani', 0, '2019-11-08 00:00:00'),
(3, 'ALL', 'ALL', 0, 'ALL', 'Albanian Lek', 0, '2019-11-08 00:00:00'),
(4, 'AMD', 'AMD', 0, 'AMD', 'Armenian Dram', 0, '2019-11-08 00:00:00'),
(5, 'ANG', 'ANG', 0, 'ANG', 'Netherlands Antillean Guilder', 0, '2019-11-08 00:00:00'),
(6, 'AOA', 'AOA', 0, 'AOA', 'Angolan Kwanza', 0, '2019-11-08 00:00:00'),
(7, 'ARS', 'ARS', 0, 'ARS', 'Argentine Peso', 0, '2019-11-08 00:00:00'),
(8, 'AUD', 'AUD', 0, 'AUD', 'Australian Dollar', 0, '2019-11-08 00:00:00'),
(9, 'AWG', 'AWG', 0, 'AWG', 'Aruban Florin', 0, '2019-11-08 00:00:00'),
(10, 'AZN', 'AZN', 0, 'AZN', 'Azerbaijani Manat', 0, '2019-11-08 00:00:00'),
(11, 'BAM', 'BAM', 0, 'BAM', 'Bosnia &amp; Herzegovina Convertible Mark', 0, '2019-11-08 00:00:00'),
(12, 'BBD', 'BBD', 0, 'BBD', 'Barbadian Dollar', 0, '2019-11-08 00:00:00'),
(13, 'BDT', 'BDT', 0, 'BDT', 'Bangladeshi Taka', 0, '2019-11-08 00:00:00'),
(14, 'BGN', 'BGN', 0, 'BGN', 'Bulgarian Lev', 0, '2019-11-08 00:00:00'),
(15, 'BHD', 'BHD', 0, 'BHD', 'Bahraini Dinar', 0, '2019-11-08 00:00:00'),
(16, 'BIF', 'BIF', 0, 'BIF', 'Burundian Franc', 0, '2019-11-08 00:00:00'),
(17, 'BMD', 'BMD', 0, 'BMD', 'Bermudian Dollar', 0, '2019-11-08 00:00:00'),
(18, 'BND', 'BND', 0, 'BND', 'Brunei Dollar', 0, '2019-11-08 00:00:00'),
(19, 'BOB', 'BOB', 0, 'BOB', 'Bolivian Boliviano', 0, '2019-11-08 00:00:00'),
(20, 'BRL', 'BRL', 0, 'BRL', 'Brazilian Real', 0, '2019-11-08 00:00:00'),
(21, 'BSD', 'BSD', 0, 'BSD', 'Bahamian Dollar', 0, '2019-11-08 00:00:00'),
(22, 'BTN', 'BTN', 0, 'BTN', 'Bhutanese Ngultrum', 0, '2019-11-08 00:00:00'),
(23, 'BWP', 'BWP', 0, 'BWP', 'Botswana Pula', 0, '2019-11-08 00:00:00'),
(24, 'BYN', 'BYN', 0, 'BYN', 'Belarus Ruble', 0, '2019-11-08 00:00:00'),
(25, 'BZD', 'BZD', 0, 'BZD', 'Belize Dollar', 0, '2019-11-08 00:00:00'),
(26, 'CAD', 'CAD', 0, 'CAD', 'Canadian Dollar', 0, '2019-11-08 00:00:00'),
(27, 'CDF', 'CDF', 0, 'CDF', 'Congolese Franc', 0, '2019-11-08 00:00:00'),
(28, 'CHF', 'CHF', 0, 'CHF', 'Swiss Franc', 0, '2019-11-08 00:00:00'),
(29, 'CLP', 'CLP', 0, 'CLP', 'Chilean Peso', 0, '2019-11-08 00:00:00'),
(30, 'CNY', 'CNY', 0, 'CNY', 'Chinese Yuan', 0, '2019-11-08 00:00:00'),
(31, 'COP', 'COP', 0, 'COP', 'Colombian Peso', 0, '2019-11-08 00:00:00'),
(32, 'CRC', 'CRC', 0, 'CRC', 'Costa Rican Colon', 0, '2019-11-08 00:00:00'),
(33, 'CUC', 'CUC', 0, 'CUC', 'Cuban Convertible Peso', 0, '2019-11-08 00:00:00'),
(34, 'CVE', 'CVE', 0, 'CVE', 'Cape Verdean Escudo', 0, '2019-11-08 00:00:00'),
(35, 'CZK', 'CZK', 0, 'CZK', 'Czech Republic Koruna', 0, '2019-11-08 00:00:00'),
(36, 'DJF', 'DJF', 0, 'DJF', 'Djiboutian Franc', 0, '2019-11-08 00:00:00'),
(37, 'DKK', 'DKK', 0, 'DKK', 'Danish Krone', 0, '2019-11-08 00:00:00'),
(38, 'DOP', 'DOP', 0, 'DOP', 'Dominican Peso', 0, '2019-11-08 00:00:00'),
(39, 'DZD', 'DZD', 0, 'DZD', 'Algerian Dinar', 0, '2019-11-08 00:00:00'),
(40, 'EGP', 'EGP', 0, 'EGP', 'Egyptian Pound', 0, '2019-11-08 00:00:00'),
(41, 'ERN', 'ERN', 0, 'ERN', 'Eritrean Nakfa', 0, '2019-11-08 00:00:00'),
(42, 'ETB', 'ETB', 0, 'ETB', 'Ethiopian Birr', 0, '2019-11-08 00:00:00'),
(43, 'EUR', 'EUR', 0, 'EUR', 'Euro', 1, '2019-11-08 00:00:00'),
(44, 'FJD', 'FJD', 0, 'FJD', 'Fiji Dollar', 0, '2019-11-08 00:00:00'),
(45, 'GBP', 'GBP', 0, 'GBP', 'British Pound Sterling', 0, '2019-11-08 00:00:00'),
(46, 'GEL', 'GEL', 0, 'GEL', 'Georgian Lari', 0, '2019-11-08 00:00:00'),
(47, 'GHS', 'GHS', 0, 'GHS', 'Ghanaian Cedi', 0, '2019-11-08 00:00:00'),
(48, 'GIP', 'GIP', 0, 'GIP', 'Gibraltar Pound', 0, '2019-11-08 00:00:00'),
(49, 'GMD', 'GMD', 0, 'GMD', 'Gambian Dalasi', 0, '2019-11-08 00:00:00'),
(50, 'GNF', 'GNF', 0, 'GNF', 'Guinea Franc', 0, '2019-11-08 00:00:00'),
(51, 'GTQ', 'GTQ', 0, 'GTQ', 'Guatemalan Quetzal', 0, '2019-11-08 00:00:00'),
(52, 'GYD', 'GYD', 0, 'GYD', 'Guyanaese Dollar', 0, '2019-11-08 00:00:00'),
(53, 'HKD', 'HKD', 0, 'HKD', 'Hong Kong Dollar', 0, '2019-11-08 00:00:00'),
(54, 'HNL', 'HNL', 0, 'HNL', 'Honduran Lempira', 0, '2019-11-08 00:00:00'),
(55, 'HRK', 'HRK', 0, 'HRK', 'Croatian Kuna', 0, '2019-11-08 00:00:00'),
(56, 'HTG', 'HTG', 0, 'HTG', 'Haiti Gourde', 0, '2019-11-08 00:00:00'),
(57, 'HUF', 'HUF', 0, 'HUF', 'Hungarian Forint', 0, '2019-11-08 00:00:00'),
(58, 'IDR', 'IDR', 0, 'IDR', 'Indonesian Rupiah', 0, '2019-11-08 00:00:00'),
(59, 'ILS', 'ILS', 0, 'ILS', 'Israeli Shekel', 0, '2019-11-08 00:00:00'),
(60, 'INR', 'INR', 0, 'INR', 'Indian Rupee', 0, '2019-11-08 00:00:00'),
(61, 'IQD', 'IQD', 0, 'IQD', 'Iraqi Dinar', 0, '2019-11-08 00:00:00'),
(62, 'IRR', 'IRR', 0, 'IRR', 'Iranian Rial', 0, '2019-11-08 00:00:00'),
(63, 'ISK', 'ISK', 0, 'ISK', 'Icelandic Krona', 0, '2019-11-08 00:00:00'),
(64, 'JMD', 'JMD', 0, 'JMD', 'Jamaican Dollar', 0, '2019-11-08 00:00:00'),
(65, 'JOD', 'JOD', 0, 'JOD', 'Jordanian Dinar', 0, '2019-11-08 00:00:00'),
(66, 'JPY', 'JPY', 0, 'JPY', 'Japanese Yen', 0, '2019-11-08 00:00:00'),
(67, 'KES', 'KES', 0, 'KES', 'Kenyan Shilling', 0, '2019-11-08 00:00:00'),
(68, 'KGS', 'KGS', 0, 'KGS', 'Kyrgystani Som', 0, '2019-11-08 00:00:00'),
(69, 'KHR', 'KHR', 0, 'KHR', 'Cambodian Riel', 0, '2019-11-08 00:00:00'),
(70, 'KMF', 'KMF', 0, 'KMF', 'Comorian Franc', 0, '2019-11-08 00:00:00'),
(71, 'KPW', 'KPW', 0, 'KPW', 'North Korean Won', 0, '2019-11-08 00:00:00'),
(72, 'KRW', 'KRW', 0, 'KRW', 'South Korean Won', 0, '2019-11-08 00:00:00'),
(73, 'KWD', 'KWD', 0, 'KWD', 'Kuwaiti Dinar', 0, '2019-11-08 00:00:00'),
(74, 'KYD', 'KYD', 0, 'KYD', 'Cayman Islands Dollar', 0, '2019-11-08 00:00:00'),
(75, 'KZT', 'KZT', 0, 'KZT', 'Kazakhstan Tenge', 0, '2019-11-08 00:00:00'),
(76, 'LAK', 'LAK', 0, 'LAK', 'Laotian Kip', 0, '2019-11-08 00:00:00'),
(77, 'LBP', 'LBP', 0, 'LBP', 'Lebanese Pound', 0, '2019-11-08 00:00:00'),
(78, 'LKR', 'LKR', 0, 'LKR', 'Sri Lankan Rupee', 0, '2019-11-08 00:00:00'),
(79, 'LRD', 'LRD', 0, 'LRD', 'Liberian Dollar', 0, '2019-11-08 00:00:00'),
(80, 'LSL', 'LSL', 0, 'LSL', 'Lesotho Loti', 0, '2019-11-08 00:00:00'),
(81, 'LYD', 'LYD', 0, 'LYD', 'Libyan Dinar', 0, '2019-11-08 00:00:00'),
(82, 'MAD', 'MAD', 0, 'MAD', 'Moroccan Dirham', 0, '2019-11-08 00:00:00'),
(83, 'MDL', 'MDL', 0, 'MDL', 'Moldovan Leu', 0, '2019-11-08 00:00:00'),
(84, 'MGA', 'MGA', 0, 'MGA', 'Malagasy Ariary', 0, '2019-11-08 00:00:00'),
(85, 'MKD', 'MKD', 0, 'MKD', 'Macedonian Denar', 0, '2019-11-08 00:00:00'),
(86, 'MMK', 'MMK', 0, 'MMK', 'Myanma Kyat', 0, '2019-11-08 00:00:00'),
(87, 'MNT', 'MNT', 0, 'MNT', 'Mongolian Tugrik', 0, '2019-11-08 00:00:00'),
(88, 'MOP', 'MOP', 0, 'MOP', 'Macau Pataca', 0, '2019-11-08 00:00:00'),
(89, 'MRO', 'MRO', 0, 'MRO', 'Mauritanian Ouguiya', 0, '2019-11-08 00:00:00'),
(90, 'MUR', 'MUR', 0, 'MUR', 'Mauritian Rupee', 0, '2019-11-08 00:00:00'),
(91, 'MVR', 'MVR', 0, 'MVR', 'Maldivian Rufiyaa', 0, '2019-11-08 00:00:00'),
(92, 'MWK', 'MWK', 0, 'MWK', 'Malawi Kwacha', 0, '2019-11-08 00:00:00'),
(93, 'MXN', 'MXN', 0, 'MXN', 'Mexican Peso', 0, '2019-11-08 00:00:00'),
(94, 'MYR', 'MYR', 0, 'MYR', 'Malaysian Ringgit', 0, '2019-11-08 00:00:00'),
(95, 'MZN', 'MZN', 0, 'MZN', 'Mozambican Metical', 0, '2019-11-08 00:00:00'),
(96, 'NAD', 'NAD', 0, 'NAD', 'Namibian Dollar', 0, '2019-11-08 00:00:00'),
(97, 'NGN', 'NGN', 0, 'NGN', 'Nigerian Naira', 0, '2019-11-08 00:00:00'),
(98, 'NIO', 'NIO', 0, 'NIO', 'Nicaragua Cordoba', 0, '2019-11-08 00:00:00'),
(99, 'NOK', 'NOK', 0, 'NOK', 'Norwegian Krone', 0, '2019-11-08 00:00:00'),
(100, 'NPR', 'NPR', 0, 'NPR', 'Nepalese Rupee', 0, '2019-11-08 00:00:00'),
(101, 'NZD', 'NZD', 0, 'NZD', 'New Zealand Dollar', 0, '2019-11-08 00:00:00'),
(102, 'OMR', 'OMR', 0, 'OMR', 'Omani Rial', 0, '2019-11-08 00:00:00'),
(103, 'PAB', 'PAB', 0, 'PAB', 'Panamanian Balboa', 0, '2019-11-08 00:00:00'),
(104, 'PEN', 'PEN', 0, 'PEN', 'Peruvian Nuevo Sol', 0, '2019-11-08 00:00:00'),
(105, 'PGK', 'PGK', 0, 'PGK', 'Papua New Guinean Kina', 0, '2019-11-08 00:00:00'),
(106, 'PHP', 'PHP', 0, 'PHP', 'Philippine Peso', 0, '2019-11-08 00:00:00'),
(107, 'PKR', 'PKR', 0, 'PKR', 'Pakistani Rupee', 0, '2019-11-08 00:00:00'),
(108, 'PLN', 'PLN', 0, 'PLN', 'Polish Zloty', 0, '2019-11-08 00:00:00'),
(109, 'PYG', 'PYG', 0, 'PYG', 'Paraguayan Guarani', 0, '2019-11-08 00:00:00'),
(110, 'QAR', 'QAR', 0, 'QAR', 'Qatari Riyal', 0, '2019-11-08 00:00:00'),
(111, 'RON', 'RON', 0, 'RON', 'Romanian Leu', 0, '2019-11-08 00:00:00'),
(112, 'RSD', 'RSD', 0, 'RSD', 'Serbian Dinar', 0, '2019-11-08 00:00:00'),
(113, 'RUB', 'RUB', 0, 'RUB', 'Russian Ruble', 0, '2019-11-08 00:00:00'),
(114, 'RWF', 'RWF', 0, 'RWF', 'Rwanda Franc', 0, '2019-11-08 00:00:00'),
(115, 'SAR', 'SAR', 0, 'SAR', 'Saudi Riyal', 0, '2019-11-08 00:00:00'),
(116, 'SBD', 'SBD', 0, 'SBD', 'Solomon Islands Dollar', 0, '2019-11-08 00:00:00'),
(117, 'SCR', 'SCR', 0, 'SCR', 'Seychellois Rupee', 0, '2019-11-08 00:00:00'),
(118, 'SDG', 'SDG', 0, 'SDG', 'Sudanese Pound', 0, '2019-11-08 00:00:00'),
(119, 'SEK', 'SEK', 0, 'SEK', 'Swedish Krona', 0, '2019-11-08 00:00:00'),
(120, 'SGD', 'SGD', 0, 'SGD', 'Singapore Dollar', 0, '2019-11-08 00:00:00'),
(121, 'SHP', 'SHP', 0, 'SHP', 'Saint Helena Pound', 0, '2019-11-08 00:00:00'),
(122, 'SLL', 'SLL', 0, 'SLL', 'Sierra Leonean Leone', 0, '2019-11-08 00:00:00'),
(123, 'SOS', 'SOS', 0, 'SOS', 'Somali Shilling', 0, '2019-11-08 00:00:00'),
(124, 'SRD', 'SRD', 0, 'SRD', 'Surinamese Dollar', 0, '2019-11-08 00:00:00'),
(125, 'SSP', 'SSP', 0, 'SSP', 'South Sudanese Pound', 0, '2019-11-08 00:00:00'),
(126, 'STD', 'STD', 0, 'STD', 'Sao Tome and Principe Dobra', 0, '2019-11-08 00:00:00'),
(127, 'SYP', 'SYP', 0, 'SYP', 'Syrian Pound', 0, '2019-11-08 00:00:00'),
(128, 'SZL', 'SZL', 0, 'SZL', 'Swazi Lilangeni', 0, '2019-11-08 00:00:00'),
(129, 'THB', 'THB', 0, 'THB', 'Thai Baht', 0, '2019-11-08 00:00:00'),
(130, 'TJS', 'TJS', 0, 'TJS', 'Tajikistan Somoni', 0, '2019-11-08 00:00:00'),
(131, 'TMT', 'TMT', 0, 'TMT', 'Turkmenistani Manat', 0, '2019-11-08 00:00:00'),
(132, 'TND', 'TND', 0, 'TND', 'Tunisian Dinar', 0, '2019-11-08 00:00:00'),
(133, 'TOP', 'TOP', 0, 'TOP', 'Tonga Paanga', 0, '2019-11-08 00:00:00'),
(134, 'TRY', 'TRY', 0, 'TRY', 'Turkish Lira', 0, '2019-11-08 00:00:00'),
(135, 'TTD', 'TTD', 0, 'TTD', 'Trinidad and Tobago Dollar', 0, '2019-11-08 00:00:00'),
(136, 'TWD', 'TWD', 0, 'TWD', 'New Taiwan Dollar', 0, '2019-11-08 00:00:00'),
(137, 'TZS', 'TZS', 0, 'TZS', 'Tanzanian Shilling', 0, '2019-11-08 00:00:00'),
(138, 'UAH', 'UAH', 0, 'UAH', 'Ukrainian Hryvnia', 0, '2019-11-08 00:00:00'),
(139, 'UGX', 'UGX', 0, 'UGX', 'Ugandan Shilling', 0, '2019-11-08 00:00:00'),
(140, 'USD', 'USD', 0, 'USD', 'United States Dollar', 0, '2019-11-08 00:00:00'),
(141, 'UYU', 'UYU', 0, 'UYU', 'Uruguayan Peso', 0, '2019-11-08 00:00:00'),
(142, 'UZS', 'UZS', 0, 'UZS', 'Uzbekistan Som', 0, '2019-11-08 00:00:00'),
(143, 'VEF', 'VEF', 0, 'VEF', 'Venezuelan Bolivar', 0, '2019-11-08 00:00:00'),
(144, 'VND', 'VND', 0, 'VND', 'Vietnamese Dong', 0, '2019-11-08 00:00:00'),
(145, 'VUV', 'VUV', 0, 'VUV', 'Vanuatu Vatu', 0, '2019-11-08 00:00:00'),
(146, 'WST', 'WST', 0, 'WST', 'Samoan Tala', 0, '2019-11-08 00:00:00'),
(147, 'XAF', 'XAF', 0, 'XAF', 'Central African CFA franc', 0, '2019-11-08 00:00:00'),
(148, 'XCD', 'XCD', 0, 'XCD', 'East Caribbean Dollar', 0, '2019-11-08 00:00:00'),
(149, 'XOF', 'XOF', 0, 'XOF', 'West African CFA franc', 0, '2019-11-08 00:00:00'),
(150, 'XPF', 'XPF', 0, 'XPF', 'CFP Franc', 0, '2019-11-08 00:00:00'),
(151, 'YER', 'YER', 0, 'YER', 'Yemeni Rial', 0, '2019-11-08 00:00:00'),
(152, 'ZAR', 'ZAR', 0, 'ZAR', 'South African Rand', 0, '2019-11-08 00:00:00'),
(153, 'ZMW', 'ZMW', 0, 'ZMW', 'Zambian Kwacha', 0, '2019-11-08 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE IF NOT EXISTS `delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) DEFAULT NULL,
  `package` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3781EC106BF700BD` (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_status`
--

DROP TABLE IF EXISTS `delivery_status`;
CREATE TABLE IF NOT EXISTS `delivery_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_status`
--

INSERT INTO `delivery_status` (`id`, `name`, `display_name`) VALUES
(1, 'STATUS_BILLED', NULL),
(2, 'STATUS_NOT_BILLED', NULL),
(3, 'STATUS_CANCELED', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discussion`
--

DROP TABLE IF EXISTS `discussion`;
CREATE TABLE IF NOT EXISTS `discussion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ean_code`
--

DROP TABLE IF EXISTS `ean_code`;
CREATE TABLE IF NOT EXISTS `ean_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E04EF53F92F3E70` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `imei_code`
--

DROP TABLE IF EXISTS `imei_code`;
CREATE TABLE IF NOT EXISTS `imei_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ean_code_id` int(11) DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serie_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_47401C75D6307EED` (`ean_code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_statistic`
--

DROP TABLE IF EXISTS `income_statistic`;
CREATE TABLE IF NOT EXISTS `income_statistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_total` double NOT NULL,
  `sell_total` double NOT NULL,
  `percent_income` double NOT NULL,
  `income` double NOT NULL,
  `pay_received` double DEFAULT NULL,
  `limit_date_at` datetime DEFAULT NULL,
  `pay_date_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) DEFAULT NULL,
  `item_groupe_id` int(11) DEFAULT NULL,
  `item_brand_id` int(11) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `ref` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sell_price` double NOT NULL,
  `purchase_price` double NOT NULL,
  `stock` int(11) NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_erasable` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `imei_code_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1F1B251EF8697D13` (`comment_id`),
  UNIQUE KEY `UNIQ_1F1B251EA4D07301` (`imei_code_id`),
  KEY `IDX_1F1B251E769F237C` (`item_groupe_id`),
  KEY `IDX_1F1B251E28F818C3` (`item_brand_id`),
  KEY `IDX_1F1B251EB2A824D8` (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_brand`
--

DROP TABLE IF EXISTS `item_brand`;
CREATE TABLE IF NOT EXISTS `item_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_groupe`
--

DROP TABLE IF EXISTS `item_groupe`;
CREATE TABLE IF NOT EXISTS `item_groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_provider`
--

DROP TABLE IF EXISTS `item_provider`;
CREATE TABLE IF NOT EXISTS `item_provider` (
  `item_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`,`provider_id`),
  KEY `IDX_FEC9BB57126F525E` (`item_id`),
  KEY `IDX_FEC9BB57A53A8AA` (`provider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `license`
--

DROP TABLE IF EXISTS `license`;
CREATE TABLE IF NOT EXISTS `license` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_enable` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `end_date_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discussion_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307F1ADED311` (`discussion_id`),
  KEY `IDX_B6BD307F3414710B` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200307125834', '2020-03-07 12:58:57'),
('20200328160657', '2020-03-28 16:08:00'),
('20200412234109', '2020-04-12 23:41:42'),
('20200503123419', '2020-05-03 12:36:01'),
('20200529201241', '2020-05-29 20:13:18'),
('20200529203915', '2020-05-29 20:39:44'),
('20200530130938', '2020-05-30 13:10:37'),
('20200607084037', '2020-06-07 08:47:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

DROP TABLE IF EXISTS `order_status`;
CREATE TABLE IF NOT EXISTS `order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `name`, `display_name`) VALUES
(1, 'STATUS_QUOTE', 'Devis'),
(2, 'STATUS_PREORDER', 'Cmd à valider'),
(3, 'STATUS_PREREFUND', 'Avoir à valider'),
(4, 'STATUS_VALID', 'Demande revalidation'),
(5, 'STATUS_ORDER', 'Commande'),
(6, 'STATUS_REFUND', 'Avoir'),
(7, 'STATUS_BILL', 'Commande facturée'),
(8, 'STATUS_REFUNDBILL', 'Avoir facturé'),
(9, 'STATUS_CLOSED', 'Commande clôturée'),
(10, 'STATUS_REFUNDCLOSED', 'Avoir payé & clôturé');

-- --------------------------------------------------------

--
-- Table structure for table `pays`
--

DROP TABLE IF EXISTS `pays`;
CREATE TABLE IF NOT EXISTS `pays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privilege`
--

DROP TABLE IF EXISTS `privilege`;
CREATE TABLE IF NOT EXISTS `privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_write` tinyint(1) NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `is_update` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `is_send_mail` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privilege`
--

INSERT INTO `privilege` (`id`, `is_write`, `is_read`, `is_update`, `is_delete`, `is_send_mail`, `created_at`) VALUES
(1, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(2, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(3, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(4, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(5, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(6, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(7, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(8, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(9, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(10, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(11, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(12, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(13, 1, 1, 1, 1, 1, '2020-04-05 11:39:14'),
(14, 1, 1, 1, 1, 1, '2020-04-05 11:39:15'),
(15, 1, 1, 1, 1, 1, '2020-04-05 11:39:15'),
(16, 1, 1, 1, 1, 1, '2020-04-05 11:39:15'),
(17, 1, 1, 1, 1, 1, '2020-04-05 11:39:15'),
(18, 0, 0, 0, 0, 0, '2020-04-05 11:39:16'),
(19, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(20, 1, 1, 1, 1, 1, '2020-04-05 11:39:15'),
(21, 1, 1, 1, 1, 1, '2020-04-05 11:39:15'),
(22, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(23, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(24, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(25, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(26, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(27, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(28, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(29, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(30, 1, 1, 1, 0, 1, '2020-04-05 11:39:15'),
(31, 1, 1, 1, 0, 1, '2020-04-05 11:39:16'),
(32, 0, 0, 0, 0, 0, '2020-04-05 11:39:16'),
(33, 1, 1, 1, 0, 1, '2020-04-05 11:39:16'),
(34, 1, 1, 1, 0, 1, '2020-04-05 11:39:16'),
(35, 1, 1, 1, 1, 1, '2020-04-05 11:39:16'),
(36, 1, 1, 1, 0, 1, '2020-04-05 11:39:16'),
(37, 0, 0, 0, 0, 0, '2020-04-05 11:39:16'),
(38, 1, 1, 1, 1, 1, '2020-04-05 11:39:16'),
(39, 1, 1, 1, 1, 1, '2020-04-05 11:39:16'),
(40, 1, 1, 1, 1, 1, '2020-04-05 11:39:15');

-- --------------------------------------------------------

--
-- Table structure for table `provider`
--

DROP TABLE IF EXISTS `provider`;
CREATE TABLE IF NOT EXISTS `provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rib` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quantity_delivery`
--

DROP TABLE IF EXISTS `quantity_delivery`;
CREATE TABLE IF NOT EXISTS `quantity_delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_detail_id` int(11) DEFAULT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4718AC5664577843` (`order_detail_id`),
  KEY `IDX_4718AC5612136921` (`delivery_id`),
  KEY `IDX_4718AC561A8C12F5` (`bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quote_order`
--

DROP TABLE IF EXISTS `quote_order`;
CREATE TABLE IF NOT EXISTS `quote_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_comment_id` int(11) DEFAULT NULL,
  `private_comment_id` int(11) DEFAULT NULL,
  `public_comment_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_quote` tinyint(1) DEFAULT NULL,
  `validity_periode` int(11) DEFAULT NULL,
  `is_ref_visible` tinyint(1) NOT NULL,
  `tax_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F8D61EF93057DB96` (`admin_comment_id`),
  UNIQUE KEY `UNIQ_F8D61EF9F5ACD764` (`private_comment_id`),
  UNIQUE KEY `UNIQ_F8D61EF962FBE97E` (`public_comment_id`),
  KEY `IDX_F8D61EF93414710B` (`agent_id`),
  KEY `IDX_F8D61EF919EB6921` (`client_id`),
  KEY `IDX_F8D61EF938248176` (`currency_id`),
  KEY `IDX_F8D61EF96BF700BD` (`status_id`),
  KEY `IDX_F8D61EF9E7A1254A` (`contact_id`),
  KEY `IDX_F8D61EF9B2A824D8` (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quote_order_detail`
--

DROP TABLE IF EXISTS `quote_order_detail`;
CREATE TABLE IF NOT EXISTS `quote_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_order_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `item_sell_price` double NOT NULL,
  `quantity_delivery` int(11) DEFAULT NULL,
  `quantity_recieved` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5A68304067A5C5A9` (`quote_order_id`),
  KEY `IDX_5A683040126F525E` (`item_id`),
  KEY `IDX_5A683040B2A824D8` (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ref_generator`
--

DROP TABLE IF EXISTS `ref_generator`;
CREATE TABLE IF NOT EXISTS `ref_generator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `display_name`) VALUES
(1, 'ROLE_ADMIN', 'Administrateur'),
(2, 'ROLE_BUYER', 'Acheteur'),
(3, 'ROLE_INITIATOR', 'Initiateur'),
(4, 'ROLE_VALIDATOR', 'Validateur'),
(5, 'ROLE_FINALIZER', 'Finisseur'),
(6, 'ROLE_SUBMITTER', 'Modificateur'),
(7, 'ROLE_OPERATOR', 'Opérateur'),
(8, 'ROLE_MONITOR', 'Superviseur'),
(9, 'ROLE_USER', 'Rôle par défaut'),
(10, 'ROLE_ANONYMOUS', 'Rôle non connecté');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(3000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_file` tinyint(1) DEFAULT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `name`, `value`, `code`, `is_file`, `display_name`) VALUES
(3, 'SOCIETE_NOM', 'BNOME', 'SOCIETE', 0, 'Nom de la société'),
(4, 'QOBD_URL', 'http://bahilo-002-site2.ftempurl.com/qobd/server.php?wsdl', 'WEBSERVICE', 0, 'Adresse pour lé téléchargement des PDF'),
(5, 'QOBD_LOGIN', '178290baac352f302c55f691b6102f78', 'WEBSERVICE', 0, 'Nom utilisateur'),
(6, 'QOBD_PASSWORD', '178290baac352f302c55f691b6102f78', 'WEBSERVICE', 0, 'Mot de passe'),
(7, 'SOCIETE_ADRESSE', 'SARL - 34, Rue Louis Pasteur - 92100 Boulogne-Billancourt', 'SOCIETE', 0, 'Adresse de la société'),
(8, 'EMAIL_VALIDATION', 'joel.dago@yahoo.fr', 'SOCIETE', 0, 'Email d\'envoi pour la validation des commandes'),
(12, 'BANQUE_NOM', 'CREDIT AGRICOLE PAUL DOUMER', 'BANQUE', 0, 'Nom de l\'établissement bancaire'),
(16, 'BANQUE_BIC', 'AGRIFRPP882', 'BANQUE', 0, 'BIC'),
(17, 'BANQUE_IBAN', 'FR76 1820 6004 0765 0198 9613 195', 'BANQUE', 0, 'IBAN'),
(18, 'SOCIETE_TELEPHONE', '06 62 52 97 93', 'SOCIETE', 0, 'Numéro de téléphone de la société'),
(19, 'SOCIETE_EMAIL', 'habib@bnome.fr', 'SOCIETE', 0, 'Email correspondance client'),
(20, 'TVA_INTRACOMMUNAUTAIRE', '44571', 'SOCIETE', 0, 'N° de TVA Intracommunautaire'),
(24, 'FACTURE_PREFIX', 'FA10', 'PDF', 0, 'Préfixe des noms de factures'),
(25, 'DEVIS_PREFIX', 'DEV10', 'PDF', 0, 'Préfixe des noms de devis'),
(26, 'LIVRAISON_PREFIX', 'BL10', 'PDF', 0, 'Préfixe des noms de bordereaux de livraison'),
(28, 'SOCIETE_LOGO', 'logo.png', 'SOCIETE', 1, 'Logo de la société'),
(29, 'CURRENCY_URL', 'http://data.fixer.io/api/latest?access_key=', 'WEBSERVICE', 0, 'Adresse de lecture des taux devise'),
(30, 'CURRENCY_TOKEN', '71be59148b7ad207272b631b5eb0546f', 'WEBSERVICE', 0, 'Identifiant lecture taux devise'),
(31, 'BANQUE_NUM_COMPTE', NULL, 'BANQUE', NULL, 'RIB'),
(32, 'CLIENT_PREFIX', 'CL10', 'PDF', NULL, 'Préfixe des identifiants client'),
(33, 'PROFORMA_PREFIX', 'PROFORMA10', 'PDF', NULL, 'Préfixe des noms de factures proforma'),
(34, 'AVOIR_PREFIX', 'AV10', 'PDF', NULL, 'Préfixe des noms des avoirs'),
(35, 'FTP_SERVER', 'ftp-eu.site4now.net', 'WEBSERVICE', NULL, 'Le server de fichiers FTP'),
(36, 'FTP_USER', 'bnome', 'WEBSERVICE', NULL, 'Nom utilisateur FTP'),
(37, 'FTP_PASSWORD', 'bnome225', 'WEBSERVICE', NULL, 'Mot de passe FTP'),
(39, 'SOCIETE_RCS', NULL, 'SOCIETE', 0, 'Registre du commerce et des sociétés (RCS)'),
(40, 'CAPITAL_SOCIAL', NULL, 'SOCIETE', 0, 'Capital social');

-- --------------------------------------------------------

--
-- Table structure for table `tax`
--

DROP TABLE IF EXISTS `tax`;
CREATE TABLE IF NOT EXISTS `tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) DEFAULT NULL,
  `income_statistic_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` double NOT NULL,
  `is_current` tinyint(1) NOT NULL,
  `create_at` datetime NOT NULL,
  `is_tvamarge` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8E81BA76F8697D13` (`comment_id`),
  KEY `IDX_8E81BA76B622266F` (`income_statistic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax`
--

INSERT INTO `tax` (`id`, `comment_id`, `income_statistic_id`, `type`, `value`, `is_current`, `create_at`, `is_tvamarge`) VALUES
(1, NULL, NULL, 'TTC', 20, 1, '2019-11-19 18:19:44', 0),
(2, NULL, NULL, 'HT', 0, 0, '2019-11-19 18:20:13', 0),
(3, NULL, NULL, 'TVA/MARGE', 20, 0, '2020-02-29 12:00:27', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `action_role`
--
ALTER TABLE `action_role`
  ADD CONSTRAINT `FK_2188316432FB8AEA` FOREIGN KEY (`privilege_id`) REFERENCES `privilege` (`id`),
  ADD CONSTRAINT `FK_218831649D32F035` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`),
  ADD CONSTRAINT `FK_21883164D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `action_tracker`
--
ALTER TABLE `action_tracker`
  ADD CONSTRAINT `FK_76C1A24E3414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`),
  ADD CONSTRAINT `FK_76C1A24E9D32F035` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`);

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `FK_D4E6F81F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Constraints for table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `FK_268B9C9DF8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Constraints for table `agent_discussion`
--
ALTER TABLE `agent_discussion`
  ADD CONSTRAINT `FK_B1FF28001ADED311` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`id`),
  ADD CONSTRAINT `FK_B1FF28003414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`);

--
-- Constraints for table `agent_role`
--
ALTER TABLE `agent_role`
  ADD CONSTRAINT `FK_FAF230893414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FAF23089D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `alert_bill`
--
ALTER TABLE `alert_bill`
  ADD CONSTRAINT `FK_F591CB671A8C12F5` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F591CB6793035F72` FOREIGN KEY (`alert_id`) REFERENCES `alert` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E66F675F31B` FOREIGN KEY (`author_id`) REFERENCES `agent` (`id`);

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `FK_7A2119E319EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_7A2119E362FBE97E` FOREIGN KEY (`public_comment_id`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `FK_7A2119E3B622266F` FOREIGN KEY (`income_statistic_id`) REFERENCES `income_statistic` (`id`),
  ADD CONSTRAINT `FK_7A2119E3E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`),
  ADD CONSTRAINT `FK_7A2119E3F5ACD764` FOREIGN KEY (`private_comment_id`) REFERENCES `comment` (`id`);

--
-- Constraints for table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C74404551A8C12F5` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`id`),
  ADD CONSTRAINT `FK_C74404553414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`),
  ADD CONSTRAINT `FK_C7440455F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `FK_4C62E63819EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_4C62E638A53A8AA` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`),
  ADD CONSTRAINT `FK_4C62E638F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `FK_4C62E638F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `FK_3781EC106BF700BD` FOREIGN KEY (`status_id`) REFERENCES `delivery_status` (`id`);

--
-- Constraints for table `ean_code`
--
ALTER TABLE `ean_code`
  ADD CONSTRAINT `FK_E04EF53F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Constraints for table `imei_code`
--
ALTER TABLE `imei_code`
  ADD CONSTRAINT `FK_47401C75D6307EED` FOREIGN KEY (`ean_code_id`) REFERENCES `ean_code` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `FK_1F1B251E28F818C3` FOREIGN KEY (`item_brand_id`) REFERENCES `item_brand` (`id`),
  ADD CONSTRAINT `FK_1F1B251E769F237C` FOREIGN KEY (`item_groupe_id`) REFERENCES `item_groupe` (`id`),
  ADD CONSTRAINT `FK_1F1B251EA4D07301` FOREIGN KEY (`imei_code_id`) REFERENCES `imei_code` (`id`),
  ADD CONSTRAINT `FK_1F1B251EB2A824D8` FOREIGN KEY (`tax_id`) REFERENCES `tax` (`id`),
  ADD CONSTRAINT `FK_1F1B251EF8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Constraints for table `item_provider`
--
ALTER TABLE `item_provider`
  ADD CONSTRAINT `FK_FEC9BB57126F525E` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FEC9BB57A53A8AA` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307F1ADED311` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`id`),
  ADD CONSTRAINT `FK_B6BD307F3414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`);

--
-- Constraints for table `quantity_delivery`
--
ALTER TABLE `quantity_delivery`
  ADD CONSTRAINT `FK_4718AC5612136921` FOREIGN KEY (`delivery_id`) REFERENCES `delivery` (`id`),
  ADD CONSTRAINT `FK_4718AC561A8C12F5` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`id`),
  ADD CONSTRAINT `FK_4718AC5664577843` FOREIGN KEY (`order_detail_id`) REFERENCES `quote_order_detail` (`id`);

--
-- Constraints for table `quote_order`
--
ALTER TABLE `quote_order`
  ADD CONSTRAINT `FK_F8D61EF919EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_F8D61EF93057DB96` FOREIGN KEY (`admin_comment_id`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `FK_F8D61EF93414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`),
  ADD CONSTRAINT `FK_F8D61EF938248176` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `FK_F8D61EF962FBE97E` FOREIGN KEY (`public_comment_id`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `FK_F8D61EF96BF700BD` FOREIGN KEY (`status_id`) REFERENCES `order_status` (`id`),
  ADD CONSTRAINT `FK_F8D61EF9B2A824D8` FOREIGN KEY (`tax_id`) REFERENCES `tax` (`id`),
  ADD CONSTRAINT `FK_F8D61EF9E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`),
  ADD CONSTRAINT `FK_F8D61EF9F5ACD764` FOREIGN KEY (`private_comment_id`) REFERENCES `comment` (`id`);

--
-- Constraints for table `quote_order_detail`
--
ALTER TABLE `quote_order_detail`
  ADD CONSTRAINT `FK_5A683040126F525E` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `FK_5A68304067A5C5A9` FOREIGN KEY (`quote_order_id`) REFERENCES `quote_order` (`id`),
  ADD CONSTRAINT `FK_5A683040B2A824D8` FOREIGN KEY (`tax_id`) REFERENCES `tax` (`id`);

--
-- Constraints for table `tax`
--
ALTER TABLE `tax`
  ADD CONSTRAINT `FK_8E81BA76B622266F` FOREIGN KEY (`income_statistic_id`) REFERENCES `income_statistic` (`id`),
  ADD CONSTRAINT `FK_8E81BA76F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
