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
                DISTINCT
                inscription.AnneeAcad AS AnneeAcad
            FROM
                inscription
            ';
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Get promotion from database
        public  function get_promotion($CodDep) {
            $query = 'SELECT
                promotion.CodPro AS id,
                promotion.Codfil AS Codfil,
                promotion.NomPro AS nom,
                departement.NomDep AS NomDep
            FROM
                promotion, departement, filiere
            WHERE
                departement.CodDep = filiere.CodDep AND  filiere.Codfil = promotion.Codfil AND departement.CodDep = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([$CodDep]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Get enseigant from database
        public  function get_enseigant($CodDep) {
            $query = 'SELECT
                enseignant.Matriculenseig AS id,
                enseignant.Nom AS nom,
                enseignant.PostNom AS postnom,
                enseignant.Prenom AS prenom,
                enseignant.Tel AS telephone,
                enseignant.email AS email
            FROM
                enseignant
            WHERE
                enseignant.CodDep = ?
            ';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $CodDep
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
                enseignant.Matriculenseig AS id,
                enseignant.Nom AS nom,
                enseignant.PostNom AS postnom,
                enseignant.Prenom AS prenom,
                enseignant.Tel AS telephone,
                enseignant.email AS email
            FROM
                enseignant
            WHERE
                enseignant.Matriculenseig = ?';
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
                inscription.idinscription AS id,
                etudiant.MatriculeInscrit as MatriculeInscrit,
                etudiant.Nom AS nom,
                etudiant.PostNom AS postnom,
                etudiant.Prenom  AS prenom,
                promotion.NomPro AS promotion,
                departement.NomDep AS departement,
                departement.CodDep AS CodDep,
                inscription.AnneeAcad AS AnneeAcad
            FROM
                inscription,
                etudiant,
                promotion,
                filiere,
                departement
            WHERE

                etudiant.MatriculeInscrit = inscription.matriculeinscrit AND
                promotion.Codfil = filiere.Codfil AND
                promotion.CodPro = inscription.CodPro AND
                departement.CodDep = filiere.CodDep AND
                promotion.CodPro = ? AND
                inscription.AnneeAcad = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $prom,
                $an,
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
                etudiant.MatriculeInscrit AS id,
                etudiant.Nom AS nom,
                etudiant.PostNom AS postnom,
                etudiant.Prenom AS prenom,
                etudiant.Tel AS telephone,
                etudiant.Email AS email
            FROM
                etudiant, inscription
            WHERE
                inscription.MatriculeInscrit = etudiant.matriculeinscrit AND
                inscription.idinscription = ?';
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
                etudiant.MatriculeInscrit AS id,
                etudiant.Nom AS nom,
                etudiant.PostNom AS postnom,
                etudiant.Prenom AS prenom,
                etudiant.Tel AS telephone,
                etudiant.Email AS email
            FROM
                etudiant, inscription
            WHERE
                inscription.matriculeinscrit = etudiant.MatriculeInscrit AND
                inscription.idinscription = ?';
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
                inscription.AnneeAcad AS annee,
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
                collab_projet_encadreur, collab_projet, inscription
            WHERE
                inscription.idinscription = collab_projet.inscription AND
                collab_projet.id = collab_projet_encadreur.projet AND
                collab_projet_encadreur.enseignant = ? AND
                collab_projet_encadreur.admin = ? AND
                inscription.AnneeAcad = ?
            ';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $encadreur,
                1,
                $last_year,
            ]);

            $result = 0;
            if($row = $stmt->fetch()) {
                $result = $row->nb;
            }
            return $result;
        }

        public function get_admin_by_project($project) {
            $query = 'SELECT
               *
            FROM
                collab_projet_encadreur
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

        public function get_student_admin($id) {
            $query = 'SELECT
                collab_projet_encadreur.enseignant AS admin
            FROM
                collab_projet_encadreur, collab_projet
            WHERE
                collab_projet_encadreur.projet = collab_projet.id AND
                collab_projet.inscription = ? AND
                collab_projet_encadreur.admin = ?
            ';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $id,
                1
            ]);

            $result = '';
            while($row = $stmt->fetch()) {
                $result = $row->admin;
            }
            return $result;
        }

        // Recuper tous les etudiant du meme directeur lors de message de groupe
        public function get_student_admin_group($admin, $year) {
            $query = 'SELECT
                inscription.idinscription
            FROM
                collab_projet, collab_projet_encadreur, inscription
            WHERE
                collab_projet.id = collab_projet_encadreur.projet AND
                collab_projet_encadreur.enseignant = ? AND
                inscription.idinscription = collab_projet.inscription AND
                inscription.AnneeAcad = ?';

                $stmt = $this->db->prepare($query);
                $stmt->execute([
                   $admin,
                   $year
                ]);

                $result = [];
                while($row = $stmt->fetch()) {
                    $result[] = $row;
                }
                return $result;
        }

        // Recuper le dicteur pour le message du group
        public function get_admin_group($admin, $year, $inscription) {
            $query = 'SELECT
                collab_projet_encadreur.*
            FROM
                collab_projet_encadreur, collab_projet, inscription
            WHERE
                collab_projet.id = collab_projet_encadreur.projet AND
                inscription.idinscription = collab_projet.inscription AND
                collab_projet_encadreur.enseignant = ? AND
                inscription.AnneeAcad = ? AND
                collab_projet.inscription = ?';

                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $admin,
                    $year,
                    $inscription
                ]);

                $result = [];
                while($row = $stmt->fetch()) {
                    $result[] = $row;
                }
                return $result;
        }

        // Get etudiant lors qu'il y a la conversation sur un  projet
        public function get_student_project($id) {
            $query = 'SELECT
                    collab_projet.*
                FROM
                    collab_projet
                WHERE
                    id = ?';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $id,
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        // Get enseigant lors qu'il y a la conversation sur un  projet
        public function get_enseignant_project($id) {
            $query = 'SELECT
                collab_projet_encadreur.*
            FROM
                collab_projet_encadreur
            WHERE
                projet = ?';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $id,
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        public function get_enseignant_project2($id, $enseignant) {
            $query = 'SELECT
                collab_projet_encadreur.*
            FROM
                collab_projet_encadreur
            WHERE
                projet = ? AND
                enseignant != ?';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $id,
                $enseignant
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }


        // Get last annee academique
        public function get_last_year() {
            $query = 'SELECT *
            FROM
                inscription
            ORDER BY
                idinscription DESC
            LIMIT
                1';
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = 0;
            while($row = $stmt->fetch()) {
                $result = $row->AnneeAcad;
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