<?php
    $path = '';
    $role = '';
    $username = '';
    if(! empty($codDep)) {
        $path = 'images/user/avatar-2.jpg';
        $role = 'Departement';
        $username = $_SESSION['departement']['email'];
    } elseif(isset($_SESSION['user']['name'] ) && ! empty($_SESSION['user']['name'])) {
        $username = $_SESSION['user']['name'];
        $role = $_SESSION['user']['sub_role'];
        $path = $_SESSION['user']['role'] == 'etudiant' ? 'etudiants/' . $_SESSION['user']['path'] : 'encadreur/' . $_SESSION['user']['path'];
    }
?>
<header class="pc-header">
    <div class="header-wrapper">
        <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="dropdown pc-h-item d-inline-flex d-md-none">
                    <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-search"></i>
                    </a>
                    <div class="dropdown-menu pc-h-dropdown drp-search">
                        <form class="px-3">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <i data-feather="search"></i>
                                <input autocomplete="off" type="search" class="form-control border-0 shadow-none"
                                    placeholder="Search here. . .">
                            </div>
                        </form>
                    </div>
                </li>
                <li class="pc-h-item d-none d-md-inline-flex">
                    <form class="header-search ">
                        <i data-feather="search" class="icon-search"></i>
                        <input autocomplete="off"  id="query" type="search" class="form-control" placeholder="Recherche. . .">
                    </form>
                </li>
            </ul>
        </div>
        <!-- [Mobile Media Block end] -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                <!-- conversation list -->
                <li <?=! empty($codDep) ? 'hidden': '' ?> class="dropdown pc-h-item">
                    <a class=" dropdown-toggle arrow-none text-dark px-2 mx-2"  data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="bi bi-messenger text-xl" ></i> <span id="count_convesation"></span>
                    </a>
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Message</h5>
                            <a class="pc-head-link bg-transparent"><i class="ti ti-x text-danger"></i></a>
                        </div>
                        <?php
                            if(! empty($_SESSION['user']['admin']) && isset($_SESSION['user']['admin'])) {
                                ?>
                                    <div class="dropdown-divider"></div>
                                    <div class="list-group list-group-flush w-100" id="conversation-group">

                                    </div>
                                <?php
                            }
                        ?>

                        <div class="dropdown-divider"></div>
                        <div class="dropdown-header py-0 px-0 text-wrap header-notification-scroll position-relative"
                            style="max-height: calc(100vh - 215px)">
                            <div class="list-group list-group-flush w-100 py-0" id="conversation">

                            </div>
                        </div>
                    </div>
                </li>
                <!-- conversation list end -->
                 <!-- User info  -->
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        <img src="assets/<?=$path?>" alt="user-image" class="user-avtar">
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex mb-1">
                                <div class="flex-shrink-0">
                                    <?php


                                        ?><img src="./assets/<?=$path ?>" alt="user-image"
                                        class="user-avtar wid-35"><?php
                                    ?>

                                </div>
                                <div class="flex-grow-1 text-truncate">
                                    <h6 class="mb-1 text-truncate"><?=$username ?></h6>
                                    <span>
                                        <?php
                                            print Functions::first_capital_letter($role)
                                        ?>
                                    </span>
                                </div>
                                <a onclick="redirect('./login-logout')" class="pc-head-link bg-transparent"><i
                                        class="ti ti-power text-danger"></i></a>
                            </div>
                        </div>
                        <hr>
                        <div class="tab-content" id="mysrpTabContent">
                            <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                aria-labelledby="drp-t1" tabindex="0">
                                <a onclick="redirect('./login-logout')" class="dropdown-item">
                                    <i class="ti ti-power"></i>
                                    <span>Deconnexion</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- user info end -->
            </ul>
        </div>
    </div>
</header>