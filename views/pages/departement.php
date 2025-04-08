<div class="card my-2 py-2 px-4">
    <p class="fs-4 mb-4">
        <span class="border-bottom border-2 fw-bold pb-1">Département</span>
        <button type="button" class="btn btn-link" data-bs-toggle="tooltip" title="Cette section permet de gérer les départements. Un département peut être activé ou désactivé pour indiquer son état actuel tout en conservant son historique dans le système.">
            <img src="../../public/img/question-circle.svg" alt="" srcset="">
        </button>
        <button class="btn btn-primary float-end" id="openModalAjouterdepartementBtn">Ajouter un département</button>
    </p>

    <div class="card mb-4">
        <div class="card-body">
            <table id="datadepartement">
                <thead>
                    <tr>
                        <th>Libellé </th>
                        <th> Statut </th>    
                        <th> Actions</th>
                    </tr>
                </thead>
                <tbody id="dataresponsable-body">
                    <?php foreach ($departements as $departement) : ?>
                        <tr>
                            <td> <?= $departement['libelle'] ?> </td>
                            <td> <span class="badge <?= $departement['actif'] ? "bg-success" : "bg-secondary"  ?> bg-primary"><?= $departement['actif'] ? "actif" : "inactif"  ?></span> </td>
                            <td>
                                <button class="btn btn-primary" onclick="editDepartement(<?=htmlspecialchars(json_encode($departement), ENT_QUOTES, 'UTF-8') ?>)">Modifier</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
          
    <hr/>
  
    <p class="fs-4 mb-4">
        <span class="border-bottom border-2 fw-bold pb-1">Département année</span>
        <button type="button" class="btn btn-link" data-bs-toggle="tooltip" title="Cette section vous permet d’associer un département à une année scolaire donnée. Elle permet de suivre l’évolution des départements dans le temps, en conservant leurs noms ou codes spécifiques pour chaque année scolaire.">
            <img src="../../public/img/question-circle.svg" alt="" srcset="">
        </button>
        <button class="btn btn-primary float-end" id="openModalAjouterdepartementAnneeBtn">Ajouter un département</button>
    </p>

    <div class="form-row">
        <div class="col-md mb-3">
            <label for="yearFilter">Année scolaire :</label>
            <select class="custom-select" name="yearFilter" id="yearFilter">
                <option value="" selected="selec    ted" >Sélectionnez une année</option>
                <?php foreach ($years as $year) : ?>
                <option value="<?= htmlspecialchars($year['idanneescolaire']) ?>"><?= htmlspecialchars($year['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="card-body" id="dataDA-container"></div>
        </div>
    </div>
</div>





<!-- Modal ajouter departement -->
<div class="modal fade" id="ajouterdepartement" tabindex="-1" role="dialog" aria-labelledby="ajouterdepartementLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouterdepartementLabel">Ajouter un département</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajouterdepartement-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle"  id="libelle" required/>
                </div>
            </div>
   
            <div class="form-row">
                <label class="mr-2" for="actif">Département actif : </label>
                <div class="form-check form-switch">
                    <input class="form-check-input" name="actif" id="actif" type="checkbox">
                </div>
            </div>
            <input type="submit" class="btn btn-primary float-end" value="Ajouter"/>

        </form>
      </div>
      
    </div>
  </div>
</div>

<!-- Modal modifier departement -->
<div class="modal fade" id="modifierdepartement" tabindex="-1" role="dialog" aria-labelledby="modifierdepartementLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifierdepartementLabel">Modifier le département</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifierdepartement-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="iddepartement" id="iddepartement">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle" maxlength="150"  id="libelle" required/>
                </div>
            </div>
   
            <div class="form-row">
                <label class="mr-2" for="actif">Département actif : </label>
                <div class="form-check form-switch">
                    <input class="form-check-input" name="actif" id="actif" type="checkbox">
                </div>
            </div>
            <input type="submit" class="btn btn-primary float-end" value="Modifier"/>

        </form>
      </div>
      
    </div>
  </div>
</div>




<!-- Modal ajouter departement annee -->
<div class="modal fade" id="ajouterdepartementannee" tabindex="-1" role="dialog" aria-labelledby="ajouterdepartementanneeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouterdepartementanneeLabel">Ajouter un département année</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajouterdepartementannee-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="idanneescolaire">Année scolaire :</label>
                    <select class="custom-select" name="idanneescolaire" id="idanneescolaire">
                        <?php foreach ($years as $year) : ?>
                        <option value="<?= htmlspecialchars($year['idanneescolaire']) ?>"><?= htmlspecialchars($year['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="iddepartement">Département :</label>
                    <select class="custom-select" name="iddepartement" id="iddepartement">
                        <?php foreach ($departements as $departement) : ?>
                        <option value="<?= htmlspecialchars($departement['iddepartement']) ?>"><?= htmlspecialchars($departement['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle" maxlength="150"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="code">Code :</label>
                    <input class="form-control" name="code" maxlength="50"  id="code" required/>
                </div>
            </div>
   
            <input type="submit" class="btn btn-primary float-end" value="Ajouter"/>

        </form>
      </div>
      
    </div>
  </div>
</div>








<!-- Modal modifier departement annee -->
<div class="modal fade" id="modifierdepartementannee" tabindex="-1" role="dialog" aria-labelledby="modifierdepartementanneeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifierdepartementanneeLabel">Modifier le département année</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifierdepartementannee-form">
            <input type="hidden" name="iddepartementannee" id="iddepartementannee">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="idanneescolaire">Année scolaire :</label>
                    <select class="custom-select" name="idanneescolaire" id="idanneescolaire">
                        <?php foreach ($years as $year) : ?>
                        <option value="<?= htmlspecialchars($year['idanneescolaire']) ?>"><?= htmlspecialchars($year['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="iddepartement">Département :</label>
                    <select class="custom-select" name="iddepartement" id="iddepartement">
                        <?php foreach ($departements as $departement) : ?>
                        <option value="<?= htmlspecialchars($departement['iddepartement']) ?>"><?= htmlspecialchars($departement['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle" maxlength="150"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="code">Code :</label>
                    <input class="form-control" name="code" maxlength="50"  id="code" required/>
                </div>
            </div>
   
            <input type="submit" class="btn btn-primary float-end" value="Modifier"/>

        </form>
      </div>
      
    </div>
  </div>
</div>




<script src="public/js/libs/bootstrap.bundle.min.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
<script src="public/js/departement.js?/v=3"></script>
