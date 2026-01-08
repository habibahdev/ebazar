<?php
namespace App\Controllers\Admin;

use App\Models\Category;
use App\Utils\Controller;

/**
 * Gestion des catégories par l'administrateur
 */
class AdminCategoryController extends Controller
{
    /**
     * constructeur
     */
    public function __construct()
    {
        $this->requireToBeAdmin();
    }

    /**
     * affiche la liste des catégories
     *
     * @return void
     */
    public function index()
    {
        $categories = Category::getAllCategory();
        $this->renderView('admin/category/index', [
            'categories' => $categories
        ]);
    }

    /**
     * affiche le formulire d'ajout d'une catégorie
     *
     * @return void
     */
    public function AddForm()
    {
        $this->renderView('admin/category/add');
    }

    /**
     * ajoute une catégorie
     *
     * @return void
     */
    public function add()
    {
        extract($_POST);
        if (empty($csrf) || !$this->checkToken($csrf)) {
            $this->setMessage('error', 'Erreur inconnue');
            return $this->redirectTo('/admin/category');
        }

        if (empty($name)) {
            $this->setMessage('error', 'Formulaire incomplet');
            return $this->redirectTo('/admin/category');
        }

        if (mb_strlen($name) < 5 || mb_strlen($name) > 100) {
            $this->setMessage('error', 'Le nom de la catégorie doit être compris en tre 5 et 100');
            return $this->redirectTo('/admin/category');
        }

        Category::createCategory($name);
        $this->refreshToken();
        $this->setMessage('success', 'Nouvelle catégorie ajoutée');
        return $this->redirectTo('/admin/category');
    }

    /**
     * affiche le formulaire de renommage d'une catégorie
     *
     * @return void
     */
    public function RenameForm()
    {
        if (empty($_GET['id'])) {
            $this->setMessage('error', 'Catégorie introuvable');
            return $this->redirectTo('/admin/category');
        }

        $category = Category::getById($_GET['id']);
        if (!$category) {
            $this->setMessage('error', 'Catégorie introuvable');
            return $this->redirectTo('/admin/category');
        }
        $this->renderView('admin/category/rename', [
            'category' => $category
        ]);
    }

    /**
     * effectue le renommage de la catégorioe choisit
     *
     * @return void
     */
    public function renameCategory()
    {
        extract($_POST);
        if (empty($csrf) || !$this->checkToken($csrf)) {
            $this->setMessage('error', 'Erreur inconnue');
            return $this->redirectTo('/admin/category');
        }

        if (empty($name)) {
            $this->setMessage('error', 'Formulaire incomplet');
            return $this->redirectTo('/admin/category');
        }

        if (empty($id)) {
            $this->setMessage('error', 'Formulaire incomplet');
            return $this->redirectTo('/admin/category');
        }

        $category = Category::getById($id);
        if (!$category) {
            $this->setMessage('error', 'Catégorie introuvable');
            return $this->redirectTo('/admin/category');
        }

        Category::renameCategory($id, $name);
        $this->refreshToken();
        $this->setMessage('success', 'Catégorie renommée');
        return $this->redirectTo('/admin/category');
    }
}