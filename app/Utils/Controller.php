<?php
namespace App\Utils;

/**
 * Contrôleur de base
 */
class Controller
{
    /**
     * affiche la vue + données si il y a 
     *
     * @param string $template chemin du template à afficher
     * @param array $data données passées à la vue 
     * @return void
     */
    protected function renderView(string $template, $data = [])
    {
        extract($data);
        $view = ROOT . '/app/Views/' . $template . '.php';
        $base = ROOT . '/app/Views/base.php';
        if (!file_exists($view)) {
            die('la vue ' . $template . ' introuvable');
        }

        ob_start();
        require $view;
        $content = ob_get_clean();
        require $base;
    }

    /**
     * inclut une vue
     *
     * @param string $view chemin de la vue à inclure
     * @param array $data données passées à la vue
     * @return void
     */
    protected function includeView(string $view, $data = [])
    {
        extract($data);
        require $view;
    }

    /**
     * si l'utilisateur n'est pas connecté en tant qu'admin effectue
     * une redirection
     *
     * @return void
     */
    protected function requireToBeAdmin()
    {
        if (!self::isAdmin()) {
            $_SESSION['redirect_after'] = $_SERVER['REQUEST_URI'] ?? '/';
            $this->setMessage('error', 'Vous devez être connecté');
            $this->redirectTo('/login');
        }
    }

    /**
     * si l'utilisateur n'est pas connecté effectue une redirection
     *
     * @return void
     */
    protected function requireToBeLogged()
    {
        if (!self::isLogged()) {
            $_SESSION['redirect_after'] = $_SERVER['REQUEST_URI'] ?? '/';
            $this->setMessage('error', 'Vous devez être connecté');
            $this->redirectTo('/login');
        }
    }

    /**
     * définit un message avec son type
     *
     * @param string $type type du message (error, success, info, warning)
     * @param string $message chaîne de caractère
     * @return void
     */
    protected function setMessage(string $type, string $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * récupère le message sotkcer en session
     *
     * @return void
     */
    protected function getMessage()
    {
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * effectue une redirection vers l'url spécifiée
     *
     * @param string $url url de la redirection
     * @return void
     */
    protected function redirectTo(string $url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * vérifie si l'utilisateur est connecté
     *
     * @return boolean retourne true si connecté, false sinon
     */
    protected function isLogged()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * vérifie si l'utilisateur est un administrateur 
     *
     * @return boolean retourne true si administrateur, false sison
     */
    protected function isAdmin()
    {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1;
    }

    /**
     * génère un token CSRF
     *
     * @return string retourne le token générer
     */
    protected function generateToken()
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    /**
     * vérifie la validité du token CSRF
     *
     * @param string $token oken CSRF
     * @return boolean retourne true si le token est valide, false sinon
     */
    protected function checkToken(string $token)
    {
        return isset($_SESSION['csrf'])
            && is_string($token)
            && hash_equals($_SESSION['csrf'], $token);
    }

    /**
     * regénère un token CSRF
     *
     * @return void
     */
    protected function refreshToken()
    {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    /**
     * echappe une chaîne de caractère
     *
     * @param string $string châine de caractère à échapper
     * @return string retourne la chaîne échappée
     */
    protected function echap(string $string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }

    /**
     * formatte le prix au format décimal
     *
     * @param string $string chaine à formatter
     * @return float|false retourne la chaîne correctement formatter, false sinon
     */
    protected function isFormattedPrice(string $string)
    {
        $tmp = trim($string);
        $tmp = str_replace(',', '.', $tmp);
        if (!is_numeric($tmp)) {
            return false;
        }
        return (float) $tmp;
    }

    /**
     * Undocumented function
     *
     * @param [type] $price
     * @param integer $decimals
     * @return void
     */
    protected function formatPrice($price, int $decimals = 2)
    {
        $price = is_string($price) ? (float) $price : $price;
        return number_format($price, $decimals, ',', ' ') . "€";
    }
}