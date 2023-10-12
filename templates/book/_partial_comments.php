<?php 

use App\Entity\Comment ;
use App\Entity\Book ;
use App\Repository\CommentRepository ;

/** @var CommentRepository $commentRepository */
/** @var Comment $comments */
/** @var Book $book */

?>


<?php foreach ($errors as $error) { ?>
    <div class="alert aslert-danger">
        <?=$error; ?>
    </div>
<?php } ?>


<div class="col-md-12 col-lg-8 col-xl-8">
    <div class="card">
        <div class="card-body p-4">
            <h2>Commentaires</h2>
            <div class="row">
                <div class="col">
                    <div class="d-flex flex-start bg-body-tertiary p-2 my-1">
                        
                    <!-- L'affichage en css est "étonnant" mais je n'ai pas pris le temps de regarder car pas vraiment l'objectif ici -->
                    <!-- En revanche l'affichage des données est bien présent -->
                        <?php foreach ($comments as $comment): ?>
                            <div class="flex-grow-1 flex-shrink-1">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">
                                            <span class="small">
                                                <?= $comment->getUser()->getFirstName(); echo " {$comment->getUser()->getLastName()}"; ?> 
                                                - Le 
                                                <?= $comment->getCreatedAt()->format('Y-m-d H:i'); ?>
                                            </span>
                                        </p>
                                    </div>
                                    <p class="small mb-0">
                                        <?= $comment->getComment(); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>


                </div>
            </div>

            <form method="POST">
                <div class="mb-3">
                    <label for="comment" class="form-label">Commenter</label>
                    <textarea type="text" class="form-control " id="comment" name="comment" rows="5"></textarea>
                </div>



                <input type="submit" name="saveComment" class="btn btn-primary" value="Commenter">

            </form>

        </div>
    </div>
</div>