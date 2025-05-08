<?php

    require_once 'app/module/functions/functions.php';
    ob_start();
    session_start();

    $parts = [];
    if (isset($_GET['url']) && !empty($_GET['url'])) {
        $parts = explode('-', $_GET['url']);
        // Maintenant tu peux accéder à $parts[1], $parts[2], etc.
    }

    $annee = '';
    $promotion = '';
    if(! empty($parts)){
        // Récupération des deux derniers éléments
        $annee = ! empty($parts[1]) ? $parts[1] : ''; // 1
        $promotion = ! empty($parts[2]) ? $parts[2] : ''; // 1
    }

    $codDep = ! empty($_SESSION['departement']['code']) && isset($_SESSION['departement']['code'])
    ? $_SESSION['departement']['code']
    : '';
    $title = ! empty($codDep) ? 'Tableau de bord': 'Accueil' ;
    $page_title = 'UAC collab | ' . $title;
?>

<title><?=$page_title ?></title>
<!-- [ Main Content ] start -->
<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10 mb-4"><?=$page_title ?></h5>
                        </div>
                    </div>
                </div>
                <div <?=! empty($codDep) ? '': 'hidden' ?> class="container p-0">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon">
                                        <i class="bi bi-box-fill card-icon-1"></i>
                                    </div>
                                    <div class="px-3">
                                        <small class="text-muted small">Projets en attente</small>
                                        <h5 class="mt-2 text-dark"><b>5</b></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon">
                                        <i class="bi bi-boxes card-icon-2"></i>
                                    </div>
                                    <div class="px-3">
                                        <small class="text-muted small">Projets en cours</small>
                                        <h5 class="mt-2 text-dark"><b>10</b></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon">
                                        <i class="bi bi-box2-fill card-icon-3"></i>
                                    </div>
                                    <div class="px-3">
                                        <small class="text-muted small">Projets terminés</small>
                                        <h5 class="mt-2 text-dark"><b>27</b></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div <?=! empty($codDep) ? '': 'hidden' ?> class="container card px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mt-2 text-muted"><b>Projets récents</b></h5>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalToggle"
                            class="btn btn-primary mt-3">Gérer les projets</a>
                    </div>
                    <hr>
                    <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Sujet</th>
                                <th>Description</th>
                                <th>Etudiant</th>
                                <th>Promotion</th>
                                <th>Année</th>
                            </tr>
                        </thead>
                        <tbody id="admin-data-recent">
                            <tr>
                                <td class="text-center" colspan="100">Chargement encours...</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div <?=! empty($codDep) ? 'hidden': '' ?> class="row" id="container">
                    <div class="col-12 justify-content-center d-flex align-items-center" style="min-height: 70vh;">
                        <h4>Chargement encours...</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
    tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="">
                    <label for="">Sélectionnez une annee academique</label>
                    <select id="annee" class="form-select mt-2">
                        <option value="" selected disabled>Chargement encours...</option>
                    </select>
                </div>
                <div class="mt-3">
                    <label for="">Sélectionnez une promotion</label>
                    <select id="promotion" class="form-select mt-2">
                        <option value="" selected disabled>Chargement encours...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="next">Suivant</button>
            </div>
        </div>
    </div>
</div>


<script src="./app/module/controllers/project.js" type="module"></script>
<?php
    $page_content = ob_get_clean();
    require_once 'views/includes/theme.php';
?>