<?php
	session_start();
	require_once(__DIR__ . '/../../config/mysql.php');
	require_once(__DIR__ . '/../code_php/databaseconnect.php');
	require_once(__DIR__ . '/../code_php/functions.php');
	require_once(__DIR__ . '/../code_php/variables.php');

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($filter === 'all') {
	$postStatement = $mysqlClient->prepare('SELECT * FROM post WHERE is_enabled = 1 ORDER BY dateCreationPost DESC;');
	$postStatement->execute();
	$posts = $postStatement->fetchAll(PDO::FETCH_ASSOC);

	if (isset($_SESSION['LOGGED_USER']))
	{
		$likeStatement = $mysqlClient->prepare('SELECT idPost FROM jaime WHERE idUtilisateur = :idUtilisateur;');
		$likeStatement->execute(['idUtilisateur' => $_SESSION['LOGGED_USER']['user_id']]);
		$likedPosts = $likeStatement->fetchAll(PDO::FETCH_COLUMN, 0);
	}
} else {
	$postStatement = $mysqlClient->prepare('SELECT * FROM post p WHERE p.is_enabled = 1 AND p.idUtilisateur IN (SELECT s.idSuivi FROM suivre s WHERE s.idUtilisateur=:monId) ORDER BY p.dateCreationPost DESC;');
	$postStatement->execute(['monId' => $_SESSION['LOGGED_USER']['user_id']]);
	$posts = $postStatement->fetchAll(PDO::FETCH_ASSOC);

	if (isset($_SESSION['LOGGED_USER']))
	{
		$likeStatement = $mysqlClient->prepare('SELECT idPost FROM jaime WHERE idUtilisateur = :idUtilisateur;');
		$likeStatement->execute(['idUtilisateur' => $_SESSION['LOGGED_USER']['user_id']]);
		$likedPosts = $likeStatement->fetchAll(PDO::FETCH_COLUMN, 0);
	}
}

foreach ($posts as $post) {
            if ( $post['is_enabled'] == 1)
            {
				if (isset($_SESSION['LOGGED_USER']))
				{
					$isLiked = in_array($post['idPost'], $likedPosts); // Vérifie si le post est aimé
					$likeClass = $isLiked ? 'bi-heart-fill' : 'bi-heart'; // Classe d'icône
					$likeAction = $isLiked ? 'dislike' : 'like'; // Action du bouton
				}

				$userStatement = $mysqlClient->prepare('SELECT cheminPdpUtilisateur, creationUtilisateur, pseudoUtilisateur, descriptionUtilisateur,nbFollower FROM utilisateur WHERE idUtilisateur = :idUtilisateur;');
				$userStatement->execute([
					'idUtilisateur' => $post['idUtilisateur'],
				]);
				$utilisateur = $userStatement->fetch(PDO::FETCH_ASSOC);

            ?>
            <div class="card mb-3">
			<div class="card-header d-flex align-items-center">
				<form method="GET" action="profil.php" class="me-0 ml-0">
					<button type="submit" name="id" value="<?= $post['idUtilisateur']; ?>" class="btn p-0">
						<img src="<?= $utilisateur['cheminPdpUtilisateur']; ?>" alt="Profile Picture" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
					</button>
				</form>
				<div class="ms-2">
					<strong class="me-auto"><?= $utilisateur['pseudoUtilisateur']; ?></strong>
					<p class="mb-0 text-muted"><small><?= $utilisateur['nbFollower']; ?> Follower(s)</small></p>
				</div>
				<small class="ms-auto text-muted"><?= $post['dateCreationPost']; ?></small>
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
                    </div>
                </div>
            </div>
            <?php } 
            }
            ?>

		</div>