<?php
	session_start();
	require_once(__DIR__ . '/config/mysql.php');
	require_once(__DIR__ . '/php/code_php/databaseconnect.php');
	require_once(__DIR__ . '/php/code_php/functions.php');
	require_once(__DIR__ . '/php/code_php/variables.php');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>

	<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
			rel="stylesheet"
	>

</head>
<body>
	<!-- inclusion de l'entête du site -->
	<?php require_once(__DIR__ . '/php/element_page/header.php'); ?>

	<div class="container">
		<?php if (!isset($_SESSION['LOGGED_USER'])) : ?>
			<form action="php/code_php/submitlogin.php" method="POST">
				<!-- si message d'erreur on l'affiche -->
				<?php if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
					<div class="alert alert-danger" role="alert">
						<?php echo $_SESSION['LOGIN_ERROR_MESSAGE'];
						unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
					</div>
				<?php endif; ?>
				<div class="mb-3">
					<label for="email" class="form-label">Email</label>
					<input type="email" class="form-control" id="email" name="email" aria-describedby="email-help" placeholder="you@exemple.com">
					<div id="email-help" class="form-text">L'email utilisé lors de la création de compte.</div>
				</div>
				<div class="mb-3">
					<label for="password" class="form-label">Mot de passe</label>
					<input type="password" class="form-control" id="password" name="password">
				</div>
				<button type="submit" class="btn btn-primary">Envoyer</button>
			</form>
			<!-- Si utilisateur/trice bien connectée on affiche un message de succès -->
		<?php else : ?>
			<div class="alert alert-success" role="alert">
				Bonjour <?php echo $_SESSION['LOGGED_USER']['email']; ?> et bienvenue sur le site !
			</div>
		<?php endif; ?>
	</div>
</body>
</html>


