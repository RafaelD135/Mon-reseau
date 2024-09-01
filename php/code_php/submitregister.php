<?php
	session_start();
	require_once(__DIR__ . '/../../config/mysql.php');
	require_once(__DIR__ . '/databaseconnect.php');
	require_once(__DIR__ . '/functions.php');
	require_once(__DIR__ . '/variables.php');


	$date = new DateTime('');
	$date = $date->format('Y-m-d');

$postData = $_POST;

$age = Age($postData['naissance']);
$bret = false;

if (isset($postData['email']) &&  isset($postData['password']) && isset($postData['nom'])  && isset($postData['prenom'])  && isset($postData['pseudo']) && isset($postData['naissance'])) {

	if (!empty($postData['email']) && !empty($postData['password']) && !empty($postData['nom']) && !empty($postData['prenom']) && !empty($postData['pseudo']) && !empty($postData['naissance'])) {

		if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
			$_SESSION['LOGIN_ERROR_MESSAGE'] = 'Il faut un email valide pour soumettre le formulaire.';
			redirectToUrl('../../register.php');
		} else {

			if ($age >= 15)
			{
				foreach ($users as $user) {
					if ($user['mailUtilisateur'] === $postData['email'])
					{
						$_SESSION['LOGIN_ERROR_MESSAGE'] = 'L\'email choisie est déjà utilisé';
						redirectToUrl('../../register.php');
					}
					if (strtolower($user['pseudoUtilisateur']) ===  strtolower($postData['pseudo']))
					{
						$_SESSION['LOGIN_ERROR_MESSAGE'] = 'Le pseudo est déjà utilisé';
						redirectToUrl('../../register.php');
					}
				}
			} else {
				$_SESSION['LOGIN_ERROR_MESSAGE'] = "Vous devez avoir 15 ans minimum";
				redirectToUrl('../../register.php');
			}
		}
	}
	else {
		$_SESSION['LOGIN_ERROR_MESSAGE'] = 'Veuillez remplir tous les champs obligatoire';
		redirectToUrl('../../register.php');
	}
}


$creationUtilisateur = $mysqlClient->prepare('
						INSERT INTO utilisateur 
						(mailUtilisateur,mdpUtilisateur,prenomUtilisateur,cheminPdpUtilisateur,creationUtilisateur,pseudoUtilisateur,nomUtilisateur,descriptionUtilisateur,ageUtilisateur, dernierLoginUtilisateur)
						VALUES (:mail,:mdp,:prenom,:pdp,:creation,:pseudo,:nom,:description,:age,:dernierLogin);
						');
						$creationUtilisateur->execute([
							'mail' => $postData['email'],
							'mdp'=> $postData['password'],
							'prenom'=> $postData['prenom'],
							'nom' => $postData['nom'],
							'pdp'=> "image/default.png",
							'creation' => $date,
							'pseudo' => $postData['pseudo'],
							'description' => null,
							'age' => $postData['naissance'],
							'dernierLogin' => $date,
						]);

						$_SESSION['LOGGED_USER'] = [
							'email' => $user['mailUtilisateur'],
							'user_id' => $user['idUtilisateur'],
							'pseudo' => $user['pseudoUtilisateur'],
							'prenom' => $user['prenomUtilisateur'],
							'nom' => $user['nomUtilisateur'],
							'pdp' => $user['cheminPdpUtilisateur'],
						];

redirectToUrl('../../register.php');