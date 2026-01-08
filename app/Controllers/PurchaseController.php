<?php
namespace App\Controllers;

use App\Models\Ad;
use App\Models\Purchase;
use App\Utils\Controller;

/**
 * Gestion des achats d'un utilisateur
 */
class PurchaseController extends Controller
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
     * achat d'une annonce
     *
     * @return void
     */
    public function buy()
    {
        extract($_POST);
        if (empty($csrf) || !$this->checkToken($csrf)) {
            $this->setMessage('error', 'Erreur inconnue');
            return $this->redirectTo('/');
        }

        if (empty($id) || empty($delivery_mode_id)) {
            $this->setMessage('error', 'Données manquantes');
            return $this->redirectTo('/ad/show?id='.$id);
        }

        $ad = Ad::getAdById($id);
        if (!$ad || $ad->sold) {
            $this->setMessage('error', 'Annonce indisponible');
            return $this->redirectTo('/ad/show?id='.$id);
        }
        
        if ($ad->user_id === $_SESSION['user_id']) {
            $this->setMessage('error', 'Action non autorisée');
            return $this->redirectTo('/ad/show?id='.$id);
        }

        Purchase::createPurchase($id, $ad->title, $ad->price, $_SESSION['user_id'], $ad->user_id, $delivery_mode_id);
        $b = Ad::markAsSold($id);
        if (!$b) {
            $this->renderView('purchase/error');
        }
        $this->refreshToken();
        return $this->redirectTo('/purchase/success');
    }

    /**
     * confirme la reception d'un bien
     *
     * @return void
     */
    public function received()
    {
        extract($_POST);
        if (empty($csrf) || !$this->checkToken($csrf)) {
            $this->setMessage('error', 'Erreur inconnue');
            return $this->redirectTo('/user/purchase');
        }

        if (empty($id)) {
            $this->setMessage('error', 'Achat introuvable');
            return $this->redirectTo('/user/purchase');
        }

        $purchase = Purchase::getById($id);
        if (!$purchase) {
            $this->setMessage('error', 'Achat introuvable');
            return $this->redirectTo('/user/purchase');
        }

        if ($purchase->buyer_id !== $_SESSION['user_id']) {
            $this->setMessage('error', 'Action non autorisée');
            return $this->redirectTo('/user/purchase');
        }

        if ($purchase->received) {
            $this->setMessage('error', 'Achat déjà reçu');
            return $this->redirectTo('/user/purchase');
        }

        
        $b = Purchase::markAsReceived($purchase->id, $_SESSION['user_id']);
        if (!$b) {
            $this->renderView('purchase/error');
        }
        $this->refreshToken();
        $this->setMessage('success', 'Vous avez confirmé la réception');
        return $this->redirectTo('/user/purchase');
    }

    /**
     * affiche la page de succès d'un achat
     *
     * @return void
     */
    public function success()
    {
        $this->renderView('purchase/success');
    }

    /**
     * affiche la page d'erreur lors d'un échec d'achat
     *
     * @return void
     */
    public function error()
    {
        $this->renderView('purchase/error');
    }
}