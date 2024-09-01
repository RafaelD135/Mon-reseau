<?php
    session_start();
    require_once(__DIR__ . '/../../config/mysql.php');
    require_once(__DIR__ . '/../code_php/databaseconnect.php');
    require_once(__DIR__ . '/../code_php/functions.php');
    require_once(__DIR__ . '/../code_php/variables.php');

    if (!isset($_SESSION['LOGGED_USER']) || !isset($_SESSION['id_autre_utilisateur'])) {
        echo "Erreur : Accès non autorisé.";
        exit;
    }

    $mon_id = $_SESSION['LOGGED_USER']['user_id'];
    $id_autre_utilisateur = $_SESSION['id_autre_utilisateur'];

    $stmt = $mysqlClient->prepare('SELECT mp.*, u.pseudoUtilisateur, u.cheminPdpUtilisateur
                                    FROM messageprive mp
                                    JOIN utilisateur u ON mp.idEnvoyeur = u.idUtilisateur
                                    WHERE (mp.idEnvoyeur = :mon_id AND mp.idDestinataire = :autre_id)
                                    OR (mp.idEnvoyeur = :autre_id AND mp.idDestinataire = :mon_id)
                                    ORDER BY mp.dateMessagePrive ASC');
    $stmt->execute([
        'mon_id' => $mon_id,
        'autre_id' => $id_autre_utilisateur
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php foreach ($messages as $message) : ?>
    <div class="message list-group-item border-0 <?= $message['idEnvoyeur'] == $mon_id ? 'text-end' : ''; ?>">
        <div class="d-flex <?= $message['idEnvoyeur'] == $mon_id ? 'justify-content-end' : ''; ?>">
            <?php if ($message['idEnvoyeur'] != $mon_id) : ?>
                <img src="<?= htmlspecialchars($message['cheminPdpUtilisateur']); ?>" alt="PDP" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
            <?php endif; ?>
            
            <div class="p-3 rounded shadow-sm <?= $message['idEnvoyeur'] == $mon_id ? 'bg-primary text-white' : 'bg-light'; ?>" style="max-width: 70%;">
                <p class="mb-1"><?= nl2br(htmlspecialchars($message['messagePrive'])); ?></p>
                <small class="<?= $message['idEnvoyeur'] == $mon_id ? 'text-white' : 'text-muted'; ?>"><?= date("d-m-Y H:i", strtotime($message['dateMessagePrive'])); ?></small>
            </div>
            
            <?php if ($message['idEnvoyeur'] == $mon_id) : ?>
                <img src="<?= htmlspecialchars($_SESSION['LOGGED_USER']['pdp']); ?>" alt="PDP" class="rounded-circle ms-3" style="width: 40px; height: 40px; object-fit: cover;">
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>




