<?php
namespace App\Models;

use PDO;
use App\Utils\Database;

/**
 * Modèle de la gestion des annonces
 */
class Ad
{
    /**
     * récupère les 4 dernières annonces
     *
     * @param integer $limit nombre maximum d'annonce à récupérer
     * @return array retourne un tableau d'annonces
     */
    public static function getFourLastAd(int $limit = 4)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select a.*, d.name as delivery_mode
            from ad a
            join delivery_mode d on d.id = a.delivery_mode_id
            where a.sold = 0
            order by a.created_at desc
            limit ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * récupère les annonces d'une catégorie
     *
     * @param integer $categoryId identifiant de la catégorie
     * @param integer $offset offset pour la pagination
     * @param integer $limit nombre maxmimum d'annonces à récupèrer
     * @return array retourne un tableau d'annonces
     */
    public static function getAdByCategory(int $categoryId, int $offset, int $limit = 10)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select a.*, d.name as delivery_mode
            from ad a
            join delivery_mode d on d.id = a.delivery_mode_id
            where a.category_id = ? and a.sold = 0
            order by a.created_at desc
            limit ?, ?"
        );
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * compte le nombre d'annonces non vendue dans une catégorie
     *
     * @param integer $categoryId identifiant de la catégorie
     * @return integer nombre d'annonce
     */
    public static function countAdByCategory(int $categoryId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select count(*)
            from ad
            where category_id = ?
            and sold = 0"
        );
        $stmt->execute([$categoryId]);
        return $stmt->fetchColumn();
    }

    /**
     * récupère une annonce selon son identifiant
     *
     * @param integer $adId identifiant de l'annonce
     * @return object|false retourne l'annonce, ou false si non trouvée
     */
    public static function getAdById(int $adId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select a.*, d.name as delivery_mode, c.name as category_name
            from ad a
            join delivery_mode d on d.id = a.delivery_mode_id
            join category c on c.id = a.category_id
            where a.id = ?"
        );
        $stmt->execute([$adId]);
        return $stmt->fetch();
    }

    /**
     * récupère toutes les annonces
     *
     * @return array retourne un tableau de toutes les annonces
     */
    public static function getAllAd()
    {
        $pdo = Database::init();
        $stmt = $pdo->query(
            "select a.*, d.name as delivery_mode
            from ad a
            join delivery_mode d on d.id = a.delivery_mode_id
            order by a.created_at desc"
        );
        return $stmt->fetchAll();
    }

    /**
     * crée une nouvelle annonce
     *
     * @param array $data tableau de données qui forment l'annocne
     * @return integer retourne le dernier identifiant de l'annonce qui a été créée
     */
    public static function createAd($data = [])
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "insert into ad
            (title, description, price, delivery_mode_id, user_id, category_id, created_at)
            values (?, ?, ?, ?, ?, ?, now())"
        );
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['price'],
            $data['delivery_mode_id'],
            $data['user_id'],
            $data['category_id']
        ]);
        return $pdo->lastInsertId();
    }

    /**
     * marque une annonce comme vendue
     *
     * @param integer $adId identifiant de l'annonce
     * @return boolean retourne true si mise à jour réussie, false sinon
     */
    public static function markAsSold(int $adId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "update ad
            set sold = 1
            where id = ?
            and sold = 0"
        );
        return $stmt->execute([$adId]);
    }

    /**
     * supprime une annonce uniqueement si non vendue
     *
     * @param integer $adId identifiant de l'annonce
     * @return boolean retourne true si suppression, false sinon
     */
    public static function deleteAdIfNotSold(int $adId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "delete from ad
            where id = ?
            and sold = 0"
        );
        return $stmt->execute([$adId]);
    }

    /**
     * supprime les annonces non vendue d'un utlisateur
     *
     * @param integer $userId identifiant de l'utilisateur
     * @return boolean retourne true si suppression, false sinon
     */
    public static function deleteAdIfNotSoldForUser(int $userId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "delete from ad
            where user_id = ?
            and sold = 0"
        );
        return $stmt->execute([$userId]);
    }

    /**
     * récupère toutes les annonces d'un utilisateur
     *
     * @param integer $userId identifiant de l'utilisateur
     * @return array retourne un tableau d'annonces
     */
    public static function getAdByUser(int $userId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select a.*, d.name as delivery_mode
            from ad a
            join delivery_mode d on d.id = a.delivery_mode_id
            where a.user_id = ?
            order by a.created_at desc"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}