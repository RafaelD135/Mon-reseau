<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/php/code_php/databaseconnect.php');
require_once(__DIR__ . '/php/code_php/functions.php');
require_once(__DIR__ . '/php/code_php/variables.php');

if (isset($_SESSION['LOGGED_USER'])) {
    $stmt = $mysqlClient->prepare('SELECT * FROM notifications WHERE idUtilisateur = :idUser ORDER BY is_read ASC, dateNotification DESC;');
    $stmt->execute([
        'idUser' => $_SESSION['LOGGED_USER']['user_id'],
    ]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

    <?php require_once(__DIR__ . '/php/element_page/header.php'); ?>
    <div class="container">

    <?php if (isset($_SESSION['LOGGED_USER'])) : ?>
        <?php if (empty($notifications)) : ?>
            <div class="alert alert-info">Aucune notification pour le moment.</div>
        <?php else : ?>
            <h3>Notifications non lues</h3>
            <?php 
            $unreadNotifications = array_filter($notifications, fn($notif) => !$notif['is_read']);
            $readNotifications = array_filter($notifications, fn($notif) => $notif['is_read']);
            ?>
            
            <?php if (empty($unreadNotifications)) : ?>
                <div class="alert alert-info">Aucune notification non lue.</div>
            <?php else : ?>
                <?php foreach ($unreadNotifications as $notification) : ?>
                    <?php
                        $isEnable = $notification['is_read'];
                        $enableClass = $isEnable ? 'bi-eye' : 'bi-eye-slash';
                        $enableAction = $isEnable ? 'masquer' : 'visible';
                    ?>
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">
                                    <?= htmlspecialchars($notification['type']); ?>
                                    <?php if (!$notification['is_read']) : ?>
                                        <span class="badge bg-danger badge-sm">NEW</span>
                                    <?php endif; ?>
                                </h5>
                                <p class="card-text"><?= htmlspecialchars($notification['messageNotification']); ?></p>
                                <p class="card-text"><small class="text-muted"><?= date("d-m-Y H:i", strtotime($notification['dateNotification'])); ?></small></p>
                            </div>
                            <div>
                                <form method="POST" action="php/code_php/mark_notification_read.php" style="display:inline;">
                                    <input type="hidden" name="idNotification" value="<?= $notification['idNotification']; ?>">
                                    <button type="submit" name="action" value="<?= $enableAction; ?>" class="btn btn-outline-success me-2"><i class="bi <?= $enableClass; ?>"></i></button>
                                </form>
                                <form method="POST" action="php/code_php/delete_notification.php" style="display:inline;">
                                    <input type="hidden" name="idNotification" value="<?= $notification['idNotification']; ?>">
                                    <button type="submit" name="action" value="supprimer" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <h3 class="mt-4">Notifications lues</h3>
            <?php if (empty($readNotifications)) : ?>
                <div class="alert alert-info">Aucune notification lue.</div>
            <?php else : ?>
                <?php foreach ($readNotifications as $notification) : ?>
                    <?php
                        $isEnable = $notification['is_read'];
                        $enableClass = $isEnable ? 'bi-eye' : 'bi-eye-slash';
                        $enableAction = $isEnable ? 'masquer' : 'visible';
                    ?>
                    <div class="card mb-3 bg-light">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">
                                    <?= htmlspecialchars($notification['type']); ?>
                                    <?php if (!$notification['is_read']) : ?>
                                        <span class="badge bg-danger">NEW</span>
                                    <?php endif; ?>
                                </h5>
                                <p class="card-text"><?= htmlspecialchars($notification['messageNotification']); ?></p>
                                <p class="card-text"><small class="text-muted"><?= date("d-m-Y H:i", strtotime($notification['dateNotification'])); ?></small></p>
                            </div>
                            <div>
                                <form method="POST" action="php/code_php/mark_notification_read.php" style="display:inline;">
                                    <input type="hidden" name="idNotification" value="<?= $notification['idNotification']; ?>">
                                    <button type="submit" name="action" value="<?= $enableAction; ?>" class="btn btn-outline-success me-2"><i class="bi <?= $enableClass; ?>"></i></button>
                                </form>
                                <form method="POST" action="php/code_php/delete_notification.php" style="display:inline;">
                                    <input type="hidden" name="idNotification" value="<?= $notification['idNotification']; ?>">
                                    <button type="submit" name="action" value="supprimer" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php else : ?>
        <div class="alert alert-danger">Vous devez être connecté pour voir vos notifications.</div>
    <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
