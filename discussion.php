<?php
    session_start();
    require_once(__DIR__ . '/config/mysql.php');
    require_once(__DIR__ . '/php/code_php/databaseconnect.php');
    require_once(__DIR__ . '/php/code_php/functions.php');
    require_once(__DIR__ . '/php/code_php/variables.php');

    if (!isset($_SESSION['LOGGED_USER'])) {
        header('Location: login.php');
        exit;
    }

    if (isset($_POST['id_autre_utilisateur'])) {
        $_SESSION['id_autre_utilisateur'] = $_POST['id_autre_utilisateur'];
    }

    if (!isset($_SESSION['id_autre_utilisateur'])) {
        echo "Erreur : Aucun utilisateur sélectionné pour cette discussion.";
        exit;
    }

    $mon_id = $_SESSION['LOGGED_USER']['user_id'];
    $id_autre_utilisateur = $_SESSION['id_autre_utilisateur'];

    $stmt = $mysqlClient->prepare('UPDATE messageprive SET is_read = true WHERE idEnvoyeur = :autre_id AND idDestinataire = :mon_id');
    $stmt->execute([
    'mon_id' => $mon_id,
    'autre_id' => $id_autre_utilisateur
    ]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 95px); /* 100% de la hauteur de l'écran moins la hauteur du header Bootstrap */
            overflow: hidden;
        }

        .chat-messages {
            flex-grow: 1;
            overflow-y: auto; /* Ajoute une scrollbar verticale */
            padding: 15px;
            background-color: #f5f5f5;
        }

        .new-message-form {
            background-color: white;
            padding: 15px;
            border-top: 1px solid #ddd;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <?php require_once(__DIR__ . '/php/element_page/header.php'); ?>
    <div class="container">

        <div class="chat-container">
            <div class="chat-messages">
                <section id="discussion"></section>
            </div>
            <form action="./php/code_php/send_message.php" method="POST" class="mt-3 new-message-form">
                <div class="input-group">
                    <input type="hidden" name="id_destinataire" value="<?= htmlspecialchars($id_autre_utilisateur); ?>">
                    <input type="text" name="message" class="form-control" placeholder="Écrire un message..." required>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Charger la discussion initialement
        $(document).ready(function() {
            load_discussion(function() {
                const chatMessages = document.querySelector('.chat-messages');
                chatMessages.scrollTop = chatMessages.scrollHeight; // Défilement initial au bas de la conversation
            });
        });

        // Charger la discussion toutes les 500ms
        setInterval(load_discussion, 500);

        // Fonction pour charger la discussion
        function load_discussion(callback) {
            $('#discussion').load('./php/element_page/loadDiscussion.php', function() {
                if (callback) callback(); // Appelle le callback après le premier chargement
            });
        }
    </script>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
