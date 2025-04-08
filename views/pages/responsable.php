<div class="card my-2 py-2 px-4">
  <p class="fs-4 mb-4">
    <span class="border-bottom border-2 fw-bold pb-1">Les utilisateurs</span>
  </p>

  <div class="form-row">
        <div class="col-md mb-3">
            <label for="roleFilter">Rôle :</label>
            <select class="custom-select" name="roleFilter" id="roleFilter">
                <option value= ""> Tous</option>
                <?php foreach ($roles as $role) : ?>
                <option value="<?= htmlspecialchars($role['idrole']) ?>"><?= htmlspecialchars($role['label'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>


  <div class="card mb-4">
    <div class="card-body">
      <div class="card-body" id="datresponsable-container"></div>
    </div>
  </div>
</div>



<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Modifier Responsable</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editresponsable-form">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <input type="hidden" name="idresponsable" id="editIdResponsable">
          <div class="form-group">
            <label for="editNom">Nom</label>
            <input type="text" name="nomresponsable" class="form-control" id="editNom" required disabled>
          </div>
          <div class="form-group">
            <label for="editContact">Contact</label>
            <input type="email" class="form-control" name="contact" id="editContact" required disabled>
          </div>
          
          <div class="form-group">
            <label for="editRole">Rôle</label>
            <select class="custom-select" name="editRole" id="editRole">
                <?php foreach ($roles as $role) : ?>
                <option value="<?= htmlspecialchars($role['idrole']) ?>"><?= htmlspecialchars($role['label'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <input type="submit" class="btn btn-primary float-end" value="Enregistrer"/>
        </form>
      </div>
    </div>
  </div>
</div>








<script src="public/js/responsablescript.js"></script>
