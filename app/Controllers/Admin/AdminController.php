<?php
namespace App\Controllers\Admin;

use App\Utils\Controller;

/**
 * Gère le côté administration
 */
class AdminController extends Controller
{
    /**
     * construcuteur
     */
    public function __construct()
    {
        $this->requireToBeAdmin();
    }

    /**
     * affiche le menu
     *
     * @return void
     */
    public function index()
    {
        $this->renderView('admin/index');
    }
}