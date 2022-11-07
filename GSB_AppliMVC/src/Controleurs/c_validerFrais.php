<?php

use Outils\Utilitaires;

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$lesVisiteurs = $pdo->getToutLesVisiteurs();

switch ($action) {

case 'selectionVisiteur':
    include PATH_VIEWS . 'v_listeVisiteurs.php';
    break;

case 'selectionMois':
    $_SESSION['lstVisiteur'] = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $leVisiteurSelect = $_SESSION['lstVisiteur'];
    $lesMois = $pdo->getLesMoisDisponibles($leVisiteurSelect);
    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
    // on demande toutes les clés, et on prend la première,
    // les mois étant triés de manière décroissantes
    $lesCles = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
    include PATH_VIEWS . 'v_listeMois.php';
    break;


case 'validationFrais':
    $_SESSION['lstVisiteur'] = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $leVisiteurSelect = $_SESSION['lstVisiteur'];
    $lesVisiteurs = $pdo->getToutLesVisiteurs();
    include PATH_VIEWS . 'v_listeVisiteurs.php';
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lesMois = $pdo->getLesMoisDisponibles($leVisiteurSelect);
    $moisASelectionner = $leMois;
    include PATH_VIEWS . 'v_listeMois.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteurSelect, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteurSelect, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteurSelect, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include PATH_VIEWS . 'v_etatFrais.php';
}