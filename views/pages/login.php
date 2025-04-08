<div class="card my-2 py-2 px-4">
    <h2>Se connecter</h2>
    <?php if (isset($_SESSION['error'])) : ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form id="formLogin" method="post" action="index.php?page=login" >
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="form-group">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>


<script>
    $('#formLogin').on('submit',(e)=>{
        $("#formLogin button").prop("disabled",true);
    })
</script>
