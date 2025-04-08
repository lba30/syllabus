<h2 class="mt-4"></h2>

<div class="card my-2 py-2 px-4">
    <p class="fs-4 mb-4"><span class="border-bottom border-2 pb-1">Ajouter l'élémént constitutif de l'UE</span></p>

    <form id="form-info">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="form-row">
            <div class="col-md mb-3">
                <label for="libelle">Libellé</label>
                <input class="form-control" required name="libelle" id="libelle" placeholder="nom du matiére">
            </div>
            <div class="col-md-2 mb-3">
                <label for="libelle">Ordre</label>
                <input class="form-control numberfield" required name="ordre" id="ordre" placeholder="ordre de la matiére" >
            </div>
            <div class="col-md-2 mb-3">
                <label for="coefficient">Coefficient</label>
                <input class="form-control numberfield" required name="coefficient" id="coefficient" placeholder="coefficient de la matiére" >
            </div>
        </div>

        <div class="form-row">
            <div class="col mb-3">
                <label for="nbhcours">Nombre d'heures de cours</label>
                <input class="form-control numberfield" name="nbhcours" id="nbhcours"  >
            </div>
            <div class="col mb-3">
                <label for="nbhtd">Nombre d'heures de td</label>
                <input class="form-control numberfield" name="nbhtd" id="nbhtd"  >
            </div>
            <div class="col mb-3">
                <label for="nbhtp">Nombre d'heures de tp</label>
                <input class="form-control numberfield" name="nbhtp" id="nbhtp"  >
            </div>
        </div>
        <div class="form-row">
            <div class="col mb-3">
                <label for="nbhprojet">Nombre d'heures de projets</label>
                <input class="form-control numberfield" name="nbhprojet" id="nbhprojet" >
            </div>
            <div class="col mb-3">
                <label for="nbhcontrole">Nombre d'heures de contrôles et soutenances </label>
                <input class="form-control numberfield" name="nbhcontrole" id="nbhcontrole"  >
            </div>
            <div class="col mb-3">
                <label for="nbhautonomie">Nombre d'heures de travail personnel</label>
                <input class="form-control numberfield" name="nbhautonomie" id="nbhautonomie">
            </div>
            <div class="col mb-3">
                <label for="nbhautre">Nombre d'heures de travail en autonomie encadré</label>
                <input class="form-control numberfield" name="nbhautre" id="nbhautre"  >
            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3">
                <label  for="responsable">Responsable :</label>
                <select class="custom-select" name="responsable"  id="responsable" <?= checkAccess('administrateur') ? "" : "disabled" ?>>
                <option value=""></option>
                <?php foreach ($responsables as $responsable) : ?>
                <option value="<?= htmlspecialchars($responsable['id'], ENT_QUOTES, 'UTF-8') ?>" ?> <?= htmlspecialchars($responsable["nomresponsable"], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
                </select>

            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3 textfield">
                <label for="contexte">Contexte et enjeux de l'enseignement</label>
                <textarea class="form-control" name="contexte" id="contexte" rows="5" maxlength="1200" placeholder="contexte d'ECUE"></textarea>
                <small  class="form-text text-muted text-end">
                    <span></span>/1200
                </small>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md mb-3">
                <label for="socioenvdimension">Prise en compte des dimensions socio-environnementales :</label>
    
                <select class="form-select" name="socioenvdimension[]" id="socioenvdimension" data-placeholder="Choisissez une ou plusieurs options" multiple>
                    <?php foreach ($onuOptions as $onuOption) : ?>
                        <option value="<?= $onuOption['id'] ?>"><?= htmlspecialchars($onuOption['name'], ENT_QUOTES, 'UTF-8') ?></option>
                    
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3 textfield">
                <label for="prerequis">Prérequis</label>
                <textarea class="form-control" name="prerequis" id="prerequis" rows="2" maxlength="200"></textarea>
                <small  class="form-text text-muted text-end">
                    <span></span>/200
                </small>
            </div>
        </div>

        <hr style="margin:0"/>
        <small style="margin:0">Alignement pédagogique :</small> 
        <p style="margin-bottom:16px;font-size: 10px;" >L’alignement pédagogique, proposé par Biggs (1997, 2004), peut se définir comme la cohérence entre les objectifs d’apprentissage, les activités d’apprentissage et les méthodes d’évaluation de ces apprentissages.</p>
        
        <div class="d-flex justify-content-between lign-items-start flex-grow-1 gap-2">
            <div class="form-row" style="width:100%">
                <div class="col-md mb-3 textfield">
                    <label for="objectif">Objectifs pédagogiques :
                        <p style="font-size: 10px;">(à la fin de cet enseignement, l'étudiant sera capable de …)</p>
                    </label>
                    <textarea class="form-control" name="objectif" id="objectif" rows="10" maxlength="500"></textarea>
                    <small  class="form-text text-muted text-end">
                        <span></span>/500
                    </small>
                </div>
            </div>
            <div class="form-row" style="width:100%">
                <div class="col-md mb-3 textfield">
                    <label for="activites">Activités :
                        <p style="font-size: 10px;">(CM, TD, TP, projet, sortie terrain, etc. )</p>
                    </label>
                    <textarea class="form-control" name="activites" id="activites" rows="10" maxlength="500"></textarea>
                    <small  class="form-text text-muted text-end">
                        <span></span>/1500
                    </small>
                </div>
            </div>
            <div class="form-row" style="width:100%">
                <div class="col-md mb-3 textfield">
                    <label for="evaluation">Évaluations et retours faits aux élèves :
                        <p style="font-size: 10px;">(évaluations qui comptent pour la note ou qui permettent à
                        l'étudiant de se situer, corrigés, feedback personnalisé...)</p>
                    </label>
                    <textarea class="form-control" name="evaluation" id="evaluation" rows="10" maxlength="500"></textarea>
                    <small  class="form-text text-muted text-end">
                        <span></span>/500
                    </small>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3 textfield">
                <label for="plandecours">Plan de cours</label>
                <textarea class="form-control" name="plandecours" id="plandecours" rows="5" maxlength="2000" placeholder="plan de cours"></textarea>
                <small  class="form-text text-muted text-end">
                    <span></span>/2000
                </small>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md mb-3 textfield">
                <label for="ressourcereference">Ressources et références</label>
                <textarea class="form-control" name="ressourcereference" id="ressourcereference" rows="5" maxlength="5000" placeholder="ressources et references de cours"></textarea>
                <small  class="form-text text-muted text-end">
                    <span></span>/5000
                </small>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="button" id="newECUEBtn" onclick="ajouterEcue(<?=$idmodule?>)" class="btn btn-primary ">Ajouter</button>
        </div>
    </form>
</div>

<script src="public/js/ecueScript.js" ></script>
