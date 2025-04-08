<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$maxLength = 0;
foreach ($ue['bloccompetences'] as $item) {
    if (isset($item['competences']) && is_array($item['competences'])) {
        $currentLength = count($item['competences']);
        if ($currentLength > $maxLength) {
            $maxLength = $currentLength;
        }
    }
}
?>

<div class="card my-2 py-2 px-4">
  <p class="fs-4 mb-4"><span class="border-bottom border-2 pb-1">Informations générales</span></p>
 
  <form id="form-infogenerale">
    <div class="form-row">
      <div class="col-md-2 mb-3">
        <label for="code">Code</label>
        <input class="form-control" name="code" id="code" required placeholder="code ex. TC_7_1" value="<?= htmlspecialchars($ue['code'], ENT_QUOTES, 'UTF-8') ?>">
      </div>

      <div class="col-md-6 mb-3">
        <label for="libelle">Unité d'enseignement</label>
        <input class="form-control" name="libelle" id="libelle" required placeholder="Unité d'enseignement" value="<?= htmlspecialchars($ue['libelle'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
      </div>

      <div class="col-md-4 mb-3">
        <label for="ects">Nombre d'ECTS</label>
        <input class="form-control" name="ects" id="ects" required placeholder="nombre d'ects" value="<?= htmlspecialchars($ue['ects'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
      </div>
    </div>

    <div class="form-row">
      <div class="col-md mb-3">
        <label for="description">Pourquoi cette UE ?</label>
        <textarea class="form-control" name="description" id="description" rows="5" maxlength="700" placeholder="description d'ue"><?= htmlspecialchars($ue['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <small  class="form-text text-muted text-end">
              <span id="currentLength">0</span>/700
        </small>
      </div>
    </div>

    <div class="form-row">
      <div class="col-md mb-3">
        <label  for="responsable">Responsable :</label>
        <select class="custom-select" name="responsable"  id="responsable" <?= checkAccess('administrateur') ? "" : "disabled" ?>>
          <option value=""></option>
          <?php foreach ($responsables as $responsable) : ?>
          <option value="<?= $responsable['id'] ?>" <?= $responsable['id'] === $ue["responsable"] ? "selected" : "" ?> ><?= htmlspecialchars($responsable["nomresponsable"], ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>

      </div>
    </div>


  </form>

  <p class="fs-4 mb-1"><span class="border-bottom border-2 pb-1">Compétences</span></p>
  <p style="margin-top:0px;font-size: 12px;" >Parmi les compétences visées par la formation, lesquelles sont développées dans cette UE ?</p>


  <div class="form-row">
    <div class="col-md mb-3">
      <label  for="ajouterBC">Ajouter un bloc de Compétences :</label>
      <select class="custom-select col-sm-8"  id="ajouterBC">
        <?php foreach ($bcOptions as $bcOption) : ?>
        <option value="<?= $bcOption['idbloccompetence'] ?>"><?= htmlspecialchars($bcOption['code'] . ' - ' . $bcOption['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>

      <button class="float-end btn btn-primary" id="addBCBtn" type="button"  onclick="addbloccompetence(<?= $ue['idmoduleannee'] ?>)">Ajouter</button>
    </div>
  </div>
  <div class="card  p-2">
  <div class="row">
    <div class="col">
      <table style="border-collapse: collapse;" cellspacing="0" cellpadding="0" >
        <tr id="competency-table" >
          <?php foreach ($ue['bloccompetences'] as $blocCompetence) : ?>
            <td>
              <table class="bloc-comp">
                <tr><td><button type="button" onclick="supprimerBlocCompetence(<?= $ue['idmoduleannee'] ?>,<?= $blocCompetence['id'] ?>)" class="btn btn-danger except">&times;</button></td></tr>
                <tr ><td id="<?= $blocCompetence['id'] ?>" data-bs-toggle="tooltip" data-bs-title="<?= htmlspecialchars($blocCompetence['libelle'], ENT_QUOTES, 'UTF-8') ?? ' ' ?>" class="bloc-header <?= $blocCompetence['actif'] ? 'bloc-actif' : ''?>"><?= htmlspecialchars($blocCompetence['code'], ENT_QUOTES, 'UTF-8') ?></td></tr>
                <?php $index = 0;
                foreach ($blocCompetence['competences'] as $competence) :
                    $index++; ?>
                  <tr><td id="<?=$competence['id']?>"  data-bs-toggle="tooltip" data-bs-title="<?= $competence['libelle'] ?>" class="comp <?= $competence['etat'] ?>"> <?= htmlspecialchars($competence['code'], ENT_QUOTES, 'UTF-8') ?> </td></tr>
                <?php endforeach; ?>
                  <?php for ($i = $index; $i < $maxLength; $i++) :?>
                    <tr><td class="vide"></td></tr>
                  <?php endfor; ?>
              </table>
            </td>
          <?php endforeach; ?>
        </tr>    
      </table>      
    </div>
    <div  class="col-2 align-self-end">
      <div class="d-flex align-items-center gap-1 ">
        <div class="label-bc-notactif">BC1</div>
        <div class=" label-text">L'UE ne contribue pas à ce bloc de compétences</div>
      </div>
      <div class="d-flex align-items-center gap-1" >
        <div class="label-bc-actif">BC1</div>
        <div class=" label-text ">L'UE contribue à ce bloc de compétences</div>
      </div>
      <div class="d-flex align-items-center gap-1 ">
        <div class="label-c-nad">C1</div>
        <div class=" label-text">Compétence non adressée dans cette UE </div>
      </div>
      <div class="d-flex align-items-center gap-1 ">
        <div class="label-c-meo">C1</div>
        <div class=" label-text">Compétence mise en œuvre dans cette UE</div>
      </div>
      <div class="d-flex align-items-center gap-1 ">
        <div class="label-c-ens">C1</div>
        <div class=" label-text">Compétence enseignée dans cette UE</div>
      </div>
      <div class="d-flex align-items-center gap-1 ">
        <div class="label-c-eva">C1</div>
        <div class=" label-text">Compétence évaluée dans cette UE</div>
      </div>
      <div class="d-flex align-items-center gap-1 ">
        <div class="label-c-enseva">C1</div>
        <div class=" label-text">Compétence enseignée et évaluée dans cette UE</div>
      </div>
    </div>
  </div> 
  </div>
  <div class="d-flex justify-content-end mt-2">
    <button type="button" id="bcModifyBtn" onclick="updateUE(<?= $ue['idmoduleannee'] ?>)" class="btn btn-primary ">Enregistrer</button>
  </div>
</div>
<div class="card py-2 px-4 my-2">
  <p class="fs-4 mb-4">
    <span class="border-bottom border-2 pb-1 ">ECUES</span>
    <a type="button" href="index.php?page=ajouterecue&id=<?=$ue['idmoduleannee']?>" class="btn btn-primary float-end">Ajouter une ECUE</a>
  </p>

  <table class="table table-bordered">
      <?php foreach ($ue['matiereenseignee'] as $ecue) : ?>
        <tr>
          <td class=""><?= $ecue['libelle'] ?></td>
          <td style="width: 25%;">
            <a type="button" href="index.php?page=modifierecue&id=<?=$ecue['idmatiereenseignee'] ?>" class="btn btn-primary text-white ">Modifier</a>
            <button type="button" onclick="setItemToDelete(<?= $ue['idmoduleannee'] ?>,<?=$ecue['idmatiereenseignee'] ?>)" class="btn btn-danger ">Supprimer</button>
          </td>
        </tr>

      <?php endforeach; ?>
  </table>
</div>



<script src="public/js/libs/bootstrap.bundle.min.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
<script src="public/js/modifierueScript.js"></script>
