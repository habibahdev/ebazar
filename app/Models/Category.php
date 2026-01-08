<?php
namespace App\Models;

use App\Utils\Database;

/**
 * Modèle de la gestion des catégories
 */
class Category
{
    /**
     * récupère toutes les catégories
     *
     * @return array retourne un tableau de catégories
     */
    public static function getAllCategory()
    {
        $pdo = Database::init();
        $stmt = $pdo->query(
            "select *
            from category"
        );
        return $stmt->fetchAll();
    }

    /**
     * récupère une catégorie en fonction de son identifiant
     *
     * @param integer $categoryId identifiant de la catégorie
     * @return object|false retourne la catégorie, false sinon
     */
    public static function getById(int $categoryId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select *
            from category
            where id = ?"
        );
        $stmt->execute([$categoryId]);
        return $stmt->fetch();
    }

    /**
     * créer une nouvelle catégorie
     *
     * @param string $name nom de la catégorie
     * @return boolean retourne true si ajout, false sinon
     */
    public static function createCategory(string $name)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "insert into category (name)
            values (?)"
        );
        return $stmt->execute([$name]);
    }

    /**
     * renomme une catégorie
     *
     * @param integer $categoryId identifiant de la catégorie
     * @param string $new nouveau nom de la catégorie
     * @return boolean retourne true si succès, false sinon
     */
    public static function renameCategory(int $categoryId, string $new)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "update category
            set name = ?
            where id = ?"
        );
        return $stmt->execute([$new, $categoryId]);
    }

    /**
     * récupère toutes les catégories avec le nombre d'annonces non vendues pour chacune
     *
     * @return array retourne un tableau de catégorie
     */
    public static function getCategoryWithNumberOfAdNotSold()
    {
        $pdo = Database::init();
        $stmt = $pdo->query(
            "select c.id, c.name, count(a.id) as ad_count
            from category c
            left join ad a on a.category_id = c.id and a.sold = 0
            group by c.id
            order by c.name asc"
        );
        return $stmt->fetchAll();
    }
}