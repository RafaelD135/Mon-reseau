-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 04 août 2024 à 22:26
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `idCommentaire` int NOT NULL AUTO_INCREMENT,
  `commentaire` varchar(255) NOT NULL,
  `dateCommentaire` datetime NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `idUtilisateur` int NOT NULL,
  `idPost` int NOT NULL,
  PRIMARY KEY (`idCommentaire`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idPost` (`idPost`)
);

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`idCommentaire`, `commentaire`, `dateCommentaire`, `is_enabled`, `idUtilisateur`, `idPost`) VALUES
(14, 'J\'adore les trams', '2024-07-30 14:19:47', 1, 28, 13),
(21, 'é\"ré\"r', '2024-07-30 15:32:31', 1, 28, 17),
(22, 'zadazd', '2024-07-30 15:37:42', 1, 28, 19),
(23, 'C\'est où ?', '2024-07-30 15:47:06', 1, 28, 23),
(41, 'zeze', '2024-07-30 19:29:23', 1, 1, 24),
(42, 'Répond tocard !!!', '2024-07-30 19:29:41', 1, 28, 23),
(43, 'Trop BG !!!!', '2024-07-30 20:12:04', 1, 1, 19),
(44, 'zezr', '2024-07-30 20:15:00', 1, 1, 19),
(45, 'hjkhgj', '2024-07-31 17:40:25', 1, 1, 23),
(46, 'ugvyevugf', '2024-07-31 17:40:49', 1, 1, 24),
(47, 'Nouveau commentaire', '2024-07-31 17:42:55', 1, 1, 24);

-- --------------------------------------------------------

--
-- Structure de la table `jaime`
--

DROP TABLE IF EXISTS `jaime`;
CREATE TABLE IF NOT EXISTS `jaime` (
  `idJaime` int NOT NULL AUTO_INCREMENT,
  `dateJaime` date NOT NULL,
  `idUtilisateur` int NOT NULL,
  `idPost` int NOT NULL,
  PRIMARY KEY (`idJaime`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idPost` (`idPost`)
);

--
-- Déchargement des données de la table `jaime`
--

INSERT INTO `jaime` (`idJaime`, `dateJaime`, `idUtilisateur`, `idPost`) VALUES
(5, '2024-07-28', 1, 17),
(10, '2024-07-28', 1, 15),
(19, '2024-07-29', 28, 13),
(20, '2024-07-29', 1, 13),
(25, '2024-07-29', 28, 22),
(41, '2024-07-30', 28, 17),
(45, '2024-07-30', 1, 22),
(55, '2024-07-30', 1, 16),
(58, '2024-07-30', 28, 23),
(60, '2024-07-30', 1, 23),
(61, '2024-07-30', 28, 19),
(80, '2024-07-30', 1, 20),
(81, '2024-07-30', 1, 19),
(84, '2024-07-31', 1, 24);

-- --------------------------------------------------------

--
-- Structure de la table `messageprive`
--

DROP TABLE IF EXISTS `messageprive`;
CREATE TABLE IF NOT EXISTS `messageprive` (
  `idMessagePrive` int NOT NULL AUTO_INCREMENT,
  `messagePrive` varchar(255) NOT NULL,
  `dateMessagePrive` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `idEnvoyeur` int NOT NULL,
  `idDestinataire` int NOT NULL,
  PRIMARY KEY (`idMessagePrive`),
  KEY `idEnvoyeur` (`idEnvoyeur`),
  KEY `idDestinataire` (`idDestinataire`)
);

--
-- Déchargement des données de la table `messageprive`
--

INSERT INTO `messageprive` (`idMessagePrive`, `messagePrive`, `dateMessagePrive`, `is_read`, `idEnvoyeur`, `idDestinataire`) VALUES
(1, 'Test DoAZip vers Trioze', '2024-08-02 14:21:33', 0, 1, 28),
(2, 'Test Trioze vers DoAZip', '2024-08-02 14:22:13', 0, 28, 1);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `idNotification` int NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `messageNotification` varchar(255) NOT NULL,
  `dateNotification` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `idUtilisateur` int NOT NULL,
  `idPost` int NOT NULL,
  `idMessage` int NOT NULL,
  `idEnvoyeur` int NOT NULL,
  `idComm` int NOT NULL,
  PRIMARY KEY (`idNotification`),
  KEY `idUtilisateur` (`idUtilisateur`)
);

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`idNotification`, `type`, `messageNotification`, `dateNotification`, `is_read`, `idUtilisateur`, `idPost`, `idMessage`, `idEnvoyeur`, `idComm`) VALUES
(55, 'like', 'DoAZip a aimé votre poste.', '2024-07-31 17:42:47', 0, 28, 24, 0, 1, 0),
(56, 'commentaire', 'DoAZip a ajouté un commentaire.', '2024-07-31 17:42:55', 0, 28, 0, 0, 1, 47),
(57, 'suivre', 'DoAZip a commencer à vous suivre.', '2024-07-31 17:42:57', 0, 28, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `idPost` int NOT NULL AUTO_INCREMENT,
  `dateCreationPost` datetime NOT NULL,
  `cheminImagePost` varchar(100) DEFAULT NULL,
  `descriptionPost` varchar(255) DEFAULT NULL,
  `nbLikePost` int NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `idUtilisateur` int NOT NULL,
  `nbCommentaires` int NOT NULL,
  PRIMARY KEY (`idPost`),
  UNIQUE KEY `cheminImagePost` (`cheminImagePost`),
  KEY `idUtilisateur` (`idUtilisateur`)
);

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`idPost`, `dateCreationPost`, `cheminImagePost`, `descriptionPost`, `nbLikePost`, `is_enabled`, `idUtilisateur`, `nbCommentaires`) VALUES
(13, '2024-07-28 17:03:40', 'image/post/1_2024-07-28_17-03-40.jpg', 'Test2', 2, 1, 1, 1),
(15, '2024-07-28 17:04:06', 'image/post/1_2024-07-28_17-04-06.jpg', 'Test4', 1, 0, 1, 0),
(16, '2024-07-28 17:04:15', 'image/post/1_2024-07-28_17-04-15.jpeg', 'Test5', 1, 1, 1, 0),
(17, '2024-07-28 18:14:02', 'image/post/1_2024-07-28_18-14-02.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor i', 2, 1, 1, 1),
(19, '2024-07-28 21:08:24', NULL, 'Mon sapin de con la ', 2, 1, 28, 3),
(20, '2024-07-28 21:08:40', NULL, 'Test', 1, 1, 28, 0),
(22, '2024-07-29 17:07:24', 'image/post/1_2024-07-29_17-07-24.jpg', 'MAIS MON DIEU LE PAYSAGE EST INCROYABLE', 2, 0, 1, 0),
(23, '2024-07-30 13:46:32', 'image/post/1_2024-07-30_13-46-32.jpg', 'Quel paysage !!!!!', 2, 1, 1, 3),
(24, '2024-07-30 15:16:09', 'image/post/28_2024-07-30_15-16-09.jpeg', '<jr vopir mlrd zutrd\r\n', 1, 1, 28, 3);

-- --------------------------------------------------------

--
-- Structure de la table `suivre`
--

DROP TABLE IF EXISTS `suivre`;
CREATE TABLE IF NOT EXISTS `suivre` (
  `idUtilisateur` int NOT NULL,
  `idSuivi` int NOT NULL,
  `dateSuivre` date NOT NULL,
  PRIMARY KEY (`idUtilisateur`,`idSuivi`),
  KEY `idSuivi` (`idSuivi`)
);

--
-- Déchargement des données de la table `suivre`
--

INSERT INTO `suivre` (`idUtilisateur`, `idSuivi`, `dateSuivre`) VALUES
(1, 28, '2024-07-31'),
(28, 1, '2024-07-30');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idUtilisateur` int NOT NULL AUTO_INCREMENT,
  `mailUtilisateur` varchar(50) NOT NULL,
  `mdpUtilisateur` varchar(50) NOT NULL,
  `prenomUtilisateur` varchar(50) NOT NULL,
  `cheminPdpUtilisateur` varchar(100) DEFAULT NULL,
  `creationUtilisateur` date NOT NULL,
  `pseudoUtilisateur` varchar(50) NOT NULL,
  `nomUtilisateur` varchar(50) NOT NULL,
  `descriptionUtilisateur` varchar(255) DEFAULT NULL,
  `ageUtilisateur` date NOT NULL,
  `dernierLoginUtilisateur` date NOT NULL,
  `nbFollower` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`idUtilisateur`),
  UNIQUE KEY `mailUtilisateur` (`mailUtilisateur`),
  UNIQUE KEY `pseudoUtilisateur` (`pseudoUtilisateur`)
);

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `mailUtilisateur`, `mdpUtilisateur`, `prenomUtilisateur`, `cheminPdpUtilisateur`, `creationUtilisateur`, `pseudoUtilisateur`, `nomUtilisateur`, `descriptionUtilisateur`, `ageUtilisateur`, `dernierLoginUtilisateur`, `nbFollower`) VALUES
(1, 'debein@gmail.com', '135', 'Rafael', 'image/pdp1.jpg', '2024-06-15', 'DoAZip', 'Debein', 'Biographie de RafaelDebin', '2005-11-09', '2024-06-15', 1),
(28, 'trioze@gmail.com', '246', 'Julien', 'image/default.png', '2024-07-28', 'Trioze', 'Martin', NULL, '2005-11-09', '2024-07-28', 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`);

--
-- Contraintes pour la table `jaime`
--
ALTER TABLE `jaime`
  ADD CONSTRAINT `jaime_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`),
  ADD CONSTRAINT `jaime_ibfk_2` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`);

--
-- Contraintes pour la table `messageprive`
--
ALTER TABLE `messageprive`
  ADD CONSTRAINT `messageprive_ibfk_1` FOREIGN KEY (`idEnvoyeur`) REFERENCES `utilisateur` (`idUtilisateur`),
  ADD CONSTRAINT `messageprive_ibfk_2` FOREIGN KEY (`idDestinataire`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `suivre`
--
ALTER TABLE `suivre`
  ADD CONSTRAINT `suivre_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`),
  ADD CONSTRAINT `suivre_ibfk_2` FOREIGN KEY (`idSuivi`) REFERENCES `utilisateur` (`idUtilisateur`);
