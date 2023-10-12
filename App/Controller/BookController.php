<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CommentRepository;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Rating;
use App\Tools\FileTools;
use App\Repository\TypeRepository;
use App\Repository\AuthorRepository;
use App\Repository\RatingRepository;
use PDO;

class BookController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'show':
                        $this->show();
                        break;
                    case 'add':
                        $this->add();
                        break;
                    case 'edit':
                        $this->edit();
                        break;
                    case 'delete':
                        $this->delete();
                        break;
                    case 'list':
                        $this->list();
                        break;
                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                        break;
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
    /*
    Exemple d'appel depuis l'url
        ?controller=book&action=show&id=1
    */
    protected function show()
    {
        $errors = [];

        try {
            if (isset($_GET['id'])) {

                $id = (int)$_GET['id'];
                // Charger le livre par un appel au repository findOneById
                $bookRepository = new BookRepository();
                $book = $bookRepository->findOneById($id);

                if ($book) {
                    // créer une nouvelle instance de CommentRepository
                    // créer une nouvelle instance de commentaire en settant le book id et l'id de l'utilisateur connecté (User::getCurrentUserId())
                    $commentRepository = new CommentRepository();
                    $comment = new Comment(); 
                    $comment->setBookId($book->getId());
                    $comment->setUserId(User::getCurrentUserId());

                    if (isset($_POST['saveComment'])) {
                        if (!User::isLogged()) {
                            throw new \Exception("Accès refusé");
                        }

                        // appeler la méthode hydrate de l'objet $comment en passant le tableau $_POST
                        $comment->hydrate($_POST);
                        
                        // verifier que le commentaire est valide en appelant la commande validate
                        $errors = $comment->validate();
                        
                        if (empty($errors)) {
                            // si il n'y a pas d'erreur, alors appeler la méthode persist de l'objet commentRepository en passant $comment
                            $commentRepository->persist($comment);
                        }
                    }
                    
                    $ratingRepository = new RatingRepository();
                    $rating = $ratingRepository->findOneByBookIdAndUserId($id, User::getCurrentUserId());

                    if (!$rating) {
                        $rating = new Rating();
                        $rating->setBookId($book->getId());
                        $rating->setUserId(User::getCurrentUserId());
                    }

                    if (isset($_POST['saveRating'])) {
                        if (!User::isLogged()) {
                            throw new \Exception("Accès refusé");
                        }

                        // appeler la méthode hydrate en passant le tableau $_POST
                        $rating->hydrate($_POST);
                        
                        // verifier que la note est valide
                        $errors = $rating->validate();
                        
                        if (empty($errors)) {
                            // si il n'y a pas d'erreur, alors appeler la méthode persist de l'objet commentRepository en passant $comment
                            $ratingRepository->persist($rating);
                        }
                    }

                    // récupérer les commentaires existants
                    $comments = $commentRepository->findAllByBookId($id);

                    // récupérer les notes existants
                    $averageRate = $ratingRepository->findAverageByBookId($id);
                    

                    // remplacer petit à petit les valeurs 
                    $this->render('book/show', [
                        'book' => $book,
                        'comments' => $comments,
                        'newComment' => $comment,
                        'rating' => $rating,
                        'averageRate' => $averageRate,
                        'errors' => $errors,
                    ]);
                } else {
                    $this->render('errors/default', [
                        'error' => 'Livre introuvable'
                    ]);
                }
            } else {
                throw new \Exception("L'id est manquant en paramètre");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function add()
    {
        $this->add_edit();
    }

    protected function edit()
    {
        try {
            if (isset($_GET['id'])) {
                $this->add_edit((int)$_GET['id']);
            } else {
                throw new \Exception("L'id est manquant en paramètre");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }


    protected function add_edit($id = null)
    {
        try {
            // Cette action est réservée aux administrateurs
            if (!User::isLogged() || !User::isAdmin()) {
                throw new \Exception("Accès refusé");
            }

            $bookRepository = new BookRepository();
            $typeRepository = new TypeRepository();
            $authorRepository = new AuthorRepository();
            $types = [];
            $authors = [];

            $addOrEdit = "";

            $errors = [];

            // Si on n'a pas d'id, on est dans le cas d'une création
            if (is_null($id)) {
                $book = new Book();
                $addOrEdit = "Ajouter";
            } else {
                // Si on a un id, il faut récupérer le livre
                $book = $bookRepository->findOneById($id);
                if (!$book) {
                    throw new \Exception("Le livre n'existe pas");
                }
                $addOrEdit = "Modifier";

                // Récupération des types
                $types = $typeRepository->findAll();

                // Récupération des auteurs
                $authors = $authorRepository->findAll();
            }

            if (isset($_POST['saveBook'])) {
                // Envoyer les données POST à la méthode hydrate de l'objet $book
                $book->hydrate($_POST);
                

                // Appeler la méthode validate de l'objet book pour récupérer les erreurs (titre vide)
                $errors = $book->validate();

                // Si pas d'erreur, on peut traiter l'upload de fichier
                if (empty($errors)) {
                    $fileErrors = [];
                    // On lance l'upload de fichier
                    if (isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] !== '') {
                        //appeler la méthode static uploadImage de la classe FileTools et stocker le résultat dans $res
                        $res = FileTools::uploadImage(_BOOKS_IMAGES_FOLDER_, $_FILES['file']);

                        if (empty($res['errors'])) {
                            // décommenter cette ligne
                            $book->setImage($res['fileName']);
                        } else {
                            $fileErrors = $res['errors'];
                        }
                    }
                    if (empty($fileErrors)) {
                        // Si pas d'erreur, on appelle persist de bookRepository en passant $book
                        $bookRepository->persist($book);

                        // On redirige vers la page du livre (avec header location)
                        header('Location: index.php?controller=book&action=show&id=' . $book->getId());
                        exit();
                    } else {
                        $errors = array_merge($errors, $fileErrors);
                    }
                }
            }
            $this->render('book/add_edit', [
                'book' => $book,
                'types' => $types,
                'authors' => $authors,
                'pageTitle' => "{$addOrEdit} un livre",
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
}


    protected function list()
    {

        $bookRepository = new BookRepository;

        // On récupère la page courante, si page de page on met à 1
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
        } else {
            $page = 1;
        }

        // récupérer tous les livres (avec pagination plus tard)
        $res = $bookRepository->findAll(_ITEM_PER_PAGE_, $page);
        
        // pour la pagination, on a besoin de connaitre le nombre total de livres
        $nbrBooks = $bookRepository->count();

        // pour la pagination on a besoin de connaitre le nombre de pages
        $nbrPages = ceil($nbrBooks/_ITEM_PER_PAGE_);


        $this->render('book/list', [
            'books' => $res,
            'totalPages' => $nbrPages,
            'page' => $page,
        ]);
    }


    protected function delete()
    {
        try {
            // Cette action est réservé aux admin
            if (!User::isLogged() || !User::isAdmin()) {
                throw new \Exception("Accès refusé");
            }

            if (!isset($_GET['id'])) {
                throw new \Exception("L'id est manquant en paramètre");
            }
            $bookRepository = new BookRepository();

            $id = (int)$_GET['id'];

            $book = $bookRepository->findOneById($id);

            if (!$book) {
                throw new \Exception("Le livre n'existe pas");
            }
            if ($bookRepository->removeById($id)) {
                // On redirige vers la liste de livre
                header('location: index.php?controller=book&action=list&alert=delete_confirm');
            } else {
                throw new \Exception("Une erreur est survenue l'ors de la suppression");
            }

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
