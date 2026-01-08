<?php
namespace App\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\DeliveryMode;
use App\Models\Photo;
use App\Utils\Controller;

/**
 * Gestion des annonces par un utilisateur
 */
class AdController extends Controller
{
    /**
     * affiche le formulaire d'ajout d'une annonce
     *
     * @return void
     */
    public function form()
    {
        $this->requireToBeLogged();

        if ($this->isAdmin())
        {
            $this->setMessage('error', 'Action non autorisée');
            return $this->redirectTo('/admin');
        }
        $categories = Category::getAllCategory();
        $deliveryModes = DeliveryMode::getAllMode();
        $this->renderView('ad/add', [
            'categories' => $categories,
            'deliveryModes' => $deliveryModes
        ]);
    }

    /**
     * ajoute une nouvelle annonce
     *
     * @return void
     */
    public function add()
    {
        $this->requireToBeLogged();

        if ($this->isAdmin())
        {
            $this->setMessage('error', 'Action non autorisée');
            return $this->redirectTo('/admin');
        }

        extract($_POST);
        if (empty($csrf) || !$this->checkToken($csrf)) {
            $this->setMessage('error', 'Erreur inconnue');
            return $this->redirectTo('/ad/add');
        }

        if (empty($title) || empty($description) || empty($price) || empty($category_id) || empty($delivery_mode_id)) {
            $this->setMessage('error', 'Formulaire incomplet');
            return $this->redirectTo('/ad/add');
        }

        if (mb_strlen($title) < 5 || mb_strlen($title) > 30) {
            $this->setMessage('error', 'Le titre doit être compris entre 5 et 30 caractères max');
            return $this->redirectTo('/ad/add');
        }

        if (mb_strlen($description) < 5 || mb_strlen($description) > 200) {
            $this->setMessage('error', 'Le description doit être compris entre 5 et 200 caractères max');
            return $this->redirectTo('/ad/add');
        }

        $deliveryMode = DeliveryMode::getById($delivery_mode_id);
        if (!$deliveryMode) {
            $this->setMessage('error', 'Mode de livraison invalide');
            return $this->redirectTo('/ad/add');
        }

        $category = Category::getById($category_id);
        if (!$category) {
            $this->setMessage('error', 'Catégorie invalide');
            return $this->redirectTo('/ad/add');
        }

        $price = $this->isFormattedPrice($price);
        if ($price === false) {
            $this->setMessage('error', 'Prix invalide');
            return $this->redirectTo('/ad/add');
        }

        $data = [
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'delivery_mode_id' => $delivery_mode_id,
            'user_id' => $_SESSION['user_id'],
            'category_id' => $category_id,
        ];

        $adId = Ad::createAd($data);
        if (!empty($_FILES['photos']['name'][0])) {
            if (count($_FILES['photos']['name']) > 5) {
                $this->setMessage('error', '5 photos max par annonces');
                return $this->redirectTo('ad/add');
            }
            $upload = Photo::uploadPhoto($adId, $_FILES['photos']);
            if (isset($upload['error'])) {
                $this->setMessage('error', $upload['error']);
                return $this->redirectTo('/ad/add');
            }
        }
        $this->refreshToken();
        $this->setMessage('success', 'Annonce publiée');
        return $this->redirectTo('/');
    }

    /**
     * affiche le détail d'une annonce
     *
     * @return void
     */
    public function show()
    {
        if (empty($_GET['id'])) {
            $this->setMessage('error', 'Annonce introuvabe');
            return $this->redirectTo('/');
        }

        $ad = Ad::getAdById($_GET['id']);
        if (!$ad) {
            $this->setMessage('error', 'Annonce introuvabe');
            return $this->redirectTo('/ad/add');
        }

        $photos = Photo::getPhotoFromAd($ad->id);
        $this->renderView('ad/show', [
            'ad' => $ad,
            'photos' => $photos
        ]);
    }
}