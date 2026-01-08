<?php
namespace App\Models;

use App\Utils\Database;

/**
 * Modèle de la gestion des utilisateurs
 */
class User
{
    /**
     * crée un utilisateur
     *
     * @param string $email adresse email de l'utilisateur
     * @param string $password mot de passe de l'utilisateur
     * @return boolean retourne true si ajouter, false sinon
     */
    public static function createUser(string $email, string $password)
    {
        $pdo = Database::init();
        $passwordHashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare(
            "insert into user (email, password)
            values (?, ?)"
        );
        return $stmt->execute([$email, $passwordHashed]);
    }

    /**
     * récupère un utilisateur en fonction de son identifiant
     *
     * @param integer $userId identifiant de l'utilisateur
     * @return object|false retourne utilisateur, false sinon
     */
    public static function getById(int $userId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select *
            from user
            where id = ?"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    /**
     * récupère un utilisateur ne fonction de son email
     *
     * @param string $email adresse email de l'utilsateur
     * @return object|false retourne l'utilisateur, false sinon
     */
    public static function getByEmail(string $email)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select *
            from user
            where email = ?"
        );
        $stmt->execute([$email]);
        return $stmt->fetch();
    }


    /**
     * supprime un utilisateur
     *
     * @param integer $userId identifiant de l'utilisateur
     * @return boolean true si suppression, false sinon
     */
    public static function delete(int $userId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "delete from user
            where id = ?"
        );
        return $stmt->execute([$userId]);
    }

    /**
     * récupère tous les utilisateurs
     *
     * @return array retourne un tableau d'utilisateur
     */
    public static function getAllUser()
    {
        $pdo = Database::init();
        $stmt = $pdo->query(
            "select *
            from user
            where is_admin = 0
            order by id desc"
        );
        return $stmt->fetchAll();
    }
}