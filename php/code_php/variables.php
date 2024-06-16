<?php

// Récupération des variables à l'aide du client MySQL
	$usersStatement = $mysqlClient->prepare('SELECT * FROM Utilisateur');
	$usersStatement->execute();
	$users = $usersStatement->fetchAll();