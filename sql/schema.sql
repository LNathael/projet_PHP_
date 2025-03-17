-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
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


-- Listage de la structure de la base pour projet_php_
CREATE DATABASE IF NOT EXISTS `projet_php_` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `projet_php_`;

-- Listage de la structure de table projet_php_. avis
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int NOT NULL AUTO_INCREMENT,
  `id_produit` int DEFAULT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `commentaire` text NOT NULL,
  `note` int DEFAULT NULL,
  `date_avis` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type_contenu` enum('recette','programme') NOT NULL,
  `contenu_id` int NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_produit` (`id_produit`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE,
  CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `avis_chk_1` CHECK ((`note` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.avis : ~0 rows (environ)
INSERT INTO `avis` (`id_avis`, `id_produit`, `id_utilisateur`, `commentaire`, `note`, `date_avis`, `type_contenu`, `contenu_id`) VALUES
	(2, NULL, 2, 'miam super bonn !!!!!', 5, '2025-01-05 22:57:03', 'recette', 3);

-- Listage de la structure de table projet_php_. commandes
CREATE TABLE IF NOT EXISTS `commandes` (
  `id_commande` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `date_commande` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut_commande` enum('en cours','validée','expédiée','livrée','annulée') DEFAULT 'en cours',
  PRIMARY KEY (`id_commande`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.commandes : ~0 rows (environ)

-- Listage de la structure de table projet_php_. ligne_commande
CREATE TABLE IF NOT EXISTS `ligne_commande` (
  `id_ligne_commande` int NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_ligne_commande`),
  KEY `id_commande` (`id_commande`),
  KEY `id_produit` (`id_produit`),
  CONSTRAINT `ligne_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE,
  CONSTRAINT `ligne_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.ligne_commande : ~0 rows (environ)

-- Listage de la structure de table projet_php_. ligne_panier
CREATE TABLE IF NOT EXISTS `ligne_panier` (
  `id_ligne_panier` int NOT NULL AUTO_INCREMENT,
  `id_panier` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL,
  PRIMARY KEY (`id_ligne_panier`),
  KEY `id_panier` (`id_panier`),
  KEY `id_produit` (`id_produit`),
  CONSTRAINT `ligne_panier_ibfk_1` FOREIGN KEY (`id_panier`) REFERENCES `panier` (`id_panier`) ON DELETE CASCADE,
  CONSTRAINT `ligne_panier_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.ligne_panier : ~0 rows (environ)

-- Listage de la structure de table projet_php_. messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_salon` int NOT NULL,
  `contenu` text NOT NULL,
  `date_message` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_recette` int DEFAULT NULL,
  `id_produit` int DEFAULT NULL,
  PRIMARY KEY (`id_message`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_salon` (`id_salon`),
  KEY `id_recette` (`id_recette`),
  KEY `id_produit` (`id_produit`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`id_salon`) REFERENCES `salons` (`id_salon`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`id_recette`) REFERENCES `recettes` (`id_recette`) ON DELETE SET NULL,
  CONSTRAINT `messages_ibfk_4` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.messages : ~0 rows (environ)

-- Listage de la structure de table projet_php_. panier
CREATE TABLE IF NOT EXISTS `panier` (
  `id_panier` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL DEFAULT '1',
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_panier`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_produit` (`id_produit`),
  CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.panier : ~0 rows (environ)
INSERT INTO `panier` (`id_panier`, `id_utilisateur`, `id_produit`, `quantite`, `date_ajout`) VALUES
	(4, 9, 6, 2, '2025-03-17 13:09:22');

-- Listage de la structure de table projet_php_. produits
CREATE TABLE IF NOT EXISTS `produits` (
  `id_produit` int NOT NULL AUTO_INCREMENT,
  `nom_produit` varchar(100) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `quantite_disponible` int NOT NULL,
  `libelle` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.produits : ~2 rows (environ)
INSERT INTO `produits` (`id_produit`, `nom_produit`, `description`, `prix`, `quantite_disponible`, `libelle`, `image`) VALUES
	(6, 'LE BIHAN', 'test', 20.00, 1, NULL, 'uploads/produits/produit_6784eba8db85f.jpg'),
	(9, 'LE BIHAN', 'je suis trop beau , putain de merde', 10000.00, 1, NULL, 'uploads/produits/produit_67851bcfd9d9d.jpg');

-- Listage de la structure de table projet_php_. programmes
CREATE TABLE IF NOT EXISTS `programmes` (
  `id_programme` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `objectif` varchar(255) NOT NULL,
  `frequence` int DEFAULT NULL,
  `niveau` varchar(50) NOT NULL,
  `programme` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_programme`),
  KEY `fk_programmes_user` (`id_utilisateur`) USING BTREE,
  CONSTRAINT `fk_programmes_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.programmes : ~0 rows (environ)

-- Listage de la structure de table projet_php_. recettes
CREATE TABLE IF NOT EXISTS `recettes` (
  `id_recette` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `categorie` enum('Prise de masse','Maintien','Sèche') NOT NULL,
  `ingredients` text NOT NULL,
  `etapes` text NOT NULL,
  `id_utilisateur` int NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_recette`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `recettes_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.recettes : ~2 rows (environ)
INSERT INTO `recettes` (`id_recette`, `titre`, `description`, `categorie`, `ingredients`, `etapes`, `id_utilisateur`, `date_creation`, `image`) VALUES
	(3, 'omelette', 'casser les oeuf dans un bol \r\net mettre dans un poêle avec du lait', 'Prise de masse', 'lait\r\noeuf', '2', 2, '2025-01-02 23:22:42', 'uploads/recettes/recette_677711b23dfc5.jpg'),
	(4, 'crepe', 'tres bon', 'Prise de masse', 'oeuf \r\nlait\r\nfarine', '2', 1, '2025-01-18 13:06:22', 'uploads/recettes/recette_678b993eb8f7a.webp');

-- Listage de la structure de table projet_php_. salons
CREATE TABLE IF NOT EXISTS `salons` (
  `id_salon` int NOT NULL AUTO_INCREMENT,
  `nom_salon` varchar(50) NOT NULL,
  `description` text,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_salon`),
  UNIQUE KEY `nom_salon` (`nom_salon`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.salons : ~5 rows (environ)
INSERT INTO `salons` (`id_salon`, `nom_salon`, `description`, `date_creation`) VALUES
	(1, '#Général', 'Chat libre entre membres.', '2025-02-17 13:57:45'),
	(2, '#Entraînement', 'Conseils et retours sur les exercices.', '2025-02-17 13:57:45'),
	(3, '#Nutrition', 'Idées de repas et plans alimentaires.', '2025-02-17 13:57:45'),
	(4, '#Produits', 'Avis sur les produits en vente.', '2025-02-17 13:57:45'),
	(5, '#Annonces', 'News et promos du site.', '2025-02-17 13:57:45');

-- Listage de la structure de table projet_php_. user_programs
CREATE TABLE IF NOT EXISTS `user_programs` (
  `id_programme` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `objectif` varchar(255) NOT NULL,
  `frequence` int DEFAULT NULL,
  `niveau` varchar(50) NOT NULL,
  `programme` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_programme`),
  KEY `fk_user_programs_user` (`user_id`),
  CONSTRAINT `fk_user_programs_user` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.user_programs : ~0 rows (environ)
INSERT INTO `user_programs` (`id_programme`, `user_id`, `objectif`, `frequence`, `niveau`, `programme`, `created_at`, `updated_at`) VALUES
	(1, 2, 'perte de poids', 4, 'débutant', 'Lundi : Cardio léger (marche rapide, vélo 30 min)\r\nMercredi : Renforcement musculaire avec poids légers\r\nVendredi : Yoga ou stretching', '2025-01-05 19:16:33', '2025-01-05 19:16:41'),
	(2, 2, 'powerlifting', 4, 'débutant', 'Jour 1 : Squat 3x5, Leg Press 3x10, Crunchs 3x12\nJour 2 : Développé couché 3x5, Pompes 3x10, Planche 3x30s\nJour 3 : Soulevé de terre 3x5, Tractions 3x5, Extensions lombaires 3x10', '2025-01-05 19:16:37', '2025-01-05 19:16:37'),
	(3, 2, 'powerlifting', 5, 'intermédiaire', 'Jour 1 : Squat 4x4, Front squat 3x6, Fentes avec haltères 3x8\nJour 2 : Développé couché 4x4, Développé incliné 3x6, Dips 3x6\nJour 3 : Soulevé de terre 4x4, Deadlift jambes tendues 3x6, Rowing 3x8\nJour 4 : Deadlift partiel (rack pulls) 3x4, Squat pause 3x5', '2025-01-06 08:39:29', '2025-01-06 08:39:29'),
	(4, 2, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-01-06 09:26:34', '2025-01-06 09:26:34'),
	(5, 2, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-01-06 09:26:40', '2025-01-06 09:26:40'),
	(7, 1, 'powerlifting', 4, 'intermédiaire', 'Jour 1 : Squat 4x4, Front squat 3x6, Fentes avec haltères 3x8\nJour 2 : Développé couché 4x4, Développé incliné 3x6, Dips 3x6\nJour 3 : Soulevé de terre 4x4, Deadlift jambes tendues 3x6, Rowing 3x8\nJour 4 : Deadlift partiel (rack pulls) 3x4, Squat pause 3x5', '2025-01-20 12:04:06', '2025-01-20 12:04:06'),
	(8, 1, 'powerlifting', 4, 'intermédiaire', 'Jour 1 : Squat 4x4, Front squat 3x6, Fentes avec haltères 3x8\nJour 2 : Développé couché 4x4, Développé incliné 3x6, Dips 3x6\nJour 3 : Soulevé de terre 4x4, Deadlift jambes tendues 3x6, Rowing 3x8\nJour 4 : Deadlift partiel (rack pulls) 3x4, Squat pause 3x5', '2025-01-20 12:04:09', '2025-01-20 12:04:09'),
	(9, 6, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-02-14 16:04:45', '2025-02-14 16:04:45'),
	(10, 6, 'gain de masse', 4, 'débutant', 'Lundi : Entraînement basique haut du corps\nMercredi : Bas du corps (squats, fentes)\nVendredi : Exercices combinés avec poids légers', '2025-02-14 16:04:49', '2025-02-14 16:04:49'),
	(11, 7, 'perte de poids', 4, 'intermédiaire', 'Lundi : Cardio modéré (course légère, vélo 45 min)\nMardi : Renforcement musculaire avec poids moyens\nJeudi : HIIT 20 min\nSamedi : Étirements et relaxation', '2025-02-17 12:11:26', '2025-02-17 12:11:26'),
	(12, 7, 'perte de poids', 4, 'intermédiaire', 'Lundi : Cardio modéré (course légère, vélo 45 min)\nMardi : Renforcement musculaire avec poids moyens\nJeudi : HIIT 20 min\nSamedi : Étirements et relaxation', '2025-02-17 12:11:30', '2025-02-17 12:11:30');

-- Listage de la structure de table projet_php_. utilisateurs
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('utilisateur','administrateur','super_administrateur') DEFAULT 'utilisateur',
  `date_naissance` date DEFAULT NULL,
  `sexe` enum('Homme','Femme','Autre') DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `photo_profil` varchar(255) DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projet_php_.utilisateurs : ~4 rows (environ)
INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `date_naissance`, `sexe`, `date_creation`, `photo_profil`, `badge`) VALUES
	(1, 'Le Bihan', 'Nathaël', 'xarunax69@gmail.com', '$2y$10$j3VjdNMrOq4gyFpKUDs9MOOLRbT4oCohOvzD.TFOhgz17joIST1Oy', 'administrateur', '2005-10-12', 'Homme', '2024-12-11 20:54:21', NULL, NULL),
	(2, 'LE BIHAN', 'Nathaël', 'nathael.lebihan12102005@gmail.com', '$2y$10$D03sL3VkP9kD.LRdaljwu.KpvbtU37P7.4yAwkRWL9JbLLrHX8ONi', 'utilisateur', '2005-10-12', 'Homme', '2024-12-20 11:39:16', NULL, NULL),
	(6, 'Nathael', 'dsdza', 'nathael.lebihanIDBZSDZOILD@gmail.com', '$2y$10$WXEO6NJOY/qJ.K2PXSxavOsnSNexYMMbbP0xjszBmbt.6AO9ec4Y6', 'super_administrateur', '2055-10-12', 'Homme', '2025-02-14 16:03:52', NULL, NULL),
	(7, 'LE BIHAN', 'Nathaël', 'xarunax68@gmail.com', '$2y$10$OPoyVHXYDQGYOlrVt56hiuOPNkrT3NEzx9hUBOpwnf.uqQtKokEaW', 'super_administrateur', '2005-10-12', 'Homme', '2025-02-17 10:39:04', NULL, NULL),
	(8, 'Perin', 'Yanis', 'yanis@perin.fr', '$2y$10$AfRQPB6.us5k/1MJ5Jv0cup6iGaj3kCI301CZPAAnvG7Id1R1Lyzy', 'utilisateur', '2025-12-03', 'Femme', '2025-03-17 09:11:54', NULL, NULL),
	(9, 'yanis', 'Perin', 'yanis.perin@gmail.com', '$2y$10$iU3tBG3BFwZVq5.3x5VGZejDJBxMnAHQoQLS3SAg.qcCibSVZjVUO', 'utilisateur', '2005-10-12', 'Homme', '2025-03-17 10:11:58', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
