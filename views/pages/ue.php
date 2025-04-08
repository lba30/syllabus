<div class="card my-2 py-2 px-4">
    <p class="fs-4 mb-4"><span class="border-bottom border-2 fw-bold pb-1">Les unités d’enseignements</span></p>
    
    <label for="yearFilter">Année scolaire :</label>
    <select class="custom-select" id="yearFilter">
        <option value="" selected="selected" >Sélectionnez une année</option>
        <?php foreach ($years as $year) : ?>
        <option value="<?= htmlspecialchars($year['idanneescolaire']) ?>"><?= htmlspecialchars($year['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
    </select>
        
    <br>
        
    <label for="cycleFilter">Formation :</label>
    <select class="custom-select"  id="cycleFilter" disabled>
        <option value="">Tous</option>
    </select>
    
    <br>
        
    <label for="departementFilter">Département:</label>
    <select class="custom-select"  id="departementFilter" disabled>
        <option value="">Sélectionnez une département</option>
    </select>
    
    <br>
    
    <label for="semestreFilter">Semestre :</label>
    <select class="custom-select mb-4" id="semestreFilter" disabled>
        <option value="">Tous</option>
    </select>
    <br>
    
    <?php if (checkAccess('interne')) : ?>
    <div class="form-check">
        <input class="form-check-input" style="display: none;" type="checkbox" id="infopersoFilter" checked disabled>
    </div>
    <?php endif; ?>
    <br>
    
            
    <div class="card mb-4">    
        <div class="card-body" id="dataue-container">
            <table id="dataue">
                <thead>
                    <th> Libellé </th>
                    <th> Code </th>
                    <th> Actions</th>
                </thead>
                <tbody id="dataue-body"></tbody>
            </table>
        </div>
    </div>
    
        
</div>






    
<script>

    const role = "<?php echo $_SESSION['role'] ?? "externe"; ?>";

</script>
<script src="public/js/uescripts.js?v=3"></script>
