<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once './models/helpers.php';
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Syllabus</title>
        <link rel="icon" type="" href="public/img/logo-imt.png" />
        <script src="public/js/libs/jquery.min.js"></script>
        <script src="public/js/libs/fontawesome.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        <link href="public/css/libs/simple-datatables.min.css" rel="stylesheet" />
        <link href="public/css/libs/bootstrap.min.css" rel="stylesheet" />
        <link href="public/css/styles.css" rel="stylesheet" />
        <link href="public/css/modifierUE.css" rel="stylesheet" />
        <script src="public/js/config.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

        <div class="collapse navbar-collapse mr-4">
        <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['user_id'])) : ?>
                <li class="nav-item">
                    <span class="navbar-text text-white me-3"><?php echo htmlspecialchars(str_replace(['.'], ' ', $_SESSION['username']), ENT_QUOTES, 'UTF-8'); ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=logout">Déconnexion</a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=login">Se connecter</a>
                </li>
            <?php endif; ?>
        </ul>
        </div>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            Accueil
                        </a>
                        <a class="nav-link" href="index.php?page=ue">
                            Fiche UE
                        </a>
                        <?php if (isset($_SESSION['user_id'])) : ?>
                            <?php if (checkAccess('responsable')) : ?>
                                <a class="nav-link" href="index.php?page=profile">
                                    Mon Profil
                                </a>
                            <?php endif; ?>
                            <?php if (checkAccess('administrateur')) : ?>
                        <a class="nav-link" href="index.php?page=ajouterue">
                            Ajouter UE
                        </a>
                        <a class="nav-link" href="index.php?page=competence">
                            Compétences
                        </a>
                        <a class="nav-link" href="index.php?page=anneescolaire">
                            Année scolaire
                        </a>

                        <div class="nav-link collapsed" style="user-select: none;"  data-bs-toggle="collapse" data-bs-target="#pagesCollapseCycle" aria-expanded="false" aria-controls="pagesCollapseCycle">
                            Formations
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </div>
                        <div class="collapse" id="pagesCollapseCycle" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="index.php?page=cycleenseignement">Référence</a>
                                <a class="nav-link" href="index.php?page=cycleenseignementannee">Cycle d'enseignement</a>
                            </nav>
                        </div>

                        <div class="nav-link collapsed" style="user-select: none;"  data-bs-toggle="collapse" data-bs-target="#pagesCollapseFormation" aria-expanded="false" aria-controls="pagesCollapseFormation">
                        Période de formation
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </div>
                        <div class="collapse" id="pagesCollapseFormation" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="index.php?page=periodedeformation">Référence</a>
                                <a class="nav-link" href="index.php?page=periodedeformationannee">Période de formation</a>
                            </nav>
                        </div>

                       
                        <a class="nav-link" href="index.php?page=departement">
                            Départements
                        </a>

                        <a class="nav-link" href="index.php?page=option">
                            Options
                        </a>
                        <a class="nav-link" href="index.php?page=responsable">
                            Utilisateurs
                        </a>
                        
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    IMT Mines Alès
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content" >
            <main class="h-100 p-4 container-fluid">
                <?php if (!empty($breadcrumbs)) : ?>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <?php foreach ($breadcrumbs as $label => $url) : ?>
                        <?php if ($url === end($breadcrumbs)) : ?>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php else : ?>
                            <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </ol>
                </nav>
                <?php endif; ?>
                <?php require $template; ?>
            </main>
        </div>
    </div>


<!-- Modal supprimer  -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
        <button type="button" class="close" id="closeModalButton" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.</p>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="cancelModalButton">Annuler</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Supprimer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de succès/erreur -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" id="modalContent">
      <div class="modal-header">
        <h5 class="modal-title" id="infoModalLabel">Statut de l'action</h5>
        <button type="button" class="close" id="closeInfoModalButton" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="infoMessage">Votre action a été réalisée avec succès.</p>
      </div>
    </div>
  </div>
</div>


<!-- Modal session  -->
<div class="modal fade" id="sessionModal" tabindex="-1" role="dialog" aria-labelledby="sessionModal" aria-hidden="true"  data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sessionModalLabel">Session expirée</h5>
      </div>
      <div class="modal-body">
        <p>Votre session est sur le point d'expirer. Souhaitez-vous prolonger votre session ?</p>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="handleSessionExpired()">Se déconnecter</button>
        <button type="button" class="btn btn-primary" onclick="extendSession()">Prolonger la session</button>
      </div>
    </div>
  </div>
</div>


    


    <script src="public/js/libs/simple-datatables.min.js"></script>
    <script src="public/js/libs/popper.min.js"></script>
    <script src="public/js/libs/bootstrap.min.js"></script>
    <script src="public/js/libs/bootstrap.bundle.min.js"></script>

    
   

    <script>
        
        window.addEventListener('DOMContentLoaded', event => {
            // Toggle the side navigation
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
                });
            }
        });

        function showInfoModal(message, type) {
            // Mettre à jour le message dans le modal
            $('#infoMessage').text(message);

            // Modifier la couleur du contenu du modal en fonction du type de message (succès ou erreur)
            if (type === 'success') {
                $('#infoModalLabel').text('Succès').removeClass('text-danger').addClass('text-success');
            } else {
                $('#infoModalLabel').text('Erreur').removeClass('text-success').addClass('text-danger');;
            }

            // Afficher le modal
            $('#infoModal').modal('show');
        }
       
        $('#closeInfoModalButton').click(function() {
            $('#infoModal').modal('hide');; // Cacher le modal lorsque le bouton "Fermer" est cliqué
        });

        $('#cancelModalButton').click(function() {
            $('#deleteModal').modal('hide'); // Hide the modal
        });
    
        // Manually hide the modal when the 'X' close button is clicked
        $('#closeModalButton').click(function() {
            $('#deleteModal').modal('hide'); // Hide the modal
        });

        if (!allowDelete) {
            $('.btn-danger').not('.except').hide(); 
        }
    </script>
    <script src="public/js/session.js"></script>
    </body>
</html>
