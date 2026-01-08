<?php
namespace App\Controllers;

use App\Utils\Controller;

/**
 * gestion de la déconnexion des utilisateurs
 */
class LogoutController extends Controller
{
    /**
     * déconnecte un utilisateur
     *
     * @return void
     */
    public function logout()
    {
        session_destroy();
        $this->redirectTo('/');
    }
}