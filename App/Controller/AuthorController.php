<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\UserRepository;

class AuthorController extends Controller
{

    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'add':
                        $this->add();
                        break;
                    case 'edit':
                        $this->edit();
                        break;
                    case 'show':
                        $this->show();
                        break;
                    case 'list':
                        $this->list();
                        break;
                    case 'delete':
                        $this->delete();
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

    protected function show()
    {
        $errors = [];

        try {
            if (isset($_GET['id'])) {

                $id = (int)$_GET['id'];
                // Charger le livre par un appel au repository findOneById
                $auteurRepository = new AuthorRepository();
                $auteur = $auteurRepository->findOneById($id);

                if (!$auteur) {
                    $this->render('errors/default', [
                        'error' => 'auteur introuvable'
                    ]);
                } else {
                    $this->render('author/show', [
                        'author' => $auteur,
                        'errors' => $errors
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

    protected function list()
    {
        $errors = [];

        try {
            // Charger le livre par un appel au repository findOneById
            $auteurRepository = new AuthorRepository();
            $auteurs = $auteurRepository->findAll();

            if (!$auteurs) {
                $this->render('errors/default', [
                    'error' => 'Aucune auteur'
                ]);
            } else {
                $this->render('author/list', [
                    'authors' => $auteurs,
                    'errors' => $errors
                ]);
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
            // Cette action est réservé aux admin
            if (!User::isLogged() || !User::isAdmin()) {
                throw new \Exception("Accès refusé");
            }
            $auteurRepository = new AuthorRepository();
            $errors = [];
            // Si on a pas d'id on est dans le cas d'une création
            if (is_null($id)) {
                $auteur = new Author();
            } else {
                // Si on a un id, il faut récupérer l'auteur
                $auteur = $auteurRepository->findOneById($id);
                if (!$auteur) {
                    throw new \Exception("Le livre n'existe pas");
                }
            }


            if (isset($_POST['saveAuthor'])) {
                //envoyer les données post à la méthode hydrate de l'objet $book
                $auteur->hydrate($_POST);
                //appeler la méthode validate de l'objet book pour récupérer les erreurs (titre vide)
                $err = $auteur->validate();

                $errors = array_merge($errors, $err);

                // Si pas d'erreur on peut traiter l'upload de fichier
                if (empty($errors)) {

                    // si pas d'erreur alors on appelle persit de bookRepository en passant $book
                   $auteurRepository->persist($auteur);
                   header('Location: /index.php?controller=author&action=show&id=' . $auteur->getId());
                    // On redirige vers la page du livre (avec header location)
                }
            }

            $this->render('author/add_edit', [
                'author' => $auteur,
                'pageTitle' => 'Auteur',
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
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
            $auteurRepository = new AuthorRepository();

            $id = (int)$_GET['id'];

            $book = $auteurRepository->findOneById($id);

            if (!$book) {
                throw new \Exception("Le livre n'existe pas");
            }
            if ($auteurRepository->removeById($id)) {
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
