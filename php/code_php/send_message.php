<?php
session_start();
require_once(__DIR__ . '/../../config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

if (isset($_SESSION['LOGGED_USER'], $_POST['id_destinataire'], $_POST['message'])) {
    $idEnvoyeur = $_SESSION['LOGGED_USER']['user_id'];
    $idDestinataire = (int)$_POST['id_destinataire'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $mysqlClient->prepare('INSERT INTO messageprive (idEnvoyeur, idDestinataire, messagePrive, dateMessagePrive) 
                                       VALUES (:idEnvoyeur, :idDestinataire, :messagePrive, NOW())');
        $stmt->execute([
            'idEnvoyeur' => $idEnvoyeur,
            'idDestinataire' => $idDestinataire,
            'messagePrive' => $message,
        ]);
    }
}

header('Location: ../../discussion.php');
exit;
?>
