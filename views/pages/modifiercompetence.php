<div class="card my-2 py-2 px-4">
    <p class="fs-4 mb-4"><span class="border-bottom border-2 pb-1">Modifer le bloc de Compétences</span></p>

    <form id="form-info">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="idbloccompetence" id="idbloccompetence" value="<?= htmlspecialchars($bc['idbloccompetence'], ENT_QUOTES, 'UTF-8') ?>">
        <div class="form-row">
            <div class="col-md mb-3">
                <label for="code">Code</label>
                <input class="form-control text-uppercase" required name="code" id="code" placeholder="code du bloc" value="<?=htmlspecialchars($bc['code'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md mb-3">
                <label for="libelle">Libellé</label>
                <input class="form-control text-capitalize" maxlength="300" required name="libelle" id="libelle" placeholder="libellé du bloc" value="<?= htmlspecialchars($bc['libelle'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3">
                <label for="activitesexercees">Activités exercées</label>
                <textarea class="form-control" name="activitesexercees" id="activitesexercees" rows="5" placeholder="Activités exercées du bloc"><?= htmlspecialchars($bc['activitesexercees'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3">
                <label for="modalitesevaluation">Modalités d'évaluation</label>
                <textarea class="form-control" name="modalitesevaluation" id="modalitesevaluation" rows="5" placeholder="Modalités d'évaluation du bloc"><?= htmlspecialchars($bc['modalitesevaluation'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3">
                <label for="criteresevaluation">Critères d'évaluation</label>
                <textarea class="form-control" name="criteresevaluation" id="criteresevaluation" rows="5" placeholder="Critères d'évaluation du bloc"><?= htmlspecialchars($bc['criteresevaluation'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-2">
            <input type="submit" class="btn btn-primary " value="Enregistrer"/>
        </div>
    </form>

</div>

<div class="card py-2 px-4 my-2">
    <p class="fs-4 mb-4">
        <span class="border-bottom border-2 pb-1 ">Compétences</span>
        <button type="button" data-toggle="modal" data-target="#ajoutercompetence" class="btn btn-primary float-end">Ajouter une compétence</button>
    </p>

    <table class="table table-bordered">
    <?php foreach ($bc['competences'] as $competence) : ?>
        <tr>
            <td><span class="text-uppercase"><?= htmlspecialchars($competence['code'], ENT_QUOTES, 'UTF-8') ?></span> - <span class="text-capitalize"><?= htmlspecialchars($competence['actionobservable'], ENT_QUOTES, 'UTF-8') ?></span></td>
            <td style="width: 25%;">
                <button class="btn btn-primary" onclick="editCompetence(<?= htmlspecialchars(json_encode($competence), ENT_QUOTES, 'UTF-8'); ?>)">Modifier</button>
                <button class="btn btn-danger" onclick="setItemToDelete(<?= $competence['idcompetence'] ?>)" >Supprimer</button>
            </td>
        </tr>

    <?php endforeach; ?>
  </table>
</div>




<!-- Modal ajouter competence -->
<div class="modal fade" id="ajoutercompetence" tabindex="-1" role="dialog" aria-labelledby="ajoutercompetenceLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajoutercompetenceLabel">Ajouter une compétence</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajoutercompetence-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="idbloccompetence" id="idbloccompetence" value="<?= htmlspecialchars($bc['idbloccompetence'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="codecompetence">Code de la compétence :</label>
                    <input class="form-control" name="code"  id="codecompetence" required/>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="actionobservable">Action observable</label>
                    <input class="form-control" type="text" name="actionobservable"  id="actionobservable" required/>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="ressourcesmobilisees">Ressources mobilisées</label>
                    <input class="form-control" type="text" name="ressourcesmobilisees"  id="ressourcesmobilisees" required/>
                </div>
            </div>
            
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="finalitesatteignables">Finalités atteignables</label>
                    <input class="form-control" type="text" name="finalitesatteignables"  id="finalitesatteignables" required/>
                </div>
            </div>
            <input type="submit" class="btn btn-primary float-end" value="Ajouter"/>

        </form>
      </div>
      
    </div>
  </div>
</div>





<!-- Modal modifier competence -->
<div class="modal fade" id="modifiercompetence" tabindex="-1" role="dialog" aria-labelledby="modifiercompetenceLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifiercompetenceLabel">Modifier une compétence</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modifiercompetence-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="idcompetence" id="idcompetence">
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="codecompetence">Code de la compétence :</label>
                    <input class="form-control" name="code"  id="codecompetence" required/>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="actionobservable">Action observable</label>
                    <input class="form-control" type="text" name="actionobservable"  id="actionobservable" required/>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="ressourcesmobilisees">Ressources mobilisées</label>
                    <input class="form-control" type="text" name="ressourcesmobilisees"  id="ressourcesmobilisees" required/>
                </div>
            </div>
            
            <div class="form-row">
                <div class="col-md mb-3">
                    <label for="finalitesatteignables">Finalités atteignables</label>
                    <input class="form-control" type="text" name="finalitesatteignables"  id="finalitesatteignables" required/>
                </div>
            </div>
            <input type="submit" class="btn btn-primary float-end" value="Enregistrer"/>

        </form>
      </div>
      
    </div>
  </div>
</div>


















<script src="public/js/modifierCompetenceScript.js"></script>
