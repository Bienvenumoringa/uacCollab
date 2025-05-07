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

CREATE TABLE `promotion` (
  `CodPro` varchar(15) NOT NULL DEFAULT '',
  `Codfil` varchar(15) DEFAULT NULL,
  `NomPro` varchar(255) DEFAULT NULL,
  `lmd` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `filiere` (
  `Codfil` varchar(15) NOT NULL DEFAULT '',
  `NomFil` varchar(250) DEFAULT NULL,
  `CodDep` varchar(15) DEFAULT NULL,
  `lmd` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

CREATE TABLE `departement` (
  `CodDep` varchar(15) NOT NULL DEFAULT '',
  `NomDep` varchar(255) DEFAULT NULL,
  `Codfac` varchar(15) DEFAULT NULL,
  `presentation` text NOT NULL,
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