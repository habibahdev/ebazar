<?php
namespace App\Models;

use App\Utils\Database;

/**
 * Modèle pour la gestion des photos
 */
class Photo
{
    /**
     * ajoute une photo à une annonce donnée
     *
     * @param string $photoName nom de la photo
     * @param integer $adId identifiant de l'annonce
     * @return boolean retourne true si ajout, false sinon
     */
    public static function addPhoto(string $photoName, int $adId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "insert into photo (filename, ad_id)
            values (?, ?)"
        );
        return $stmt->execute([$photoName, $adId]);
    }

    /**
     * récupère toutes les photos d'une annonce donnée
     *
     * @param integer $adId identifiant de l'annonce
     * @return array retourne un tableau de photo
     */
    public static function getPhotoFromAd(int $adId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select *
            from photo
            where ad_id = ?"
        );
        $stmt->execute([$adId]);
        return $stmt->fetchAll();
    }

    /**
     * supprime toutes les photos d'une annnonce donnée
     *
     * @param integer $adId identifiant de l'annonce
     * @return boolean true si suppression, false sinon
     */
    public static function delete(int $adId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "delete from photo
            where ad_id = ?"
        );
        return $stmt->execute([$adId]);
    }

    /**
     * upload de photos pour une annonce donnée
     *
     * @param integer $adId identifiant de l'annonce
     * @param array $photos tableau de photos
     * @return boolean|array true si aucune erreur, tableau d'erreur sinon
     */
    public static function uploadPhoto(int $adId, $photos = [])
    {
        $allowed = ['image/jpeg', 'image/jpg'];
        $maxSize = 200 * 1024;
        if (count($photos['name']) > 5) {
            return ['error' => '5 photos maximum autorisée'];
        }

        foreach ($photos['tmp_name'] as $i => $photo) {
            if ($photos['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }
            if ($photos['size'][$i] > $maxSize) {
                return ['error' => 'La photo ne doit pas dépassée la taille de 200Kio'];
            }
            if (!in_array($photos['type'][$i], $allowed)) {
                return ['error' => 'Format jpeg ou jpg uniquement'];
            }
            $extension = pathinfo($photos['name'][$i], PATHINFO_EXTENSION);
            $name = uniqid('photo') . '.' . $extension;
            $dir = UPLOADS_DIR;
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $dest = $dir . '/' . $name;
            move_uploaded_file($photo, $dest);
            self::addPhoto($name, $adId);
        }
        return true;
    }

    /**
     * récupère la première photo d'une annonce
     *
     * @param integer $adId identifiant de l'annonce
     * @return string|false retourne le nom de la photo, false sinon
     */
    public static function getTheFirstPhtot(int $adId)
    {
        $pdo = Database::init();
        $stmt = $pdo->prepare(
            "select filename
            from photo
            where ad_id = ?
            order by id asc
            limit 1"
        );
        $stmt->execute([$adId]);
        return $stmt->fetchColumn();
    }
}