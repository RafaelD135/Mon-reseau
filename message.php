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

			<section id="messages"></section>
			<script>
				setInterval('load_messages()',500);
				function load_messages()
				{
					$('#messages').load('./php/element_page/loadMessages.php');
				}
			</script>
		</div>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>