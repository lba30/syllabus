<div class="card my-2 py-2 px-4">
    <p class="fs-4 mb-4">
        <span class="border-bottom border-2 fw-bold pb-1">Période de formation Année</span>
        <button type="button" class="btn btn-link" data-bs-toggle="tooltip" title="Cette section permet d'associer une période de formation à une année scolaire spécifique.">
            <img src="../../public/img/question-circle.svg" alt="" srcset="">
        </button>
        <button class="btn btn-primary float-end" id="openModalAjouterPFABtn">Ajouter une periode</button>
    </p>
    <div class="form-row">
        <div class="col-md mb-3">
            <label for="yearFilter">Année scolaire :</label>
            <select class="custom-select" name="yearFilter" id="yearFilter">
                <?php foreach ($years as $year) : ?>
                <option value="<?= htmlspecialchars($year['idanneescolaire'], ENT_QUOTES, 'UTF-8') ?>"> <?= htmlspecialchars($year['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="col-md mb-3">
            <label for="cycleFilter">Cycle d'enseignement :</label>
            <select class="custom-select" name="cycleFilter"  id="cycleFilter">
            </select>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body" id="dataPA-container"></div>
      
    </div>
  </div>     

</div>


<!-- Modal ajouter periode -->
<div class="modal fade" id="ajouterPFA" tabindex="-1" role="dialog" aria-labelledby="ajouterPFALabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouterPFALabel">Ajouter une période</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajouterPFA-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="idanneescolaire">Année scolaire :</label>
                    <select class="custom-select" name="idanneescolaire" id="idanneescolaire" required>
                        <option value="" selected="selected" >Sélectionnez une année</option>
                        <?php foreach ($years as $year) : ?>
                        <option value="<?= htmlspecialchars($year['idanneescolaire']) ?>"><?= htmlspecialchars($year['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="idcycleenseignementannee">Cycle d'enseignement :</label>
                    <select class="custom-select" name="idcycleenseignementannee"  id="idcycleenseignementannee" required>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="idtypeperiodeformation">Periode de formation :</label>
                    <select class="custom-select" name="idperiodeformation" id="idperiodeformation">
                        <?php foreach ($periodeF as $periode) : ?>
                        <option value="<?= htmlspecialchars($periode['idperiodeformation']) ?>"><?= htmlspecialchars($periode['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé court:</label>
                    <input class="form-control" name="libellecourt"  id="libellecourt" required/>
                </div>
            </div> 
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="code">Code:</label>
                    <input class="form-control" name="code"  id="code" required/>
                </div>
            </div>

            <input type="submit" class="btn btn-primary float-end" value="Ajouter"/>

        </form>
      </div>
      
    </div>
  </div>
</div>







<!-- Modal modifier periode de formation Année -->
<div class="modal fade" id="modifierPFA" tabindex="-1" role="dialog" aria-labelledby="modifierPFALabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifierPFALabel">Modifier une période</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifierPFA-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <input type="hidden" name="idperiodeformationannee" id="idperiodeformationannee">
            <div class="form-row">
                    <div class="col-md mb-3">
                        <label for="idtypeperiodeformation">Periode de formation :</label>
                        <select class="custom-select" name="idperiodeformation" id="idperiodeformation">
                            <?php foreach ($periodeF as $periode) : ?>
                            <option value="<?= htmlspecialchars($periode['idperiodeformation'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($periode['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé court:</label>
                    <input class="form-control" name="libellecourt"  id="libellecourt" required/>
                </div>
            </div> 
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="code">Code:</label>
                    <input class="form-control" name="code"  id="code" required/>
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

<script src="public/js/periodeformationannee.js"></script>
