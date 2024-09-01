<?php 
	session_start();
	require_once(__DIR__ . '/../../config/mysql.php');
	require_once(__DIR__ . '/databaseconnect.php');
	require_once(__DIR__ . '/functions.php');
	require_once(__DIR__ . '/variables.php');


$image = $_FILES;

if ( isset($image['pdp']) && $image['pdp']['error'] == 0)
{
	if ($image['pdp']['size'] > 1000000)
	{
		$_SESSION['LOGIN_ERROR_MESSAGE'] = 'Fichier trop volumineux';
		redirectToUrl('../../configprofil.php');
	}

	$fileInfo = pathinfo($image['pdp']['name']);
	$extension = $fileInfo['extension'];
	$allowedExtensions = ['jpg','jpeg'];
	if (!in_array($extension,$allowedExtensions))
	{
		$_SESSION['LOGIN_ERROR_MESSAGE'] = 'Extension invalide (jpg,jpeg).';
		redirectToUrl('../../configprofil.php');
	}

	$path = __DIR__ . '/../../image/pdp';
	if (!is_dir($path))
	{
		$_SESSION['LOGIN_ERROR_MESSAGE'] = 'Le dossier uploads est manquant';
		redirectToUrl('../../configprofil.php');
	}

	move_uploaded_file($image['pdp']['tmp_name'], $path . $_SESSION['LOGGED_USER']['user_id'] . '.' . $extension);
	$_SESSION['IMAGE_MESSAGE'] = 'Image modifier avec succes !';

	$changementImage = $mysqlClient->prepare('
						UPDATE utilisateur
						SET cheminPdpUtilisateur = :chemin
						WHERE idUtilisateur = :idUser;
						');
	$changementImage->execute([
					'chemin' => 'image/' . 'pdp' . $_SESSION['LOGGED_USER']['user_id'] . '.' . $extension,
					'idUser' => $_SESSION['LOGGED_USER']['user_id'],
					]);

	$_SESSION['LOGGED_USER']['pdp'] = 'image/' . 'pdp' . $_SESSION['LOGGED_USER']['user_id'] . '.' . $extension;
	
	redirectToUrl('../../configprofil.php');
}
redirectToUrl('../../configprofil.php');