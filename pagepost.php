<?php
	session_start();
	require_once(__DIR__ . '/config/mysql.php');
	require_once(__DIR__ . '/php/code_php/databaseconnect.php');
	require_once(__DIR__ . '/php/code_php/functions.php');
	require_once(__DIR__ . '/php/code_php/variables.php');

	// Vérifiez si le post_id est défini dans $_POST ou $_GET, sinon redirigez ou affichez un message d'erreur
	if (!isset($_POST['post_id']) && !isset($_GET['post_id'])) {
		die('Post ID is required.');
	}
	
	$postId = isset($_POST['post_id']) ? (int) $_POST['post_id'] : (int) $_GET['post_id'];

	// Informations du post
	$postStatement = $mysqlClient->prepare('SELECT * FROM post WHERE idPost = :idPost;');
	$postStatement->execute([
	    'idPost' => $postId,
	]);
	$post = $postStatement->fetchAll(PDO::FETCH_ASSOC);

	// Informations utilisateur du post
	$userStatement = $mysqlClient->prepare('SELECT cheminPdpUtilisateur,pseudoUtilisateur,idUtilisateur FROM utilisateur WHERE idUtilisateur = :idUtilisateur;');
	$userStatement->execute([
	    'idUtilisateur' => $post[0]['idUtilisateur'],
	]);
	$utilisateur = $userStatement->fetchAll(PDO::FETCH_ASSOC);

	// Likes de la personne connect
	if (isset($_SESSION['LOGGED_USER']))
	{
		// Récupération des likes de l'utilisateur connecté
		$likeStatement = $mysqlClient->prepare('SELECT idPost FROM jaime WHERE idUtilisateur = :idUtilisateur;');
		$likeStatement->execute(['idUtilisateur' => $_SESSION['LOGGED_USER']['user_id']]);
		$likedPosts = $likeStatement->fetchAll(PDO::FETCH_COLUMN, 0); // Retourne un tableau des idPost aimés
	}

		// Commentaire du post
		$postStatement = $mysqlClient->prepare('SELECT idCommentaire,commentaire,dateCommentaire,is_enabled,cheminPdpUtilisateur,pseudoUtilisateur,U.idUtilisateur FROM commentaire C
		                                        JOIN utilisateur U ON C.idUtilisateur = U.idUtilisateur WHERE C.idPost = :idPost ORDER BY dateCommentaire DESC;');
		$postStatement->execute([
			'idPost' => $postId,
		]);
		$commentaires = $postStatement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Index</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

		<?php require_once(__DIR__ . '/php/element_page/header.php'); ?>

		<div class="container">
			<?php
				if (isset($_SESSION['LOGGED_USER']))
				{
					$isLiked = in_array($post[0]['idPost'], $likedPosts); // Vérifie si le post est aimé
					$likeClass = $isLiked ? 'bi-heart-fill' : 'bi-heart'; // Classe d'icône
					$likeAction = $isLiked ? 'dislike' : 'like'; // Action du bouton
				}
			?>
			<div class="card mb-3">
				<div class="card-header d-flex align-items-center">
					<form method="GET" action="profil.php" class="me-0 ml-0">
						<button type="submit" name="id" value="<?= $post[0]['idUtilisateur']; ?>" class="btn p-0">
							<img src="<?= $utilisateur[0]['cheminPdpUtilisateur']; ?>" alt="Profile Picture" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
						</button>
					</form>
					<div>
						<h5 class="card-title mb-0"><?= $utilisateur[0]['pseudoUtilisateur']; ?></h5>
						<small class="text-muted">Membre depuis le <?= date("d-m-Y", strtotime($post[0]['dateCreationPost'])); ?></small>
					</div>
				</div>
				<div class="card-body">
					<p class="card-text"><?= $post[0]['descriptionPost']; ?></p>
					<?php if ($post[0]['cheminImagePost']) { ?>
						<img src="<?= $post[0]['cheminImagePost']; ?>" class="card-img-top img-fluid" style="max-height: 300px; width: auto;" alt="image_post">
					<?php } ?>

					<div class="d-flex justify-content-between align-items-center mt-3">
						<div class="d-flex align-items-center">
							<?php
								if (isset($_SESSION['LOGGED_USER']))
								{ ?>
									<form method="POST" action="php/code_php/handlelikePost.php" class="me-2">
										<input type="hidden" name="post_id" value="<?= $post[0]['idPost']; ?>">
										<button type="submit" name="action" value="<?= $likeAction; ?>" class="btn btn-outline-primary">
											<i class="bi <?= $likeClass; ?>"></i> <?= $post[0]['nbLikePost']; ?>
										</button>
									</form>
								<?php }
								else 
								{ ?>
									<button class="btn btn-outline-primary me-2" disabled>
										<i class="bi bi-heart-fill"></i> <?= $post[0]['nbLikePost']; ?>
									</button>
								<?php } ?>
						</div>
						<form method="POST" action="pagepost.php" class="me-2">
							<input type="hidden" name="post_id" value="<?= $post[0]['idPost']; ?>">
							<button type="submit" name="action" value="commentaire" class="btn btn-outline-secondary" disabled>
								<i class="bi bi-chat-dots"></i> Commentaires
							</button>
						</form>
					</div>
				</div>
			</div>

			<?php if (isset($_SESSION['LOGGED_USER'])) { ?>
				<div class="card mt-3">
					<div class="card-body">
						<?php if (isset($_SESSION['ERROR_COMMENTAIRE'])) : ?>
							<div class="alert alert-danger" role="alert">
								<?php echo htmlspecialchars($_SESSION['ERROR_COMMENTAIRE']);
								unset($_SESSION['ERROR_COMMENTAIRE']); ?>
							</div>
						<?php endif; ?>
						<form method="POST" action="php/code_php/newComment.php">
							<div class="mb-3">
								<textarea class="form-control" name="commentaire" rows="3" placeholder="Ajouter un commentaire..."></textarea>
								<input type="hidden" name="post_id" value="<?= $post[0]['idPost']; ?>">
							</div>
							<button type="submit" class="btn btn-primary mb-3">Commenter</button>
						</form>
					</div>
				</div>
			<?php } else { ?>
				<div class="alert alert-info mt-3">Connectez-vous pour ajouter un commentaire.</div>
			<?php } ?>
			
			<div class="card mt-3 mb-5">
				<div class="card-body">
					<h5 class="card-title mb-4">Commentaires (<?= $post[0]['nbCommentaires'];?>)</h5> 
					<?php
						if ($commentaires == null)
						{
							?>
								<h6>Aucun commentaire pour le moment.</h6>
							<?php
						}
					?>
					<?php foreach ($commentaires as $commentaire) { ?>
						<div class="card mb-3">
							<div class="card-body d-flex align-items-start">
								<div class="d-flex justify-content-between w-100">
									<div class="d-flex align-items-start">
										<form method="GET" action="profil.php" class="me-0 ml-0">
											<button type="submit" name="id" value="<?= $commentaire['idUtilisateur']; ?>" class="btn p-0">
												<img src="<?= $commentaire['cheminPdpUtilisateur']; ?>" alt="Profile Picture" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
											</button>
										</form>
										<div>
											<h6 class="card-subtitle mb-1"><?= $commentaire['pseudoUtilisateur']; ?> <small class="text-muted"><?= date("d-m-Y H:i", strtotime($commentaire['dateCommentaire'])); ?></small></h6>
											<p class="card-text"><?= $commentaire['commentaire']; ?></p>
										</div>
									</div>

									<?php if (isset($_SESSION['LOGGED_USER']) && ($_SESSION['LOGGED_USER']['user_id'] == $commentaire['idUtilisateur'] || $_SESSION['LOGGED_USER']['user_id'] == $post[0]['idUtilisateur'])) { ?>
										<form method="POST" action="php/code_php/deletecomment.php">
											<input type="hidden" name="idComm" value="<?= $commentaire['idCommentaire']; ?>">
											<input type="hidden" name="idPost" value="<?= $post[0]['idPost']; ?>">
											<button type="submit" name="action" value="delete" class="btn btn-outline-danger ms-3">
												<i class="bi bi-trash"></i>
											</button>
										</form>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>

		</div>



	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>