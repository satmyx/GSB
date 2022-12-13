<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Outils\Utilitaires;

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idVisiteur = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$montants = 0;
$date = date('Ym');
switch ($action) {
  case 'selectionnerSuiviVisiteur':
    if (empty($pdo->getVisiteurFromMoisVA($date))) {
      ?></br><?php
      Utilitaires::ajouterErreur("Aucun visiteur n'a de fiche de frais validée ce mois ci");
      include PATH_VIEWS . 'v_erreurs.php';
      include PATH_VIEWS . '/comptable/v_listeVisiteur.php';
    } else {
      $_SESSION['date'] = $date;
      $lesVisiteurs = $pdo->getVisiteurFromMoisVA($date);
      $selectedValue = $lesVisiteurs[0];
      include PATH_VIEWS . '/comptable/v_listeVisiteur.php';
    }
    break;
  case 'FicheFraisSP':
    $idVisi = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lesVisiteurs = $pdo->getVisiteurFromMoisVA($_SESSION['date']);
    $selectedValue = $idVisi;
    include PATH_VIEWS . '/comptable/v_listeVisiteur.php';
    $nomVis = (filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    trim($nomVis);
    $_SESSION['visiteur'] = $idVisi;
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($_SESSION['visiteur'], $_SESSION['date']);
    $lesFraisForfait = $pdo->getLesFraisForfait($_SESSION['visiteur'], $_SESSION['date']);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_SESSION['visiteur'], $_SESSION['date']);
    include PATH_VIEWS . '/comptable/v_etatFraisComptable.php';
    $_SESSION['montant'] = $montants;
    break;
  case 'Valider' :
    $pdo->validerFicheDeFraisVA($_SESSION['visiteur'], $_SESSION['date'], $_SESSION['montant']);
    ?>
    <div class = "alert alert-success" role = "alert">
      <p>Votre fiche de frais a bien été mise en paiement ! <a href = "index.php?uc=suivrePaiement&action=selectionnerSuiviVisiteur">Cliquez ici</a>
        pour revenir à la selection.</p>
    </div>
  <?php
}