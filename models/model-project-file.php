<?php
class Project_file {
    private $db;
    private $date;
    private $projet;
    private $fichier;
    private $user;
    private $commentaire;
    private $type;
    private $version;
    private $status = 1;
    public function __construct($db) {
        $this->db = $db;
        date_default_timezone_set('UTC');
        $this->date = date('Y-m-d H:i:s');
    }
    // Hydrate les propriétés
    public function Project_files($projet = null, $fichier = null, $user = null, $commentaire = null, $type = null, $version = null) {
        $this->projet = $projet;
        $this->fichier = $fichier;
        $this->user = $user;
        $this->commentaire = $commentaire;
        $this->type = $type;
        $this->version = $version;
    }
    public function create() {
        $query = 'INSERT INTO collab_fichiers_projet (dates, projet, fichier, user, commentaire, type, version, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->date,
            $this->projet,
            $this->fichier,
            $this->user,
            $this->commentaire,
            $this->type,
            $this->version,
            $this->status
        ]);
    }
    public function update_file($fichier, $id){
        $query = 'UPDATE collab_fichiers_projet SET fichier = ? WHERE id = ?';
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $fichier, $id
        ]);
    }
    public function get_all($project) {
        $query = "SELECT
                collab_fichiers_projet.id,
                collab_fichiers_projet.dates as date,
                collab_fichiers_projet.projet,
                collab_fichiers_projet.fichier,
                collab_fichiers_projet.user,
                collab_fichiers_projet.commentaire,
                collab_fichiers_projet.type,
                collab_fichiers_projet.version
            FROM
                collab_fichiers_projet
            WHERE
                collab_fichiers_projet.projet = ?
            AND
                collab_fichiers_projet.status = ?
            ORDER BY
                collab_fichiers_projet.id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $project,
            $this->status
        ]);
        return $stmt->fetch();
    }
    public function get_version_by_project($project) {
        $query = "SELECT COUNT(version) as version FROM collab_fichiers_projet WHERE projet = ? AND status = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$project, $this->status]);
        $result = 0;
            while($row = $stmt->fetch()) {
                $result = $row->version;
            }
            return $result + 1;
    }
    public function get_title($project, $version): array {
        $query = "
            SELECT *
            FROM collab_fichiers_projet
            WHERE projet = ? AND version = ? AND status = ?;
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$project, $version, $this->status]);
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
    public function get_data_version($project, $version): array{
        $query = "SELECT * FROM collab_fichiers_projet WHERE projet = ? AND version = ? AND status = ? ORDER BY version DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$project, $version, $this->status]);
        $result = [];
        while($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
    public function get_version($project){
        $query = "SELECT * FROM collab_fichiers_projet WHERE projet = ? AND status = ? ORDER BY version DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$project, $this->status]);
        $result = [];
        while($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
    public function get_project_by_id($project) {
        $query = "SELECT
            collab_projet.id AS id,
            collab_projet.dates AS date,
            collab_projet.titre AS titre,
            collab_projet.description AS description,
            collab_projet.inscription AS etudiant,
            collab_projet_encadreur.enseignant AS encadreur,
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
            collab_projet, etudiant, inscription, promotion, departement, filiere, collab_projet_encadreur
        WHERE
            etudiant.MatriculeInscrit = inscription.matriculeinscrit
        AND
            inscription.idinscription = collab_projet.inscription
        AND
            promotion.CodPro = inscription.CodPro
        AND
            departement.CodDep = filiere.CodDep
        AND
            filiere.Codfil = promotion.Codfil
        AND
            collab_projet_encadreur.projet = collab_projet.id
        AND
            collab_projet.id = ?
        GROUP BY
            collab_projet.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([ $project
        ]);
        $result = [];
        while($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
    public function count() {
        $query = "SELECT
             COUNT(*) AS nb
        FROM
            collab_fichiers_projet
        WHERE
            status = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $this->status
        ]);
        $result = 1;
        if($res = $stmt->fetch()) {
            $result = $res->nb + 1;
        }
        return $result;
    }
    public function get_project_by_directeur($project){
        $query = " SELECT
            collab_projet_encadreur.id,
            collab_projet_encadreur.projet,
            collab_projet_encadreur.enseignant as encadreur,
            collab_projet_encadreur.admin
        FROM
            collab_projet_encadreur
        WHERE
            collab_projet_encadreur.projet = ?
        AND
            collab_projet_encadreur.status = ?
        ORDER BY
            collab_projet_encadreur.id ASC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$project, $this->status]);
        $result = [];
        while($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
    public function restaure($id) {
        $query = 'UPDATE collab_fichiers_projet SET status = ? WHERE id = ?';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->status, $id]);
    }

    public function get_send_email_project($projet) {
        $query = "
            SELECT
                enseignant.Matriculenseig AS matricule,
                enseignant.Nom AS nom,
                enseignant.PostNom AS postnom,
                enseignant.Prenom AS prenom,
                enseignant.Email AS email,
                collab_projet.titre AS titre
            FROM
                collab_projet
            JOIN
                collab_projet_encadreur ON collab_projet_encadreur.projet = collab_projet.id
            JOIN
                enseignant ON enseignant.Matriculenseig = collab_projet_encadreur.enseignant
            WHERE
                collab_projet.id = ?
                AND collab_projet_encadreur.status = ?

            UNION

            SELECT
                etudiant.MatriculeInscrit AS matricule,
                etudiant.Nom AS nom,
                etudiant.PostNom AS postnom,
                etudiant.Prenom AS prenom,
                etudiant.Email AS email,
                collab_projet.titre AS titre
            FROM
                collab_projet
            JOIN
                inscription ON inscription.idinscription = collab_projet.inscription
            JOIN
                etudiant ON etudiant.MatriculeInscrit = inscription.matriculeinscrit
            WHERE
                collab_projet.id = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $projet,
            $this->status,
            $projet
        ]);

        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function get_send_email_encadreur_by_id($encadreur){
        $query = "SELECT
            enseignant.Nom as nom,
            enseignant.PostNom as postnom,
            enseignant.Prenom as prenom,
            enseignant.Email as email
        FROM
            enseignant
        WHERE
            enseignant.Matriculenseig = ?
        AND
            enseignant.status = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $encadreur,
            $this->status
        ]);

        $result = [];
        while($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
}
