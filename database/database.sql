CREATE TABLE `administrateur`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP()) ENGINE=InnoDB;
ALTER TABLE
    `administrateur` ADD UNIQUE `administrateur_email_unique`(`email`);
CREATE TABLE `secteur`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `libelle` VARCHAR(100) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE `ville`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `libelle` VARCHAR(100) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE `type_contrat`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `libelle` VARCHAR(100) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE `candidat`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `telephone` VARCHAR(30) NOT NULL,
    `id_ville` INT UNSIGNED NULL,
    `cv_fichier` VARCHAR(255) NULL,
    `statut` VARCHAR(30) NOT NULL DEFAULT 'actif',
    `date_inscription` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP()) ENGINE=InnoDB;
ALTER TABLE
    `candidat` ADD UNIQUE `candidat_email_unique`(`email`);
CREATE TABLE `entreprise`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(150) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `telephone` VARCHAR(30) NOT NULL,
    `adresse` VARCHAR(255) NOT NULL,
    `id_secteur` INT UNSIGNED NOT NULL,
    `id_ville` INT UNSIGNED NOT NULL,
    `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP()) ENGINE=InnoDB;
CREATE TABLE `offre`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `titre` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `salaire` DECIMAL(12, 2) NULL,
    `date_limite` DATE NOT NULL,
    `statut` VARCHAR(30) NOT NULL DEFAULT 'brouillon',
    `id_entreprise` INT UNSIGNED NOT NULL,
    `id_secteur` INT UNSIGNED NOT NULL,
    `id_ville` INT UNSIGNED NOT NULL,
    `id_type_contrat` INT UNSIGNED NOT NULL,
    `date_publication` DATETIME NULL
) ENGINE=InnoDB;
CREATE TABLE `candidature`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_candidat` INT UNSIGNED NOT NULL,
    `id_offre` INT UNSIGNED NOT NULL,
    `lettre_motivation` TEXT NOT NULL,
    `statut` VARCHAR(30) NOT NULL DEFAULT 'en_attente',
    `date_candidature` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP()) ENGINE=InnoDB;
ALTER TABLE
    `candidature` ADD UNIQUE `candidature_id_candidat_id_offre_unique`(`id_candidat`, `id_offre`);
ALTER TABLE
    `candidat` ADD CONSTRAINT `candidat_id_ville_foreign` FOREIGN KEY(`id_ville`) REFERENCES `ville`(`id`);
ALTER TABLE
    `offre` ADD CONSTRAINT `offre_id_type_contrat_foreign` FOREIGN KEY(`id_type_contrat`) REFERENCES `type_contrat`(`id`);
ALTER TABLE
    `offre` ADD CONSTRAINT `offre_id_secteur_foreign` FOREIGN KEY(`id_secteur`) REFERENCES `secteur`(`id`);
ALTER TABLE
    `candidature` ADD CONSTRAINT `candidature_id_candidat_foreign` FOREIGN KEY(`id_candidat`) REFERENCES `candidat`(`id`);
ALTER TABLE
    `offre` ADD CONSTRAINT `offre_id_entreprise_foreign` FOREIGN KEY(`id_entreprise`) REFERENCES `entreprise`(`id`);
ALTER TABLE
    `entreprise` ADD CONSTRAINT `entreprise_id_ville_foreign` FOREIGN KEY(`id_ville`) REFERENCES `ville`(`id`);
ALTER TABLE
    `entreprise` ADD CONSTRAINT `entreprise_id_secteur_foreign` FOREIGN KEY(`id_secteur`) REFERENCES `secteur`(`id`);
ALTER TABLE
    `candidature` ADD CONSTRAINT `candidature_id_offre_foreign` FOREIGN KEY(`id_offre`) REFERENCES `offre`(`id`);
ALTER TABLE
    `offre` ADD CONSTRAINT `offre_id_ville_foreign` FOREIGN KEY(`id_ville`) REFERENCES `ville`(`id`);
-- ============================================================
-- DONNEES DE TEST : REFERENTIELS
-- ============================================================

INSERT INTO secteur (id, nom) VALUES
(1, 'Informatique'),
(2, 'Commerce'),
(3, 'Finance'),
(4, 'Bâtiment'),
(5, 'Santé'),
(6, 'Éducation'),
(7, 'Transport'),
(8, 'Hôtellerie');

INSERT INTO ville (id, nom) VALUES
(1, 'Cotonou'),
(2, 'Abomey-Calavi'),
(3, 'Porto-Novo'),
(4, 'Parakou'),
(5, 'Abomey'),
(6, 'Bohicon'),
(7, 'Ouidah'),
(8, 'Natitingou');

INSERT INTO type_contrat (id, nom) VALUES
(1, 'CDI'),
(2, 'CDD'),
(3, 'Stage'),
(4, 'Alternance'),
(5, 'Freelance');

-- ============================================================
-- DONNEES DE TEST : ENTREPRISES
-- ============================================================

INSERT INTO entreprise (nom, email, telephone, id_secteur, id_ville) VALUES
('TechnoBénin', 'contact@technobenin.test', '+229 21 30 40 50', 1, 1),
('Bénin Commerce', 'contact@benincommerce.test', '+229 21 31 41 51', 2, 2),
('Finance Plus Bénin', 'contact@financeplus.test', '+229 21 32 42 52', 3, 3),
('Bâtir Bénin', 'contact@batirbenin.test', '+229 21 33 43 53', 4, 4),
('Santé Bénin', 'contact@santebenin.test', '+229 21 34 44 54', 5, 1);

-- ============================================================
-- DONNEES DE TEST : OFFRES
-- ============================================================

INSERT INTO offre (titre, description, salaire, date_publication, date_expiration, statut, id_entreprise, id_secteur, id_ville, id_type_contrat) VALUES
('Développeur Web PHP', 'Développement et maintenance d applications web en PHP.', 450000, NOW(), '2026-12-31', 'publiee', 1, 1, 1, 1),
('Technicien Support Informatique', 'Assistance technique aux utilisateurs et maintenance du parc informatique.', 300000, NOW(), '2026-11-30', 'publiee', 1, 1, 2, 1),
('Commercial Terrain', 'Développement du portefeuille clients et prospection commerciale.', 250000, NOW(), '2026-12-15', 'publiee', 2, 2, 2, 2),
('Assistant Commercial', 'Appui administratif et commercial auprès de l équipe de vente.', 220000, NOW(), '2026-11-30', 'publiee', 2, 2, 1, 3),
('Analyste Financier', 'Analyse financière et préparation des rapports de gestion.', 500000, NOW(), '2026-12-20', 'publiee', 3, 3, 3, 1),
('Comptable Junior', 'Saisie comptable, suivi des opérations et préparation des documents financiers.', 280000, NOW(), '2026-11-15', 'publiee', 3, 3, 3, 2),
('Chef de Chantier', 'Supervision des équipes et suivi opérationnel des travaux.', 450000, NOW(), '2026-12-10', 'publiee', 4, 4, 4, 1),
('Conducteur de Travaux', 'Coordination et suivi technique des projets de construction.', 550000, NOW(), '2026-12-25', 'publiee', 4, 4, 4, 1),
('Infirmier Diplômé', 'Prise en charge des patients et participation aux activités de soins.', 300000, NOW(), '2026-11-30', 'publiee', 5, 5, 1, 1),
('Assistant Administratif Santé', 'Gestion administrative et accueil des patients.', 230000, NOW(), '2026-12-05', 'publiee', 5, 5, 1, 3);

-- ============================================================
-- DONNEES DE TEST : CANDIDATS
-- Mot de passe de test : Password123
-- ============================================================

INSERT INTO candidat (nom, prenom, email, mot_de_passe, telephone, id_ville, statut) VALUES
('Ahouandjinou', 'Marc', 'marc.ahouandjinou@test.local', '$2y$10$jvBJQm6hNOcaP4F7EkPc1OvyykWK6yeReMvcoG2TGLEiO68e8sysu', '+229 90 10 20 01', 1, 'actif'),
('Adjovi', 'Sarah', 'sarah.adjovi@test.local', '$2y$10$jvBJQm6hNOcaP4F7EkPc1OvyykWK6yeReMvcoG2TGLEiO68e8sysu', '+229 90 10 20 02', 2, 'actif'),
('Dossou', 'Kevin', 'kevin.dossou@test.local', '$2y$10$jvBJQm6hNOcaP4F7EkPc1OvyykWK6yeReMvcoG2TGLEiO68e8sysu', '+229 90 10 20 03', 3, 'actif'),
('Houngbédji', 'Julie', 'julie.houngbedji@test.local', '$2y$10$jvBJQm6hNOcaP4F7EkPc1OvyykWK6yeReMvcoG2TGLEiO68e8sysu', '+229 90 10 20 04', 4, 'actif'),
('Soglo', 'David', 'david.soglo@test.local', '$2y$10$jvBJQm6hNOcaP4F7EkPc1OvyykWK6yeReMvcoG2TGLEiO68e8sysu', '+229 90 10 20 05', 1, 'actif');

-- ============================================================
-- DONNEES DE TEST : ADMINISTRATEUR
-- Mot de passe de test : Password123
-- ============================================================

INSERT INTO administrateur (nom, prenom, email, mot_de_passe) VALUES
('Alitonou', 'Habib', 'admin@joblink-benin.test', '$2y$10$T.UsTTEt7jLNqWkmgyzN2O9nm8GFNFGOgs4D52DGUcc4QM7C/SYI6');
