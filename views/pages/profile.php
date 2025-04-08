<?php if (isset($userData)) : ?>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Profil d'utilisateur</h3>
        </div>
        <div class="card-body">
            <p><strong>Nom et prénom :</strong> <?= htmlspecialchars(str_replace(['.'], ' ', $userData['username']), ENT_QUOTES, 'UTF-8')  ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($userData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Status :</strong> <?= htmlspecialchars($userData['role'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </div>

    <?php if ($userData['role'] === 'administrateur') : ?>
        <div class="mt-5">
            <div class="card">
                <div class="card-header">
                    <h3>Configuration du délai d'expiration de la session</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success'];
                            unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="index.php?page=profile" onsubmit="disableSubmitButton()">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="form-group">
                            <label for="timeout">Expiration de la session (en minutes):</label>
                            <input type="number" name="timeout" id="timeout" class="form-control" 
                                   value="<?= htmlspecialchars($currentTimeout, ENT_QUOTES, 'UTF-8') ?>" required min="1">
                        </div>
                        <button id="submitButton" type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <div class="mt-5">
        <h3>Mes UEs</h3>
        <?php if (!empty($userUEs)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Libellé</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userUEs as $ue) : ?>
                        <tr>
                            <td><?= htmlspecialchars($ue['code'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($ue['libelle'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <a href="index.php?page=modifierue&id=<?= $ue['idmoduleannee'] ?>" class="btn btn-secondary">Modifier</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-muted">Vous n'êtes responsable d'aucune UE pour le moment.</p>
        <?php endif; ?>
    </div>
</div>
<?php else : ?>
<div class="container mt-5">
    <p class="text-danger">No user data found. Please try again later.</p>
</div>
<?php endif; ?>


<script>
    function disableSubmitButton() {
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;
       
    }
</script>
