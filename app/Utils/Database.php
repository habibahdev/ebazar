<?php
namespace App\Utils;

use PDO;

/**
 * Gestion de la connexion à la base de données en garantissant
 * une seule instance de PDO
 */
class Database
{
    /**
     * instance de PDO
     *
     * @var PDO|null
     */
    private static ?PDO $pdo = null;

    /**
     * initialisation de la connexion PDO
     *
     * @return PDO retourne une instance de PDO
     * @throws PDOException si connexion échoue une exception est lancée
     */
    public static function init(): PDO
    {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                    ]
                    );
            } catch (\PDOException $e)
            {
                die('erreur : ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}