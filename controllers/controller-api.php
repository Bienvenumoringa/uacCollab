<?php
    // Require the configuration file
    require_once('../config/config.php');
    require_once '../app/module/functions/functions.php';
    require_once('../models/model-api.php');

    session_start();

    $database = new Connexion();
    $db = $database->get_connexion();

    $API = new Api($db);
    $codDep = ! empty($_SESSION['departement']['code']) && isset($_SESSION['departement']['code'])
    ? $_SESSION['departement']['code']
    : '';

    if(isset($_POST['action']) && ! empty($_POST['action'])) {
        $action = htmlspecialchars($_POST['action']);
        switch($action){
            case 'get_annee':
                try {
                    $result = $API->get_annee();
                    $msg = false;
                    foreach($result as $data) {
                        $msg = true;
                        ?><option value="<?=$data->AnneeAcad ?>"><?=$data->AnneeAcad ?></option><?php
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
            case 'get_promotion':
                try {
                    $result = $API->get_promotion($codDep);
                    $msg = false;
                    foreach($result as $data) {
                        $msg = true;
                        ?><option value="<?=$data->id ?>"><?=$data->nom . ' ' . $data->NomDep ?></option><?php
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
            case 'get_etudiant':
                try {
                    $annee = htmlspecialchars($_POST['annee']);
                    $promotion = htmlspecialchars($_POST['promotion']);
                    $result = $API->get_etudiant($annee, $promotion);
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
            // Log all users
            case 'login':
                header('Content-Type: application/json');
                $response = [];
                try {
                    $email = htmlspecialchars($_POST['email']);
                    $password = htmlspecialchars($_POST['password']);
                    $remember = htmlspecialchars($_POST['remember']);

                    // Check if it is an email address, a phone number, or a matriculation number.
                    $label = '';
                    if(is_numeric($email)) {
                        $label = 'Le numéro de téléphone ';
                    } elseif(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $label = 'L\'adresse email ';
                    } else {
                        $label = 'Le matricule ou nom d\'utilisateur ';
                    }
                    // Log decanat
                    $result1 = $API->log_decanant($email);
                    // Log enseignant
                    $result2 = $API->log_enseignant($email);
                    // Log etudiant
                    $result3 = $API->log_etudiants($email);
                    if(! empty($result1)) {
                        foreach($result1 as $data) {
                            if($data->password == md5($password)) {
                                $_SESSION['departement']['code'] = $data->identifiant;
                                $_SESSION['departement']['username'] = $data->username;
                                $_SESSION['departement']['email'] = $data->email;

                                $response['status'] = 'success';
                                $response['content'] = 'Connexion reussie';
                            } else {
                                $response['status'] = 'error';
                                $response['content'] = 'Le mot de passe que vous avez tapé est incorrect, veuillez réessayer.';
                            }
                        }
                    } elseif(! empty($result2)) {
                        foreach($result2 as $data) {
                            if($data->pwd == md5($password)) {
                                $_SESSION['user']['role'] = 'encadreur';
                                $_SESSION['user']['sub_role'] = 'encadreur';
                                $_SESSION['user']['id'] = $data->Matriculenseig;
                                $_SESSION['user']['name'] = $data->Nom . ' ' . $data->PostNom . ' ' . $data->Prenom;
                                $_SESSION['user']['path'] = $data->photo;

                                $response['status'] = 'success';
                                $response['content'] = 'Connexion reussie';
                            } else {
                                $response['status'] = 'error';
                                $response['content'] = 'Le mot de passe que vous avez tapé est incorrect, veuillez réessayer.';
                            }
                        }
                    } elseif(! empty($result3)) {
                        foreach($result3 as $data) {
                            if($data->mot_de_passe == md5($password)) {
                                $_SESSION['user']['id'] = $data->id;
                                $_SESSION['user']['role'] = 'etudiant';
                                $_SESSION['user']['name'] = $data->nom . ' ' . $data->prenom;
                                $_SESSION['user']['path'] = $data->image;

                                $response['status'] = 'success';
                                $response['content'] = 'Connexion reussie';
                            } else {
                                $response['status'] = 'error';
                                $response['content'] = 'Le mot de passe que vous avez tapé est incorrect, veuillez réessayer.';
                            }
                        }
                    } else {
                        $response['status'] = 'error';
                        $response['content'] = $label . ' que vous avez entré est incorrect, veuillez réessayer';
                    }

                }
                catch (Exception $ex) {
                    // In case of an exception, return a warning message with the exception message.
                    $response['status'] = 'warning';
                    $response['content'] = 'Exception ' . $ex->getMessage();
                }
                print json_encode($response);
            break;

            // Get the students affected for a project associated with a supervisor and an academic year.
            case 'get_etudiant_by_encadreur':
                try {
                    $encadreur = ! empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
                    // $year = $API->get_last_year();
                    $result = $API->get_etudiant_by_year($encadreur, $year);
                    $msg = false;
                    foreach($result as $data) {
                        $msg = true;
                        ?><option value="<?=$data->id ?>"><?=$data->nom . ' ' . $data->postnom . ' ' . $data->prenom . ' ' . $data->promotion ?></option><?php
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
        }
    }