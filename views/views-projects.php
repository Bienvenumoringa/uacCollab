<?php
    $page_title = 'UAC collab | Projets';
    require_once 'app/module/functions/functions.php';
    ob_start();
    session_start();

    if (!isset($_SESSION['departement']['code']) && !isset($_SESSION['user']['id'])) {
        header('location:./login');
    }

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

                <?php
                    if(! empty($annee) && ! empty($promotion)) {
                        ?>
                            <div class="container card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalToggle" class="btn btn-primary">Créer un nouveau projet</a>
                                    <span>Projet recente</span>
                                </div>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>dates</th>
                                            <th>Sujet</th>
                                            <th>Description</th>
                                            <th>Etudiant</th>
                                            <th>Promotion</th>
                                            <th>Annee</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin-data">
                                        <tr>
                                            <td class="text-center" colspan="100">Chargement encours...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        <?php
                    } else {
                        ?>
                            <div <?=! empty($codDep) ? 'hidden': '' ?> class="row" id="container">
                                <div class="col-12 justify-content-center d-flex align-items-center" style="min-height: 70vh;">
                                    <h4>Chargement encours...</h4>
                                </div>
                            </div>
                            <div <?=! empty($codDep) ? '': 'hidden' ?>  class="container card p-4">
                                <div class="my-3">
                                    <label for="">Sélectionnez une annee academique</label>
                                    <select id="annee" class="form-select mt-2">
                                        <option value="" selected disabled>Chargement encours...</option>
                                    </select>
                                </div>
                                <div class="my-3">
                                    <label for="">Sélectionnez une promotion</label>
                                    <select id="promotion" class="form-select mt-2">
                                        <option value="" selected disabled>Chargement encours...</option>
                                    </select>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-primary" id="next">Suivant</button>
                                </div>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Créer un nouveau projet</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <span id="id"></span>
      <div class="modal-body">
        <div>
            <label for="">Sujet</label>
            <input autocomplete="off" id="titre" type="text" class="form-control mt-2" placeholder="Entrez le sujet">
        </div>
        <div class="my-3">
            <label for="">Description</label>
            <textarea id="description" class="form-control mt-2" placeholder="Entrez la description du projet"></textarea>
        </div>

        <div class="my-3">
            <label for="">Sélectionnez un étudiant</label>
            <select id="etudiant" class="form-select mt-2">
                <option value="" selected disabled>Chargement encours...</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button id="save" class="btn btn-primary">Créer</button>
      </div>
    </div>
  </div>
</div>

<script src="./app/module/controllers/project.js" type="module"></script>
<?php
    $page_content = ob_get_clean();
    require_once 'views/includes/theme.php';
?>