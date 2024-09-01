<?php
session_start();
require_once(__DIR__ . '/../../config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/variables.php');

if (isset($_POST['post_id'], $_POST['action']) && isset($_SESSION['LOGGED_USER'])) {
    $postId = (int) $_POST['post_id'];
    $userId = $_SESSION['LOGGED_USER']['user_id'];
    $action = $_POST['action']; // 'activer' ou 'desactiver'

    if ($action === 'activer') {
        $stmt = $mysqlClient->prepare('UPDATE post SET is_enabled = 1 WHERE idPost = :idPost');
        $stmt->execute([
            'idPost' => $postId,
        ]);

    } elseif ($action === 'desactiver') {
        $stmt = $mysqlClient->prepare('UPDATE post SET is_enabled = 0 WHERE idPost = :idPost');
        $stmt->execute([
            'idPost' => $postId,
        ]);
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo 'Erreur : Action non valide.';
}
