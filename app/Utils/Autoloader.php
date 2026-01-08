<?php
namespace App\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Gestion de l'autoload des classes
 */
class Autoloader
{
    public static function register()
    {
        spl_autoload_register([
            __CLASS__,
            'autoload'
        ]);
    }

    /**
     * fonction d'autoload
     *
     * @param string $class nom de la classe à charger
     * @return void
     */
    private static function autoload(string $class)
    {
        $namespace = str_replace('\\', '/', $class) . '.php';
        $baseRoot = [
            'App\\Controllers' => CONTROLLERS_DIR,
            'App\\Models' => MODELS_DIR,
            'App\\Utils' => UTILS_DIR,
        ];

        foreach ($baseRoot as $root => $baseDir) {
            if (str_starts_with($class, $root)) {
                $relativePath = substr($namespace, strlen(str_replace('\\', '/', $root)) + 1);
                $file = $baseDir . '/' . $relativePath;
                if ($file && file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }

        $directories = [$baseRoot['App\\Controllers'], $baseRoot['App\\Models'], $baseRoot['App\\Utils']];
        foreach ($directories as $directory) {
            $file = self::findPHPFile($directory, basename($class));
            if ($file) {
                require_once $file;
                return;
            }
        }
        echo "Autoloader : classe '$class' introuvable";
    }

    /**
     * cherche de manière récursive un fichier php correspndant à la classe donnée
     * dans un répertoire donné
     *
     * @param string $directory répertoire de recherche
     * @param string $class classe cherchée
     * @return string|null retourne le chemin du fichier trouvé, null sinon
     */
    private static function findPHPFile(string $directory, string $class)
    {
        $file = $directory . '/' . $class . '.php';
        if (file_exists($file)) {
            return $file;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $next) {
            if ($next->isFile() && $next->getFilename() === $class . '.php') {
                return $next->getPathname();
            }
        }
        return null;
    }
}
