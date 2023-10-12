<?php 

use App\Entity\Book ;
require_once _ROOTPATH_ . '\templates\header.php';


/** @var Book[] $books */
/** @var int $totalPages */
/** @var int $page */



?>

<h1>Liste complète</h1>

<div class="row text-center mb-3">

    <?php 
    foreach ($books as $book) {

    echo "<div class=\"col-md-4 my-2 d-flex\">
        <div class=\"card\">
            <img src=\"{$book->getImagePath()}\" class=\"card-img-top\" alt=\"{$book->getTitle()}\">
            <div class=\"card-body\">
                <h5 class=\"card-title\">{$book->getTitle()}</h5>
                <p class=\"card-text\">{$book->getDescription()}</p>
                <a href=\"index.php?controller=book&amp;action=show&amp;id={$book->getId()}\" class=\"btn btn-primary\">Lire la suite</a>
            </div>
        </div>
    </div>"; } ?>

</div>


<div class="row">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <!-- Gestion de la pagination -->
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="index.php?controller=book&action=list&page=<?= $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>






<?php require_once _ROOTPATH_ . '\templates\footer.php'; ?>