<?php

function redirectToUrl(string $url): never
{
    header("Location: {$url}");
    exit();
}

function Age($dateNaissance)
{
    // Convertir la date de naissance en objet DateTime
    $dateNaissance = new DateTime($dateNaissance);
    // Obtenir la date actuelle
    $dateActuelle = new DateTime();
    // Calculer l'intervalle entre la date de naissance et la date actuelle
    $interval = $dateActuelle->diff($dateNaissance);
    // Retourner l'âge en années
    return $interval->y;
}