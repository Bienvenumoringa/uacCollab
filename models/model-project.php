<?php
    class Project {
        private $db;
        private $date;
        private $titre;
        private $description;
        private $etudiant;
        private $encadreur;
        private $backgroud;
        private $running;
        private $status = 1;

        public function __construct($db) {
            $this->db = $db;
            $this->date = date('Y-m-d');
        }


        public function Project($titre = null, $description = null, $etudiant = null, $encadreur = null, $backgroud = null, $running = null) {
            $this->titre = $titre;
            $this->description = $description;
            $this->etudiant = $etudiant;
            $this->encadreur = $encadreur;
            $this->backgroud = $backgroud;
            $this->running = $running;
        }

        public function create() {
            $query = 'INSERT INTO collab_projet VALUES (?, ?, ?, ?, ?, ?, ?)';
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                null,
                $this->date,
                $this->titre,
                $this->description,
                $this->etudiant,
                $this->backgroud,
                $this->running,
            ]);
        }

        public function get_last_project() {
            $query = 'SELECT *
            FROM
                collab_projet
            ORDER BY
                id DESC
            LIMIT
                1';
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = 0;
            while($row = $stmt->fetch()) {
                $result = $row->id;
            }
            return $result;
        }

        public function create_encadreur($projet, $encadreur, $admin = null) {
            $query = 'INSERT INTO collab_projet_encadreur VALUES (?, ?, ?, ?, ?)';
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                null,
                $projet,
                $encadreur,
                $admin,
                $this->status,
            ]);
        }
        // get user of one project
        public function get_users_project($projet) {
            $query = 'SELECT
                projet_encadreur.id AS id,
                projet_encadreur.projet AS projet,
                projet_encadreur.encadreur AS encadreur,
                projet_encadreur.status AS status
            FROM
                projet_encadreur
            WHERE
                projet_encadreur.status = ? AND
                projet_encadreur.projet = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $this->status,
                $projet
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        public function get_exist_encadreur_by_project($projet, $encadreur) {
            $query = 'SELECT COUNT(*) AS nb FROM collab_projet_encadreur WHERE collab_projet = ? AND encadreur = ? AND status = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $projet,
                $encadreur,
                $this->status,
            ]);

            $result = 0;
            while($row = $stmt->fetch()) {
                $result = $row->nb;
            }
            return $result > 0 ? true : false;
        }

        // get student project
        public function get_student_project($id) {
            $query = 'SELECT
                collab_projet.id AS id,
                collab_projet.dates AS date,
                collab_projet.titre AS titre,
                collab_projet.description AS description,
                collab_projet.etudiant AS etudiant,
                collab_projet.backgroud AS backgroud,
                collab_projet.running AS running,
                collab_projet.status AS status
            FROM
                collab_projet
            WHERE
                collab_projet.id = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $id
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        public function get_student_directeur($id) {
            $query = 'SELECT
                collab_projet.id AS collab_projet,
                collab_projet.dates AS date,
                collab_projet.titre AS titre,
                collab_projet.description AS description,
                collab_projet.etudiant AS etudiant,
                collab_projet.encadreur AS encadreur,
                collab_projet.backgroud AS backgroud,
                collab_projet.running AS running,
                collab_projet.status AS status
            FROM
                collab_projet
            WHERE
                collab_projet.encadreur = ?';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $id
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        public function get_all() {
            $query = "SELECT
                DISTINCT
                collab_projet.id AS id,
                collab_projet.dates AS date,
                collab_projet.titre AS titre,
                collab_projet.description AS description,
                collab_projet.inscription AS etudiant,
                collab_projet.backgroud AS backgroud,
                collab_projet.running AS running,
                etudiant.Nom AS nom,
                etudiant.PostNom AS postnom,
                etudiant.Prenom AS prenom,
                etudiant.Sexe AS genre,
                etudiant.photo AS image,
                inscription.idinscription AS id_inscription,
                CONCAT(promotion.NomPro, ' ',  departement.NomDep ) AS promotion,
                collab_projet_encadreur.enseignant AS encadreur_id
            FROM
                collab_projet, etudiant, inscription, promotion, filiere, departement, collab_projet_encadreur
            WHERE
                filiere.CodDep = departement.CodDep AND
                filiere.Codfil = promotion.Codfil AND
                promotion.CodPro = inscription.CodPro AND
                etudiant.MatriculeInscrit = inscription.matriculeinscrit AND
                inscription.idinscription = collab_projet.inscription AND
                collab_projet_encadreur.projet = collab_projet.id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        public function get_by_id($id) {
            $query = "SELECT
                projet.id AS id,
                projet.dates AS date,
                projet.titre AS titre,
                projet.description AS description,
                projet.etudiant AS etudiant,
                projet.encadreur AS encadreur,
                projet.backgroud AS backgroud,
                projet.running AS running,
                projet.status AS status,
                etudiant.nom AS nom,
                etudiant.postnom AS postnom,
                etudiant.prenom AS prenom,
                etudiant.genre AS genre,
                etudiant.image AS image,
                inscription.id AS id_inscription,
                CONCAT(promotion.description, ' ',  departement.description ) AS promotion,
                projet_encadreur.encadreur AS encadreur_id
            FROM
                projet, etudiant, inscription, promotion, departement, projet_encadreur
            WHERE
                etudiant.id = inscription.etudiant AND
                inscription.id = projet.etudiant AND
                promotion.id = inscription.promotion AND
                departement.id = promotion.departement AND
                projet_encadreur.projet = projet.id AND
                projet.status = ? AND
                projet.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $this->status,
                $id
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        public function verify() {
            $query = 'SELECT * FROM collab_projet WHERE inscription = ? ';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $this->etudiant,
            ]);

            $result = [];
            while($row = $stmt->fetch()) {
                $result[] = $row;
            }
            return $result;
        }

        public function restaure($id) {
            $query = 'UPDATE collab_projet SET status = ? WHERE id = ?';
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                $this->status,
                $id
            ]);
        }

    }