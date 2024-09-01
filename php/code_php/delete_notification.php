<?php
session_start();
require_once(__DIR__ . '/../../config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/variables.php');

if (isset($_POST['idNotification'], $_POST['action']) && isset($_SESSION['LOGGED_USER'])) {
    $notifId = (int) $_POST['idNotification'];
    $action = $_POST['action']; // 'like' ou 'dislike'


    if ($action === 'supprimer') {
        $stmt = $mysqlClient->prepare('DELETE FROM notifications WHERE idNotification = :idNotif');
        $stmt->execute([
            'idNotif' => $notifId,
        ]);

    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo 'Erreur : Action non valide.';
}
