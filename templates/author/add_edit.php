<?php require_once _TEMPLATEPATH_ . '\header.php'; ?>

<h1><?= $pageTitle; ?></h1>

<?php foreach ($errors as $error) : ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error ?>
    </div>
<?php endforeach ?>


<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="last_name" class="form-label">Nom</label>
        <input type="text" class="form-control " id="last_name" name="last_name" value="<?php echo $author->getLastName() ?>">
    </div>

    <div class="mb-3">
        <label for="first_name" class="form-label">Prénom</label>
        <input type="text" class="form-control " id="first_name" name="first_name" value="<?php echo $author->getFirstName() ?>">
    </div>

    <div class="mb-3">
        <label for="nickname" class="form-label">Surnom</label>
        <input type="text" class="form-control " id="nickname" name="nickname" value="<?php echo $author->getNickname() ?>">
    </div>
    </div>

    <input type="submit" name="saveAuthor" class="btn btn-primary" value="Enregistrer">

</form>


<?php require_once _TEMPLATEPATH_ . '\footer.php'; ?>