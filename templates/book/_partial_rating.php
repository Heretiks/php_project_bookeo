
<?php

use App\Entity\Book;
use App\Entity\Rating;

/** @var Book $book */
/** @var Rating $rating */

?>


<div class="card">
    <div class="card-body p-4">

        <div class="row mb-3">
            <h2>Note des utilisateurs</h2>
            <div class="row align-items-center justify-content-center">
                <div class="rate col-6">

                    <?php for ($i = 5; $i >= 1; $i--) { ?>
                        <input disabled="disabled" type="radio" id="avgstar<?= $i ?>" name="avgrate" value="<?= $i ?>" <?= ($averageRate == $i) ? 'checked' : '' ?>>
                        <label for="avgstar<?= $i ?>" title="<?= $i ?> étoiles"><?= $i ?> étoiles</label>
                    <?php }; ?>
                    
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <h3>Noter ce livre</h3>
 
            <form method="POST">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-4 py-2">
                            <label for="rate" class="form-label">Votre note :</label>

                        </div>
                        <div class="col-8">
                            <div class="rate enabled">

                                <?php for ($i = 5; $i >= 1; $i--) { ?>
                                    <input type="radio" id="star<?= $i ?>" name="rate" value="<?= $i ?>" <?= ($rating && $rating->getRate() == $i) ? 'checked' : '' ?>>
                                    <label for="star<?= $i ?>" title="<?= $i ?> étoile<?= ($i > 1) ? 's' : '' ?>"><?= $i ?> étoile<?= ($i > 1) ? 's' : '' ?></label>
                                <?php }; ?>

                            </div>
                        </div>
                    </div>
                </div>

                



                <div class="mb-3">
                    <input type="submit" name="saveRating" class="btn btn-primary form-control " value="Noter">
                </div>
                

            </form>
        </div>



    </div>
</div>
