<?php
namespace App\Models;

use App\Utils\Database;

/**
 * Modèle de gestion des moyens de livraison
 */
class DeliveryMode
{
    /**
     * récupère tous les moyens de livraison
     *
     * @return array retourne un tableau des modes de livraison
     */
    public static function getAllMode()
    {
        $pdo = Database::init();
        $stmt = $pdo->query(
            "select *
            from delivery_mode
            order by name"
        );
        return $stmt->fetchAll();
    }

    /**
     * récupère un mode de livraison en fonction de son identifiant
     *
     * @param integer $deliveryModeId identifiant du mode de livraison
     * @return object|false retourne le moyen de livraison, false sinon
     */
    public static function getById(int $deliveryModeId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select *
            from delivery_mode
            where id = ?"
        );
        $stmt->execute([$deliveryModeId]);
        return $stmt->fetch();
    }
}