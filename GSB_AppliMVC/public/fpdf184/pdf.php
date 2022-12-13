<?php

// Infos BDD
$bddname = 'gsb_frais';
$hostname = 'localhost';
$username = 'userGsb';
$password = 'secret';
$db = mysqli_connect($hostname, $username, $password, $bddname);

// FPDF
require("tfpdf.php");

// Création de la class PDF
class PDF extends tFPDF {

    // En-tête
    function Header() {

        // Positionnement à 1,5 cm du bas
        $this->SetY(50);
        // Logo
        $this->Image('../images/logo.jpg', 75, 6, 60);
        // Police Arial gras 15
        $this->SetFont('Arial', 'B', 15);
        // Décalage à droite
        $this->Cell(40);
        // Saut de ligne
        $this->Ln(20);
    }

// Pied de page
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
    }

}

// Instanciation de la classe dérivée
$pdf = new PDF();
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

// Titre
$pdf->SetY(55);
$pdf->SetX(50);
$pdf->SetTextColor(30, 73, 125); // Couleur texte
$pdf->SetDrawColor(30, 73, 125); // Couleur des lignes
$pdf->SetFont('Arial', 'B'); // Texte en gras
$pdf->Cell(110, 10, utf8_decode('REMBOURSEMENT DE FRAIS ENGAGÉS'), 1, 0, 'C');
$pdf->SetFont('Arial', ''); // Réinitilisation de l'épaisseur du texte

$unId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$unMois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$req = "SELECT id, CONCAT(nom, ' ', prenom)as nomvisiteur, mois FROM visiteur inner join lignefraisforfait on lignefraisforfait.idvisiteur=visiteur.id WHERE id='$unId' and mois='$unMois'";
$rep = mysqli_query($db, $req);
$row = mysqli_fetch_array($rep);

$pdf->SetTitle("Fiche de frais de" . ' ' . $row['nomvisiteur']);

// Infos de la commande
$pdf->SetY($pdf->GetY()+15);
$pdf->Text(15, 78, 'Visiteur');
// ID du visiteur
$pdf->Cell(0,10,$row['id'],0,0,'C');
// Nom et prénom du visiteur
$pdf->Cell(0, 10, $row['nomvisiteur'], 0, 0, 'R');
$mois = substr($row['mois'], -2);
$annee = substr($row['mois'], 0, 4);
date_default_timezone_set('Europe/Paris');
$dateObj = DateTime::createFromFormat('!m', $mois);
  
// Nom du mois dans la var
$nomMois = $dateObj->format('F');

$pdf->SetY($pdf->GetY()+10);
$pdf->Text(15, 88, 'Mois');
$pdf->Cell(0,10, $nomMois . ' ' . $annee,0,0,'C');;

$position_entete = 58;

function entete_table($position_entete) {
    global $pdf;
    $pdf->SetDrawColor(30, 73, 125); // Couleur des lignes
    $pdf->SetFillColor(255); // Couleur du fond
    $pdf->SetTextColor(30, 73, 125); // Couleur du texte
    $pdf->SetY($position_entete);
    $pdf->SetY(95);
    $pdf->SetX(15);
    $pdf->SetFont('Arial', 'B'); // Texte en gras
    $pdf->Cell(45, 10, 'Frais forfaitaires', 1, 0, 'L', 1);
    $pdf->SetX(60); // 8 + 96
    $pdf->Cell(45, 10, utf8_decode('Quantité'), 1, 0, 'C', 1);
    $pdf->SetX(105); // 104 + 10
    $pdf->Cell(45, 10, 'Montant unitaire', 1, 0, 'C', 1);
    $pdf->SetX(150); // 104 + 10
    $pdf->Cell(45, 10, 'Total', 1, 0, 'C', 1);
    $pdf->SetFont('Arial', ''); // Réinitilisation de l'épaisseur du texte
    $pdf->Ln(); // Retour à la ligne
}

entete_table($position_entete);

// Liste des détails
$position_detail = 105; // Position à 8mm de l'entête

$req2 = "SELECT libelle, montant, quantite, (montant*quantite) as total FROM fraisforfait inner join lignefraisforfait on lignefraisforfait.idfraisforfait = fraisforfait.id WHERE idvisiteur='$unId' and mois='$unMois'";
$rep2 = mysqli_query($db, $req2);
$total = 0;
while ($row2 = mysqli_fetch_array($rep2)) {
    $pdf->SetY($position_detail);
    $pdf->SetX(15);
    $pdf->MultiCell(45, 10, utf8_decode($row2['libelle']), 1, 'L');
    $pdf->SetY($position_detail);
    $pdf->SetX(60);
    $pdf->MultiCell(45, 10, utf8_decode($row2['quantite']), 1, 'C');
    $pdf->SetY($position_detail);
    $pdf->SetX(105);
    $pdf->MultiCell(45, 10, utf8_decode($row2['montant']), 1, 'C');
    $pdf->SetY($position_detail);
    $pdf->SetX(150);
    $pdf->MultiCell(45, 10, utf8_decode($row2['total']), 1, 'C');
    $position_detail += 10;
    $total += $row2['total'];
}

    $pdf->SetY($pdf->GetY()+3);
    $pdf->SetFont('Arial', 'B'); // Texte en gras
    $text = 'AUTRES FRAIS';
    $pdf->Text(($pdf->GetPageWidth() / 2) - $pdf->GetStringWidth($text) / 2, $pdf->GetY() + 11, $text); // Alignement du texte au centre du PDF
    $pdf->SetFont('Arial', ''); // Réinitilisation de l'épaisseur du texte

function entete_table2() {
    global $pdf;
    $pdf->SetDrawColor(30, 73, 125); // Couleur des lignes
    $pdf->SetFillColor(255); // Couleur du fond
    $pdf->SetTextColor(30, 73, 125); // Couleur du texte
    $pdf->SetY(170);
    $pdf->SetX(15);
    $pdf->SetFont('Arial', 'B'); // Texte en gras
    $pdf->Cell(60, 10, 'Date', 1, 0, 'C', 1);
    $pdf->SetX(75);
    $pdf->Cell(60, 10, utf8_decode('Libellé'), 1, 0, 'C', 1);
    $pdf->SetX(135);
    $pdf->Cell(60, 10, 'Montant', 1, 0, 'C', 1);
    $pdf->SetFont('Arial', ''); // Réinitilisation de l'épaisseur du texte
    $pdf->Ln(); // Retour à la ligne
}

entete_table2();

// Liste des détails
$position_detail2 = 180;

$req3 = "SELECT libelle, montant, date FROM lignefraishorsforfait WHERE idvisiteur='$unId' and mois='$unMois'";
$rep3 = mysqli_query($db, $req3);
$montant = 0;
while ($row3 = mysqli_fetch_array($rep3)) {
    $pos = strpos($row3['libelle'], 'REFUSE');

    $pdf->SetY($position_detail2);
    $pdf->SetX(15);
    $pdf->MultiCell(60, 10, utf8_decode(implode('/',array_reverse(explode('-', $row3['date'])))), 1, 'C');
    $pdf->SetY($position_detail2);
    $pdf->SetX(75);
    $pdf->MultiCell(60, 10, substr(utf8_decode($row3['libelle']), 0, 20), 1, 'C');
    $pdf->SetY($position_detail2);
    $pdf->SetX(135);
    if ($pos !== FALSE) {
        $refusMontant = 0;
        $pdf->MultiCell(60, 10, $refusMontant, 1, 'C');
        $montant -= $row3['montant'];
    }
    else {
        $pdf->MultiCell(60, 10, $row3['montant'], 1, 'C');
    }
    $montant += $row3['montant'];

    $position_detail2 += 10;
}

$pdf->SetY($position_detail2+10);
$pdf->SetX(115);
$pdf->Cell(40, 10, 'TOTAL ' . $mois . '/' . $annee, 1, 0, 'C');
$pdf->SetX(155);
$pdf->Cell(40, 10, $total + $montant, 1, 0, 'C');
$pdf->Ln();
$pdf->SetXY(114, $pdf->GetY()+ 5);
$pdf->Cell(0, 10, utf8_decode('Fait à Toulon, le ') . utf8_decode($formatter->format(time())));
$pdf->Ln();
$pdf->SetXY(114, $pdf->GetY());
$pdf->Cell(0, 10, 'Vu l\'agent comptable');
$imgSignatureComptable = '../../resources/images/signatureComptable.png';
$pdf->Image($imgSignatureComptable, 110, $pdf->GetY()+15, 60);

$pdf->Output();
?>