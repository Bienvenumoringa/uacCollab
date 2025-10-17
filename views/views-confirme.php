<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>UAC - 404</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
    <meta name="keywords"
        content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
    <meta name="author" content="CodedThemes">

    <!-- [Favicon] icon -->
    <link rel="icon" href="assets/themes/logo.png" type="image/png">
     <!-- [Google Font] Family -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="assets/fonts/material.css">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="assets/css/style-preset.css">
</head>


<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <div class="maintenance-block">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="error-card">
                        <div class="card-body">
                            <div class="error-image-block text-center">
                                <img class="img-fluid" src="assets/themes/logo.png" alt="img">
                                <!-- <img class="img-fluid img-twocone" src="assets/themes/TwoCone.png" alt="img"> -->
                            </div>
                            <div class="text-center">
                                <h1 class="mt-5"><b>Confirmer</b></h1>
                                <p class="mt-2 mb-4 text-muted">Veuillez confirmer votre collaboration en cliquant sur ce bouton :</p>
                                <button type="button" id="confirme" class="btn btn-primary mb-3">Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-toast toast fade position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive"
        aria-atomic="true" id="toast-example" style="z-index: 100000;">
        <div class="toast-header">
            <i class="bi bi-bell me-2" id="icon"></i>
            <div class="me-auto fw-semibold" id="title"></div>
            <small>now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-white" id="content">

        </div>
    </div>
    <script src="./assets/js/plugins/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.js"></script>
    <script src="./app/module/controllers/confirme.js" type="module"></script>



</body>
<!-- [Body] end -->

</html>