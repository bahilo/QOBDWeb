-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 08 nov. 2019 à 14:44
-- Version du serveur :  5.7.26
-- Version de PHP :  7.3.5

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
(43, 'EUR', 'EUR', 0, 'EUR', 'Euro', 0, '2019-11-08 00:00:00'),
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
