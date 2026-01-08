<?php
namespace App\Controllers;

use App\Models\Ad;
use App\Models\Photo;
use App\Models\Purchase;
use App\Utils\Controller;

/**
 * gestion de l'espace personnel d'un utilisateur
 */
class UserController extends Controller
{
    /**
     * constructeur
     */
    public function __construct()
    {
        $this->requireToBeLogged();

        if ($this->isAdmin())
        {
            $this->setMessage('error', 'Action non autorisée');
            return $this->redirectTo('/admin');
        }
    }

    /**
     * affiche la liste des annonce d'un utilisateur
     *
     * @return void
     */
    public function ads()
    {
        $ads = Ad::getAdByUser($_SESSION['user_id']);
        $this->renderView('user/ad', [
            'ads' => $ads
        ]);
    }

    /**
     * affiche la liste des achats d'un utilisateur
     *
     * @return void
     */
    public function purchases()
    {
        $purchases = Purchase::purchaseOfBuyer($_SESSION['user_id']);
        foreach ($purchases as $purchase) {
            $purchase->photo = $purchase->ad_id ? Photo::getTheFirstPhtot($purchase->ad_id) : null;
        }
        $this->renderView('user/purchase', [
            'purchases' => $purchases
        ]);
    }

    /**
     * affiche la liste des vente d'un utilisateur
     *
     * @return void
     */
    public function sales()
    {
        $sales = Purchase::saleOfSeller($_SESSION['user_id']);
        foreach ($sales as $sale) {
            $sale->photo = $sale->ad_id ? Photo::getTheFirstPhtot($sale->ad_id) : null;
        }
        $this->renderView('user/sale', [
            'sales' => $sales
        ]);
    }

    /**
     * supprime une annonce non vendue
     *
     * @return void
     */
    public function delete()
    {
        extract($_POST);
        if (empty($csrf) || !$this->checkToken($csrf)) {
            $this->setMessage('error', 'Erreur inconnue');
            return $this->redirectTo('/user/ad');
        }

        if (empty($id)) {
            $this->setMessage('error', 'Annonce introuvable');
            return $this->redirectTo('/user/ad');
        }

        $ad = Ad::getAdById($id);
        if (!$ad) {
            $this->setMessage('error', 'Annonce introuvable');
            return $this->redirectTo('/user/ad');
        }

        if ($ad->user_id !== $_SESSION['user_id']) {
            $this->setMessage('error', 'Action non autorisée');
            return $this->redirectTo('/user/ad');
        }

        if ($ad->sold) {
            $this->setMessage('error', 'Action non autorisée');
            return $this->redirectTo('/user/ad');
        }


        Ad::deleteAdIfNotSold($id);
        $this->refreshToken();
        $this->setMessage('success', 'Annonce supprimée');
        return $this->redirectTo('/user/ad');
    }
}