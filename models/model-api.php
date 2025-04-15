<?php
    class Api {
        private $db;
        private $status = 1;

        public function __construct($db) {
            $this->db = $db;
        }

        // Get annee academique from database
        public  function get_annee() {
            $query = 'SELECT
                annee.id AS id,
                annee.description AS description
            FROM
                annee
            WHERE
                status = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $this->status
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Get promotion from database
        public  function get_promotion() {
            $query = 'SELECT
                promotion.id AS id,
                promotion.description AS description,
                departement.description AS description_departement
            FROM
                promotion, departement
            WHERE
                departement.id = promotion.departement AND
                promotion.status = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $this->status
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Get encadreur from database
        public  function get_encadreur() {
            $query = 'SELECT
                encadreur.id AS id,
                encadreur.nom AS nom,
                encadreur.postnom AS postnom,
                encadreur.prenom AS prenom,
                encadreur.telephone AS telephone,
                encadreur.adresse AS adresse,
                encadreur.email AS email
            FROM
                encadreur
            WHERE
                encadreur.status = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $this->status
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Get encadreur from database by id
        public  function get_encadreur_id($id) {
            $query = 'SELECT
                encadreur.id AS id,
                encadreur.nom AS nom,
                encadreur.postnom AS postnom,
                encadreur.prenom AS prenom,
                encadreur.telephone AS telephone,
                encadreur.adresse AS adresse,
                encadreur.email AS email
            FROM
                encadreur
            WHERE
                encadreur.id = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $id
            ]);

            $result = '';
            while($row = $stmt->fetch()) {
                $result = $row->nom . ' ' . $row->prenom;
            }
            return $result;
        }

        // Get etudiant from database
        public  function get_etudiant($an, $prom) {
            $query = 'SELECT
                etudiant.id AS id,
                etudiant.nom AS nom,
                etudiant.postnom AS postnom,
                etudiant.prenom AS prenom,
                etudiant.telephone AS telephone,
                etudiant.adresse AS adresse,
                etudiant.email AS email
            FROM
                etudiant, inscription
            WHERE
                inscription.etudiant = etudiant.id AND
                etudiant.status = ? AND
                inscription.annee = ? AND
                inscription.promotion = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $this->status,
                $an,
                $prom
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Get etudiant by id
        public  function get_etudiant_id($id) {
            $query = 'SELECT
                etudiant.id AS id,
                etudiant.nom AS nom,
                etudiant.postnom AS postnom,
                etudiant.prenom AS prenom,
                etudiant.telephone AS telephone,
                etudiant.adresse AS adresse,
                etudiant.email AS email
            FROM
                etudiant, inscription
            WHERE
                inscription.etudiant = etudiant.id AND
                inscription.id = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $id,
            ]);

            $result = '';
            while($row = $stmt->fetch()) {
                $result = $row->nom . ' ' . $row->prenom;
            }
            return $result;
        }

        // Get etudiant by id
        public  function get_etudiant_email($id) {
            $query = 'SELECT
                etudiant.id AS id,
                etudiant.nom AS nom,
                etudiant.postnom AS postnom,
                etudiant.prenom AS prenom,
                etudiant.telephone AS telephone,
                etudiant.adresse AS adresse,
                etudiant.email AS email
            FROM
                etudiant, inscription
            WHERE
                inscription.etudiant = etudiant.id AND
                inscription.id = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $id,
            ]);

            $result = '';
            while($row = $stmt->fetch()) {
                $result = $row->email;
            }
            return $result;
        }

        public function log_decanant($email) {
            $query = 'SELECT *
            FROM
                decanatlogin
            WHERE
                (username = ? OR email = ?)';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $email,
                $email,
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Log all enseignant
        public function log_enseignant($email) {
            $query = 'SELECT *
            FROM
                enseignant
            WHERE
                (username = ? OR email = ? OR Matriculenseig = ?)';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $email,
                $email,
                $email
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Log etudiant
        public function log_etudiants($email) {
            $query = 'SELECT
                inscription.idinscription AS id,
                inscription.Dateinscription AS date,
                inscription.matriculeinscrit AS etudiant,
                inscription.CodPro AS promotion,
                YEAR(inscription.Dateinscription) AS annee,
                etudiant.MatriculeInscrit AS matricule,
                etudiant.Nom AS nom,
                etudiant.PostNom AS postnom,
                etudiant.Prenom AS prenom,
                etudiant.Sexe AS genre,
                etudiant.Datenaissance AS date_naissance,
                etudiant.Adresse AS adresse,
                etudiant.photo AS image,
                etudiant.Tel AS telephone,
                etudiant.Email AS email,
                etudiant.password AS mot_de_passe
            FROM
                etudiant, inscription
            WHERE
                etudiant.MatriculeInscrit = inscription.matriculeinscrit AND
                (etudiant.MatriculeInscrit = ? OR etudiant.Email = ?)
            ORDER BY
                inscription.idinscription DESC
            LIMIT
                1';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $email,
                $email
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Get admin for project
        public function get_admin($encadreur, $last_year) {
            $query = 'SELECT
                COUNT(*) AS nb
            FROM
                projet_encadreur, projet, inscription
            WHERE
                inscription.id = projet.etudiant AND
                projet.id = projet_encadreur.projet AND
                projet_encadreur.encadreur = ? AND
                projet_encadreur.admin = ? AND
                inscription.annee = ? AND
                projet_encadreur.status = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $encadreur,
                $this->status,
                $last_year,
                $this->status
            ]);

            $result = 0;
            if($row = $stmt->fetch()) {
                $result = $row->nb;
            }
            return $result > 0 ? true : false;
        }

        public function get_admin_by_project($project) {
            $query = 'SELECT
               *
            FROM
                projet_encadreur
            WHERE
                projet = ? AND
                admin = ?
                ';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $project,
                $this->status
            ]);

            $result = [];
            if($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }



        // Get last annee academique
        public function get_last_year() {
            $query = 'SELECT annee.id as id
            FROM
                annee, affectation
            WHERE
                affectation.annee = annee.id  AND
                affectation.status = ?
            ORDER BY
                affectation.id DESC
            LIMIT
                1';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $this->status
            ]);

            $result = 0;
            while($row = $stmt->fetch()) {
                $result = $row->id;
            }
            return $result;
        }

        // Get the students affected for a project associated with a supervisor and an academic year.
        public  function get_etudiant_by_year($encadreur, $annee) {
            $query = 'SELECT
                inscription.id AS id,
                etudiant.nom AS nom,
                etudiant.postnom AS postnom,
                etudiant.prenom AS prenom,
                etudiant.telephone AS telephone,
                etudiant.adresse AS adresse,
                etudiant.email AS email,
                CONCAT(promotion.description, " ", departement.description ) AS promotion
            FROM
                etudiant, inscription, affectation, promotion, departement
            WHERE
                inscription.etudiant = etudiant.id AND
                affectation.etudiant = inscription.id AND
                promotion.id = inscription.promotion AND
                departement.id = promotion.departement AND
                affectation.status = ? AND
                affectation.encadreur = ? AND
                affectation.annee = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $this->status,
                $encadreur,
                $annee
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

    }