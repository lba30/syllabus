<div class="card my-2 py-2 px-4">
  <p class="fs-4 mb-4">
    <span class="border-bottom border-2 fw-bold pb-1">Les cycles d'enseignement</span>
    <button type="button" class="btn btn-link" data-bs-toggle="tooltip" title="Cette page permet de gérer les cycles d'enseignement (formations). Un cycle représente une catégorie d'enseignements ou simplement une formation. Ces données servent de référence dans le système.">
      <img src="../../public/img/question-circle.svg" alt="" srcset="">
    </button>
    <button class="btn btn-primary float-end" id="modalAddNewCycleBtn">Ajouter un cycle d'enseignement</button>
  </p>


<div class="card mb-4">
 
    <div class="card-body">
        <table id="datacycleenseignement">
            <thead>
                <tr>
                    <th>Libellé </th> 
                    <th>Statut </th> 
                    <th> Actions</th>
                </tr>
            </thead>
            <tbody id="datacycleenseignement-body">
                <?php foreach ($cyclesenseignement as $cycleenseignement) : ?>
                  <tr>
                    <td> <?= htmlspecialchars($cycleenseignement['libelle'], ENT_QUOTES, 'UTF-8') ?> </td>
                    <td > <span class="badge <?= $cycleenseignement['actif'] ? "bg-success" : "bg-secondary"  ?> bg-primary"><?= $cycleenseignement['actif'] ? "actif" : "inactif"  ?></span> </td>
                    <td>
                        <button class="btn btn-primary" onclick="editCycleenseignement(<?= $cycleenseignement['idcycleenseignement'] ?>,`<?= htmlspecialchars($cycleenseignement['libelle']) ?>`,<?= $cycleenseignement['actif'] ?>)">Modifier</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>






<!-- Modal ajouter cycleenseignement -->
<div class="modal fade" id="ajoutercycleenseignement" tabindex="-1" role="dialog" aria-labelledby="ajoutercycleenseignementLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajoutercycleenseignementLabel">Ajouter un cycle d'enseignement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajoutercycleenseignement-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé:</label>
                    <input class="form-control" maxlength="150" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <label class="mr-2" for="actif">Cycle actif : </label>
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





<!-- Modal modifier cycleenseignement -->
<div class="modal fade" id="modifiercycleenseignement" tabindex="-1" role="dialog" aria-labelledby="modifiercycleenseignementLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifiercycleenseignementLabel">Modifier un cycle d'enseignement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifiercycleenseignement-form">
            <input type="hidden" name="idcycleenseignement" id="idcycleenseignement">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé:</label>
                    <input class="form-control" maxlength="150" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <label class="mr-2" for="actif">Cycle actif : </label>
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



<script src="public/js/libs/bootstrap.bundle.min.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>



<script src="public/js/cycleenseignement.js"></script>

