<div class="card my-2 py-2 px-4">
    <p class="fs-4 mb-4">
        <span class="border-bottom border-2 fw-bold pb-1">Les blocs de compétences</span>
        <button class="btn btn-primary float-end" data-toggle="modal" data-target="#ajouterbloccompetence">Ajouter un bloc de compétences</button>
    </p>


    <div class="card mb-4">
 
        <div class="card-body" id="datacompetence-container">

            <table id="datacompetence">
                <thead>
                    <tr>
                        <th>Code </th>
                        <th> Libillé </th>    
                        <th> Actions</th>
                    </tr>
                </thead>
                <tbody id="datacompetence-body">
                    <?php foreach ($bloccompetences as $bloccompetence) : ?>
                        <tr>
                            <td> <p  class="text-uppercase"> <?= htmlspecialchars($bloccompetence['code'], ENT_QUOTES, 'UTF-8') ?></p></td>
                            <td> <p class="text-capitalize"><?= htmlspecialchars($bloccompetence['libelle'], ENT_QUOTES, 'UTF-8') ?></p> </td>
                            <td> 
                                <a class="btn btn-primary text-white" href="index.php?page=modifiercompetence&id=<?= htmlspecialchars($bloccompetence['idbloccompetence'], ENT_QUOTES, 'UTF-8') ?>">Modifier</a> 
                                <button class="btn btn-danger" onclick="setItemToDelete(<?= htmlspecialchars($bloccompetence['idbloccompetence'], ENT_QUOTES, 'UTF-8') ?>)">Supprimer</button> 
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal ajouter bloc competence -->
<div class="modal fade" id="ajouterbloccompetence" tabindex="-1" role="dialog" aria-labelledby="ajouterbloccompetenceLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouterbloccompetenceLabel">Ajouter un bloc de compétences</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajouterbloccompetence-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="code">Code</label>
                    <input class="form-control" required name="code" id="code" placeholder="code du bloc">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="libelle">Libellé</label>
                    <input class="form-control" maxlength="300" required name="libelle" id="libelle" placeholder="libellé du bloc">
                </div>
            </div>

            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="activitesexercees">Activités exercées</label>
                    <textarea class="form-control"  name="activitesexercees" id="activitesexercees" rows="5" placeholder="Activités exercées du bloc"></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="modalitesevaluation">Modalités d'évaluation</label>
                    <textarea class="form-control"  name="modalitesevaluation" id="modalitesevaluation" rows="5" placeholder="Modalités d'évaluation du bloc"></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="criteresevaluation">Critères d'évaluation</label>
                    <textarea class="form-control"  name="criteresevaluation" id="criteresevaluation" rows="5" placeholder="Critères d'évaluation du bloc"></textarea>
                </div>
            </div>
            <input type="submit" id="ajoutetBCBtn" class="btn btn-primary float-end" value="Ajouter"/>

        </form>
      </div>
      
    </div>
  </div>
</div>



<script src="public/js/competencescript.js"></script>
