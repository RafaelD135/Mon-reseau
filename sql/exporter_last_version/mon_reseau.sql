-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 11 juil. 2024 à 12:04
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mon_reseau`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `idCommentaire` int NOT NULL AUTO_INCREMENT,
  `commentaire` varchar(255) NOT NULL,
  `dateCommentaire` date NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `idUtilisateur` int NOT NULL,
  `idPost` int NOT NULL,
  PRIMARY KEY (`idCommentaire`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idPost` (`idPost`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messageprive`
--

DROP TABLE IF EXISTS `messageprive`;
CREATE TABLE IF NOT EXISTS `messageprive` (
  `idMessagePrive` int NOT NULL AUTO_INCREMENT,
  `messagePrive` varchar(255) NOT NULL,
  `dateMessagePrive` date NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `idEnvoyeur` int NOT NULL,
  `idDestinataire` int NOT NULL,
  PRIMARY KEY (`idMessagePrive`),
  KEY `idEnvoyeur` (`idEnvoyeur`),
  KEY `idDestinataire` (`idDestinataire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `idNotification` int NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `messageNotification` varchar(255) NOT NULL,
  `dateNotification` date NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `idUtilisateur` int NOT NULL,
  PRIMARY KEY (`idNotification`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `idPost` int NOT NULL AUTO_INCREMENT,
  `dateCreationPost` date NOT NULL,
  `cheminImagePost` varchar(100) DEFAULT NULL,
  `descriptionPost` varchar(255) DEFAULT NULL,
  `nbLikePost` int NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `idUtilisateur` int NOT NULL,
  `nbCommentaires` int NOT NULL,
  PRIMARY KEY (`idPost`),
  UNIQUE KEY `cheminImagePost` (`cheminImagePost`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  PRIMARY KEY (`idUtilisateur`),
  UNIQUE KEY `mailUtilisateur` (`mailUtilisateur`),
  UNIQUE KEY `pseudoUtilisateur` (`pseudoUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `mailUtilisateur`, `mdpUtilisateur`, `prenomUtilisateur`, `cheminPdpUtilisateur`, `creationUtilisateur`, `pseudoUtilisateur`, `nomUtilisateur`, `descriptionUtilisateur`, `ageUtilisateur`, `dernierLoginUtilisateur`) VALUES
(1, 'debein@gmail.com', '135', 'Rafael', 'image/pdp1.jpg', '2024-06-15', 'DoAZip', 'Debein', 'Biographie de RafaelDebin', '2005-11-09', '2024-06-15'),
(2, 'test@exemple.com', 'Test', 'Test', 'image/default.png', '2024-06-15', 'Testicule', 'Test', NULL, '2005-11-09', '2024-06-15'),
(3, 'eere@ee.com', 'tyguhj', 'tyguh', 'image/default.png', '2024-06-15', 'ytgvbhu', 'tvgbhj', NULL, '2000-11-09', '2024-06-15'),
(5, 'test135@gmail.com', 'cfgvybhj', 'tyguh', 'image/default.png', '2024-06-15', 'cvgbh', 'vubhj', NULL, '2000-11-01', '2024-06-15'),
(7, 'tygvbh@ctfygvbh.com', 'tygvbhunj', 'tyguhjn', 'image/default.png', '2024-06-15', 'cvgybhunj', 'vyubh', NULL, '1990-11-09', '2024-06-15'),
(10, 'erer@ereer.com', 'tyguhj', 'tgyuij', 'image/default.png', '2024-06-15', 'yuhijkl', '_yghuji', NULL, '1111-11-11', '2024-06-15'),
(13, 'hggr@df.com', 'ngfdgsf', 'nhgfdsqfd', 'image/default.png', '2024-06-15', 'ngfsdvqfsd', 'nhdqgsf', NULL, '0001-11-09', '2024-06-15'),
(16, 'test125@gmail.com', 'gyuhijk', 'tyguhijk', 'image/default.png', '2024-06-15', 'tyguihj', 'ugvyhij', NULL, '1005-05-05', '2024-06-15'),
(21, 'rtfygvbh@tvyh.com', 'vgh', 'tyguhij', 'image/default.png', '2024-06-15', 'dozip', 'vgyuhij', NULL, '2005-11-09', '2024-06-15'),
(23, 'brvef@grefd.com', 'vygbhnj', 'ygbhunj', 'image/default.png', '2024-06-16', 'vgybhj', 'tghj', NULL, '5000-11-09', '2024-06-16'),
(24, 'yghujik@ghjk.com', 'yghj', 'byhjk', 'image/default.png', '2024-06-16', 'hjbh', 'hujik', NULL, '9000-11-09', '2024-06-16');

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
