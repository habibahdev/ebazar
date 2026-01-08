<?php
namespace App\Controllers;

use App\Models\User;
use App\Utils\Controller;

/**
 * gestion de la connexion des utilisateurs
 */
class LoginController extends Controller
{
    /**
     * affichage du formulaire de connexion
     *
     * @return void
     */
    public function index()
    {
        $this->renderView('login/login');
    }

    /**
     * effectue la connexion de l'utilisateur
     *
     * @return void
     */
    public function login()
    {
        extract($_POST);
        if (empty($csrf) || !$this->checkToken($csrf)) {
            $this->setMessage('error', 'Erreur inconnue');
            return $this->redirectTo('/login');
        }

        $user = User::getByEmail($email);
        if (!$user || !password_verify($password, $user->password)) {
            $this->setMessage('error', 'Identifiants invalides');
            return $this->redirectTo('/login');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['email'] = $user->email;
        $_SESSION['is_admin'] = $user->is_admin;

        if (!empty($_SESSION['redirect_after'])) {
            $url = $_SESSION['redirect_after'];
            unset($_SESSION['redirect_after']);
            return $this->redirectTo($url);
        }
        $this->refreshToken();
        return $this->redirectTo('/');
    }
}