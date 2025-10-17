<?php
    // Require the configuration file
    require_once('../config/config.php');
    require_once '../app/module/functions/functions.php';
    require_once('../models/model-project.php');
    require_once('../models/model-chat.php');
    require_once('../models/model-api.php');

    $database = new Connexion();
    $db = $database->get_connexion();

    session_start();

    $user_timezone = ! empty($_SESSION['user_timezone']) ? $_SESSION['user_timezone'] : 'UTC';
    $codDep = ! empty($_SESSION['departement']['code']) && isset($_SESSION['departement']['code'])
    ? $_SESSION['departement']['code']
    : '';

    $admin_id = ! empty($_SESSION['user']['admin']) && isset($_SESSION['user']['admin'])
    ? $_SESSION['user']['admin'] : 0;

    $user_id = ! empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
    $user_role = ! empty($_SESSION['user']['role']) ? $_SESSION['user']['role'] : '';

    $project = new Project($db);
    $chat = new Message($db);
    $API = new Api($db);

    if(isset($_POST['action']) && ! empty($_POST['action'])) {
        $action = htmlspecialchars($_POST['action']);

        switch($action){
            case 'save':
                header('Content-Type: application/json');
                $response = [];
                try{
                    // Get data from form
                    $title = htmlspecialchars($_POST['title']);
                    $description = htmlspecialchars($_POST['description']);
                    $etudiant = htmlspecialchars($_POST['etudiant']);
                    $id = htmlspecialchars($_POST['id']);

                    $backround = Functions::generate_color();
                    $running = 0;

                    $etudiant_noms = $API->get_etudiant_id($etudiant) ?? null;
                    $etudiant_email = $API->get_etudiant_email($etudiant) ?? null;
                    $objet = "Confirmation de création de projet";
                    $content = "
                        Bonjour <b>$etudiant_noms</b>,
                        Nous avons le plaisir de vous informer que votre projet a été créé avec succès.
                        <ul>
                            <li><b>Titre</b>: $title</li>
                            <li><b>Description</b>: $description</li>
                        </ul>
                        Pour plus d’informations, cliquez sur le lien ci-dessous : <a   href='http://localhost/uacCollab/projects'>En savoir plus</a> pour en savoir plus.
                        <br>
                        <br>
                        <a style='
                            text-align: center;
                            color: white;
                            border: 0;
                            outline: 0;
                            border-radius: 5px;
                            background: #007bff;
                            padding: 5px 30px;
                            text-decoration: none;
                            padding: 10px 30px;
                            margin: 10px 0;
                        ' href='http://localhost/uacCollab/projects'>Ouvrir avec UAC collab<a>
                        <br>
                        <br>
                        Cordialement,
                        <a href='mailto:uaccolla@gmail.com'>uaccolla</a>
                    ";

                    if(! empty($title) && ! empty($description) && ! empty($etudiant)) {
                        $project->Project($title, $description, $etudiant, $user_id, $backround, $running);

                        if(! empty($id)) {
                            if(! empty($project->verify_update($id))) {
                                $response['status'] = 'info';
                                $response['content'] = 'Cet projet existe déjà dans la base de données';
                            } else {
                                if($project->update($id)){
                                    $response['status'] = 'success';
                                    $response['content'] = 'Le projet a été modifié avec succès';
                                } else {
                                    $response['status'] = 'error';
                                    $response['content'] = 'Erreur lors de la modification de projet';
                                }
                            }
                        } else {
                            if(! empty($project->verify())) {
                                $response['status'] = 'info';
                                $response['content'] = 'Cet projet a déjà été creé';
                            } else {
                                // insert the project
                                if($project->create()) {
                                    $_SESSION['user']['sub_role'] = 'Directeur';
                                    $response['status'] = 'success';
                                    $response['content'] = 'Le projet crée avec succès';
                                    Functions::send_mail($etudiant_email, $etudiant_noms, $objet, $content);
                                } else {
                                    $response['status'] = 'error';
                                    $response['content'] = 'Erreur lors de l\'enregistrement de projet';
                                }
                            }
                        }
                    } else {
                        // display an error message if the fields are empty
                        $response['status'] = 'info';
                        $response['content'] = 'Veuillez compléter les champs marqués par <b class="star">*</b>';
                    }
                } catch(Exception $ex) {
                    $response['status'] = 'warning';
                    $response['content'] = 'Exception ' . $ex->getMessage();
                }
                print json_encode($response);
            break;
            case 'get_admin_project':
                try {
                    $CodPro = htmlspecialchars($_POST['CodPro']);
                    $AnneeAcad = htmlspecialchars($_POST['AnneeAcad']);
                    $result = $project->get_admin_project($CodPro, $AnneeAcad);
                    if(! empty($result)) {
                        $num = 0;
                        foreach($result as $data) {
                            $num ++;
                            ?>
                                <tr>
                                    <th><?=$num ?></th>
                                    <th><?=Functions::date_format($data->dates) ?></th>
                                    <td hidden><?=$data->id ?></td>
                                    <td><?=$data->titre ?></td>
                                    <td><?=$data->description ?></td>
                                    <td hidden><?=$data->inscription ?></td>
                                    <td><?=$data->nom . ' ' . $data->postnom . ' ' . $data->prenom ?></td>
                                    <td><?=$data->promotion . ' ' . $data->CodDep ?></td>
                                    <td><?=$data->AnneeAcad ?></td>
                                    <td>
                                        <a onclick="redirect('affectation-<?=$data->id ?>')" class="text-muted"><i class="bi bi-joystick mx-2"></i></a>
                                        <a href="#" class="update text-primary" data-bs-toggle="modal" data-bs-target="#exampleModalToggle"><i class="bi bi-pencil-square mx-2"></i></a>
                                    </td>
                                </tr>
                            <?php
                        }
                    } else {
                        ?>
                            <tr>
                                <td colspan="100" class="text-center">Aucun résultat trouvé</td>
                            </tr>
                        <?php
                    }
                } catch (Exception $ex) {
                    ?>
                        <tr>
                            <td colspan="100" class="text-center"><?=$ex->getMessage() ?></td>
                        </tr>
                    <?php
                }
            break;

            case 'get_admin_project_recent':
                try {

                    $result = $project->get_admin_project_recent($codDep);
                    if(! empty($result)) {
                        $num = 0;
                        foreach($result as $data) {
                            $num ++;
                            ?>
                                <tr>
                                    <th><?=$num ?></th>
                                    <td><?=Functions::date_format($data->dates) ?></td>
                                    <td><?=$data->titre ?></td>
                                    <td><?=$data->description ?></td>
                                    <td><?=$data->nom . ' ' . $data->postnom . ' ' . $data->prenom ?></td>
                                    <td><?=$data->promotion . ' ' . $data->CodDep ?></td>
                                    <td><?=$data->AnneeAcad ?></td>
                                </tr>
                            <?php
                        }
                    } else {
                        ?>
                            <tr>
                                <td colspan="100" class="text-center">Aucun résultat trouvé</td>
                            </tr>
                        <?php
                    }
                } catch (Exception $ex) {
                    ?>
                        <tr>
                            <td colspan="100" class="text-center"><?=$ex->getMessage() ?></td>
                        </tr>
                    <?php
                }
            break;
            case 'load':
                $encadreur_id = ! empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;

                $result = [];
                if(! empty($user_role) && ! empty($user_id)) {
                    if($user_role == 'etudiant') {
                        $result = $project->get_student_project_recent($user_id);
                    } elseif($user_role == 'encadreur') {
                        $result = $project->get_enseigant_project_recent($user_id);
                    }
                }

                if(! empty($result)) {
                    foreach($result as $data) {
                        ?>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 p-2">
                                <div class="card p-0 ">
                                    <div class="card-hearder p-3 bg-img text-start " style="background: <?=$data->backgroud ?>;">
                                        <div>
                                            <div class="one-truncate"><h4 class="text-white"><?=$data->titre ?> </h4></div>
                                            <b class="text-white one-truncate"><?=$data->nom . ' ' . $data->postnom . ' ' . $data->prenom ?> </b>
                                            <small><b class="one-truncate prom"><?=$data->promotion  . ' ' . $data->departement ?></b></small>
                                        </div>
                                    </div>
                                    <div class="card-icon d-flex justify-content-end px-3">
                                        <img src="assets/etudiants/<?=$data->image ?>" class="img">
                                    </div>
                                    <div class="ml-auto card-body px-3 pt-0 pb-3" style="min-height: 11vh;">
                                        <span class="text-muted custom-truncate"><?=$data->description ?></span>
                                    </div>
                                    <div class="card-footer p-3">
                                        <a onclick="redirect('./openProjects-<?=$data->id ?>')" class="mx-2 text-primary">Ouvrir</a>
                                        <a onclick="redirect('./chat-<?=$data->id ?>')" class="mx-2 text-primary">Conversations</a>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                } else {
                    ?>
                        <div class="container mt-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 30vh;">
                            <img src="assets/themes/data.png"
                                alt="Aucune donnée trouvée"
                                class="img-fluid mb-4"
                                style="max-width: 200px;">
                                <h4 class="text-muted fw-bold">Aucun projet trouvé.</h4>
                                <p class="text-secondary text-center">Nous n'avons trouvé aucune information correspondant à la liste de vos projets.</p>
                                <p class="text-secondary text-center"><b class="text-primary">Veuillez contacter votre departement.</b></p>
                        </div>
                    <?php
                }
            break;
            case 'get_conversation':

                $result = [];
                if(! empty($user_role) && ! empty($user_id)) {
                    if($user_role == 'etudiant') {
                        $result = $project->get_student_project_recent($user_id);
                    } elseif($user_role == 'encadreur') {
                        $result = $project->get_enseigant_project_recent($user_id);
                    }
                }

                if(! empty($result)) {
                   foreach($result as $data) {
                    ?>
                        <a onclick="redirect('./chat-<?=$data->id ?>')" class="list-group-item list-group-item-action">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="assets/etudiants/<?=$data->image ?>" alt="user-image"
                                        class="user-avtar">
                                </div>
                                <?php
                                    if(! empty($chat->get_last_conversation($data->id, 0))) {
                                        foreach($chat->get_last_conversation($data->id, 0) as $row) {
                                            $auteur = '';
                                            if($user_role == $row->role && $user_id == $row->auteur) {
                                                $auteur = 'Vous';
                                            } elseif($row->role == 'encadreur') {
                                                $auteur = $API->get_encadreur_id($row->auteur);
                                            } elseif($row->role == 'etudiant') {
                                                $auteur = $API->get_etudiant_id($row->auteur);
                                            }
                                            ?>
                                                <div class="flex-grow-1 ms-1">
                                                    <span class="float-end text-muted"><?=Functions::local_time($row->date, $user_timezone) ?></span>
                                                    <p class="text-body mb-1"><b><?=$data->titre ?></b></p>
                                                    <?php
                                                        $count_message = $chat->count($data->id, $user_id, $user_role);
                                                        if(! empty($count_message)) {
                                                            ?><span class="float-end circle "><?=$count_message ?></span><?php
                                                        }
                                                    ?>
                                                    <span class="text-muted"><b><?=$auteur ?>:</b> <?=! empty($row->fichier) ? '<i class="bi bi-paperclip"></i> fichier' : $row->contenu ?></span>
                                                </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                            <div class="flex-grow-1 ms-1">
                                                <span class="float-end text-muted"></span>
                                                <p class="text-body mb-1"><b><?=$data->titre ?></b></p>
                                                <span class="text-muted"><b>Aucun message </b></span>
                                            </div>
                                        <?php
                                    }
                                ?>

                            </div>
                        </a>
                    <?php
                   }
                } else {
                    ?>
                        <div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 30vh;">
                            <img src="assets/themes/chat.png"
                                alt="Aucune donnée trouvée"
                                class="img-fluid"
                                style="max-width: 70px;">
                                <h4 class="text-muted fw-bold">Aucune conversation trouvée.</h4>
                                <p class="text-secondary text-center">Nous n'avons trouvé aucune de vos conversations. </p>
                        </div>
                    <?php
                }
            break;

            case 'get_conversation_group':
                $encadreur_id = ! empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
                $role = ! empty($_SESSION['user']['role']) ? $_SESSION['user']['role'] : '';

                ?>
                    <a onclick="redirect('./chat-0')" class="list-group-item list-group-item-action ">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <img src="./assets/images/groupe.png" alt="user-image"
                                    class="user-avtar">
                            </div>
                            <?php
                                if(! empty($chat->get_last_conversation(0, $admin_id))) {
                                    foreach($chat->get_last_conversation(0, $admin_id) as $row) {
                                        $auteur = '';
                                        if($role == $row->role && $encadreur_id == $row->auteur) {
                                            $auteur = 'Vous';
                                        } elseif($row->role == 'encadreur') {
                                            $auteur = $API->get_encadreur_id($row->auteur);
                                        } elseif($row->role == 'etudiant') {
                                            $auteur = $API->get_etudiant_id($row->auteur);
                                        }
                                        ?>
                                            <div class="flex-grow-1 ms-1">
                                                <span class="float-end text-muted"><?=Functions::local_time($row->date, $user_timezone) ?></span>
                                                <p class="text-body mb-1"><b>Groupe</b></p>
                                                <?php
                                                    $count_message = $chat->count(0, $encadreur_id, $role);
                                                    if(! empty($count_message)) {
                                                        ?><span class="float-end circle "><?=$count_message ?></span><?php
                                                    }
                                                ?>
                                                <span class="text-muted"><b><?=$auteur ?>:</b> <?=! empty($row->fichier) ? '<i class="bi bi-paperclip"></i> fichier' : $row->contenu ?></span>
                                            </div>
                                        <?php
                                    }
                                } else {
                                    ?>
                                        <div class="flex-grow-1 ms-1">
                                            <span class="float-end text-muted text-sm"></span>
                                            <p class="text-body mb-1"><b>Groupe</b></p>
                                            <span class="text-muted text-sm">Aucun message</span>
                                        </div>
                                    <?php
                                }
                            ?>
                        </div>
                    </a>
                <?php
            break;
            case 'get_count_convesation':
                $auteur = ! empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
                $role = ! empty($_SESSION['user']['role']) ? $_SESSION['user']['role'] : '';
                $result = $chat->count_conversation($auteur, $role);

                $is_not = 0;
                foreach($result as $data) {
                    $is_not += 1;
                }
                if(! empty($is_not) && $is_not > 0){
                    ?><small class="notification"><b><?=$is_not ?></b></small><?php
                }
            break;
            case 'get_project_attente':
                try{
                    $yar = $API->get_last_year();
                    print $project->get_project_attente($codDep, $yar);
                } catch(Exception $ex) {
                    print $ex->getMessage();
                }
            break;
            case 'get_project_encours':
                try{
                    $yar = $API->get_last_year();
                    print $project->get_project_encours($codDep, $yar);
                } catch(Exception $ex) {
                    print $ex->getMessage();
                }
            break;
            case 'get_project_finish':
                try{
                    $yar = $API->get_last_year();
                    print $project->get_project_finish($codDep, $yar);
                } catch(Exception $ex) {
                    print $ex->getMessage();
                }
            break;
        }

    }
