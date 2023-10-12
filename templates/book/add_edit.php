<?php 
require_once _TEMPLATEPATH_ . '\header.php';

use App\Repository\AuthorRepository;
use App\Repository\TypeRepository ;

$typeRepository = new TypeRepository();
$types = $typeRepository->findAll();

$authorRepository = new AuthorRepository();
$authors = $authorRepository->findAll();


?>

<h1><?= $pageTitle; ?></h1>



<?php foreach ($errors as $error) { ?>
    <div class="alert aslert-danger">
        <?=$error; ?>
    </div>
<?php } ?>


<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title" class="form-label">Titre</label>
        <input type="text" class="form-control " id="title" name="title" value="">

    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>

    <!-- Attention, cette liste doit être récupérer avec une requête-->
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select name="type_id" id="type" class="form-select">
        <?php foreach ($types as $type): ?>
                <option value="<?= $type->getId(); ?>"><?= $type->getName(); ?></option>
        <?php endforeach; ?>
        </select>
    </div>

    <!-- Attention, cette liste doit être récupérer avec une requête-->
    <div class="mb-3">
        <label for="author" class="form-label">Auteur</label>
        <select name="author_id" id="author" class="form-select">
        <?php foreach ($authors as $author): ?>
            <option value="<?= $author->getId(); ?>"><?= $author->getLastName(); ?></option>
        <?php endforeach; ?>
        </select>
    </div>

    <input type="hidden" name="image" value="">
    <div class="mb-3">
        <label for="file" class="form-label">Image</label>
        <input type="file" name="file" id="file" class="form-control ">
    </div>

    <input type="submit" name="saveBook" class="btn btn-primary" value="Enregistrer">

</form>


<?php require_once _TEMPLATEPATH_ . '\footer.php'; ?>