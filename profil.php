<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/php/code_php/databaseconnect.php');
require_once(__DIR__ . '/php/code_php/functions.php');
require_once(__DIR__ . '/php/code_php/variables.php');

$idUser = isset($_GET['id']) ? intval($_GET['id']) : null;
if(isset($_SESSION['LOGGED_USER']))
{
    if ($idUser === null && isset($_SESSION['LOGGED_USER'])) {
        $idUser = $_SESSION['LOGGED_USER']['user_id'];
    }
}


if ($idUser === null) {
    echo 'Utilisateur non spécifié.';
    exit;
}

// Récupération des informations de l'utilisateur
$userStatement = $mysqlClient->prepare('SELECT cheminPdpUtilisateur, creationUtilisateur, pseudoUtilisateur, descriptionUtilisateur,nbFollower FROM utilisateur WHERE idUtilisateur = :idUtilisateur;');
$userStatement->execute([
    'idUtilisateur' => $idUser,
]);
$utilisateur = $userStatement->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    echo 'Utilisateur non trouvé.';
    exit;
}

// Récupération des posts de l'utilisateur
$postStatement = $mysqlClient->prepare('SELECT * FROM post WHERE idUtilisateur = :idUtilisateur ORDER BY dateCreationPost DESC;');
$postStatement->execute([
    'idUtilisateur' => $idUser,
]);
$postsId = $postStatement->fetchAll(PDO::FETCH_ASSOC);

if(isset($_SESSION['LOGGED_USER']))
{
    // Récupération des likes de l'utilisateur connecté
    $likeStatement = $mysqlClient->prepare('SELECT idPost FROM jaime WHERE idUtilisateur = :idUtilisateur;');
    $likeStatement->execute(['idUtilisateur' => $_SESSION['LOGGED_USER']['user_id']]);
    $likedPosts = $likeStatement->fetchAll(PDO::FETCH_COLUMN, 0); // Retourne un tableau des idPost aimés

    $followstm = $mysqlClient->prepare('SELECT idSuivi FROM suivre WHERE idUtilisateur = :idUtilisateur;');
    $followstm->execute(['idUtilisateur' => $_SESSION['LOGGED_USER']['user_id']]);
    $follows = $followstm->fetchAll(PDO::FETCH_COLUMN, 0); // Retourne un tableau des idPost aimés

    $isFollow = in_array($idUser, $follows); // Vérifie si il la personne connecter suit le compte
    $followClass = $isFollow ? 'bi-person-check' : 'bi-person-plus'; // Classe d'icône
    $followAction = $isFollow ? 'desuivre' : 'suivre'; // Action du bouton
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

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
                <img src="<?= $utilisateur['cheminPdpUtilisateur']; ?>" alt="Profile Picture" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <h5 class="card-title mb-0"><?= $utilisateur['pseudoUtilisateur']; ?></h5>
                    <p class="mb-0 text-muted"><small><?= $utilisateur['nbFollower']; ?> Follower(s)</small></p>
                    <small class="text-muted">Membre depuis le <?= date("d-m-Y", strtotime($utilisateur['creationUtilisateur'])); ?></small>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text"><?= $utilisateur['descriptionUtilisateur']; ?></p>
                <div class="d-flex justify-content-start">
                    <?php if (isset($_SESSION['LOGGED_USER']) && $_SESSION['LOGGED_USER']['user_id'] != $idUser) { ?>
                        <form method="POST" action="php/code_php/suivre.php" class="me-2">
                            <input type="hidden" name="suivi_id" value="<?= $idUser; ?>">
                            <button type="submit" name="action" value="<?= $followAction ;?>" class="btn btn-outline-primary">
                                <i class="<?= $followClass ;?>"></i> <?php echo $isFollow == true ? 'Suivi(e)' : 'Suivre' ;?>
                            </button>
                        </form>
                        <form method="POST" action="discussion.php" class="me-2">
                            <input type="hidden" name="id_autre_utilisateur" value="<?= $idUser; ?>">
                            <button type="submit" name="action" value="delete" class="btn btn-outline-success">
                                <i class="bi bi-envelope"></i> Envoyer un message
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Affichage des posts de l'utilisateur -->
        <?php foreach ($postsId as $post) {
            $isAfficher = false;
            if ( $post['is_enabled'] == 1 )
            {
                $isAfficher = true;
            }else
            {
                if(isset($_SESSION['LOGGED_USER']))
                {
                    if(!$post['is_enabled'] && $idUser == $_SESSION['LOGGED_USER']['user_id'])
                    {
                        $isAfficher = true;
                    }
                }
            }


            if ( $isAfficher)
            {
                if(isset($_SESSION['LOGGED_USER']))
                {
                    $isLiked = in_array($post['idPost'], $likedPosts); // Vérifie si le post est aimé
                    $likeClass = $isLiked ? 'bi-heart-fill' : 'bi-heart'; // Classe d'icône
                    $likeAction = $isLiked ? 'dislike' : 'like'; // Action du bouton
                }
                
                $isEnabled = $post['is_enabled'] == 1 ? True : False; // Vérifie si le post est activer
                $enabledClass = $isEnabled ? 'bi-eye-slash' : 'bi-eye'; // Classe d'icône
                $enabledAction = $isEnabled ? 'desactiver' : 'activer'; // Action du bouton
                ?>
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center <?php echo $isEnabled == 0 ? 'bg-dark text-white' : '' ;?>">
                        <img src="<?= $utilisateur['cheminPdpUtilisateur']; ?>" alt="Profile Picture" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                        <strong class="me-auto"><?= $utilisateur['pseudoUtilisateur']; ?></strong>
                        <small class="me-3 <?php echo $isEnabled == 0 ? 'text-white' : 'text-muted' ;?>"><?= $post['dateCreationPost']; ?></small>
                        <?php if (isset($_SESSION['LOGGED_USER']) && $_SESSION['LOGGED_USER']['user_id'] == $idUser) { ?>
                                <form method="POST" action="php/code_php/deletepost.php" class="me-2">
                                    <input type="hidden" name="post_id" value="<?= $post['idPost']; ?>">
                                    <button type="submit" name="action" value="delete" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                        <?php } ?>
                    </div>

                    <div class="card-body">
                        <?php if ($post['cheminImagePost'] != null) { ?>
                            <img src="<?= $post['cheminImagePost']; ?>" class="card-img-top img-fluid" style="max-height: 300px; width: auto;" alt="image_post">
                        <?php } ?>
                        <?php if ($post['descriptionPost'] != null) { ?>
                            <p class="card-text"><?= $post['descriptionPost']; ?></p>
                        <?php } ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                            <?php 
                                if (isset($_SESSION['LOGGED_USER']))
                                { ?>
                                    <form method="POST" action="php/code_php/handlelike.php" class="me-2">
                                        <input type="hidden" name="post_id" value="<?= $post['idPost']; ?>">
                                        <button type="submit" name="action" value="<?= $likeAction; ?>" class="btn btn-outline-primary">
                                            <i class="bi <?= $likeClass; ?>"></i> <?= $post['nbLikePost']; ?>
                                        </button>
                                    </form>
                                <?php }
                                else 
                                { ?>
                                    <button class="btn btn-outline-primary me-2" disabled>
                                        <i class="bi bi-heart-fill"></i> <?= $post['nbLikePost']; ?>
                                    </button>
                                <?php } ?>
                                <form method="POST" action="pagepost.php" class="me-2">
                                    <button type="submit" name="post_id" value="<?= $post['idPost']; ?>" class="btn btn-outline-secondary">
                                        <i class="bi bi-chat-dots"></i> <?= $post['nbCommentaires'];?>
                                    </button>
                                </form>
                            </div>
                            <?php if (isset($_SESSION['LOGGED_USER']) && $_SESSION['LOGGED_USER']['user_id'] == $idUser) { ?>
                                <form method="POST" action="php/code_php/handleenable.php" class="me-2">
                                    <input type="hidden" name="post_id" value="<?= $post['idPost']; ?>">
                                    <button type="submit" name="action" value="<?= $enabledAction; ?>" class="btn <?php echo $isEnabled == 1 ? 'btn-outline-danger' : 'btn-outline-success' ;?>">
                                        <i class="bi <?= $enabledClass; ?>"></i> <?php echo $enabledAction == 'desactiver' ? 'Masquer' : 'Rendre visible'; ?>
                                    </button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
