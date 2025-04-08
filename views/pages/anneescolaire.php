<div class="card my-2 py-2 px-4">
  <p class="fs-4 mb-4">
    <span class="border-bottom border-2 fw-bold pb-1">Les années scolaires</span>
    <button class="btn btn-primary float-end" id="modalAddNewYearBtn">Ajouter une année scolaire</button>
  </p>


<div class="card mb-4">
 
    <div class="card-body">
        <table id="dataanneescolaire">
            <thead>
                <tr>
                    <th>Libellé </th>
                    <th> Date de début </th>    
                    <th> Date de fin </th>    
                    <th> Statut </th>    
                    <th> Actions</th>
                </tr>
            </thead>
            <tbody id="dataresponsable-body">
                <?php foreach ($anneesscolaires as $anneescolaire) : ?>
                  <tr>
                    <td> <?= htmlspecialchars($anneescolaire['libelle'], ENT_QUOTES, 'UTF-8') ?> </td>
                    <td> <?= htmlspecialchars($anneescolaire['datedebut'], ENT_QUOTES, 'UTF-8') ?> </td>
                    <td> <?= htmlspecialchars($anneescolaire['datefin'], ENT_QUOTES, 'UTF-8') ?> </td>
                    <td > <span class="badge <?= $anneescolaire['actif'] ? "bg-success" : "bg-secondary"  ?> bg-primary"><?= htmlspecialchars($anneescolaire['actif'], ENT_QUOTES, 'UTF-8') ? "actif" : "inactif"  ?></span> </td>
                    <td>
                        <button class="btn btn-primary" onclick='editAnneescolaire(<?= json_encode($anneescolaire) ?>)'>Modifier</button>
                       
                    </td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>






<!-- Modal ajouter anneescolaire -->
<div class="modal fade" id="ajouteranneescolaire" tabindex="-1" role="dialog" aria-labelledby="ajouteranneescolaireLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouteranneescolaireLabel">Ajouter une année scolaire</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajouteranneescolaire-form">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé:</label>
                    <input class="form-control" maxlength="9" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="datedebut">date de début :</label>
                    <input class="form-control" type="date" name="datedebut"  id="datedebut" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="datefin">date de fin :</label>
                    <input class="form-control" type="date" name="datefin"  id="datefin" required/>
                </div>
            </div>
            <div class="form-row">
                <label class="mr-2" for="actif">Année active : </label>
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




<!-- Modal modifier anneescolaire -->
<div class="modal fade" id="modifieranneescolaire" tabindex="-1" role="dialog" aria-labelledby="modifieranneescolaireLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifieranneescolaireLabel">Modifier une année scolaire</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifieranneescolaire-form">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <input type="hidden" name="idanneescolaire" id="idanneescolaire">

            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé:</label>
                    <input class="form-control" maxlength="9" name="libelle"  id="libelle" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="datedebut">date de début :</label>
                    <input class="form-control" type="date" name="datedebut"  id="datedebut" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="datefin">date de fin :</label>
                    <input class="form-control" type="date" name="datefin"  id="datefin" required/>
                </div>
            </div>
            <div class="form-row">
                <label class="mr-2" for="actif">Année active : </label>
                <div class="form-check form-switch">
                    <input class="form-check-input" name="actif" id="actif" type="checkbox">
                </div>
            </div>
            <input type="submit" class="btn btn-primary float-end" value="Enregistrer"/>

        </form>
      </div>
      
    </div>
  </div>
</div>









<script src="public/js/anneescolaireScript.js"></script>

