<?php
session_start();
require_once(__DIR__ . '/../../config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');

if (isset($_POST['commentaire'], $_POST['post_id']) && isset($_SESSION['LOGGED_USER'])) {
    $commentaire = trim($_POST['commentaire']);
    $postId = (int) $_POST['post_id'];
    $userId = $_SESSION['LOGGED_USER']['user_id'];

    $stmt = $mysqlClient->prepare('SELECT P.idUtilisateur,U.pseudoUtilisateur FROM post P JOIN utilisateur U ON P.idUtilisateur = P.idUtilisateur WHERE idPost = :idPost;');
    $stmt->execute([
        'idPost' => $postId,
    ]);
    $postUser = $stmt->fetchall();

    if (empty($commentaire)) {
        $_SESSION['ERROR_COMMENTAIRE'] = "Le commentaire ne peut pas être vide.";
        header('Location: ../../pagepost.php?post_id=' . $postId);
        exit;
    }

    $stmt = $mysqlClient->prepare('INSERT INTO commentaire (commentaire, dateCommentaire, is_enabled, idUtilisateur, idPost) VALUES (:commentaire, NOW(), 1, :idUtilisateur, :idPost)');
    $stmt->execute([
        'commentaire' => $commentaire,
        'idUtilisateur' => $userId,
        'idPost' => $postId,
    ]);


    $stmt = $mysqlClient->prepare('UPDATE post SET nbCommentaires = nbCommentaires + 1 WHERE idPost = :idPost;');
    $stmt->execute([
        'idPost' => $postId,
    ]);

    if ($userId != $postUser[0]['idUtilisateur'])
        {
            $stmt = $mysqlClient->prepare('SELECT idCommentaire FROM commentaire WHERE commentaire = :comm AND idUtilisateur = :idUser AND idPost = :idPost ORDER BY dateCommentaire DESC;');
            $stmt->execute([
                'comm' => $commentaire,
                'idUser' => $userId,
                'idPost' => $postId,
            ]);
            $idComm = $stmt->fetchall();

            $stmt = $mysqlClient->prepare('INSERT INTO notifications (type,messageNotification,dateNotification,is_read,idUtilisateur,idPost,idMessage,idComm,idEnvoyeur) VALUES (:type,:message,NOW(),false,:idUtilisateur,0,0,:idComm,:idEnvoyeur) ;');
            $stmt->execute([
                'type' => 'commentaire',
                'message' => $postUser[0]['pseudoUtilisateur'] . ' a ajouté un commentaire.',
                'idUtilisateur' => $postUser[0]['idUtilisateur'],
                'idComm' => $idComm[0]['idCommentaire'],
                'idEnvoyeur' => $_SESSION['LOGGED_USER']['user_id'],
            ]);
        }


    header('Location: ../../pagepost.php?post_id=' . $postId);
    exit;
} else {
    echo 'Erreur : Action non valide.';
}
?>
