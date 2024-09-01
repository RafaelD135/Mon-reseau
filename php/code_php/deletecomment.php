<?php
session_start();
require_once(__DIR__ . '/../../config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/variables.php');

if (isset($_POST['idPost'],$_POST['idComm'], $_POST['action']) && isset($_SESSION['LOGGED_USER'])) {
    $postId = (int) $_POST['idPost'];
    $commId = (int) $_POST['idComm'];
    $action = $_POST['action'];

    if ($action === 'delete') {
        $stmt = $mysqlClient->prepare('DELETE FROM commentaire WHERE idCommentaire = :idComm');
        $stmt->execute([
            'idComm' => $commId,
        ]);

        $stmt = $mysqlClient->prepare('UPDATE post SET nbCommentaires = nbCommentaires - 1 WHERE idPost = :idPost');
        $stmt->execute([
            'idPost' => $postId,
        ]);

        $stmt = $mysqlClient->prepare('DELETE FROM notifications WHERE idComm = :idComm');
        $stmt->execute([
            'idComm' => $commId,
        ]);

    }
    
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo 'Erreur : Action non valide.';
}
