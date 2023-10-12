<?php

use App\Entity\User;

require_once _TEMPLATEPATH_ . '\header.php';
/** @var  App\Entity\Author $author */
?>
<?php foreach ($errors as $error) : ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error ?>
    </div>
<?php endforeach ?>

<div class="row align-items-start g-5 py-5 my-5 bg-body-tertiary">
    <?php echo $author->getLastName()?>
    <?php echo $author->getFirstName()?>
    <?php echo $author->getDisplayName()?>
</div>

<?php require_once _TEMPLATEPATH_ . '\footer.php'; ?>