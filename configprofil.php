<?php
	session_start();
	require_once(__DIR__ . '/config/mysql.php');
	require_once(__DIR__ . '/php/code_php/databaseconnect.php');
	require_once(__DIR__ . '/php/code_php/functions.php');
	require_once(__DIR__ . '/php/code_php/variables.php');

	if (isset($_SESSION['LOGGED_USER']))
	{
		$profile = $_SESSION['LOGGED_USER'];
	}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register</title>

	<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
			rel="stylesheet"
	>

</head>
<body>
	<?php require_once(__DIR__ . '/php/element_page/header.php'); ?>
	
	<div class="container">
		<?php if (isset($_SESSION['LOGGED_USER'])) : ?>

			<h1>
				<?php echo $profile['pseudo']; ?>
			</h1>

			<?php if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
					<div class="alert alert-danger" role="alert">
						<?php echo $_SESSION['LOGIN_ERROR_MESSAGE'];
						unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
					</div>
			<?php endif; ?>
			<?php if (isset($_SESSION['IMAGE_MESSAGE'])) : ?>
				<div class="alert alert-success" role="alert">
					<?php echo $_SESSION['IMAGE_MESSAGE'];
						unset($_SESSION['IMAGE_MESSAGE']); ?>
				</div>
			<?php endif; ?>

			<form method="POST" action="php/code_php/sumbitimage.php" enctype="multipart/form-data" class="border border-primary mb-3" style="padding:10px;border-radius:10px;">
				<div class="mb-3">
					<label for="pdp" class="form-label"><img src="<?php echo $profile['pdp'];?>" alt="pdp" width="100px" height="100px" style="border-radius: 50%;"></label>
					<input type="file" class="form-control-file" id="pdp" name="pdp">
				</div>
				<button type="submit" class="btn btn-primary">Sauvegarder</button>
			</form>

			<form method="POST" class="border border-primary bg-secondary" style="padding:10px;border-radius:10px;margin-bottom:10px;">

				<div class="mb-3">
					<label for="email" class="form-label">Email *</label>
					<input type="email" class="form-control" id="email" name="email" aria-describedby="email-help" placeholder="you@exemple.com">
				</div>

				<div class="mb-3">
					<label for="nom" class="form-label">Nom</label>
					<input type="texte" class="form-control" id="nom" name="nom" value="<?= $profile['nom'] ?>">
				</div>

				<div class="mb-3">
					<label for="prenom" class="form-label">Prénom</label>
					<input type="text" class="form-control" id="prenom" name="prenom" value="<?= $profile['prenom'] ?>">
				</div>

				<div class="mb-3">
					<label for="password" class="form-label">Nouveau mot de passe</label>
					<input type="password" class="form-control" id="password" name="password">
				</div>

				<div class="mb-3">
					<label for="pseudo" class="form-label">Pseudo *</label>
					<input type="text" class="form-control" id="pseudo" name="pseudo">
				</div>

				<div class="mb-3">
					<label for="description" class="form-label">Biographie</label>
					<input type="text" class="form-control" id="description" name="description">
				</div>

				<div class="mb-3">
					<label for="naissance" class="form-label">Date de Naissance</label>
					<input type="date" class="form-control" id="naissance" name="naissance" max="<?php $date = new DateTime(''); echo $date->format('Y-m-d'); ?>">
				</div>

				<button type="submit" class="btn btn-primary">Sauvegarder</button>

			</form>

		<?php else : ?>
			<div class="alert alert-danger" role="alert">
				Vous devez être connecté pour accéder à cette page !
			</div>
		<?php endif; ?>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>


