<?php
namespace App\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Photo;
use App\Utils\Controller;

/**
 * gestion de la page d'accueil
 */
class HomeController extends Controller
{
    /**
     * affiche la page d'accueil
     *
     * @return void
     */
    public function index()
    {
        $categories = Category::getCategoryWithNumberOfAdNotSold();
        $fourLastAd = Ad::getFourLastAd(4);
        
        foreach ($fourLastAd as $ad) {
            $ad->first_photo = Photo::getTheFirstPhtot($ad->id);
        }

        $this->renderView('home/index', [
            'categories' => $categories,
            'fourLastAd' => $fourLastAd
        ]);
    }

    /**
     * affiche les annonces d'une catégorie paginée
     *
     * @return void
     */
    public function category()
    {
        $id = intval($_GET['id']);
        if (!isset($id)) {
            $this->setMessage('error', 'Annonce introuvable');
            return $this->redirectTo('/');
        }

        $category = Category::getById($id);
        if (!$category) {
            $this->setMessage('error', 'Annonce introuvable dans cette catégorie');
            return $this->redirectTo('/');
        }

        $page = isset($_GET['p']) ? max(1, $_GET['p']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $ads = Ad::getAdByCategory($id, $offset, $limit);
        foreach ($ads as $ad) {
            $ad->first_photo = Photo::getTheFirstPhtot($ad->id);
        }

        $totalAd = Ad::countAdByCategory($id);
        $pages = ceil($totalAd / $limit);
        $this->renderView('category/index', [
            'ads' => $ads,
            'pages' => $pages,
            'page' => $page,
            'category' => $category,
            'categoryId' => intval($_GET['id'])
        ]);
    }
}