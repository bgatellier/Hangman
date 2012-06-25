-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Dim 17 Avril 2011 à 15:12
-- Version du serveur: 5.1.54
-- Version de PHP: 5.3.5-1ubuntu6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `projetweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `pass` varchar(32) NOT NULL COMMENT 'Hash MD5',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `login`, `pass`) VALUES
(5, 'Gatellier', 'Bastien', 'bastien', 'e409f05a10574adb8d47dcb631f8e3bb'),
(6, 'Do', 'Minh', 'minh', 'c92f1d1f2619172bf87a12e5915702a6'),
(7, 'Styfalova', 'Lenka', 'lenka', '91f551e66171e891e5d67ee404c97d2b'),
(8, 'Ruckly', 'Stéphane', 'stephane', 'f7885ad36a637f4a1212716eb9cdcff2');

-- --------------------------------------------------------

--
-- Structure de la table `words`
--

CREATE TABLE IF NOT EXISTS `words` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_proposition` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_validation` timestamp NULL DEFAULT NULL,
  `fr` varchar(255) NOT NULL,
  `en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fr` (`fr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Contenu de la table `words`
--

INSERT INTO `words` (`id`, `date_proposition`, `date_validation`, `fr`, `en`) VALUES
(8, '2011-04-17 01:47:58', '2011-04-17 01:47:58', 'arbre', 'tree'),
(9, '2011-04-17 01:48:16', '2011-04-17 01:48:16', 'citron', 'lemon'),
(10, '2011-04-17 01:48:23', '2011-04-17 01:48:23', 'lune', 'moon'),
(11, '2011-04-17 01:48:30', '2011-04-17 01:48:30', 'printemps', 'spring'),
(12, '2011-04-17 01:48:36', '2011-04-17 01:48:36', 'hiver', 'winter'),
(13, '2011-04-17 01:48:42', '2011-04-17 01:48:42', 'été', 'summer'),
(14, '2011-04-17 01:48:52', '2011-04-17 01:48:52', 'automne', 'fall'),
(15, '2011-04-17 01:49:17', '2011-04-17 01:49:17', 'voiture', 'car'),
(16, '2011-04-17 01:49:37', '2011-04-17 01:49:37', 'poisson rouge', 'goldfish'),
(17, '2011-04-17 01:50:19', '2011-04-17 01:50:19', 'eau', 'water'),
(18, '2011-04-17 01:50:32', '2011-04-17 01:50:32', 'poisson', 'fish'),
(19, '2011-04-17 01:50:49', '2011-04-17 01:50:49', 'herbe', 'grass'),
(20, '2011-04-17 01:51:16', '2011-04-17 01:51:16', 'règle', 'rule'),
(21, '2011-04-17 01:52:00', '2011-04-17 01:52:00', 'ordinateur', 'computer'),
(22, '2011-04-17 01:52:10', '2011-04-17 01:52:10', 'porte', 'door'),
(23, '2011-04-17 01:52:29', '2011-04-17 01:52:29', 'livre', 'book'),
(24, '2011-04-17 01:52:58', '2011-04-17 01:52:58', 'bateau', 'boat'),
(25, '2011-04-17 02:10:42', '2011-04-17 02:11:43', 'oiseau', 'bird'),
(26, '2011-04-17 02:11:57', '2011-04-17 02:11:57', 'poire', 'pear'),
(27, '2011-04-17 02:12:01', '2011-04-17 02:12:01', 'pomme', 'apple'),
(28, '2011-04-17 02:12:44', '2011-04-17 02:12:44', 'fraise', 'strawberry'),
(29, '2011-04-17 02:13:28', '2011-04-17 02:13:28', 'pamplemousse', 'grapefruit'),
(30, '2011-04-17 02:19:11', '2011-04-17 02:19:11', 'pain', 'bread'),
(31, '2011-04-17 02:19:20', '2011-04-17 02:19:20', 'sucre', 'sugar'),
(32, '2011-04-17 02:19:30', '2011-04-17 02:19:30', 'beurre', 'butter'),
(33, '2011-04-17 02:19:49', '2011-04-17 02:19:49', 'farine', 'flour'),
(34, '2011-04-17 02:19:57', '2011-04-17 02:19:57', 'oeuf', 'egg'),
(35, '2011-04-17 02:20:41', '2011-04-17 02:20:41', 'verre', 'glass'),
(36, '2011-04-17 02:20:49', '2011-04-17 02:20:49', 'bois', 'wood'),
(37, '2011-04-17 02:20:58', '2011-04-17 02:20:58', 'carte', 'card'),
(38, '2011-04-17 02:21:21', '2011-04-17 02:21:21', 'crayon', 'pencil'),
(39, '2011-04-17 02:21:50', '2011-04-17 02:21:50', 'papier', 'paper'),
(40, '2011-04-17 02:35:06', '2011-04-17 02:35:06', 'main', 'hand'),
(41, '2011-04-17 02:35:11', '2011-04-17 02:35:11', 'sable', 'sand'),
(42, '2011-04-17 02:35:28', '2011-04-17 02:35:28', 'couteau', 'knife'),
(43, '2011-04-17 02:36:51', '2011-04-17 02:36:51', 'bibliothèque', 'library'),
(44, '2011-04-17 02:38:51', '2011-04-17 02:38:51', 'rivière', 'river');
