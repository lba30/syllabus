<div class="card my-2 py-2 px-4">
  <p class="fs-4 mb-4">
    <span class="border-bottom border-2 fw-bold pb-1">Type de période de formation</span>
    <button type="button" class="btn btn-link" data-bs-toggle="tooltip" title="Cette section définit les types de périodes de formation disponibles (ex : année, semestre, trimestre). Chaque type représente une structure temporelle utilisée pour organiser les cycles d'enseignement.">
      <img src="../../public/img/question-circle.svg" alt="" srcset="">
    </button>
    <button class="btn btn-primary float-end" id="openModalAjoutertypeBtn">Ajouter une type</button>
  </p>

  <div class="card mb-4">
    <div class="card-body">
      <table id="datatype">
        <thead>
          <tr>
            <th>Libellé </th>
            <th> Code </th>    
            <th> Statut </th>    
            <th> Actions</th>
          </tr>
        </thead>
        <tbody id="dataresponsable-body">
          <?php foreach ($typePF as $type) : ?>
            <tr>
              <td> <?= htmlspecialchars($type['libelle'], ENT_QUOTES, 'UTF-8') ?> </td>
              <td> <?= htmlspecialchars($type['code'], ENT_QUOTES, 'UTF-8') ?> </td>
              <td > <span class="badge <?= $type['actif'] ? "bg-success" : "bg-secondary"  ?> bg-primary"><?= $type['actif'] ? "active" : "inactive"  ?></span> </td>
              <td>
                  <button class="btn btn-primary" onclick="editType(<?=htmlspecialchars(json_encode($type), ENT_QUOTES, 'UTF-8') ?>)">Modifier</button>
                 
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
          
  <hr/>

  <p class="fs-4 mb-4">
    <span class="border-bottom border-2 fw-bold pb-1">Période de formation</span>
    <button type="button" class="btn btn-link" data-bs-toggle="tooltip" title="Cette section permet de gérer les périodes spécifiques de formation, telles que '1ère année', '1er semestre', ou '2ème trimestre'. Ces périodes sont associées à un type de période (année, semestre, etc.) et servent à structurer les formations dans le temps.">
      <img src="../../public/img/question-circle.svg" alt="" srcset="">
    </button>
    <button class="btn btn-primary float-end" id="openModalAjouterperiodeBtn">Ajouter une periode</button>
  </p>

  <div class="card mb-4">
    <div class="card-body">
      <table id="dataperiode">
        <thead>
          <tr>
            <th>Libellé </th>
            <th> Type </th>    
            <th> Statut </th>    
            <th> Actions</th>
          </tr>
        </thead>
        <tbody id="dataresponsable-body">
          <?php foreach ($periodeF as $periode) : ?>
            <tr>
              <td> <?= $periode['libelle'] ?> </td>
              <td> <?= $periode['typepf'] ?> </td>
              <td > <span class="badge <?= $periode['actif'] ? "bg-success" : "bg-secondary"  ?> bg-primary"><?= $periode['actif'] ? "active" : "inactive"  ?></span> </td>
              <td>
                  <button class="btn btn-primary" onclick="editPeriode(<?=htmlspecialchars(json_encode($periode), ENT_QUOTES, 'UTF-8') ?>)">Modifier</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>     

</div>






<!-- Modal ajouter type -->
<div class="modal fade" id="ajoutertype" tabindex="-1" role="dialog" aria-labelledby="ajoutertypeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajoutertypeLabel">Ajouter une type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajoutertype-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="code">Code :</label>
                    <input class="form-control" name="code"  id="code" required/>
                </div>
            </div>
            <div class="form-row">
                <label class="mr-2" for="actif">Type de période de formation active : </label>
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




<!-- Modal modifier type -->
<div class="modal fade" id="modifiertype" tabindex="-1" role="dialog" aria-labelledby="modifiertypeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifiertypeLabel">Modifier une type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifiertype-form">
          <input type="hidden" name="idtypeperiodeformation" id="idtypeperiodeformation">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="code">Code :</label>
                    <input class="form-control" name="code"  id="code" required/>
                </div>
            </div>
            <div class="form-row">
                <label class="mr-2" for="actif">Période de formation active : </label>
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










<!-- Modal ajouter periode -->
<div class="modal fade" id="ajouterperiode" tabindex="-1" role="dialog" aria-labelledby="ajouterperiodeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouterperiodeLabel">Ajouter une période</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajouterperiode-form">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="idtypeperiodeformation">Type :</label>
                    <select class="custom-select" name="idtypeperiodeformation" id="idtypeperiodeformation">
                        <?php foreach ($typePF as $type) : ?>
                        <option value="<?= htmlspecialchars($type['idtypeperiodeformation']) ?>"><?= htmlspecialchars($type['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <label class="mr-2" for="actif">Période de formation active : </label>
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







<!-- Modal modifier periode -->
<div class="modal fade" id="modifierperiode" tabindex="-1" role="dialog" aria-labelledby="modifierperiodeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifierperiodeLabel">Modifier une période</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifierperiode-form">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <input type="hidden" name="idperiodeformation" id="idperiodeformation">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé :</label>
                    <input class="form-control" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="idtypeperiodeformation">Type :</label>
                    <select class="custom-select" name="idtypeperiodeformation" id="idtypeperiodeformation">
                        <?php foreach ($typePF as $type) : ?>
                        <option value="<?= htmlspecialchars($type['idtypeperiodeformation']) ?>"><?= htmlspecialchars($type['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <label class="mr-2" for="actif">Période de formation active : </label>
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

<script src="public/js/periodeformation.js"></script>
