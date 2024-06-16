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
	<!-- inclusion de l'entête du site -->
	<?php require_once(__DIR__ . '/php/element_page/header.php'); ?>
	
	<div class="container">
		<?php if (isset($_SESSION['LOGGED_USER'])) : ?>

			<h1>
				<?php echo $profile['pseudo']; ?>
			</h1>

			<!-- Si utilisateur/trice n'est pas connectée on affiche un message d'erreur -->
		<?php else : ?>
			<div class="alert alert-danger" role="alert">
				Vous devez être connecté pour accéder à cette page !
			</div>
		<?php endif; ?>
	</div>
</body>
</html>


