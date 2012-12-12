-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Mer 12 Décembre 2012 à 22:51
-- Version du serveur: 5.5.24
-- Version de PHP: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `filedownloader`
--

-- --------------------------------------------------------

--
-- Structure de la table `File`
--

CREATE TABLE IF NOT EXISTS `File` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `mime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `external` tinyint(1) NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2CAD992EF675F31B` (`author_id`),
  KEY `IDX_2CAD992E727ACA70` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=152 ;

-- --------------------------------------------------------

--
-- Structure de la table `Parameter`
--

CREATE TABLE IF NOT EXISTS `Parameter` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `Parameter`
--

INSERT INTO `Parameter` (`id`, `value`) VALUES
('enable_register', '1'),
('enable_share', '1'),
('enable_upload', '1');

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DA1797792FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_2DA17977A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;


-- --------------------------------------------------------

--
-- Structure de la table `users_files_downloaded`
--

CREATE TABLE IF NOT EXISTS `users_files_downloaded` (
  `user_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`file_id`),
  KEY `IDX_73AF5C7AA76ED395` (`user_id`),
  KEY `IDX_73AF5C7A93CB796C` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `users_files_seen`
--

CREATE TABLE IF NOT EXISTS `users_files_seen` (
  `user_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`file_id`),
  KEY `IDX_F4F1A0EDA76ED395` (`user_id`),
  KEY `IDX_F4F1A0ED93CB796C` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `users_files_shared`
--

CREATE TABLE IF NOT EXISTS `users_files_shared` (
  `user_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`file_id`),
  KEY `IDX_4C44FB16A76ED395` (`user_id`),
  KEY `IDX_4C44FB1693CB796C` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `File`
--
ALTER TABLE `File`
  ADD CONSTRAINT `FK_2CAD992E727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `File` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_2CAD992EF675F31B` FOREIGN KEY (`author_id`) REFERENCES `User` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `users_files_downloaded`
--
ALTER TABLE `users_files_downloaded`
  ADD CONSTRAINT `FK_73AF5C7A93CB796C` FOREIGN KEY (`file_id`) REFERENCES `File` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_73AF5C7AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users_files_seen`
--
ALTER TABLE `users_files_seen`
  ADD CONSTRAINT `FK_F4F1A0ED93CB796C` FOREIGN KEY (`file_id`) REFERENCES `File` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F4F1A0EDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users_files_shared`
--
ALTER TABLE `users_files_shared`
  ADD CONSTRAINT `FK_4C44FB1693CB796C` FOREIGN KEY (`file_id`) REFERENCES `File` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_4C44FB16A76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
