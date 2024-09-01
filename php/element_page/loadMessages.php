<?php
session_start();
require_once(__DIR__ . '/../../config/mysql.php');
require_once(__DIR__ . '/../code_php/databaseconnect.php');
require_once(__DIR__ . '/../code_php/functions.php');
require_once(__DIR__ . '/../code_php/variables.php');

if (isset($_SESSION['LOGGED_USER'])) {
    $stmt = $mysqlClient->prepare('SELECT 
                                        u.pseudoUtilisateur AS pseudo_autre_utilisateur,
                                        u.cheminPdpUtilisateur AS pdp_autre_utilisateur,
                                        u.idUtilisateur AS id_autre_utilisateur,
                                        mp.messagePrive AS dernier_message,
                                        mp.dateMessagePrive AS date_dernier_message,
                                        mp.is_read AS est_lu,
                                        mp.idEnvoyeur AS id_envoyeur
                                    FROM 
                                        messageprive mp
                                    INNER JOIN 
                                        utilisateur u ON 
                                        (
                                            (mp.idEnvoyeur = u.idUtilisateur AND mp.idDestinataire = :mon_id) 
                                            OR 
                                            (mp.idDestinataire = u.idUtilisateur AND mp.idEnvoyeur = :mon_id)
                                        )
                                    WHERE 
                                        u.idUtilisateur != :mon_id
                                    AND 
                                        mp.dateMessagePrive = (
                                            SELECT 
                                                MAX(mp2.dateMessagePrive)
                                            FROM 
                                                messageprive mp2
                                            WHERE 
                                                (mp2.idEnvoyeur = u.idUtilisateur AND mp2.idDestinataire = :mon_id)
                                                OR 
                                                (mp2.idDestinataire = u.idUtilisateur AND mp2.idEnvoyeur = :mon_id)
                                        )
                                    ORDER BY 
                                        dateMessagePrive DESC;
                                    ');
    $stmt->execute([
        'mon_id' => $_SESSION['LOGGED_USER']['user_id'],
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h3>Vos discussions</h3>

<?php if (isset($_SESSION['LOGGED_USER'])) : ?>
    <?php if (empty($messages)) : ?>
        <div class="alert alert-info">Vous n'avez aucune conversation pour le moment.</div>
    <?php else : ?>
        <?php foreach ($messages as $message) : ?>
            <?php 
                $envoyeur = $message['id_envoyeur'] == $_SESSION['LOGGED_USER']['user_id'] ? 'moi' : 'autre';
                $statut = $envoyeur == 'moi' ? 'Envoyé' : 'Reçu';

            ?>
            <form method="POST" action="discussion.php">
                <input type="hidden" name="id_autre_utilisateur" value="<?= htmlspecialchars($message['id_autre_utilisateur']); ?>">
                <div class="card mb-3" style="cursor: pointer; border-radius: 0.5rem;" onclick="this.closest('form').submit();">
                    <div class="card-body d-flex align-items-center">
                        <div class="position-relative me-3">
                            <img src="<?= htmlspecialchars($message['pdp_autre_utilisateur']); ?>" alt="PDP" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                        </div>

                        <div class="flex-grow-1">
                        <h5 class="card-title mb-1 d-flex align-items-center">
                            <?= htmlspecialchars($message['pseudo_autre_utilisateur']); ?>
                            <?= ($envoyeur == 'moi') ? '' : ($message['est_lu'] ? '' : '<span class="ms-2 badge bg-primary">New</span>')?>
                        </h5>


                            <?php if($envoyeur == 'moi') : ?>
                                <p class="card-text text-muted mb-0"><?= htmlspecialchars($message['dernier_message']); ?></p>
                            <?php elseif(!$message['est_lu']) : ?>
                                <p class="card-text mb-0 text-primary fw-bold">Nouveau message(s)</p>
                            <?php else : ?>
                                <p class="card-text text-muted mb-0"><?= htmlspecialchars($message['dernier_message']); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="text-muted ms-2">
                            <small><?= date("d-m-Y H:i", strtotime($message['date_dernier_message'])); ?></small>
                            <div class="mt-2 d-flex align-items-center">
                                <?php if ($envoyeur == 'moi') : ?>
                                    <i class="bi <?= $message['est_lu'] ? 'bi-check-all' : 'bi-check'; ?> text-muted"></i>
                                    <span class="ms-2"><?= $message['est_lu'] ? 'Lu' : $statut; ?></span>
                                <?php else : ?>
                                    <span><?= $statut; ?></span>
                                    <i class="bi bi-arrow-right text-muted ms-2"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else : ?>
    <div class="alert alert-danger">Vous devez être connecté pour voir vos messages privés.</div>
<?php endif; ?>
