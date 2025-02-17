
-- TABLE objectifs : besoin d'inserer les anciens	


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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.objectifs : ~11 rows (environ)
INSERT INTO `objectifs` (`id_ligne`, `annee`, `user`, `objectif`, `indicateur`, `echeance`) VALUES
	(0, 2024, 34, 'Mettre en place un outils de suivi des consommables informatiques', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Feb-24'),
	(1, 2024, 34, 'Mettre en place un outils de suivi de la fourniture de bureau ', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Feb-24'),
	(2, 2024, 34, 'Mettre en place des DASHBOARD / Outils d\'assistance des Utilisateurs/Directeurs (Achats, ventes...)', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Toute l\'année 2024'),
	(3, 2024, 34, 'Mettre en place l\'outil de suivi de la téléphonie', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Feb-24'),
	(4, 2024, 34, 'Participer et s\'assurer de la bonne mise en œuvre des projets de mise à niveau de l\'infrastructure IT', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Mar-24'),
	(5, 2024, 34, 'Participer et s\'assurer de la bonne mise en œuvre du projet de l\'intranet ', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Sep-24'),
	(6, 2024, 34, 'Mise en place et déploiement des procédures IT ', '1- Nombre des procédures produit / Nombre prévu\n2- Qualité du livrable ','Apr-24'),
	(7, 2024, 34, 'Veiller à la disponibilité de l\'infrastructre informatique ', 'Le nombre d\'incident constaté dans l\'année','Toute l\'année 2024'),
	(8, 2024, 34, 'Assistance users solution (SAP, téléphonie, GECIMMO, Sage Paie …)', 'Nombre de tickets traité / Nombre de tickets ouvert','Toute l\'année 2024'),
	(0, 2024, 36, 'Mettre en place un outils de suivi des consommables informatiques', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Feb-24'),
	(1, 2024, 36, 'Mettre en place un outils de suivi de la fourniture de bureau ', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Feb-24'),
	(2, 2024, 36, 'Mettre en place des DASHBOARD / Outils d\'assistance des Utilisateurs/Directeurs (Achats, ventes...)', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Toute l\'année 2024'),
	(3, 2024, 36, 'Mettre en place l\'outil de suivi de la téléphonie', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Feb-24'),
	(4, 2024, 36, 'Participer et s\'assurer de la bonne mise en œuvre des projets de mise à niveau de l\'infrastructure IT', 'Finalisation et stabilisation de la solution :\n1- Délais réel de réalisation du projet / Délais prévu\n2- Coût réel / Coût prévu\n3- Qualité du projet ','Mar-24');
	
	
-- TABLE evolution : pas besoin	
	
	
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- TABLE formations : pas besoin	

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- TABLE score  :  besoin	d'initalisation 

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.scores : ~1 rows (environ)
INSERT INTO `scores` (`annee`, `user`) VALUES	(2025, 34),(2025, 36);



-- TABLE softskills  :  besoin	d'initalisation 


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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.softskills : ~14 rows (environ)
INSERT INTO `softskills` ( `annee`, `user`, `id_ss`) VALUES
	(2024, 34, '1'),
	(2024, 34, '2'),
	(2024, 34, '3'),
	(2024, 34, '4'),
	(2024, 34, '5'),
	(2024, 34, '6'),
	(2024, 34, '7'),
	(2024, 34, '8'),
	(2024, 34, '9'),
	(2024, 34, '10'),
	(2024, 34, '11'),
	(2024, 34, '12'),
	(2024, 34, '13'),
	(2024, 34, '14'),
	(2024, 36, '1'),
	(2024, 36, '2'),
	(2024, 36, '3'),
	(2024, 36, '4'),
	(2024, 36, '5'),
	(2024, 36, '6'),
	(2024, 36, '7'),
	(2024, 36, '8'),
	(2024, 36, '9'),
	(2024, 36, '10'),
	(2024, 36, '11'),
	(2024, 36, '12'),
	(2024, 36, '13'),
	(2024, 36, '14');
-- TABLE validation  :  besoin	d'initalisation 

DROP TABLE IF EXISTS `validation`;
CREATE TABLE `validation` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`annee` INT(10) NULL DEFAULT NULL,
	`user` INT(10) NULL DEFAULT NULL,
	`saisie` INT(10) NULL DEFAULT '0',
	`submit` INT(10) NULL DEFAULT '0',
	`date_submit` DATE NULL DEFAULT NULL,
	`valideur1` INT(10) NULL DEFAULT NULL,
	`validation1` INT(10) NULL DEFAULT '0',
	`date_validation1` DATE NULL DEFAULT NULL,
	`valideur2` INT(10) NULL DEFAULT NULL,
	`validation2` INT(10) NULL DEFAULT '0',
	`date_validation2` DATE NULL DEFAULT NULL,
	`validationRH` INT(10) NULL DEFAULT '0',
	`date_validationRH` DATE NULL DEFAULT NULL,
	`validationDG` INT(10) NULL DEFAULT '0',
	`date_validationDG` DATE NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;

-- Listage des données de la table evaluation.validation : ~2 rows (environ)
INSERT INTO `validation` (`annee`, `user`,`valideur1`,`valideur2`) VALUES
	(2025, 34,2,1),(2025, 36,3,2);

-- TABLE evolution  :  inserer la ligne

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table evaluation.evolution : ~1 rows (environ)
INSERT INTO `evolution` ( `annee`, `user`, `souhait`) VALUES
	(2025, 34, 1),(2025, 36, 1);


