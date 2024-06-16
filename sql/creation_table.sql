CREATE DATABASE IF NOT EXISTS `mon_reseau`;
USE `mon_reseau`;

DROP TABLE IF EXISTS Commentaire;
DROP TABLE IF EXISTS MessagePrive;
DROP TABLE IF EXISTS Notifications;
DROP TABLE IF EXISTS Jaime;
DROP TABLE IF EXISTS Post;
DROP TABLE IF EXISTS Suivre;
DROP TABLE IF EXISTS Utilisateur;

CREATE TABLE IF NOT EXISTS `Utilisateur`(
   `idUtilisateur` INT NOT NULL AUTO_INCREMENT,
   `mailUtilisateur` VARCHAR(50) NOT NULL,
   `mdpUtilisateur` VARCHAR(50) NOT NULL,
   `prenomUtilisateur` VARCHAR(50) NOT NULL,
   `cheminPdpUtilisateur` VARCHAR(100),
   `creationUtilisateur` DATE NOT NULL,
   `pseudoUtilisateur` VARCHAR(50) NOT NULL,
   `nomUtilisateur` VARCHAR(50) NOT NULL,
   `descriptionUtilisateur` VARCHAR(255),
   `ageUtilisateur` DATE NOT NULL,
   `dernierLoginUtilisateur` DATE NOT NULL,
   PRIMARY KEY(`idUtilisateur`),
   UNIQUE(`mailUtilisateur`),
   UNIQUE(`pseudoUtilisateur`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Post`(
   `idPost` INT NOT NULL AUTO_INCREMENT,
   `dateCreationPost` DATE NOT NULL,
   `cheminImagePost` VARCHAR(100),
   `descriptionPost` VARCHAR(255),
   `nbLikePost` INT NOT NULL,
   `is_enabled` BOOLEAN NOT NULL,
   `idUtilisateur` INT NOT NULL,
   `nbCommentaires` INT NOT NULL,
   PRIMARY KEY(`idPost`),
   UNIQUE(`cheminImagePost`),
   FOREIGN KEY(`idUtilisateur`) REFERENCES `Utilisateur`(`idUtilisateur`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Commentaire`(
   `idCommentaire` INT NOT NULL AUTO_INCREMENT,
   `commentaire` VARCHAR(255) NOT NULL,
   `dateCommentaire` DATE NOT NULL,
   `is_enabled` BOOLEAN NOT NULL,
   `idUtilisateur` INT NOT NULL,
   `idPost` INT NOT NULL,
   PRIMARY KEY(`idCommentaire`),
   FOREIGN KEY(`idUtilisateur`) REFERENCES `Utilisateur`(`idUtilisateur`),
   FOREIGN KEY(`idPost`) REFERENCES `Post`(`idPost`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `MessagePrive`(
   `idMessagePrive` INT NOT NULL AUTO_INCREMENT,
   `messagePrive` VARCHAR(255) NOT NULL,
   `dateMessagePrive` DATE NOT NULL,
   `is_read` BOOLEAN NOT NULL,
   `idEnvoyeur` INT NOT NULL,
   `idDestinataire` INT NOT NULL,
   PRIMARY KEY(`idMessagePrive`),
   FOREIGN KEY(`idEnvoyeur`) REFERENCES `Utilisateur`(`idUtilisateur`),
   FOREIGN KEY(`idDestinataire`) REFERENCES `Utilisateur`(`idUtilisateur`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Notifications`(
   `idNotification` INT NOT NULL AUTO_INCREMENT,
   `type` VARCHAR(50) NOT NULL,
   `messageNotification` VARCHAR(255) NOT NULL,
   `dateNotification` DATE NOT NULL,
   `is_read` BOOLEAN NOT NULL,
   `idUtilisateur` INT NOT NULL,
   PRIMARY KEY(`idNotification`),
   FOREIGN KEY(`idUtilisateur`) REFERENCES `Utilisateur`(`idUtilisateur`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Jaime`(
   `idJaime` INT NOT NULL AUTO_INCREMENT,
   `dateJaime` DATE NOT NULL,
   `idUtilisateur` INT NOT NULL,
   `idPost` INT NOT NULL,
   PRIMARY KEY(`idJaime`),
   FOREIGN KEY(`idUtilisateur`) REFERENCES `Utilisateur`(`idUtilisateur`),
   FOREIGN KEY(`idPost`) REFERENCES `Post`(`idPost`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Suivre`(
   `idUtilisateur` INT,
   `idSuivi` INT,
   `dateSuivre` DATE NOT NULL,
   PRIMARY KEY(`idUtilisateur`, `idSuivi`),
   FOREIGN KEY(`idUtilisateur`) REFERENCES `Utilisateur`(`idUtilisateur`),
   FOREIGN KEY(`idSuivi`) REFERENCES `Utilisateur`(`idUtilisateur`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO Utilisateur (mailUtilisateur,mdpUtilisateur,prenomUtilisateur,cheminPdpUtilisateur,creationUtilisateur,pseudoUtilisateur,nomUtilisateur,descriptionUtilisateur,ageUtilisateur,dernierLoginUtilisateur)
VALUES ('debein@gmail.com','135','Rafael','/image/defautlt.png','2024-06-15','DoAZip','Debein','Biographie de RafaelDebin','2005-11-09','2024-06-15');