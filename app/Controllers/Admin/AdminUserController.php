<?php
namespace App\Controllers\Admin;

use App\Models\Ad;
use App\Models\Purchase;
use App\Models\User;
use App\Utils\Controller;

/**
 * Gestion des utilisateurs par l'administrateur
 */
class AdminUserController extends Controller
{
    /**
     * constrcuteur
     */
    public function __construct()
    {
        $this->requireToBeAdmin();
    }

    /**
     * affiche la liste des utilisateurs
     *
     * @return void
     */
    public function index()
    {
        $users = User::getAllUser();
        $this->renderView('admin/user/index', [
            'users' => $users
        ]);
    }

    /**
     * supprime un utilisateur
     *
     * @return void
     */
    public function delete()
    {
        extract($_POST);
        if (empty($csrf) || !$this->checkToken($csrf)) {
            $this->setMessage('error', 'Erreur inconnue');
            return $this->redirectTo('/admin/ad');
        }

        if (empty($id)) {
            $this->setMessage('error', 'Utilisateur introuvable');
            return $this->redirectTo('/admin/user');
        }

        $user = User::getById($id);
        if (!$user) {
            $this->setMessage('error', 'Utilisateur introuvable');
            return $this->redirectTo('/admin/user');
        }

        if ($user->is_admin) {
            $this->setMessage('error', 'Cet utilisateur est l\'administrateur');
            return $this->redirectTo('/admin/user');
        }

        if (Purchase::isUserHasPendingPurchase($id)) {
            $this->setMessage('error', 'L\'utilisateur a des achats/ventes non récpetionnéees');
            return $this->redirectTo('/admin/user');
        }

        //User::disable($id);
        User::delete($id);
        Ad::deleteAdIfNotSoldForUser($id);

        $this->refreshToken();
        $this->setMessage('success', 'Utilisateur supprimé');
        return $this->redirectTo('/admin/user');
    }
}