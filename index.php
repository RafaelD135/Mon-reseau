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
	<title>Index</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>

		<?php require_once(__DIR__ . '/php/element_page/header.php'); ?>
		<div class="container">
			<?php 
				if (isset($_SESSION['LOGGED_USER']))
				{
					require_once(__DIR__ . '/php/element_page/new_post.php');
				}
			?>

			<?php
			if (isset($_SESSION['LOGGED_USER'])) : ?>
				<div class="d-flex justify-content-center my-4">
					<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
						<input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
						<label class="btn btn-outline-primary" for="btnradio1">Tous les posts</label>

						<input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
						<label class="btn btn-outline-primary" for="btnradio2">Post des suivis</label>
					</div>
				</div>
			<?php endif; ?>


			<section id="post"></section>

			<script>
				$(document).ready(function() {
					// Fonction pour charger les posts en fonction du bouton sélectionné
					function loadPosts(filter) {
						$.ajax({
							url: './php/element_page/loadPost.php',
							type: 'GET',
							data: { filter: filter }, // Envoi la valeur du bouton sélectionné
							success: function(response) {
								$('#post').html(response); // Remplit la section .post avec la réponse
							}
						});
					}

					// Charger tous les posts par défaut (bouton "Tous les posts" est coché au départ)
					loadPosts('all');

					// Écouter les changements sur les boutons radio
					$('input[name="btnradio"]').change(function() {
						var selectedFilter = $(this).attr('id') === 'btnradio1' ? 'all' : 'followed';
						loadPosts(selectedFilter);
					});
				});
			</script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>