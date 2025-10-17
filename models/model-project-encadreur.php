<?php
class Project_encadreur {
    private $db;
    private $projet;
    private $enseignant;
    private $role;
    private $status;
    public function __construct($db) {
        $this->db = $db;
    }
    // Hydrate les propriétés
    public function Project_encadreur($projet = null, $enseignant = null, $role = null, $status = null) {
        $this->projet = $projet;
        $this->enseignant = $enseignant;
        $this->role = $role;
        $this->status = $status;
    }
    public function create() {
        $query = 'INSERT INTO collab_projet_encadreur (projet, enseignant, admin, status) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->projet,
            $this->enseignant,
            $this->role,
            $this->status
        ]);
    }
    public function update($id){
        $query = 'UPDATE collab_projet_encadreur SET projet = ?, enseignant = ?, admin = ?, status = ? WHERE id = ?';
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $this->projet,
            $this->enseignant,
            $this->role,
            $this->status,
            $id
        ]);
    }

    public function get_all($project) {
        $query = "SELECT
            enseignant.Nom as nom,
            enseignant.PostNom as postnom,
            enseignant.Prenom as prenom,
            collab_projet_encadreur.id,
            collab_projet_encadreur.admin,
            collab_projet_encadreur.status,
            collab_projet_encadreur.projet
        FROM
            enseignant, collab_projet_encadreur
        WHERE
            enseignant.Matriculenseig = collab_projet_encadreur.enseignant
        AND collab_projet_encadreur.projet = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $project
        ]);
        $result = [];
        while($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
    public function count($project) {
        $query = "SELECT
             COUNT(*) AS nb
        FROM
            collab_projet_encadreur
        WHERE
            projet = ?
        AND admin = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $project, 1
        ]);
        $result = 0;
        if($res = $stmt->fetch()) {
            $result = $res->nb;
        }
        return $result > 0 ? true : false;
    }
    public function get_project_by_directeur($project){
        $query = "SELECT projet_encadreur.id, projet_encadreur.projet, projet_encadreur.encadreur FROM projet_encadreur WHERE projet_encadreur.projet = ? AND projet_encadreur.status = ? ORDER BY projet_encadreur.id ASC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$project, $this->status]);
        $result = [];
        while($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
    public function restaure($id) {
        $query = 'UPDATE fichiers_projet SET status = ? WHERE id = ?';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->status, $id]);
    }

    public function get_send_email_encadreur($projet){
        $query = "SELECT encadreur.nom, encadreur.postnom, encadreur.prenom, encadreur.email, projet.titre FROM encadreur, projet_encadreur, projet WHERE encadreur.id=projet_encadreur.encadreur AND projet.id=projet_encadreur.projet AND projet_encadreur.projet = ?  AND projet_encadreur.status = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $projet,
            $this->status
        ]);

        $result = [];
        while($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
    public function get_last_affectation(){
        $query = "SELECT * FROM collab_projet_encadreur ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = 0;
        while($row = $stmt->fetch()) {
            $result = $row->id;
        }
        return $result;
    }
    public function get_send_email_encadreur_by_id($encadreur){
        $query = "SELECT encadreur.nom, encadreur.postnom, encadreur.prenom, encadreur.email FROM encadreur WHERE encadreur.id = ? AND encadreur.status = ?";
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

    public function confirme($affectation, $status){
        if(! empty($status)) {
            $query = 'UPDATE collab_projet_encadreur SET status = ? WHERE id = ?';
            $stmt = $this->db->prepare($query);
            return $stmt->execute([0, $affectation]);
        } else {
            $query = 'UPDATE collab_projet_encadreur SET status = ? WHERE id = ?';
            $stmt = $this->db->prepare($query);
            return $stmt->execute([1, $affectation]);
        }


    }
}
