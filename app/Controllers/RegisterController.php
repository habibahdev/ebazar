<?php
namespace App\Controllers;

use App\Models\User;
use App\Utils\Controller;

/**
 * gestion de l'inscription d'un utilisateur
 */
class RegisterController extends Controller
{
    /**
     * affiche le formulaire d'inscription
     *
     * @return void
     */
    public function index()
    {
        $this->renderView('register/register');
    }

    /**
     * effectue l'enregistrement de l'utilisateur
     *
     * @return void
     */
    public function register()
    {
        extract($_POST);
        if (empty($csrf) || !$this->checkToken($csrf)) {
            $this->setMessage('error', 'Erreur inconnue');
            return $this->redirectTo('/register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setMessage('error', 'Adresse email invalides');
            return $this->redirectTo('/register');
        }

        if (mb_strlen($password) < 4) {
            $this->setMessage('error', 'Mot de passe trop court. Au moins 4 caractères');
            return $this->redirectTo('/register');
        }

        if (mb_strlen($password) > 12) {
            $this->setMessage('error', 'Mot de passe trop long. Au plus 12 caractères');
            return $this->redirectTo('/register');
        }

        if (User::getByEmail($email)) {
            $this->setMessage('error', 'Adresse email déjà utilisée');
            return $this->redirectTo('/register');
        }

        User::createUser($email, $password);

        $this->refreshToken();
        $this->setMessage('success', 'Connectez-vous');
        return $this->redirectTo('/login');
    }
}
