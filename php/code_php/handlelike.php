<?php
session_start();
require_once(__DIR__ . '/../../config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/variables.php');

if (isset($_POST['post_id'], $_POST['action']) && isset($_SESSION['LOGGED_USER'])) {
    $postId = (int) $_POST['post_id'];
    $userId = $_SESSION['LOGGED_USER']['user_id'];
    $userPseudo = $_SESSION['LOGGED_USER']['pseudo'];
    $action = $_POST['action']; // 'like' ou 'dislike'

    $stmt = $mysqlClient->prepare('SELECT idUtilisateur FROM post WHERE idPost = :idPost;');
    $stmt->execute([
        'idPost' => $postId,
    ]);
    $utilisateurPost = $stmt->fetchall();

    if ($action === 'like') {
        $stmt = $mysqlClient->prepare('INSERT INTO jaime (dateJaime, idUtilisateur, idPost) VALUES (NOW(), :idUtilisateur, :idPost)');
        $stmt->execute([
            'idUtilisateur' => $userId,
            'idPost' => $postId,
        ]);

        $stmt = $mysqlClient->prepare('UPDATE post SET nbLikePost = nbLikePost + 1 WHERE idPost = :idPost');
        $stmt->execute([
            'idPost' => $postId,
        ]);

        if ($_SESSION['LOGGED_USER']['user_id'] != $utilisateurPost[0]['idUtilisateur'])
        {
            $stmt = $mysqlClient->prepare('INSERT INTO notifications (type,messageNotification,dateNotification,is_read,idUtilisateur,idPost,idMessage,idEnvoyeur) VALUES (:type,:message,NOW(),false,:idUtilisateur,:idPost,0,:idEnvoyeur) ;');
            $stmt->execute([
                'type' => 'like',
                'message' => $userPseudo . ' a aimé votre poste.',
                'idUtilisateur' => $utilisateurPost[0]['idUtilisateur'],
                'idPost' => $postId,
                'idEnvoyeur' => $_SESSION['LOGGED_USER']['user_id'],
            ]);
        }


    } elseif ($action === 'dislike') {
        $stmt = $mysqlClient->prepare('DELETE FROM jaime WHERE idUtilisateur = :idUtilisateur AND idPost = :idPost');
        $stmt->execute([
            'idUtilisateur' => $userId,
            'idPost' => $postId,
        ]);
        $stmt = $mysqlClient->prepare('UPDATE post SET nbLikePost = nbLikePost - 1 WHERE idPost = :idPost');
        $stmt->execute([
            'idPost' => $postId,
        ]);

        if ($_SESSION['LOGGED_USER']['user_id'] != $utilisateurPost[0]['idUtilisateur'])
        {
            $stmt = $mysqlClient->prepare('DELETE FROM notifications WHERE idUtilisateur = :idUtilisateur AND idPost = :idPost AND type = :type AND idEnvoyeur = :idEnvoyeur');
            $stmt->execute([
                'idUtilisateur' => $utilisateurPost[0]['idUtilisateur'],
                'idPost' => $postId,
                'type' => 'like',
                'idEnvoyeur' => $_SESSION['LOGGED_USER']['user_id'],
            ]);
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo 'Erreur : Action non valide.';
}
