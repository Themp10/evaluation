-- --------------------------------------------------------
-- Hôte:                         172.28.0.22
-- Version du serveur:           8.0.40-0ubuntu0.22.04.1 - (Ubuntu)
-- SE du serveur:                Linux
-- HeidiSQL Version:             12.5.0.6677
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

-- Listage de la structure de la table evaluation. evolution
DROP TABLE IF EXISTS `evolution`;
CREATE TABLE IF NOT EXISTS `evolution` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annee` int DEFAULT NULL,
  `user` int DEFAULT NULL,
  `souhait` int DEFAULT '1',
  `motivation` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `axes` varchar(1000) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avis` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.evolution : ~1 rows (environ)
INSERT INTO `evolution` (`id`, `annee`, `user`, `souhait`, `motivation`, `axes`, `avis`) VALUES
	(28, 2025, 34, 1, '4414141i', 'Array', 'ioiiiiii');

-- Listage de la structure de la table evaluation. formations
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.formations : ~0 rows (environ)

-- Listage de la structure de la table evaluation. objectifs
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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.objectifs : ~15 rows (environ)
INSERT INTO `objectifs` (`id`, `id_ligne`, `annee`, `user`, `objectif`, `indicateur`, `realisation`, `score`, `resultat_analyse`, `echeance`, `validation`, `validation2`) VALUES
	(1, 0, 2024, 34, 'Mettre en place un outils de suivi des consommables informatiques', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 100, 1, '742', 'Feb-24', 0, 0),
	(2, 1, 2024, 34, 'Mettre en place un outils de suivi de la fourniture de bureau ', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 20, 1, '472', 'Feb-24', 0, 0),
	(3, 2, 2024, 34, 'Mettre en place des DASHBOARD / Outils d\'assistance des Utilisateurs/Directeurs (Achats, ventes...)', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 200, 1, '72', 'Toute l\'année 2024', 0, 0),
	(4, 3, 2024, 34, 'Mettre en place l\'outil de suivi de la téléphonie', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 20, 1, '42', 'Feb-24', 0, 0),
	(5, 4, 2024, 34, 'Participer et s\'assurer de la bonne mise en œuvre des projets de mise à niveau de l\'infrastructure IT', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 20, 1, '710', 'Mar-24', 0, 0),
	(6, 5, 2024, 34, 'Participer et s\'assurer de la bonne mise en œuvre du projet de l\'intranet ', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 10, 1, '10', 'Sep-24', 0, 0),
	(7, 6, 2024, 34, 'Mise en place et déploiement des procédures IT ', '1- Nombre des procédures produit / Nombre prévu\n2- Qualité du livrable ', 55, 1, '410', 'Apr-24', 0, 0),
	(8, 7, 2024, 34, 'Veiller à la disponibilité de l\'infrastructre informatique ', 'Le nombre d\'incident constaté dans l\'année', 20, 1, '410', 'Toute l\'année 2024', 0, 0),
	(9, 8, 2024, 34, 'Assistance users solution (SAP, téléphonie, GECIMMO, Sage Paie …)', 'Nombre de tickets traité / Nombre de tickets ouvert', 50, 4, '70', 'Toute l\'année 2024', 0, 0),
	(10, 0, 2024, 36, 'Mettre en place un outils de suivi des consommables informatiques', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 0, 1, NULL, 'Feb-24', 0, 0),
	(11, 1, 2024, 36, 'Mettre en place un outils de suivi de la fourniture de bureau ', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 0, 1, NULL, 'Feb-24', 0, 0),
	(12, 2, 2024, 36, 'Mettre en place des DASHBOARD / Outils d\'assistance des Utilisateurs/Directeurs (Achats, ventes...)', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 0, 1, NULL, 'Toute l\'année 2024', 0, 0),
	(13, 3, 2024, 36, 'Mettre en place l\'outil de suivi de la téléphonie', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 0, 1, NULL, 'Feb-24', 0, 0),
	(14, 4, 2024, 36, 'Participer et s\'assurer de la bonne mise en œuvre des projets de mise à niveau de l\'infrastructure IT', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ', 0, 1, NULL, 'Mar-24', 0, 0),
	(33, 0, 2025, 34, '111', '77777', 0, 1, NULL, '10', 0, 0);

-- Listage de la structure de la table evaluation. scores
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.scores : ~2 rows (environ)
INSERT INTO `scores` (`id`, `annee`, `user`, `s1`, `s2`, `s3`, `s4`, `p1`, `p2`, `p3`, `p4`, `n1`, `n2`, `n3`, `n4`, `ng`, `obj`, `finale`) VALUES
	(1, 2025, 34, 1.33, 1.50, 1.00, 0.00, 50, 20, 30, 0, 0.67, 0.30, 0.30, 0.00, 1.27, 2.00, 63),
	(2, 2025, 36, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0);

-- Listage de la structure de la table evaluation. set_softskills
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
	(12, 12, 3, 'Structurer l\'activité'),
	(13, 13, 3, 'Déléguer (Pour les managers)'),
	(14, 14, 3, 'Transversalité managériale (Pour les managers)');

-- Listage de la structure de la table evaluation. softskills
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.softskills : ~28 rows (environ)
INSERT INTO `softskills` (`id`, `annee`, `user`, `id_ss`, `point`, `score`, `commentaire`, `validation`, `validation2`) VALUES
	(1, 2024, 34, '1', 'fort', 4, '1041', 0, 0),
	(2, 2024, 34, '2', 'fort', 1, '710', 0, 0),
	(3, 2024, 34, '3', 'fort', 1, '10', 0, 0),
	(4, 2024, 34, '4', 'fort', 1, '70', 0, 0),
	(5, 2024, 34, '5', 'fort', 1, '0440', 0, 0),
	(6, 2024, 34, '6', 'fort', 1, '410', 0, 0),
	(7, 2024, 34, '7', 'fort', 1, '410', 0, 0),
	(8, 2024, 34, '8', 'progret', 1, '140', 0, 0),
	(9, 2024, 34, '9', 'fort', 1, '7401', 0, 0),
	(10, 2024, 34, '10', NULL, NULL, NULL, 0, 0),
	(11, 2024, 34, '11', NULL, NULL, NULL, 0, 0),
	(12, 2024, 34, '12', NULL, NULL, NULL, 0, 0),
	(13, 2024, 34, '13', NULL, NULL, NULL, 0, 0),
	(14, 2024, 34, '14', NULL, NULL, NULL, 0, 0),
	(15, 2024, 36, '1', NULL, NULL, NULL, 0, 0),
	(16, 2024, 36, '2', NULL, NULL, NULL, 0, 0),
	(17, 2024, 36, '3', NULL, NULL, NULL, 0, 0),
	(18, 2024, 36, '4', NULL, NULL, NULL, 0, 0),
	(19, 2024, 36, '5', NULL, NULL, NULL, 0, 0),
	(20, 2024, 36, '6', NULL, NULL, NULL, 0, 0),
	(21, 2024, 36, '7', NULL, NULL, NULL, 0, 0),
	(22, 2024, 36, '8', NULL, NULL, NULL, 0, 0),
	(23, 2024, 36, '9', NULL, NULL, NULL, 0, 0),
	(24, 2024, 36, '10', NULL, NULL, NULL, 0, 0),
	(25, 2024, 36, '11', NULL, NULL, NULL, 0, 0),
	(26, 2024, 36, '12', NULL, NULL, NULL, 0, 0),
	(27, 2024, 36, '13', NULL, NULL, NULL, 0, 0),
	(28, 2024, 36, '14', NULL, NULL, NULL, 0, 0);

-- Listage de la structure de la table evaluation. users
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
  `collaborateurs` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valideur2` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.users : ~63 rows (environ)
INSERT INTO `users` (`id`, `login`, `nom`, `prenom`, `date_embauche`, `direction`, `poste`, `responsable`, `pwd`, `collaborateurs`, `valideur2`) VALUES
	(1, 'YMFADEL ', 'MFADEL ', 'YASSINE', '2010-01-01', 'Direction Générale', 'DG', 1, '1234', '0', 1),
	(2, 'TMFADEL', 'MFADEL', 'TARIK', '2010-01-01', 'Direction Générale', 'DG', 2, '1234', '0', 2),
	(3, 'MRIDA', 'RIDA', 'MEHDI', '2010-01-01', 'BU promotion immobilière', 'Directeur BU promotion immobilière', 63, '1234', '7,9,33,47,48,14,16,21,22,23,24,54,57,58,15,32,26,28,30,40,8,46,56,61', 63),
	(4, 'IRIDA', 'RIDA', 'IMAD OMAR', '2021-05-17', 'Gestion des actifs', 'Directeur Pôle Foncier & H', 63, '1234', '11', 63),
	(5, 'AJERAOUI', 'JERAOUI', 'AICHA', '2010-01-01', 'Direction Générale', 'Assistante DG', 63, '1234', '6,19,51,59', 63),
	(6, 'ADAOUICHY', 'DAOUICHY', 'ABDELHAK', '2015-05-01', 'Moyens généraux', 'AGENT ADMINISTRATIF', 5, '1234', 'NA', 63),
	(7, 'NMEZOUARI', 'MEZOUARI', 'NIZAR', '2015-08-01', 'Commercial', 'RESPONSABLE COMMERCIAL GROUPE', 3, '1234', '14,16,21,22,23,24,54,57,58', 63),
	(8, 'MRAHMOUNI', 'RAHMOUNI', 'MORAD', '2017-08-01', 'SAV', 'AGENT SAV', 47, '1234', 'NA', 3),
	(9, 'HSOUSSY', 'SOUSSY', 'HANANE', '2004-03-01', 'ADV', 'RESPONSABLE ADV', 3, '1234', '15,32', 63),
	(10, 'HBAMAAROUF', 'BAMAAROUF', 'HOUDA', '2017-12-04', 'Finance', 'COMPTABLE', 50, '1234', 'NA', 41),
	(11, 'MFARAHI', 'FARAHI', 'MERYEM', '2018-12-26', 'Gestion des actifs', 'PROPRETY MANAGER', 4, '1234', 'NA', 63),
	(12, 'RBOUZID', 'BOUZID', 'RACHID', '2019-11-20', 'Marketing et communication externe', 'INFOGRAPHISTE', 31, '1234', 'NA', 2),
	(13, 'GSAIDI', 'SAIDI', 'GHIZLANE', '2020-02-03', 'RH et communication interne', 'CHARGEE RH', 2, '1234', '0', 2),
	(14, 'ICHAHBANE', 'CHAHBANE', 'ISMAIL', '2020-06-01', 'Commercial', 'Conseiller commercial', 7, '1234', 'NA', 3),
	(15, 'AMOULHSSINE', 'MOULHSSINE', 'ASMAA', '2021-03-01', 'ADV', 'Cadre ADV', 9, '1234', 'NA', 3),
	(16, 'JHAMDOUNE', 'HAMDOUNE', 'JIHANE', '2021-06-15', 'Commercial', 'Conseiller commercial', 7, '1234', 'NA', 3),
	(17, 'HLAMKINSI', 'LAMKINSI', 'HALIMA', '2021-06-14', 'Technique', 'Directrice des Projets', 62, '1234', '49,55', 63),
	(18, 'MSAHIR', 'SAHIR', 'MOUNIR', '2021-07-26', 'Contrôle de gestion', 'DIRECTEUR C.G Groupe', 63, '1234', '29,37', 63),
	(19, 'ZYASSINE', 'YASSINE', 'ZINEB', '2021-07-26', 'Moyens généraux', 'Chargée d\'accueil', 5, '1234', 'NA', 63),
	(20, 'SSIFOUH', 'SIFOUH', 'SOUFIANE', '2021-08-02', 'Comptabilité', 'CHEF Comptable', 50, '1234', 'NA', 41),
	(21, 'NBARAKA', 'BARAKA', 'NEZHA', '2021-10-04', 'Commercial', 'Conseiller commercial', 7, '1234', 'NA', 3),
	(22, 'IYAGOU', 'YAGOU', 'IMANE', '2021-12-30', 'Commercial', 'Conseiller commercial', 7, '1234', 'NA', 3),
	(23, 'YMERNISSI', 'MERNISSI', 'YOUNES', '2022-08-15', 'Commercial', 'Conseiller commercial', 7, '1234', 'NA', 3),
	(24, 'WLABRINI', 'LABRINI', 'WAFA', '2022-10-10', 'Commercial', 'Conseiller commercial', 7, '1234', 'NA', 3),
	(25, 'SLAKHLIFA', 'LAKHLIFA', 'SAMIRA', '2022-10-10', 'Finance', 'Chargée d\'assurance', 50, '1234', 'NA', 41),
	(26, 'ZEL KHALIFI', 'EL KHALIFI', 'ZAINAB', '2022-10-01', 'CRC', 'Chargée clientèle', 33, '1234', 'NA', 3),
	(27, 'ACHABINI', 'CHABINI', 'AFAF', '2022-12-01', 'Organisation et système d\'information', 'DOSI', 63, '1234', '34,36', 63),
	(28, 'SFARDOUSSI', 'FARDOUSSI', 'SOUMAYA', '2023-03-01', 'CRC', 'Chargée clientèle', 33, '1234', 'NA', 3),
	(29, 'OKASSAOUI', 'KASSAOUI', 'OMAR', '2023-05-02', 'Contrôle de gestion', 'Contrôleur de gestion', 18, '1234', 'NA', 63),
	(30, 'NLAHRIDI', 'LAHRIDI', 'NOUREIMANE', '2023-06-01', 'CRC', 'Chargée clientèle', 33, '1234', 'NA', 3),
	(31, 'SGHALIMI', 'GHALIMI', 'SARA', '2023-09-11', 'Marketing et communication externe', 'Directrice marketing et communication externe', 2, '1234', '12,38', 2),
	(32, 'KBARKA', 'BARKA', 'KHADIJA', '2023-09-01', 'ADV', 'ASSISTANTE ADV', 9, '1234', 'NA', 3),
	(33, 'FMEBARKIA', 'MEBARKIA', 'FERIAL', '2023-09-15', 'CRC', 'Responsable CRC', 3, '1234', '26,28,30,40', 63),
	(34, 'OABOUJAAFAR', 'ABOUJAAFAR', 'OUSSAMA', '2023-09-18', 'Informatique', 'Responsable SI', 27, '1234', 'NA', 63),
	(35, 'TSIRAI', 'SIRAI', 'TAOUFIK', '2023-09-01', 'Finance', 'Comptable', 50, '1234', 'NA', 41),
	(36, 'HBOUHLAL', 'BOUHLAL', 'HAMZA', '2023-10-09', 'Informatique', 'Chargé d\'infrastructure & réseaux', 27, '1234', 'NA', 63),
	(37, 'ABELAOUD', 'BELAOUD', 'ABDOU', '2023-11-20', 'Contrôle de gestion', 'Contrôleur de gestion', 18, '1234', 'NA', 63),
	(38, 'ISOULHI', 'SOULHI', 'IKRAM', '2024-05-02', 'Marketing et communication externe', 'Chef de projet marketing', 31, '1234', 'NA', 2),
	(39, 'NSABRI', 'SABRI', 'NAJLAE', '2024-06-24', 'Juridique', 'Directrice Juridique', 63, '1234', 'NA', 63),
	(40, 'ITAYANE', 'TAYANE', 'IBTISSAM', '2024-06-25', 'CRC', 'Chargée clientèle', 33, '1234', 'NA', 3),
	(41, 'RGHAFIL', 'GHAFIL', 'RABII', '2024-11-04', 'Finance', 'Directeur Administratif et Financier', 63, '1234', '50,10,20,25,35,52,53,60', 63),
	(42, 'OBOUKHRESS', 'BOUKHRESS', 'OUM KELTOUM', '2016-08-03', 'Achat et approvisionnement', 'Acheteur Sénior', 44, '1234', 'NA', 63),
	(43, 'CTAHIR', 'TAHIR', 'CHANAZ', '2017-05-15', 'Moyens généraux', 'Chargé des moyens généraux', 2, '1234', 'NA', 2),
	(44, 'MHABB', 'HABB', 'EL MAHDI', '2019-01-14', 'Achat et approvisionnement', 'Acheteur', 63, '1234', '42', 63),
	(45, 'SBENARROU', 'BENARROU', 'SOUHAIL', '2019-02-01', 'Technique', 'Directeur des Projets', 62, '1234', 'NA', 63),
	(46, 'FEL GHATASSE', 'EL GHATASSE', 'FAHD', '2020-09-18', 'SAV', 'Agent SAV', 47, '1234', 'NA', 3),
	(47, 'MERRITOUNI', 'ERRITOUNI', 'MARIA', '2021-12-14', 'SAV', 'Responsable SAV et Maintenance', 3, '1234', '8,46,56,61', 63),
	(48, 'CSENHAJI', 'SENHAJI', 'Camélia Sherry', '2024-02-01', 'Technique', 'Responsable des programmes', 3, '1234', 'NA', 63),
	(49, 'RBADAOUI', 'BADAOUI', 'RIDA', '2024-08-15', 'Technique', 'Chef de Projet', 17, '1234', 'NA', 63),
	(50, 'WBENADDI', 'BENADDI', 'WAFAA', '1999-09-01', 'Finance', 'Responsable administratif', 41, '1234', '10,20,25,35,52,53,60', 63),
	(51, 'MZARKANI', 'ZARKANI', 'MOHAMMED', '2016-05-01', 'Moyens généraux', 'Coursier', 5, '1234', 'NA', 63),
	(52, 'JREDDADI', 'REDDADI', 'JIHAD', '2008-03-01', 'Finance', 'Comptable', 50, '1234', 'NA', 41),
	(53, 'KOULHOUSS', 'OULHOUSS', 'KELTOUM', '2009-02-02', 'Recouvrement', 'Responsable recouvrement', 50, '1234', 'NA', 41),
	(54, 'MSIDKI', 'SIDKI', 'MERYEM', '2019-06-01', 'Commercial', 'Conseiller commercial', 7, '1234', 'NA', 3),
	(55, 'BEL MAZOUZI', 'EL MAZOUZI', 'BRAHIM', '2022-03-01', 'Technique', 'Conducteur travaux', 17, '1234', 'NA', 63),
	(56, 'ARAHMOUNI', 'RAHMOUNI', 'ADIL', '2023-04-01', 'SAV', 'Agent SAV', 47, '1234', 'NA', 3),
	(57, 'KMACHKOUR', 'MACHKOUR', 'KHADIJA', '2023-04-01', 'Commercial', 'Responsable commercial Tertiaire', 7, '1234', 'NA', 3),
	(58, 'ANAFIS', 'NAFIS', 'AMINE', '2023-04-01', 'Commercial', 'Conseiller commercial', 7, '1234', 'NA', 3),
	(59, 'SES-SARAOUI', 'ES-SARAOUI', 'SALAH', '2023-04-01', 'Moyens généraux', 'Coursier', 5, '1234', 'NA', 63),
	(60, 'IOUKKAS', 'OUKKAS', 'IBTISSAME', '2023-04-01', 'Finance', 'Comptable', 50, '1234', 'NA', 41),
	(61, 'HEDDAHER', 'EDDAHER', 'HICHAM', '2024-05-01', 'SAV', 'Agent SAV', 47, '1234', 'NA', 3),
	(62, 'ANACER', 'NACER', 'ADIB', '2010-01-01', 'Direction technique', 'Directeur technique', 63, '1234', '17,45,49,55', 63),
	(63, 'ALAHCINI', 'LAHCINI', 'ABDELJALIL', '2010-01-01', 'Support', 'Secretaire général', 1, '1234', '0', 1);

-- Listage de la structure de la table evaluation. validation
DROP TABLE IF EXISTS `validation`;
CREATE TABLE IF NOT EXISTS `validation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annee` int DEFAULT NULL,
  `user` int DEFAULT NULL,
  `saisie` int DEFAULT '0',
  `submit` int DEFAULT '0',
  `date_submit` date DEFAULT NULL,
  `valideur1` int DEFAULT NULL,
  `validation1` int DEFAULT '0',
  `date_validation1` date DEFAULT NULL,
  `valideur2` int DEFAULT NULL,
  `validation2` int DEFAULT '0',
  `date_validation2` date DEFAULT NULL,
  `validationRH` int DEFAULT '0',
  `date_validationRH` date DEFAULT NULL,
  `validationDG` int DEFAULT '0',
  `date_validationDG` date DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.validation : ~2 rows (environ)
INSERT INTO `validation` (`id`, `annee`, `user`, `saisie`, `submit`, `date_submit`, `valideur1`, `validation1`, `date_validation1`, `valideur2`, `validation2`, `date_validation2`, `validationRH`, `date_validationRH`, `validationDG`, `date_validationDG`) VALUES
	(1, 2025, 34, 1, 1, '2025-01-24', 27, 1, '2025-01-24', 63, 0, NULL, 0, NULL, 0, NULL),
	(2, 2025, 36, 0, 0, NULL, 27, 0, NULL, 63, 0, NULL, 0, NULL, 0, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
