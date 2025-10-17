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
                <div class="container card p-3">
                    <div class="card-body" id="hearder">

                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalToggle" class="btn btn-primary">Ajouter un Directeur/Encadreur</a>

                    </div>
                    <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Enseignant</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="get_all">
                            <tr>
                                <td class="text-center" colspan="100">Chargement encours...</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Ajouter directeur/Encadreur</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <span id="id"></span>
      <div class="modal-body">

        <div class="my-3">
            <label for="">Sélectionnez Directeur</label>
            <select id="encadreur" class="form-select mt-2">
                <option value="" selected disabled>Chargement encours...</option>
            </select>
        </div>

        <div class="my-3">
            <label for="">Sélectionnez le rôle</label>
            <select id="role" class="form-select mt-2">
                <option value="1">Directeur</option>
                <option value="0">Encadreur</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button id="save" class="btn btn-primary">Ajouter</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="cofirmModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <span id="id_confirm"></span>
      <span id="status"></span>
      <div class="modal-body">
            <div id="confirm_message"></div>
      </div>
      <div class="modal-footer">
        <button id="confirm" class="btn btn-primary">Confirmer</button>
      </div>
    </div>
  </div>
</div>

<script src="./app/module/controllers/project-encadreur.js" type="module"></script>
<?php
    $page_content = ob_get_clean();
    require_once 'views/includes/theme.php';
?>