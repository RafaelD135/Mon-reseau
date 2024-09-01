<?php if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($_SESSION['LOGIN_ERROR_MESSAGE']);
        unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['SUCCESS_MESSAGE'])) : ?>
    <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($_SESSION['SUCCESS_MESSAGE']);
        unset($_SESSION['SUCCESS_MESSAGE']); ?>
    </div>
<?php endif; ?>

<form method="POST" action="php/code_php/submit_post.php" enctype="multipart/form-data" class="card mb-4 p-3">
    <h5 class="card-title mb-3">Publier un nouveau post</h5>
    <div class="mb-3">
        <label for="image_post" class="form-label">Ajouter une image</label>
        <input type="file" class="form-control" id="image_post" name="image_post">
    </div>
    <div class="mb-3">
        <label for="text_post" class="form-label">Texte du post</label>
        <textarea class="form-control" id="text_post" name="text_post" rows="3" placeholder="Écrivez quelque chose..."></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Publier</button>
</form>
