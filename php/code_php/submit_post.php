<?php
session_start();
require_once(__DIR__ . '/../../config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/variables.php');

$postData = $_POST;
$imagepost = $_FILES;

if (isset($_SESSION['LOGGED_USER'])) {
    $userId = $_SESSION['LOGGED_USER']['user_id'];
    $date = date("Y-m-d H:i:s");

    $imageUploaded = isset($imagepost['image_post']) && $imagepost['image_post']['error'] == 0;
    $textPosted = isset($postData['text_post']) && !empty(trim($postData['text_post']));

    if ($imageUploaded) {
        if ($imagepost['image_post']['size'] > 1000000) {
            $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Fichier trop volumineux';
            redirectToUrl('../../index.php');
        }

        $fileInfo = pathinfo($imagepost['image_post']['name']);
        $extension = $fileInfo['extension'];
        $allowedExtensions = ['jpg', 'jpeg'];
        if (!in_array($extension, $allowedExtensions)) {
            $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Extension invalide (jpg, jpeg).';
            redirectToUrl('../../index.php');
        }

        $path = __DIR__ . '/../../image/post';
        if (!is_dir($path)) {
            mkdir($path, 0777, true); // Création du répertoire si manquant
        }

        $imageFileName = $userId . '_' . date("Y-m-d_H-i-s") . '.' . $extension;
        move_uploaded_file($imagepost['image_post']['tmp_name'], $path . '/' . $imageFileName);
        $imagePath = 'image/post/' . $imageFileName;
    } else {
        $imagePath = null;
    }

    if ($imageUploaded || $textPosted) {
        $changementImage = $mysqlClient->prepare('
            INSERT INTO post (dateCreationPost, cheminImagePost, descriptionPost, nbLikePost, is_enabled, nbCommentaires, idUtilisateur)
            VALUES (:date, :imagepost, :description, 0, true, 0, :idUtilisateur);
        ');
        $changementImage->execute([
            'date' => $date,
            'imagepost' => $imagePath,
            'description' => $textPosted ? $postData['text_post'] : null,
            'idUtilisateur' => $userId,
        ]);

        $_SESSION['SUCCESS_MESSAGE'] = 'Post publié avec succès !';
    } else {
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Veuillez ajouter une image ou un texte avant de poster.';
    }

    redirectToUrl('../../index.php');
} else {
    $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Vous devez être connecté pour poster.';
    redirectToUrl('../../index.php');
}
?>
