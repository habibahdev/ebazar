<?php
namespace App\Models;

use App\Utils\Database;

/**
 * Modèle de la gestion des achats/ventes
 */
class Purchase
{
    /**
     * créer un achat
     *
     * @param integer $adId identifiant de l'annonce
     * @param integer $buyerId identifiant de l'acheteur
     * @param integer $sellerId identifiant du vendeur
     * @param string $adTitle titre de l'annonce
     * @param float $adPrice prix de l'annocne
     * @param integer $deliveryMode mode de livraison
     * @return boolean retourne true si insertion, false sinon
     */
    public static function createPurchase(int $adId, string $adTitle,float $adPrice, int $buyerId, int $sellerId, int $deliveryMode)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "insert into purchase
            (ad_id, ad_title, ad_price, buyer_id, seller_id, delivery_mode_id, created_at)
            values (?, ?, ?, ?, ?, ?, now())"
        );
        return $stmt->execute([$adId, $adTitle, $adPrice, $buyerId, $sellerId, $deliveryMode]);
    }

    /**
     * récupère tous les achats d'un acheteur
     *
     * @param integer $buyerId identifiant de l'acheteur
     * @return array retourne un tableau d'achats
     */
    public static function purchaseOfBuyer(int $buyerId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select p.*, p.ad_price, d.name as delivery_mode, a.title
            from purchase p
            left join ad a on p.ad_id = a.id
            join delivery_mode d on p.delivery_mode_id = d.id
            where p.buyer_id = ?"
        );
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll();
    }

    /**
     * récupère toutes les ventes d'un vendeur
     *
     * @param integer $sellerId identifiant du vendeur
     * @return array retourne un tableau de vente
     */
    public static function saleOfSeller(int $sellerId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select p.*, p.ad_price, d.name as delivery_mode, a.title
            from purchase p
            left join ad a on p.ad_id = a.id
            join delivery_mode d on p.delivery_mode_id = d.id
            where p.seller_id = ?"
        );
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll();
    }

    /**
     * marque une annonce comme reçu
     *
     * @param integer $purchaseId identifiant de l'achat
     * @param integer $buyerId identifiant de l'acheteur
     * @return boolean retourne true si mise à jour, false sinon
     */
    public static function markAsReceived(int $purchaseId, int $buyerId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "update purchase
            set received = 1
            where id = ?
            and buyer_id = ?"
        );
        return $stmt->execute([$purchaseId, $buyerId]);
    }

    /**
     * récupère un achat en fonction de son identifiant
     *
     * @param integer $purchaseId identifiant d'un achat
     * @return object|false retourne l'achat, false sinon
     */
    public static function getById(int $purchaseId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select *
            from purchase
            where id = ?"
        );
        $stmt->execute([$purchaseId]);
        return $stmt->fetch();
    }

    /**
     * retourne le nombre d'achat non réceptionné d'une utilisateur
     *
     * @param integer $userId identifiant de l'utilisateur
     * @return boolean retourne true si supérieur à zéro, false sinon
     */
    public static function isUserHasPendingPurchase(int $userId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select count(*)
            from purchase
            where received = 0
            and (buyer_id = ? or seller_id = ?)"
        );
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchColumn() > 0;
    }
}