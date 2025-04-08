<div class="card my-2 py-2 px-4">
    <p class="fs-4 mb-4">
        <span class="border-bottom border-2 fw-bold pb-1">Option</span>
        <button type="button" class="btn btn-link" data-bs-toggle="tooltip" title="Cette section permet de gérer les options associées aux matières enseignées, telles que les modules ou spécialisations facultatives. Une option peut être activée ou désactivée pour refléter son état actuel, tout en conservant son historique dans le système.">
            <img src="../../public/img/question-circle.svg" alt="" srcset="">
        </button>
        <button class="btn btn-primary float-end" id="openModalAjouteroptionBtn">Ajouter une option</button>
    </p>

    <div class="card mb-4">
        <div class="card-body">
            <table id="dataoption">
                <thead>
                    <tr>
                        <th>Libellé </th>
                        <th> Statut </th>    
                        <th> Actions</th>
                    </tr>
                </thead>
                <tbody id="dataresponsable-body">
                    <?php foreach ($options as $option) : ?>
                        <tr>
                            <td> <?= $option['libelle'] ?> </td>
                            <td> <span class="badge <?= $option['actif'] ? "bg-success" : "bg-secondary"  ?> bg-primary"><?= $option['actif'] ? "active" : "inactive"  ?></span> </td>
                            <td>
                                <button class="btn btn-primary" onclick="editOption(<?=htmlspecialchars(json_encode($option), ENT_QUOTES, 'UTF-8') ?>)">Modifier</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
          
    <hr/>
  
    <p class="fs-4 mb-4">
        <span class="border-bottom border-2 fw-bold pb-1">Option année</span>
        <button type="button" class="btn btn-link" data-bs-toggle="tooltip" title="Cette section vous permet d’associer une option à une année scolaire donnée. Elle garantit le suivi de l’évolution des options dans le temps, en conservant leurs libellés ou codes spécifiques pour chaque année scolaire.">
            <img src="../../public/img/question-circle.svg" alt="" srcset="">
        </button>
        <button class="btn btn-primary float-end" id="openModalAjouteroptionanneeBtn">Ajouter une option</button>
    </p>

    <div class="form-row">
        <div class="col-md mb-3">
            <label for="yearFilter">Année scolaire :</label>
            <select class="custom-select" name="yearFilter" id="yearFilter">
                <option value="" selected="selected" >Sélectionnez une année</option>
                <?php foreach ($years as $year) : ?>
                <option value="<?= htmlspecialchars($year['idanneescolaire'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($year['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="card-body" id="dataOA-container"></div>
        </div>
    </div>
</div>





<!-- Modal ajouter option -->
<div class="modal fade" id="ajouteroption" tabindex="-1" role="dialog" aria-labelledby="ajouteroptionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouteroptionLabel">Ajouter une option</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajouteroption-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle" maxlength="150"  id="libelle" required/>
                </div>
            </div>
   
            <div class="form-row">
                <label class="mr-2" for="actif">Option active : </label>
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

<!-- Modal modifier option -->
<div class="modal fade" id="modifieroption" tabindex="-1" role="dialog" aria-labelledby="modifieroptionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifieroptionLabel">Modifier l'option</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifieroption-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="idoption" id="idoption">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle" maxlength="150"  id="libelle" required/>
                </div>
            </div>
   
            <div class="form-row">
                <label class="mr-2" for="actif">Option active : </label>
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




<!-- Modal ajouter option annee -->
<div class="modal fade" id="ajouteroptionannee" tabindex="-1" role="dialog" aria-labelledby="ajouteroptionanneeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouteroptionanneeLabel">Ajouter une option année</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajouteroptionannee-form">
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
                    <label for="idoption">Option :</label>
                    <select class="custom-select" name="idoption" id="idoption">
                        <?php foreach ($options as $option) : ?>
                        <option value="<?= htmlspecialchars($option['idoption']) ?>"><?= htmlspecialchars($option['libelle']) ?></option>
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








<!-- Modal modifier option annee -->
<div class="modal fade" id="modifieroptionannee" tabindex="-1" role="dialog" aria-labelledby="modifieroptionanneeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifieroptionanneeLabel">Modifier l'option année</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifieroptionannee-form">
            <input type="hidden" name="idoptionannee" id="idoptionannee">
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
                    <label for="idoption">Option :</label>
                    <select class="custom-select" name="idoption" id="idoption">
                        <?php foreach ($options as $option) : ?>
                        <option value="<?= htmlspecialchars($option['idoption']) ?>"><?= htmlspecialchars($option['libelle']) ?></option>
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
<script src="public/js/option.js"></script>
