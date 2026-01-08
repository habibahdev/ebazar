<?php
namespace App\Controllers\Admin;

use App\Models\Ad;
use App\Utils\Controller;

/**
 * Gestion des annonces par un administrateur
 */
class AdminAdController extends Controller
{
    /**
     * constructeur
     */
    public function __construct()
    {
        $this->requireToBeAdmin();
    }

    /**
     * affiche la liste des annonces
     *
     * @return void
     */
    public function index()
    {
        $ads = Ad::getAllAd();
        $this->renderView('admin/ad/index', [
            'ads' => $ads
        ]);
    }

    /**
     * supprime une annonce non vendue d'un utilisateur
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
            $this->setMessage('error', 'Annonce introuvable');
            return $this->redirectTo('/admin/ad');
        }

        $ad = Ad::getAdById($id);
        if (!$ad) {
            $this->setMessage('error', 'Annonce introuvable');
            return $this->redirectTo('/admin/ad');
        }

        if ($ad->sold) {
            $this->setMessage('error', 'Impossible de supprimer une annonce déjà vendue');
            return $this->redirectTo('/admin/ad');
        }

        Ad::deleteAdIfNotSold($id);
        $this->refreshToken();
        $this->setMessage('success', 'Annonce supprimée');
        return $this->redirectTo('/admin/ad');
    }
}