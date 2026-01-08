<?php
namespace App\Utils;

/**
 * gestion de la création d'un routeur
 */
class Router
{
    /**
     * tableau des méthodes http
     *
     * @var array
     */
    private $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * enregistrement d'une méthode get
     *
     * @param string $url chemin de la classe
     * @param array $action méthode appellée qui sera exécutée 
     * @return void
     */
    public function get(string $url, $action = [])
    {
        $this->routes['GET'][$url] = $action;
    }

    /**
     * enregistrement d'une méthode post
     *
     * @param string $url chemin de la classe
     * @param array $action méthode appellée qui sera exécutée
     * @return void
     */
    public function post(string $url, $action = [])
    {
        $this->routes['POST'][$url] = $action;
    }

    /**
     * exécute la méthode en fonction de la méthode http 
     *
     * @return mixed retourne le nom de la méthode du contrôleur appelé
     */
    public function executeRoutes()
    {
        $url = explode('?', $_SERVER['REQUEST_URI'])[0];
        if ($url != '/' && str_ends_with($url, '/')) {
            $url = rtrim($url, '/');
        }

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        if (!isset($this->routes[$httpMethod][$url])) {
            http_response_code(404);
            return;
        }

        $action = $this->routes[$httpMethod][$url];
        if (!is_array($action) || count($action) != 2) {
            die('action invalide pour la route ' . $url);
        }

        [$controllerName, $methodName] = $action;
        if (!class_exists($controllerName)) {
            die($controllerName . ' introuvable');
        }

        $controller = new $controllerName();
        if (!method_exists($controller, $methodName)) {
            die ($methodName . ' non définie dans ' . $controllerName);
        }
        return $controller->$methodName();
    }
}