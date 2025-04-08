<div class="card my-2 py-2 px-4">
  <p class="fs-4 mb-4"><span class="border-bottom border-2 fw-bold pb-1">Les cycles d'enseignement</span>
    <button type="button" class="btn btn-link" data-bs-toggle="tooltip" title="Cette page vous permet d'associer un cycle d'enseignement à une année scolaire. Elle permet de suivre l’évolution d’un cycle dans le temps en le liant à des années spécifiques.">
      <img src="../../public/img/question-circle.svg" alt="" srcset="">
    </button>
    <button class="btn btn-primary float-end" id="modalAddNewCycleBtn">Ajouter un cycle d'enseignement</button>
  </p>

  <div class="form-row">
            <div class="col-md mb-3">
                <label for="yearFilter">Année scolaire :</label>
                <select class="custom-select" name="yearFilter" id="yearFilter">
                    <option value="" selected="selected" >Sélectionnez une année</option>
                    <?php foreach ($years as $year) : ?>
                    <option value="<?= htmlspecialchars($year['idanneescolaire']) ?>"><?= htmlspecialchars($year['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
    </div>

<div class="card mb-4">
    <div class="card-body">    
        <div class="card-body" id="data-container"></div>
    </div>
</div>
</div>






<!-- Modal ajouter cycleenseignementannee -->
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
                    <label for="idcycleenseignement">Cycle enseignement :</label>
                    <select class="custom-select" name="idcycleenseignement" id="idcycleenseignement">
                        <?php foreach ($cycles as $cycle) : ?>
                        <option value="<?= htmlspecialchars($cycle['idcycleenseignement']) ?>"><?= htmlspecialchars($cycle['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé:</label>
                    <input class="form-control" maxlength="150" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libellecourt">Libellé court:</label>
                    <input class="form-control" maxlength="25" name="libellecourt"  id="libellecourt" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="code">Code:</label>
                    <input class="form-control" maxlength="15" name="code"  id="code" required/>
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
            <input type="hidden" name="idcycleenseignementannee" id="idcycleenseignementannee">
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
                    <label for="idcycleenseignement">Cycle enseignement :</label>
                    <select class="custom-select" name="idcycleenseignement" id="idcycleenseignement">
                        <?php foreach ($cycles as $cycle) : ?>
                        <option value="<?= htmlspecialchars($cycle['idcycleenseignement']) ?>"><?= htmlspecialchars($cycle['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé:</label>
                    <input class="form-control" maxlength="150" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libellecourt">Libellé court:</label>
                    <input class="form-control" maxlength="25" name="libellecourt"  id="libellecourt" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="code">Code:</label>
                    <input class="form-control" maxlength="15" name="code"  id="code" required/>
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

<script src="public/js/cycleenseignementannee.js?v=3"></script>

