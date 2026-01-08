<?php

use App\Utils\Autoloader;
use App\Utils\Router;

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config.php';
require_once UTILS_DIR . '/Autoloader.php';

Autoloader::register();

$router = new Router();

$router->get('/', [App\Controllers\HomeController::class, 'index']);
$router->get('/category', [App\Controllers\HomeController::class, 'category']);
$router->get('/login', [App\Controllers\LoginController::class, 'index']);
$router->post('/login', [App\Controllers\LoginController::class, 'login']);
$router->get('/register', [App\Controllers\RegisterController::class, 'index']);
$router->post('/register', [App\Controllers\RegisterController::class, 'register']);
$router->get('/logout', [App\Controllers\LogoutController::class, 'logout']);
$router->get('/admin', [App\Controllers\Admin\AdminController::class, 'index']);
$router->get('/admin/category', [App\Controllers\Admin\AdminCategoryController::class, 'index']);
$router->get('/admin/category/add', [App\Controllers\Admin\AdminCategoryController::class, 'AddForm']);
$router->post('/admin/category/add', [App\Controllers\Admin\AdminCategoryController::class, 'add']);
$router->get('/admin/category/rename', [App\Controllers\Admin\AdminCategoryController::class, 'RenameForm']);
$router->post('/admin/category/rename', [App\Controllers\Admin\AdminCategoryController::class, 'renameCategory']);
$router->get('/admin/ad', [App\Controllers\Admin\AdminAdController::class, 'index']);
$router->post('/admin/ad/delete', [App\Controllers\Admin\AdminAdController::class, 'delete']);
$router->get('/admin/user', [App\Controllers\Admin\AdminUserController::class, 'index']);
$router->post('/admin/user/delete', [App\Controllers\Admin\AdminUserController::class, 'delete']);
$router->get('/ad/add', [App\Controllers\AdController::class, 'form']);
$router->post('/ad/add', [App\Controllers\AdController::class, 'add']);
$router->get('/ad/show', [App\Controllers\AdController::class, 'show']);
$router->post('/purchase/buy', [App\Controllers\PurchaseController::class, 'buy']);
$router->get('/purchase/success', [App\Controllers\PurchaseController::class, 'success']);
$router->get('/purchase/error', [App\Controllers\PurchaseController::class, 'error']);
$router->post('/purchase/received', [App\Controllers\PurchaseController::class, 'received']);
$router->get('/user/ad', [App\Controllers\UserController::class, 'ads']);
$router->get('/user/purchase', [App\Controllers\UserController::class, 'purchases']);
$router->get('/user/sale', [App\Controllers\UserController::class, 'sales']);
$router->post('/user/delete', [App\Controllers\UserController::class, 'delete']);

$router->executeRoutes();
