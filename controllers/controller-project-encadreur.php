<?php
    // Require the configuration file
    require_once('../config/config.php');
    require_once '../app/module/functions/functions.php';
    require_once('../models/model-api.php');
    require_once('../models/model-project-encadreur.php');
    require_once('../models/model-project.php');

    session_start();

    $database = new Connexion();
    $db = $database->get_connexion();

    $API = new Api($db);
    $Project_encadreur = new Project_encadreur($db);
    $Project = new Project($db);

    $codDep = ! empty($_SESSION['departement']['code']) && isset($_SESSION['departement']['code'])
    ? $_SESSION['departement']['code']
    : '';

    if(isset($_POST['action']) && ! empty($_POST['action'])) {
        $action = htmlspecialchars($_POST['action']);
        switch($action){
            case 'get_enseigant':
                try {
                    $result = $API->get_enseigant($codDep);
                    $msg = false;
                    foreach($result as $data) {
                        $msg = true;
                        ?><option value="<?=$data->id ?>"><?=$data->nom . ' ' . $data->postnom . ' ' . $data->prenom ?></option><?php
                    }
                    if(! $msg) {
                        ?><option value="">Aucun résultat trouvé</option><?php
                    }
                }
                catch (Exception $ex) {
                    // In case of an exception, return a warning message with the exception message.
                    $response['status'] = 'warning';
                    $response['content'] = 'Exception ' . $ex->getMessage();
                }
            break;

            case 'get_project_by_id':
                try {
                    $project_id = htmlspecialchars($_POST['projet']);
                    $result = $Project->get_admin_project_by_id($project_id);
                    $msg = false;
                    foreach($result as $data) {
                        $msg = true;
                        ?>
                            <h3><?= $data->titre ?></h3>
                            <p><?= $data->description ?></p>
                            <h6><?= $data->nom . " " . $data->postnom . " " . $data->prenom ?></h6>
                            <p><?= $data->promotion . " " . $data->CodDep ?></p>
                        <?php
                    }
                    if(! $msg) {
                        ?><p class="text-muted">Aucun résultat trouvé</p><?php
                    }
                }
                catch (Exception $ex) {
                    // In case of an exception, return a warning message with the exception message.
                    $response['status'] = 'warning';
                    $response['content'] = 'Exception ' . $ex->getMessage();
                }
            break;

            case 'get_project_encadreur':
                try {
                    $project_id = htmlspecialchars($_POST['projet']);
                    $result = $Project_encadreur->get_all($project_id);
                    $msg = false;
                    $num = 0;
                    foreach($result as $data) {
                        $msg = true;
                        $num++;
                        $role = $data->admin == 1 ? "Directeur" : "Encadreur";
                        $label = $data->status == 0
                            ? "Confirmez-vous l'affectation de $data->nom  $data->postnom $data->prenom  Comme $role ?"
                            : "Confirmez-vous l'annulation de l'affectation de $data->nom  $data->postnom $data->prenom  Comme $role ?";
                        ?>
                             <tr>
                                <th><?=$num ?></th>
                                <td hidden><?=$data->id ?></td>
                                <td><?= $data->nom . " " . $data->postnom . " " . $data->prenom ?></td>
                                <td><?= $role ?></td>
                                <td><?= $data->status == 1 ? "Accepté" : "Encours" ?></td>
                                <td>
                                    <a href="#" class="update text-primary" data-bs-toggle="modal" data-bs-target="#exampleModalToggle"><i class="bi bi-pencil-square mx-2"></i></a>
                                    <a data-status="<?=$data->status ?>" data-id="<?=$data->id ?>" data-label="<?=$label ?>" data-bs-toggle="modal" data-bs-target="#cofirmModal" href="confirme-<?=$data->id ?>" class="<?=$data->status == 0 ? 'text-success bi bi-check-lg' : 'text-danger bi bi-x-lg' ?> confirm"> <i class=""></i></a>
                                </td>
                            </tr>
                        <?php
                    }
                    if(! $msg) {
                        ?><p class="text-muted">Aucun résultat trouvé</p><?php
                    }
                }
                catch (Exception $ex) {
                    $response['status'] = 'warning';
                    $response['content'] = 'Exception ' . $ex->getMessage();
                }
            break;

            case 'save':
                header('Content-Type: application/json');
                $response = [];
                try {
                    $projet = htmlspecialchars($_POST['projet']);
                    $enseignant = htmlspecialchars($_POST['enseignant']);
                    $role = htmlspecialchars($_POST['role']);
                    $id = htmlspecialchars($_POST['id']);
                    $status = 0;

                    if ($Project_encadreur->count($projet) && $role == 1){
                        $response['status'] = 'info';
                        $response['content'] = 'Directeur de ce projet existe déjà ';
                        exit(print json_encode($response));
                    }

                    $Project_encadreur->Project_encadreur($projet, $enseignant, $role, $status);

                    if (! empty($id)) {
                        // Update existing record
                        if ($Project_encadreur->update($id)){
                            $response['status'] = 'success';
                            $response['content'] = 'Modification réussie';
                        }
                        else{
                            $response['status'] = 'error';
                            $response['content'] = 'Echec de modification';
                        }
                        print json_encode($response);
                        exit();
                    }else{
                        $table_enseignant = $API->get_encadreur_email($enseignant);
                        $table_project = $Project->get_by_id($projet);
                        $last_id = $Project_encadreur->get_last_affectation();
                        $email = "";
                        $full_name = "";
                        $project_name = "";
                        $student_name = "";
                        $promotion = "";
                        $student_email = "";
                        $roles = $role == 1 ? "Directeur" : "Encadreur";

                        foreach ($table_enseignant as $datas){
                            $email = $datas->email;
                            $full_name = $datas->nom . " " . $datas->prenom;
                        }

                        foreach ($table_project as $projects){
                            $project_name = $projects->titre . " " . $projects->description;
                            $student_name = $projects->nom . " " . $projects->prenom;
                            $promotion = $projects->promotion;
                            $student_email = $projects->email;
                        }

                        $subject = "Affectation sur un projet académique";
                        $body = "
                            Bonjour <strong>{$full_name}</strong>,<br><br>
                            Vous êtes affecté dans le projet #{$project_name} comme {$roles}.
                            pour {$student_name}, de {$promotion}<br>
                            Confirmer la collaboration en suivant <a href='http://localhost/uacCollab/confirme-{$last_id}'>clic ici</a> ,<br>
                            UAC Collab
                        ";

                        $body_student = "
                            Bonjour <strong>{$student_name}</strong>,<br><br>
                            Votre projet <strong>{$project_name}</strong> est affecté à <strong>{$full_name}</strong> comme {$roles}.
                            pour votre promotion de {$promotion}<br>
                            UAC Collab
                        ";

                        if ($Project_encadreur->create()){

                            if (! Functions::send_mail($email, $full_name, $subject, $body)){
                                $response['status'] = 'success';
                                $response['content'] = 'Enregistrement réussi, mais le mail n\'est pas envoyé';
                                exit(print json_encode($response));
                            }
                            if (! Functions::send_mail($student_email, $student_name, $subject, $body_student)){
                                $response['status'] = 'success';
                                $response['content'] = 'Enregistrement réussi, mais le mail étudiant n\'est pas envoyé';
                                exit(print json_encode($response));
                            }
                            $response['status'] = 'success';
                            $response['content'] = 'Enregistrement réussi';
                        }
                        else{
                            $response['status'] = 'error';
                            $response['content'] = 'Echec d\'enregistrement';
                        }
                    }


                } catch (Exception $e) {
                    $response['status'] = 'warning';
                    $response['content'] = 'Erreur' .$e->getMessage();
                }
                print json_encode($response);
            break;

            case 'confirme':
                header('Content-Type: application/json');
                $response = [];
                try {
                    $affectation = htmlspecialchars($_POST['id']);
                    $status = htmlspecialchars($_POST['status']);
                    if (! empty($affectation)){
                        if ($Project_encadreur->confirme($affectation, $status)){
                            if(! empty($status)) {
                                $response['status'] = 'success';
                                $response['content'] = 'Merci d\'avoir confimé la collaboration';
                            } else {
                                $response['status'] = 'success';
                                $response['content'] = 'Merci d\'avoir annulé la collaboration';
                            }
                        }else{
                            $response['status'] = 'error';
                            $response['content'] = 'Echec de confirmation';
                        }
                    }else{
                        $response['status'] = 'info';
                        $response['content'] = 'Aucune affectation trouvée';
                    }

                } catch (Exception $e) {
                    $response['status'] = 'warning';
                    $response['content'] = 'Erreur' .$e->getMessage();
                }
                print json_encode($response);
                break;
        }

    }