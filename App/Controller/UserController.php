<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;

class UserController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'register':
                        $this->register();
                        break;
                    case 'delete':
                        // Appeler méthode delete()
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
  
    protected function register()
    {
        try {
            $errors = [];
            $user = new User();
    
            if (isset($_POST['saveUser'])) {
                // Récupérez les données du formulaire
                $userData = $user->createAndHydrate($_POST);
    
                // Validez les données du formulaire
                if (empty($userData->getEmail()) || empty($userData->getPassword()) || empty($userData->getFirstName()) || empty($userData->getLastName())) {
                    $errors[] = "Tout les champs doivent être renseigné !";
                }
    
                // Si aucune erreur n'a été détectée, enregistrez l'utilisateur
                if (empty($errors)) {
                    $userRepo = new UserRepository();
                    $userRepo->persist($userData);

                    // Redirigez l'utilisateur vers une page de confirmation d'inscription ou une autre page appropriée
                    header('Location: index.php');
                }
            }
    
            $this->render('user/add_edit', [
                'user' => $user,
                'pageTitle' => 'Inscription',
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        } 

    }



}
