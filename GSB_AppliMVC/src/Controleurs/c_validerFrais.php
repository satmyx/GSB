<?php

use Outils\Utilitaires;

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idVisiteur=$_SESSION['idVisiteur'];
switch ($action) {

case 'selectionVisiteur':
    $lesVisiteurs = $pdo->getToutLesVisiteurs();
    include PATH_VIEWS . 'v_listeVisiteurs.php';
    break;

case 'selectionMois':
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
    // on demande toutes les clés, et on prend la première,
    // les mois étant triés décroissants
    $lesCles = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
    include PATH_VIEWS . 'v_listeMois.php';
    break;


case 'validationFrais':
    $unVisiteur = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lesVisiteurs = $pdo->getToutLesVisiteurs();
    $visiteurASelectionner = $unVisiteur;
    include PATH_VIEWS . 'v_listeVisiteurs.php';
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
    $moisASelectionner = $leMois;
    include PATH_VIEWS . 'v_listeMois.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include PATH_VIEWS . 'v_etatFrais.php';
}