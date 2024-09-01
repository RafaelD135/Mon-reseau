<?php
session_start();
require_once(__DIR__ . '/../../config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/variables.php');

if (isset($_POST['suivi_id'], $_POST['action']) && isset($_SESSION['LOGGED_USER'])) {
    $suiviId = (int) $_POST['suivi_id'];
    $userId = $_SESSION['LOGGED_USER']['user_id'];
    $action = $_POST['action']; // 'suivre' ou 'desuivre'

    if ($action === 'suivre') {
        $stmt = $mysqlClient->prepare('INSERT INTO suivre (idUtilisateur, idSuivi, dateSuivre) VALUES (:idUtilisateur, :idSuivi,NOW())');
        $stmt->execute([
            'idUtilisateur' => $userId,
            'idSuivi' => $suiviId,
        ]);

        $stmt = $mysqlClient->prepare('UPDATE utilisateur SET nbFollower = nbFollower + 1 WHERE idUtilisateur = :idUtilisateur');
        $stmt->execute([
            'idUtilisateur' => $suiviId,
        ]);

        $stmt = $mysqlClient->prepare('INSERT INTO notifications (type,messageNotification,dateNotification,is_read,idUtilisateur,idPost,idMessage,idComm,idEnvoyeur) VALUES (:type,:message,NOW(),false,:idUtilisateur,0,0,0,:idEnvoyeur) ;');
        $stmt->execute([
            'type' => 'suivre',
            'message' => $_SESSION['LOGGED_USER']['pseudo'] . ' a commencer à vous suivre.',
            'idUtilisateur' => $suiviId,
            'idEnvoyeur' => $_SESSION['LOGGED_USER']['user_id'],
        ]);

    } elseif ($action === 'desuivre') {
        $stmt = $mysqlClient->prepare('DELETE FROM suivre WHERE idUtilisateur = :idUtilisateur AND idSuivi = :idSuivi');
        $stmt->execute([
            'idUtilisateur' => $userId,
            'idSuivi' => $suiviId,
        ]);

        $stmt = $mysqlClient->prepare('UPDATE utilisateur SET nbFollower = nbFollower - 1 WHERE idUtilisateur = :idUtilisateur');
        $stmt->execute([
            'idUtilisateur' => $suiviId,
        ]);

        $stmt = $mysqlClient->prepare('DELETE FROM notifications WHERE type=:type AND idUtilisateur = :idUser AND idEnvoyeur = :idEnvoyeur');
        $stmt->execute([
            'type' => 'suivre',
            'idUser' => $suiviId,
            'idEnvoyeur' => $_SESSION['LOGGED_USER']['user_id'],
        ]);
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo 'Erreur : Action non valide.';
    echo 'Suivi : ' . $suiviId . ' | Suiveur : ' . $userId . ' | action : ' . $action;   
}
