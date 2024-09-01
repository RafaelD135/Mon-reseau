<?php
	session_start();
	require_once(__DIR__ . '/../../config/mysql.php');
	require_once(__DIR__ . '/databaseconnect.php');
	require_once(__DIR__ . '/functions.php');
	require_once(__DIR__ . '/variables.php');

$postData = $_POST;

if (isset($postData['email']) &&  isset($postData['password'])) {
	if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['LOGIN_ERROR_MESSAGE'] = 'Il faut un email valide pour soumettre le formulaire.';
	} else {
		foreach ($users as $user) {
			if (
				$user['mailUtilisateur'] === $postData['email'] &&
				$user['mdpUtilisateur'] === $postData['password']
			) {
				$_SESSION['LOGGED_USER'] = [
					'email' => $user['mailUtilisateur'],
					'user_id' => $user['idUtilisateur'],
					'pseudo' => $user['pseudoUtilisateur'],
					'prenom' => $user['prenomUtilisateur'],
					'nom' => $user['nomUtilisateur'],
					'pdp' => $user['cheminPdpUtilisateur'],
				];
				redirectToUrl('../../index.php');
			}
		}

		if (!isset($_SESSION['LOGGED_USER'])) {
			$_SESSION['LOGIN_ERROR_MESSAGE'] = sprintf(
				'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
				$postData['email'],
				strip_tags($postData['password'])
			);
			redirectToUrl('../../login.php');
		}
	}
}
redirectToUrl('../../login.php');