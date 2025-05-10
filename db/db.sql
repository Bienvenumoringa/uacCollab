DROP DATABASE uaccolab;
CREATE DATABASE uaccolab;
USE uaccolab;

CREATE TABLE `etudiant` (
  `MatriculeInscrit` varchar(50) NOT NULL DEFAULT '',
  `Nom` varchar(255) DEFAULT NULL,
  `PostNom` varchar(255) DEFAULT NULL,
  `Prenom` varchar(255) DEFAULT NULL,
  `Sexe` char(1) DEFAULT NULL,
  `Tel` varchar(255) DEFAULT NULL,
  `TelTuteur` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `Email` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `EmailTuteur` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `Lieunaissance` varchar(50) DEFAULT NULL,
  `Datenaissance` date DEFAULT NULL,
  `Adresse` varchar(100) DEFAULT NULL,
  `typeidentite` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `numeroidentite` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `ville` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `commune` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `quartier` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `numeroparcele` text,
  `Adressecontrat` varchar(50) DEFAULT NULL,
  `Nationalite` varchar(50) DEFAULT NULL,
  `ProvinceOrigine` varchar(50) DEFAULT NULL,
  `TerritoireCommune` varchar(50) DEFAULT NULL,
  `secteurorigine` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `quartierorigine` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `village` text,
  `EtatCivil` varchar(15) DEFAULT NULL,
  `EcoleSecondaire` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `provinceecole` text,
  `villeecole` text,
  `sectionecole` text,
  `AdresseEcole` varchar(100) DEFAULT NULL,
  `nomcentreexamenetat` varchar(50) DEFAULT NULL,
  `anneeobtentiondiplome` varchar(10) DEFAULT NULL,
  `Pourcentage` double DEFAULT NULL,
  `numerodiplome` varchar(30) DEFAULT NULL,
  `postsecondaire` varchar(200) DEFAULT NULL,
  `photo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `nompere` text,
  `nommere` text,
  `typesponsor` text,
  `nomsponsor` text,
  `groupesanguin` text,
  `taille` text,
  `allergie` text,
  `handicap` text,
  `dateenregistrement` datetime NOT NULL,
  `datemodification` date NOT NULL,
  `password` text,
  `emailpro` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `faculte` (
  `Codfac` varchar(15) NOT NULL DEFAULT '',
  `Nomfac` varchar(255) DEFAULT NULL,
  `presentation` text NOT NULL,
  `domaine` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `departement` (
  `CodDep` varchar(15) NOT NULL DEFAULT '',
  `NomDep` varchar(255) DEFAULT NULL,
  `Codfac` varchar(15) DEFAULT NULL,
  `presentation` text NOT NULL,
  `lmd` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `filiere` (
  `Codfil` varchar(15) NOT NULL DEFAULT '',
  `NomFil` varchar(250) DEFAULT NULL,
  `CodDep` varchar(15) DEFAULT NULL,
  `lmd` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `promotion` (
  `CodPro` varchar(15) NOT NULL DEFAULT '',
  `Codfil` varchar(15) DEFAULT NULL,
  `NomPro` varchar(255) DEFAULT NULL,
  `lmd` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;


CREATE TABLE `inscription` (
  `idinscription` int NOT NULL,
  `matriculeinscrit` varchar(50) DEFAULT NULL,
  `CodPro` varchar(15) DEFAULT NULL,
  `Dateinscription` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `AnneeAcad` varchar(15) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `Type` int NOT NULL,
  `affichepass` varchar(3) NOT NULL,
  `etat` int NOT NULL,
  `sessiondeux` int NOT NULL,
  `decision` varchar(5) NOT NULL,
  `grade` varchar(5) NOT NULL,
  `decisiondeux` varchar(5) NOT NULL,
  `gradedeux` varchar(5) NOT NULL,
  `enrolement` int NOT NULL,
  `recour` int NOT NULL,
  `groupe` int NOT NULL,
  `sem1` double DEFAULT NULL,
  `sem2` double DEFAULT NULL,
  `annuelle` double DEFAULT NULL,
  `decis1` text,
  `decis2` varchar(20) DEFAULT NULL,
  `decisanl` varchar(20) DEFAULT NULL,
  `pourcs1` double NOT NULL,
  `pourcs2` double NOT NULL,
  `blocage` int NOT NULL,
  `affichepassdeux` int NOT NULL,
  `valide` int NOT NULL,
  `suppression` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `decanatlogin` (
  `identifiant` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `enseignant` (
  `Matriculenseig` varchar(50) NOT NULL DEFAULT '',
  `Nom` varchar(255) DEFAULT NULL,
  `PostNom` varchar(255) DEFAULT NULL,
  `Prenom` varchar(255) DEFAULT NULL,
  `Sexe` char(1) DEFAULT NULL,
  `Tel` varchar(255) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `emailpro` text,
  `specialisation` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `photo` text NOT NULL,
  `CodTypeEns` varchar(50) NOT NULL,
  `CodFonc` varchar(50) NOT NULL,
  `CodDipl` varchar(50) NOT NULL,
  `CodGrad` varchar(50) NOT NULL,
  `CodDep` varchar(50) NOT NULL,
  `pwd` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `username` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;


CREATE TABLE collab_projet (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    dates DATETIME,
    titre TEXT,
    description TEXT,
    inscription TEXT,
    backgroud TEXT,
    running INT DEFAULT 0
);

CREATE TABLE collab_projet_encadreur (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    projet BIGINT,
    enseignant TEXT,
    admin INT,
    status INT
);

CREATE TABLE collab_fichiers_projet (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    dates DATETIME,
    projet BIGINT,
    fichier TEXT,
    user BIGINT,
    commentaire TEXT,
    type TEXT,
    version TEXT,
    status INT
);

CREATE TABLE collab_message (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    dates DATETIME DEFAULT NOW(),
    contenu TEXT,
    fichier TEXT,
    projet BIGINT,  -- Référence à un projet
    auteur TEXT,  -- L'utilisateur qui a envoyé le message
    admin TEXT,
    role TEXT
);

CREATE TABLE collab_suivi_message (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    message BIGINT,
    project BIGINT,
    auteur TEXT,  -- L'utilisateur qui reçoit le message
    role TEXT,
    status INT DEFAULT 0 -- 0 = non lu, 1 = lu
);

CREATE TABLE collab_commentaire (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    dates DATETIME,
    contenu TEXT,
    filtre TEXT,
    user BIGINT,
    id_file BIGINT,
    role TEXT,
    status INT
);

CREATE TABLE collab_likes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    likes INT,
    user BIGINT,
    commentaire BIGINT,
    role TEXT
);


-- TABLE: etudiant
INSERT INTO etudiant (MatriculeInscrit, Nom, PostNom, Prenom, Sexe, Tel, TelTuteur, Email, EmailTuteur, Lieunaissance, Datenaissance, Adresse, typeidentite, numeroidentite, ville, commune, quartier, numeroparcele, Adressecontrat, Nationalite, ProvinceOrigine, TerritoireCommune, secteurorigine, quartierorigine, village, EtatCivil, EcoleSecondaire, provinceecole, villeecole, sectionecole, AdresseEcole, nomcentreexamenetat, anneeobtentiondiplome, Pourcentage, numerodiplome, postsecondaire, photo, nompere, nommere, typesponsor, nomsponsor, groupesanguin, taille, allergie, handicap, dateenregistrement, datemodification, password, emailpro)
VALUES
('ET001', 'Kabongo', 'Mbuyi', 'Jean', 'M', '0999999999', '0888888888', 'jean.kabongo@example.com', 'pere.kabongo@example.com', 'Kinshasa', '2000-01-01', 'Commune de Lemba', 'CNI', '123456789', 'Kinshasa', 'Lemba', 'Makala', 'A12', 'Contrat Lemba', 'Congolaise', 'Kinshasa', 'Tshangu', 'Masina', 'Matete', 'Nsele', 'Célibataire', 'Institut Lemba', 'Kinshasa', 'Lemba', 'Scientifique', 'Av. Lemba', 'Centre1', '2018', 65.5, 'D12345', 'None', '', 'Pierre Kabongo', 'Marie Mbuyi', 'Bourse', 'UNESCO', 'O+', '1.70', 'Aucune', 'Aucun', NOW(), CURDATE(), '81dc9bdb52d04dc20036dbd8313ed055', 'kabongo@uaconline.edu.cd'),
('ET002', 'Ngoma', 'Tshibanda', 'Aline', 'F', '0977777777', '0855555555', 'aline.ngoma@example.com', 'tuteur.ngoma@example.com', 'Lubumbashi', '2001-05-15', 'Kampemba', 'CNI', '987654321', 'Lubumbashi', 'Kampemba', 'Kalubwe', 'B20', 'Contrat Kalubwe', 'Congolaise', 'Haut-Katanga', 'Kampemba', 'Kamalondo', 'Kenya', 'Mawete', 'Mariée', 'Lycée Lubumbashi', 'Haut-Katanga', 'Lubumbashi', 'Pédagogique', 'Av. Kenya', 'Centre2', '2019', 78.2, 'D54321', 'ISP', '', 'Papa Ngoma', 'Maman Tshibanda', 'Parents', 'Privé', 'A-', '1.65', 'Poussière', 'Aucun', NOW(), CURDATE(), '81dc9bdb52d04dc20036dbd8313ed055', 'aline@uaconline.edu.cd'),
('ET003', 'Mukendi', 'Kalala', 'David', 'M', '0966666666', '0844444444', 'david.mukendi@example.com', 'tuteur.kalala@example.com', 'Mbuji-Mayi', '1999-07-20', 'Dibindi', 'Passeport', 'A12345678', 'Mbuji-Mayi', 'Dibindi', 'Bena', 'C34', 'Contrat Dibindi', 'Congolaise', 'Kasaï Oriental', 'Bena Dibele', 'Bena Mukendi', 'Bena Kalala', 'Bena Nsangu', 'Célibataire', 'Collège Mbuji', 'Kasaï Oriental', 'Mbuji-Mayi', 'Scientifique', 'Av. Université', 'Centre3', '2017', 70.0, 'D67890', 'UNIKAS', '', 'Kalala Mukendi', 'Nzola Ndaye', 'ONG', 'UNICEF', 'B+', '1.75', 'Aucune', 'Aucun', NOW(), CURDATE(), '81dc9bdb52d04dc20036dbd8313ed055', 'david@uaconline.edu.cd'),
('ET004', 'Ilunga', 'Kasongo', 'Claire', 'F', '0955555555', '0833333333', 'claire.ilunga@example.com', 'tuteur.kasongo@example.com', 'Kolwezi', '2002-03-10', 'Manika', 'CNI', '555123789', 'Kolwezi', 'Manika', 'Dilala', 'D56', 'Contrat Manika', 'Congolaise', 'Lualaba', 'Dilolo', 'Kamina', 'Kalemie', 'Mokala', 'Veuve', 'Institut Kolwezi', 'Lualaba', 'Kolwezi', 'Commerciale', 'Av. Dilala', 'Centre4', '2020', 82.1, 'D11223', 'ISC', '', 'Jean Ilunga', 'Sophie Kasongo', 'Particulier', 'Kasavubu', 'AB+', '1.60', 'Pollens', 'Non', NOW(), CURDATE(), '81dc9bdb52d04dc20036dbd8313ed055', 'claire@uaconline.edu.cd'),
('ET005', 'Mbayo', 'Kalonji', 'Patrick', 'M', '0944444444', '0822222222', 'patrick.mbayo@example.com', 'tuteur.kalonji@example.com', 'Goma', '2000-11-25', 'Karisimbi', 'Passeport', 'C4567890', 'Goma', 'Karisimbi', 'Birere', 'E78', 'Contrat Goma', 'Congolaise', 'Nord-Kivu', 'Nyiragongo', 'Sake', 'Mugunga', 'Kiwanja', 'Célibataire', 'Lycée Goma', 'Nord-Kivu', 'Goma', 'Littéraire', 'Av. Goma', 'Centre5', '2018', 60.3, 'D33445', 'ISIG', '', 'Joseph Mbayo', 'Catherine Kalonji', 'Bourse', 'État', 'O-', '1.80', 'Aucune', 'Aucun', NOW(), CURDATE(), '81dc9bdb52d04dc20036dbd8313ed055', 'patrick@uaconline.edu.cd');


-- TABLE: faculte
INSERT INTO faculte (Codfac, Nomfac, presentation, domaine) VALUES
('F001', 'Sciences Informatiques', 'Faculté dédiée à la formation en informatique', 'Informatique'),
('F002', 'Sciences de la Santé', 'Forme les professionnels de la santé', 'Santé'),
('F003', 'Sciences Économiques', 'Faculté des sciences économiques et de gestion', 'Économie'),
('F004', 'Lettres et Sciences Humaines', 'Formation en lettres, philosophie et sociologie', 'Lettres'),
('F005', 'Sciences Agronomiques', 'Formation en agriculture, agronomie et environnement', 'Agronomie');

-- TABLE: departement
INSERT INTO departement (CodDep, NomDep, Codfac, presentation, lmd) VALUES
('D001', 'Informatique', 'F001', 'Département de l’informatique générale', 1),
('D002', 'Génie Logiciel', 'F001', 'Département axé sur le développement logiciel', 1),
('D003', 'Médecine Générale', 'F002', 'Département pour les futurs médecins', 1),
('D004', 'Comptabilité', 'F003', 'Département de comptabilité et audit', 1),
('D005', 'Philosophie', 'F004', 'Études philosophiques et éthiques', 1);

-- TABLE: filiere
INSERT INTO filiere (Codfil, NomFil, CodDep, lmd) VALUES
('FIL01', 'Réseaux Informatiques', 'D001', 1),
('FIL02', 'Développement Web', 'D002', 1),
('FIL03', 'Médecine Interne', 'D003', 1),
('FIL04', 'Audit Financier', 'D004', 1),
('FIL05', 'Philosophie Africaine', 'D005', 1);

-- TABLE: promotion
INSERT INTO promotion (CodPro, Codfil, NomPro, lmd) VALUES
('PRO01', 'FIL01', '1ère Licence', 1),
('PRO02', 'FIL01', '2ème Licence', 1),
('PRO03', 'FIL02', '1ère Licence', 1),
('PRO04', 'FIL03', '1ère Année', 1),
('PRO05', 'FIL04', '3ème Licence', 1);

-- TABLE: inscription
INSERT INTO inscription (idinscription, matriculeinscrit, CodPro, Dateinscription, AnneeAcad, password, Type, affichepass, etat, sessiondeux, decision, grade, decisiondeux, gradedeux, enrolement, recour, groupe, sem1, sem2, annuelle, decis1, decis2, decisanl, pourcs1, pourcs2, blocage, affichepassdeux, valide, suppression)
VALUES
(1, 'ET001', 'PRO01', NOW(), '2024-2025', 'pass123', 1, 'oui', 1, 0, 'AD', 'A', '', '', 1, 0, 1, 14.5, 15.2, 14.85, 'OK', 'AD', 'AD', 55.3, 60.2, 0, 0, 1, 0),
(2, 'ET002', 'PRO01', NOW(), '2024-2025', 'motdepasse', 1, 'oui', 1, 0, 'AD', 'A', '', '', 1, 0, 1, 13.0, 14.0, 13.5, 'OK', 'AD', 'AD', 50.3, 55.2, 0, 0, 1, 0),
(3, 'ET003', 'PRO03', NOW(), '2024-2025', '123abc', 1, 'oui', 1, 0, 'AD', 'A', '', '', 1, 0, 1, 15.0, 16.0, 15.5, 'OK', 'AD', 'AD', 60.0, 62.0, 0, 0, 1, 0),
(4, 'ET004', 'PRO04', NOW(), '2024-2025', 'cle123', 1, 'oui', 1, 0, 'AD', 'A', '', '', 1, 0, 1, 14.8, 15.5, 15.15, 'OK', 'AD', 'AD', 59.0, 64.0, 0, 0, 1, 0),
(5, 'ET005', 'PRO05', NOW(), '2024-2025', 'pw2025', 1, 'oui', 1, 0, 'AD', 'A', '', '', 1, 0, 1, 13.8, 14.4, 14.1, 'OK', 'AD', 'AD', 52.5, 54.5, 0, 0, 1, 0);

-- TABLE: decanatlogin
INSERT INTO decanatlogin (identifiant, username, email, password) VALUES
('D001', 'admin1', 'admin1@uaconline.edu.cd', '81dc9bdb52d04dc20036dbd8313ed055'),
('D002', 'admin2', 'admin2@uaconline.edu.cd', '81dc9bdb52d04dc20036dbd8313ed055'),
('D003', 'admin3', 'admin3@uaconline.edu.cd', '81dc9bdb52d04dc20036dbd8313ed055'),
('D004', 'admin4', 'admin4@uaconline.edu.cd', '81dc9bdb52d04dc20036dbd8313ed055'),
('D005', 'admin5', 'admin5@uaconline.edu.cd', '81dc9bdb52d04dc20036dbd8313ed055');

-- TABLE: enseignant
INSERT INTO enseignant (Matriculenseig, Nom, PostNom, Prenom, Sexe, Tel, email, emailpro, specialisation, photo, CodTypeEns, CodFonc, CodDipl, CodGrad, CodDep, pwd, username)
VALUES
('ENS001', 'Mavula', 'Mbala', 'Eric', 'M', '0899999999', 'eric.mavula@example.com', 'emavula@uaconline.edu.cd', 'Réseaux', '', 'T01', 'F01', 'D01', 'G01', 'D001', '81dc9bdb52d04dc20036dbd8313ed055', 'eric'),
('ENS002', 'Ngalula', 'Tshisekedi', 'Claudine', 'F', '0888888888', 'claudine.ngalula@example.com', 'cngalula@uaconline.edu.cd', 'Bases de données', '', 'T02', 'F02', 'D02', 'G02', 'D001', '81dc9bdb52d04dc20036dbd8313ed055', 'claudine'),
('ENS003', 'Kabeya', 'Kalema', 'Fiston', 'M', '0877777777', 'fiston.kabeya@example.com', 'fkabeya@uaconline.edu.cd', 'Java / Python', '', 'T03', 'F03', 'D03', 'G03', 'D002', '81dc9bdb52d04dc20036dbd8313ed055', 'fiston'),
('ENS004', 'Makiese', 'Makuta', 'Alice', 'F', '0866666666', 'alice.makiese@example.com', 'amakiese@uaconline.edu.cd', 'Médecine', '', 'T01', 'F01', 'D01', 'G01', 'D003', '81dc9bdb52d04dc20036dbd8313ed055', 'alice'),
('ENS005', 'Ilunga', 'Ndala', 'Jean-Paul', 'M', '0855555555', 'jean.ilunga@example.com', 'jilunga@uaconline.edu.cd', 'Philosophie', '', 'T02', 'F02', 'D02', 'G02', 'D005', '81dc9bdb52d04dc20036dbd8313ed055', 'jeanpaul');

UPDATE etudiant SET photo = '1.png' WHERE MatriculeInscrit = 'ET001';
UPDATE etudiant SET photo = '2.png' WHERE MatriculeInscrit = 'ET002';
UPDATE etudiant SET photo = '3.png' WHERE MatriculeInscrit = 'ET003';
UPDATE etudiant SET photo = '4.png' WHERE MatriculeInscrit = 'ET004';
UPDATE etudiant SET photo = '5.png' WHERE MatriculeInscrit = 'ET005';

UPDATE enseignant SET photo = '1.png' WHERE Matriculenseig = 'ENS001';
UPDATE enseignant SET photo = '2.png' WHERE Matriculenseig = 'ENS002';
UPDATE enseignant SET photo = '3.png' WHERE Matriculenseig = 'ENS003';
UPDATE enseignant SET photo = '4.png' WHERE Matriculenseig = 'ENS004';
UPDATE enseignant SET photo = '5.png' WHERE Matriculenseig = 'ENS005';