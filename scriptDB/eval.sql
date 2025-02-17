-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.34 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour evaluation
DROP DATABASE IF EXISTS `evaluation`;
CREATE DATABASE IF NOT EXISTS `evaluation` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `evaluation`;

-- Listage de la structure de table evaluation. evolution
DROP TABLE IF EXISTS `evolution`;
CREATE TABLE IF NOT EXISTS `evolution` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annee` int DEFAULT NULL,
  `user` int DEFAULT NULL,
  `souhait` int DEFAULT '1',
  `motivation` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avis` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.evolution : ~1 rows (environ)
INSERT INTO `evolution` (`id`, `annee`, `user`, `souhait`, `motivation`, `avis`) VALUES
	(58, 2025, 3, 1, 'aaeeeee', 'affffff');

-- Listage de la structure de table evaluation. formations
DROP TABLE IF EXISTS `formations`;
CREATE TABLE IF NOT EXISTS `formations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_ligne` int DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `user` int DEFAULT NULL,
  `formation` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `obj_formation` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `priorite` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.formations : ~3 rows (environ)
INSERT INTO `formations` (`id`, `id_ligne`, `annee`, `user`, `formation`, `obj_formation`, `priorite`) VALUES
	(145, 0, 2025, 3, 'eeeee', 'eeee', 2),
	(146, 1, 2025, 3, 'azdazd', 'dqsdqsd', 1),
	(147, 2, 2025, 3, 'aaaa', 'ddd', 2);

-- Listage de la structure de table evaluation. objectifs
DROP TABLE IF EXISTS `objectifs`;
CREATE TABLE IF NOT EXISTS `objectifs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_ligne` int DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `user` int DEFAULT NULL,
  `objectif` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `indicateur` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `realisation` int DEFAULT '0',
  `score` int DEFAULT '1',
  `resultat_analyse` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `echeance` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `validation` int DEFAULT '0',
  `validation2` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.objectifs : ~11 rows (environ)
INSERT INTO `objectifs` (`id`, `id_ligne`, `annee`, `user`, `objectif`, `indicateur`, `realisation`, `score`, `resultat_analyse`, `echeance`, `validation`, `validation2`) VALUES
	(4, 0, 2024, 3, 'Mettre en place un outils de suivi des consommables informatiques', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 50, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaa', 'Feb-24', 0, 0),
	(5, 1, 2024, 3, 'Mettre en place un outils de suivi de la fourniture de bureau ', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 120, 2, 'azdazd\n1 adfonkd\n2alkzedanklzd\n3amzdkamld', 'Feb-24', 0, 0),
	(6, 2, 2024, 3, 'Mettre en place des DASHBOARD / Outils d\'assistance des Utilisateurs/Directeurs (Achats, ventes...)', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 10, 2, 'qsdqsdqsd', 'Toute l\'année 2024', 0, 0),
	(7, 3, 2024, 3, 'Mettre en place l\'outil de suivi de la téléphonie', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 100, 4, 'dadzazd', 'Feb-24', 0, 0),
	(8, 4, 2024, 3, 'Participer et s\'assurer de la bonne mise en œuvre des projets de mise à niveau de l\'infrastructure IT', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 100, 1, 'azdazdaz', 'Mar-24', 0, 0),
	(9, 5, 2024, 3, 'Participer et s\'assurer de la bonne mise en œuvre du projet de l\'intranet ', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 99, 2, 'fq', 'Sep-24', 0, 0),
	(10, 6, 2024, 3, 'Mise en place et déploiement des procédures IT ', '1- Nombre des procédures produit / Nombre prévu\n2- Qualité du livrable ', 139, 1, 'sdfsdf', 'Apr-24', 0, 0),
	(11, 7, 2024, 3, 'Veiller à la disponibilité de l\'infrastructre informatique ', 'Le nombre d\'incident constaté dans l\'année', 849, 2, 'sdf', 'Toute l\'année 2024', 0, 0),
	(12, 8, 2024, 3, 'Assistance users solution (SAP, téléphonie, GECIMMO, Sage Paie …)', 'Nombre de tickets traité / Nombre de tickets ouvert', 39, 4, 'sdfffff', 'Toute l\'année 2024', 0, 0),
	(174, 0, 2025, 3, 'aaaaaaaaaaaaaaaaa', 'zzzz', 0, 1, NULL, 'zzz', 0, 0),
	(175, 1, 2025, 3, 'ffffgf', 'gggggg', 0, 1, NULL, 'Apv-25', 0, 0);

-- Listage de la structure de table evaluation. scores
DROP TABLE IF EXISTS `scores`;
CREATE TABLE IF NOT EXISTS `scores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annee` int DEFAULT '0',
  `user` int DEFAULT '0',
  `s1` decimal(10,2) DEFAULT '0.00',
  `s2` decimal(10,2) DEFAULT '0.00',
  `s3` decimal(10,2) DEFAULT '0.00',
  `s4` decimal(10,2) DEFAULT '0.00',
  `p1` int DEFAULT '0',
  `p2` int DEFAULT '0',
  `p3` int DEFAULT '0',
  `p4` int DEFAULT '0',
  `n1` decimal(10,2) DEFAULT '0.00',
  `n2` decimal(10,2) DEFAULT '0.00',
  `n3` decimal(10,2) DEFAULT '0.00',
  `n4` decimal(10,2) DEFAULT '0.00',
  `ng` decimal(10,2) DEFAULT '0.00',
  `obj` decimal(10,2) DEFAULT '0.00',
  `finale` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.scores : ~1 rows (environ)
INSERT INTO `scores` (`id`, `annee`, `user`, `s1`, `s2`, `s3`, `s4`, `p1`, `p2`, `p3`, `p4`, `n1`, `n2`, `n3`, `n4`, `ng`, `obj`, `finale`) VALUES
	(1, 2025, 3, 2.11, 2.83, 2.00, 1.60, 40, 10, 10, 40, 0.84, 0.28, 0.20, 0.64, 1.96, 4.00, 49);

-- Listage de la structure de table evaluation. set_softskills
DROP TABLE IF EXISTS `set_softskills`;
CREATE TABLE IF NOT EXISTS `set_softskills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_ss` int DEFAULT NULL,
  `tab_ss` int DEFAULT NULL,
  `nom_ss` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.set_softskills : ~14 rows (environ)
INSERT INTO `set_softskills` (`id`, `id_ss`, `tab_ss`, `nom_ss`) VALUES
	(1, 1, 1, 'Capacité à travailler en équipes'),
	(2, 2, 1, 'Prise d\'initiative'),
	(3, 3, 1, 'Accompagner le changement'),
	(4, 4, 1, 'Autonomie dans la résolution des problèmes'),
	(5, 5, 1, 'Communication et Feed-Back'),
	(6, 6, 1, 'Être orienté client'),
	(7, 7, 2, 'Ponctualité et Absence'),
	(8, 8, 2, 'Relation avec les collègues'),
	(9, 9, 2, 'Relation avec la hiérarchie '),
	(10, 10, 3, 'Animer une équipe '),
	(11, 11, 3, 'Gérer les conflits'),
	(12, 12, 3, 'Structurer l\'activité IT'),
	(13, 13, 3, 'Déléguer (Pour les managers)'),
	(14, 14, 3, 'Transversalité managériale (Pour les managers)');

-- Listage de la structure de table evaluation. softskills
DROP TABLE IF EXISTS `softskills`;
CREATE TABLE IF NOT EXISTS `softskills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annee` int DEFAULT NULL,
  `user` int DEFAULT NULL,
  `id_ss` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `point` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `score` int DEFAULT NULL,
  `commentaire` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `validation` int DEFAULT '0',
  `validation2` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.softskills : ~14 rows (environ)
INSERT INTO `softskills` (`id`, `annee`, `user`, `id_ss`, `point`, `score`, `commentaire`, `validation`, `validation2`) VALUES
	(1, 2024, 3, '1', 'progret', 3, 'gfgdfgaaaaaaaaaaa', 1, 0),
	(2, 2024, 3, '2', 'progret', 4, 'e', 1, 0),
	(3, 2024, 3, '3', 'progret', 2, 'e', 1, 0),
	(4, 2024, 3, '4', 'progret', 3, 'e', 1, 0),
	(5, 2024, 3, '5', 'progret', 1, 'e', 1, 0),
	(6, 2024, 3, '6', 'progret', 4, 'etgreg', 1, 0),
	(7, 2024, 3, '7', 'progret', 1, 'a', 1, 0),
	(8, 2024, 3, '8', 'progret', 1, 'a', 1, 0),
	(9, 2024, 3, '9', 'fort', 4, 'aeeeee', 1, 0),
	(10, 2024, 3, '10', 'fort', 1, 'a', 1, 0),
	(11, 2024, 3, '11', 'fort', 1, 'a', 1, 0),
	(12, 2024, 3, '12', 'progret', 1, 'a', 1, 0),
	(13, 2024, 3, '13', 'progret', 1, 'a', 1, 0),
	(14, 2024, 3, '14', 'progret', 4, '4', 1, 0);

-- Listage de la structure de table evaluation. users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `prenom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `date_embauche` date NOT NULL,
  `direction` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `poste` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `responsable` int NOT NULL DEFAULT '0',
  `pwd` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `collaborateurs` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valideur2` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.users : ~4 rows (environ)
INSERT INTO `users` (`id`, `login`, `nom`, `prenom`, `date_embauche`, `direction`, `poste`, `responsable`, `pwd`, `collaborateurs`, `valideur2`) VALUES
	(1, 'y.mfadel', 'Mfadel', 'Yassine', '2010-01-01', 'DG', 'DG', 0, '1234', '1', 1),
	(2, 'a.chabini', 'Chabini', 'Afaf', '2023-01-01', 'Direction Organisation et Système d\'information', 'DOSI', 1, '1234', '3,4', 1),
	(3, 'o.aboujaafar', 'Aboujaafar', 'Oussama', '2023-09-18', 'Direction Organisation et Système d\'information ', 'Responsable IT', 2, '1234', '4', 1),
	(4, 'h.bouhlal', 'Bouhlal', 'Hamza', '2023-10-09', 'Direction Organisation et Système d\'information ', 'Chargé Infrastructure IT & Réseaux', 3, '1234', 'N', 1);

-- Listage de la structure de table evaluation. validation
DROP TABLE IF EXISTS `validation`;
CREATE TABLE IF NOT EXISTS `validation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annee` int DEFAULT NULL,
  `user` int DEFAULT NULL,
  `submit` int DEFAULT '0',
  `date_submit` date DEFAULT NULL,
  `valideur1` int DEFAULT NULL,
  `validation1` int DEFAULT '0',
  `date_validation1` date DEFAULT NULL,
  `valideur2` int DEFAULT NULL,
  `validation2` int DEFAULT '0',
  `date_validation2` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.validation : ~2 rows (environ)
INSERT INTO `validation` (`id`, `annee`, `user`, `submit`, `date_submit`, `valideur1`, `validation1`, `date_validation1`, `valideur2`, `validation2`, `date_validation2`) VALUES
	(1, 2025, 3, 1, '2025-01-22', 2, 1, '2025-01-23', 1, 0, NULL),
	(2, 2025, 4, 0, NULL, 3, 0, NULL, 2, 0, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
