-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 20 nov. 2019 à 20:37
-- Version du serveur :  5.7.26
-- Version de PHP :  5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `qobdweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `action`
--

INSERT INTO `action` (`id`, `name`, `display_name`) VALUES
(1, 'ACTION_BILL', 'Facturation'),
(2, 'ACTION_PDF', 'Création PDF');

-- --------------------------------------------------------

--
-- Structure de la table `action_role`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `action_tracker`
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
-- Structure de la table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_principal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D4E6F81F8697D13` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id`, `comment_id`, `name`, `display_name`, `city`, `street`, `zip_code`, `country`, `is_principal`) VALUES
(1, 3, '3 rue gambetta', NULL, 'Saint-Michel-sur-Orge', 'bat I appart. 204', '91240', 'France', 1);

-- --------------------------------------------------------

--
-- Structure de la table `agent`
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_268B9C9DF8697D13` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agent`
--

INSERT INTO `agent` (`id`, `comment_id`, `first_name`, `last_name`, `phone`, `fax`, `email`, `user_name`, `password`, `picture`, `is_admin`, `is_online`, `list_size`, `is_activated`, `ipaddress`) VALUES
(1, NULL, 'JOEL', 'DAGO', '+33618319489', NULL, 'Joel.dago@yahoo.fr', 'bahilo', '$2y$13$qca5h/VVx85216/D96ahZOrFv7./2m3YJjeXtaKYjabLxNWE8Jq.u', NULL, 0, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `agent_discussion`
--

DROP TABLE IF EXISTS `agent_discussion`;
CREATE TABLE IF NOT EXISTS `agent_discussion` (
  `agent_id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  PRIMARY KEY (`agent_id`,`discussion_id`),
  KEY `IDX_B1FF28003414710B` (`agent_id`),
  KEY `IDX_B1FF28001ADED311` (`discussion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `agent_role`
--

DROP TABLE IF EXISTS `agent_role`;
CREATE TABLE IF NOT EXISTS `agent_role` (
  `agent_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`agent_id`,`role_id`),
  KEY `IDX_FAF230893414710B` (`agent_id`),
  KEY `IDX_FAF23089D60322AC` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `alert`
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
-- Structure de la table `alert_bill`
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
-- Structure de la table `bill`
--

DROP TABLE IF EXISTS `bill`;
CREATE TABLE IF NOT EXISTS `bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `private_comment_id` int(11) DEFAULT NULL,
  `public_comment_id` int(11) DEFAULT NULL,
  `income_statistic_id` int(11) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay` double NOT NULL,
  `pay_received` double DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `limit_date_at` datetime DEFAULT NULL,
  `payed_at` datetime DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7A2119E3F5ACD764` (`private_comment_id`),
  UNIQUE KEY `UNIQ_7A2119E362FBE97E` (`public_comment_id`),
  KEY `IDX_7A2119E3B622266F` (`income_statistic_id`),
  KEY `IDX_7A2119E3E7A1254A` (`contact_id`),
  KEY `IDX_7A2119E319EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bill`
--

INSERT INTO `bill` (`id`, `private_comment_id`, `public_comment_id`, `income_statistic_id`, `contact_id`, `pay_mode`, `pay`, `pay_received`, `created_at`, `limit_date_at`, `payed_at`, `client_id`) VALUES
(4, NULL, NULL, NULL, 1, NULL, 205, NULL, '2019-11-20 19:37:47', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `client`
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `agent_id`, `bill_id`, `comment_id`, `company_name`, `rib`, `crn`, `pay_delay`, `max_credit`, `is_activated`, `is_prospect`, `denomination`) VALUES
(1, NULL, NULL, 4, 'ivory', NULL, NULL, NULL, 2000, 1, 0, 'gagnoa');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(3000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `content`, `create_at`) VALUES
(1, 'mon petit commentaire 2', '2019-11-17 08:08:03'),
(2, 'mon produit 2', '2019-11-17 08:09:31'),
(3, '', '2019-11-17 12:29:32'),
(4, 'commentaire général', '2019-11-17 12:29:32'),
(17, '', '2019-11-20 19:36:19'),
(18, '', '2019-11-20 19:36:19'),
(19, '', '2019-11-20 19:36:19');

-- --------------------------------------------------------

--
-- Structure de la table `contact`
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `client_id`, `comment_id`, `provider_id`, `address_id`, `firstname`, `last_name`, `position`, `email`, `phone`, `mobile`, `fax`, `is_principal`) VALUES
(1, 1, NULL, NULL, 1, 'JOEL', 'DAGO', NULL, 'Joel.dago@yahoo.fr', '+33618319489', NULL, '', 1);

-- --------------------------------------------------------

--
-- Structure de la table `currency`
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
-- Déchargement des données de la table `currency`
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
-- Structure de la table `delivery`
--

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE IF NOT EXISTS `delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) DEFAULT NULL,
  `package` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3781EC106BF700BD` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `delivery`
--

INSERT INTO `delivery` (`id`, `status_id`, `package`, `created_at`) VALUES
(5, 1, 1, '2019-11-20 19:36:32');

-- --------------------------------------------------------

--
-- Structure de la table `delivery_status`
--

DROP TABLE IF EXISTS `delivery_status`;
CREATE TABLE IF NOT EXISTS `delivery_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `delivery_status`
--

INSERT INTO `delivery_status` (`id`, `name`) VALUES
(1, 'STATUS_BILLED'),
(2, 'STATUS_NOT_BILLED');

-- --------------------------------------------------------

--
-- Structure de la table `discussion`
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
-- Structure de la table `income_statistic`
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
-- Structure de la table `item`
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1F1B251EF8697D13` (`comment_id`),
  KEY `IDX_1F1B251E769F237C` (`item_groupe_id`),
  KEY `IDX_1F1B251E28F818C3` (`item_brand_id`),
  KEY `IDX_1F1B251EB2A824D8` (`tax_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `item`
--

INSERT INTO `item` (`id`, `comment_id`, `item_groupe_id`, `item_brand_id`, `tax_id`, `ref`, `name`, `sell_price`, `purchase_price`, `stock`, `picture`, `is_erasable`, `created_at`) VALUES
(1, 1, NULL, NULL, NULL, 'M-456521', 'Produit 1', 20, 10, 25, NULL, 1, '2019-11-17 08:08:03'),
(2, 2, NULL, NULL, NULL, 'Z-M-456521', 'Produit 2', 35, 25, 75, NULL, 1, '2019-11-17 08:09:31');

-- --------------------------------------------------------

--
-- Structure de la table `item_brand`
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
-- Structure de la table `item_groupe`
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
-- Structure de la table `item_provider`
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
-- Structure de la table `license`
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
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discussion_id` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_red` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307F1ADED311` (`discussion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message_agent`
--

DROP TABLE IF EXISTS `message_agent`;
CREATE TABLE IF NOT EXISTS `message_agent` (
  `message_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  PRIMARY KEY (`message_id`,`agent_id`),
  KEY `IDX_D92D6376537A1329` (`message_id`),
  KEY `IDX_D92D63763414710B` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20191117074540', '2019-11-17 07:46:38'),
('20191119183536', '2019-11-19 18:36:21'),
('20191119195215', '2019-11-19 19:52:36'),
('20191119204626', '2019-11-19 20:46:45');

-- --------------------------------------------------------

--
-- Structure de la table `order_status`
--

DROP TABLE IF EXISTS `order_status`;
CREATE TABLE IF NOT EXISTS `order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_status`
--

INSERT INTO `order_status` (`id`, `name`) VALUES
(1, 'STATUS_QUOTE'),
(2, 'STATUS_PREORDER'),
(3, 'STATUS_PREREFUND'),
(4, 'STATUS_VALID'),
(5, 'STATUS_ORDER'),
(6, 'STATUS_REFUND'),
(7, 'STATUS_BILL'),
(8, 'STATUS_REFUNDBILL'),
(9, 'STATUS_CLOSED'),
(10, 'STATUS_REFUNDCLOSED');

-- --------------------------------------------------------

--
-- Structure de la table `privilege`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `provider`
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
-- Structure de la table `quantity_delivery`
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `quantity_delivery`
--

INSERT INTO `quantity_delivery` (`id`, `order_detail_id`, `delivery_id`, `bill_id`, `quantity`) VALUES
(6, 13, 5, 4, 5),
(7, 14, 5, 4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `quote_order`
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F8D61EF93057DB96` (`admin_comment_id`),
  UNIQUE KEY `UNIQ_F8D61EF9F5ACD764` (`private_comment_id`),
  UNIQUE KEY `UNIQ_F8D61EF962FBE97E` (`public_comment_id`),
  KEY `IDX_F8D61EF93414710B` (`agent_id`),
  KEY `IDX_F8D61EF919EB6921` (`client_id`),
  KEY `IDX_F8D61EF938248176` (`currency_id`),
  KEY `IDX_F8D61EF96BF700BD` (`status_id`),
  KEY `IDX_F8D61EF9E7A1254A` (`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `quote_order`
--

INSERT INTO `quote_order` (`id`, `admin_comment_id`, `private_comment_id`, `public_comment_id`, `agent_id`, `client_id`, `currency_id`, `status_id`, `contact_id`, `created_at`, `is_quote`, `validity_periode`, `is_ref_visible`) VALUES
(8, 17, 18, 19, 1, 1, NULL, 5, 1, '2019-11-20 19:31:29', 1, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `quote_order_detail`
--

DROP TABLE IF EXISTS `quote_order_detail`;
CREATE TABLE IF NOT EXISTS `quote_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_order_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `quantity_delivery` int(11) DEFAULT NULL,
  `quantity_recieved` int(11) DEFAULT NULL,
  `item_sell_price` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5A68304067A5C5A9` (`quote_order_id`),
  KEY `IDX_5A683040126F525E` (`item_id`),
  KEY `IDX_5A683040B2A824D8` (`tax_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `quote_order_detail`
--

INSERT INTO `quote_order_detail` (`id`, `quote_order_id`, `item_id`, `tax_id`, `quantity`, `quantity_delivery`, `quantity_recieved`, `item_sell_price`) VALUES
(13, 8, 1, NULL, 10, 5, 0, 20),
(14, 8, 2, NULL, 5, 3, 0, 35);

-- --------------------------------------------------------

--
-- Structure de la table `ref_generator`
--

DROP TABLE IF EXISTS `ref_generator`;
CREATE TABLE IF NOT EXISTS `ref_generator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'ROLE_ADMIN'),
(2, 'ROLE_BUYER'),
(3, 'ROLE_INITIATOR'),
(4, 'ROLE_VALIDATOR'),
(5, 'ROLE_FINALIZER'),
(6, 'ROLE_SUBMITTER'),
(7, 'ROLE_OPERATOR'),
(8, 'ROLE_MONITOR'),
(9, 'ROLE_USER'),
(10, 'ROLE_ANONYMOUS');

-- --------------------------------------------------------

--
-- Structure de la table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(3000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `setting`
--

INSERT INTO `setting` (`id`, `name`, `value`, `code`) VALUES
(1, 'APILINK', 'https://www.amdoren.com/api/currency.php', 'CURRENCY'),
(2, 'APIKEY', '5Fex7jXDi492gz5jtKUkGupMzY5kMa', 'CURRENCY'),
(3, 'NOM', 'BNOME', 'SOCIETE');

-- --------------------------------------------------------

--
-- Structure de la table `tax`
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
-- Déchargement des données de la table `tax`
--

INSERT INTO `tax` (`id`, `comment_id`, `income_statistic_id`, `type`, `value`, `is_current`, `create_at`, `is_tvamarge`) VALUES
(1, NULL, NULL, 'TTC', 20, 0, '2019-11-19 18:19:44', 0),
(2, NULL, NULL, 'HT', 0, 0, '2019-11-19 18:20:13', 0),
(3, NULL, NULL, 'TVA/MARGE', 0, 0, '2019-11-19 18:20:38', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `action_role`
--
ALTER TABLE `action_role`
  ADD CONSTRAINT `FK_2188316432FB8AEA` FOREIGN KEY (`privilege_id`) REFERENCES `privilege` (`id`),
  ADD CONSTRAINT `FK_218831649D32F035` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`),
  ADD CONSTRAINT `FK_21883164D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Contraintes pour la table `action_tracker`
--
ALTER TABLE `action_tracker`
  ADD CONSTRAINT `FK_76C1A24E3414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`),
  ADD CONSTRAINT `FK_76C1A24E9D32F035` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`);

--
-- Contraintes pour la table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `FK_D4E6F81F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Contraintes pour la table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `FK_268B9C9DF8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Contraintes pour la table `agent_discussion`
--
ALTER TABLE `agent_discussion`
  ADD CONSTRAINT `FK_B1FF28001ADED311` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B1FF28003414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `agent_role`
--
ALTER TABLE `agent_role`
  ADD CONSTRAINT `FK_FAF230893414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FAF23089D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `alert_bill`
--
ALTER TABLE `alert_bill`
  ADD CONSTRAINT `FK_F591CB671A8C12F5` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F591CB6793035F72` FOREIGN KEY (`alert_id`) REFERENCES `alert` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `FK_7A2119E319EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_7A2119E362FBE97E` FOREIGN KEY (`public_comment_id`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `FK_7A2119E3B622266F` FOREIGN KEY (`income_statistic_id`) REFERENCES `income_statistic` (`id`),
  ADD CONSTRAINT `FK_7A2119E3E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`),
  ADD CONSTRAINT `FK_7A2119E3F5ACD764` FOREIGN KEY (`private_comment_id`) REFERENCES `comment` (`id`);

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C74404551A8C12F5` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`id`),
  ADD CONSTRAINT `FK_C74404553414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`),
  ADD CONSTRAINT `FK_C7440455F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Contraintes pour la table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `FK_4C62E63819EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_4C62E638A53A8AA` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`),
  ADD CONSTRAINT `FK_4C62E638F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `FK_4C62E638F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Contraintes pour la table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `FK_3781EC106BF700BD` FOREIGN KEY (`status_id`) REFERENCES `delivery_status` (`id`);

--
-- Contraintes pour la table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `FK_1F1B251E28F818C3` FOREIGN KEY (`item_brand_id`) REFERENCES `item_brand` (`id`),
  ADD CONSTRAINT `FK_1F1B251E769F237C` FOREIGN KEY (`item_groupe_id`) REFERENCES `item_groupe` (`id`),
  ADD CONSTRAINT `FK_1F1B251EB2A824D8` FOREIGN KEY (`tax_id`) REFERENCES `tax` (`id`),
  ADD CONSTRAINT `FK_1F1B251EF8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Contraintes pour la table `item_provider`
--
ALTER TABLE `item_provider`
  ADD CONSTRAINT `FK_FEC9BB57126F525E` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FEC9BB57A53A8AA` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307F1ADED311` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`id`);

--
-- Contraintes pour la table `message_agent`
--
ALTER TABLE `message_agent`
  ADD CONSTRAINT `FK_D92D63763414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D92D6376537A1329` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `quantity_delivery`
--
ALTER TABLE `quantity_delivery`
  ADD CONSTRAINT `FK_4718AC5612136921` FOREIGN KEY (`delivery_id`) REFERENCES `delivery` (`id`),
  ADD CONSTRAINT `FK_4718AC561A8C12F5` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`id`),
  ADD CONSTRAINT `FK_4718AC5664577843` FOREIGN KEY (`order_detail_id`) REFERENCES `quote_order_detail` (`id`);

--
-- Contraintes pour la table `quote_order`
--
ALTER TABLE `quote_order`
  ADD CONSTRAINT `FK_F8D61EF919EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_F8D61EF93057DB96` FOREIGN KEY (`admin_comment_id`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `FK_F8D61EF93414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`),
  ADD CONSTRAINT `FK_F8D61EF938248176` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `FK_F8D61EF962FBE97E` FOREIGN KEY (`public_comment_id`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `FK_F8D61EF96BF700BD` FOREIGN KEY (`status_id`) REFERENCES `order_status` (`id`),
  ADD CONSTRAINT `FK_F8D61EF9E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`),
  ADD CONSTRAINT `FK_F8D61EF9F5ACD764` FOREIGN KEY (`private_comment_id`) REFERENCES `comment` (`id`);

--
-- Contraintes pour la table `quote_order_detail`
--
ALTER TABLE `quote_order_detail`
  ADD CONSTRAINT `FK_5A683040126F525E` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `FK_5A68304067A5C5A9` FOREIGN KEY (`quote_order_id`) REFERENCES `quote_order` (`id`),
  ADD CONSTRAINT `FK_5A683040B2A824D8` FOREIGN KEY (`tax_id`) REFERENCES `tax` (`id`);

--
-- Contraintes pour la table `tax`
--
ALTER TABLE `tax`
  ADD CONSTRAINT `FK_8E81BA76B622266F` FOREIGN KEY (`income_statistic_id`) REFERENCES `income_statistic` (`id`),
  ADD CONSTRAINT `FK_8E81BA76F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
