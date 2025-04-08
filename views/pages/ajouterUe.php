
<div class="card my-2 py-2 px-4">
    <p class="fs-4 mb-4"><span class="border-bottom border-2 pb-1">Ajouter une UE</span></p>

    <form id="ajouterUEForm">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="form-row">
            <div class="col-md mb-3">
                <label for="yearFilter">Année scolaire :</label>
                <select class="custom-select" name="yearFilter" id="yearFilter" required>
                    <?php foreach ($years as $year) : ?>
                    <option value="<?= htmlspecialchars($year['idanneescolaire'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($year['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3">
                <label for="cycleFilter">Formation :</label>
                <select class="custom-select" name="cycleFilter"  id="cycleFilter" required>
                </select>
            </div>
            <div class="col-md mb-3">
                <label for="periodeformationFilter">Période de formation :</label>
                <select class="custom-select" name="periodeformationFilter"  id="periodeformationFilter" required>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3">
                <label for="departementFilter">Département :</label>
                <select class="custom-select" name="departementFilter"  id="departementFilter" >
                </select>
            </div>
            
            <div class="col-md mb-3">
                <label for="optionFilter">Option :</label>
                <select class="custom-select" name="optionFilter"  id="optionFilter">
                    </select>
                </div>
        </div>
        
        <hr>

        <div class="form-row">
            <div class="col-md-2 mb-3">
                <label for="code">Code</label>
                <input class="form-control" name="code" id="code" placeholder="code ex. TC_7_1" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="libelle">Unité d'enseignement</label>
                <input class="form-control" name="libelle" id="libelle" placeholder="Unité d'enseignement" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="ects">Nombre d'ECTS</label>
                <input class="form-control" name="ects" id="ects" placeholder="nombre d'ects" required >
            </div>
        </div>

        <div class="form-row">
            <div class="col-md mb-3">
                <label for="description">Pourquoi cette UE ?</label>
                <textarea class="form-control" name="description" id="description" rows="5" maxlength="700" placeholder="description d'ue"></textarea>
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
                    <option value="<?= htmlspecialchars($responsable['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($responsable["nomresponsable"], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
        </div>
        
        <input type="submit" class="btn btn-primary float-end" value="Ajouter">               
       


    </form>


</div>


<script src="public/js/ajouterueScript.js"></script>
