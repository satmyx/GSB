<?php

require('../../vendor/fpdf184/fpdf.php');
require('../../config/bdd.php');


class PDF_MySQL_Table extends FPDF
{
protected $ProcessingTable=false;
protected $aCols=array();
protected $TableX;
protected $HeaderColor;
protected $RowColors;
protected $ColorIndex;

function Header()
{
        $linkLogoGSB = '../../resources/Outils/PDF/logo.jpg';
        // Logo GSB
        $this->Image($linkLogoGSB, ($this->GetPageWidth()/2) - 20, $this->lMargin, 40);
}

function Footer() {
    // Date du jour
    setlocale(LC_TIME, "fr_FR");
    date_default_timezone_set('Europe/Paris');
    $dateJour = DateTimeImmutable::createFromFormat('U', time());
    
    $this->SetY(230);
    $this->Cell(0, 10, utf8_decode('Fait à ' . date_default_timezone_get() . ', le ' . $dateJour->format('j F Y')), 0, 0, 'R');
    
    $this->SetY($this->GetY() + 10);
    $this->Cell(0, 10, "Vu l'agent comptable", 0, 0, 'R');
    
    // Ajout de la signature du comptable
    $imgSignatureComptable = '../../resources/outils/pdf/signatureComptable.png';
    $this->Image($imgSignatureComptable, 150, 250, 50);
}

function TableHeader() {
    $this->SetDrawColor(30, 73, 125);
    $this->SetLineWidth(.3);
    $this->SetTextColor(30, 73, 125);
    $this->SetFont('Arial', 'B', 12);
    $this->SetX($this->TableX);
    $fill = !empty($this->HeaderColor);
    if ($fill)
        $this->SetFillColor($this->HeaderColor[0], $this->HeaderColor[1], $this->HeaderColor[2]);
    foreach ($this->aCols as $col)
        $this->Cell($col['w'], 6, $col['c'], 1, 0, 'C', $fill);
        $this->Ln();
}

function Row($data) {
    $this->SetTextColor(0);
    $this->SetX($this->TableX);
    $ci = $this->ColorIndex;
    $fill = !empty($this->RowColors[$ci]);
    if ($fill)
        $this->SetFillColor($this->RowColors[$ci][0], $this->RowColors[$ci][1], $this->RowColors[$ci][2]);
    foreach ($this->aCols as $col)
        $this->Cell($col['w'], 6, $data[$col['f']], 1, 0, 'C', $fill);
        $this->Ln();
        $this->ColorIndex = + - $ci;
}

function CalcWidths($width, $align)
{
    // Compute the widths of the columns
    $TableWidth=0;
    foreach($this->aCols as $i=>$col)
    {
        $w=$col['w'];
        if($w==-1)
            $w=$width/count($this->aCols);
        elseif(substr($w,-1)=='%')
            $w=$w/100*$width;
        $this->aCols[$i]['w']=$w;
        $TableWidth+=$w;
        //$TableWidth = $TableWidth + $w - $this->lMargin;
    }
    // Compute the abscissa of the table
    if($align=='C')
        $this->TableX=max(($this->w-$TableWidth)/2,0);
    elseif($align=='R')
        $this->TableX=max($this->w-$this->rMargin-$TableWidth,0);
    else
        $this->TableX=$this->lMargin;
}

function AddCol($field=-1, $width=-1, $caption='', $align='L')
{
    // Add a column to the table
    if($field==-1)
        $field=count($this->aCols);
    $this->aCols[]=array('f'=>$field,'c'=>$caption,'w'=>$width,'a'=>$align);
}

function Table($link, $query, $prop=array())
{
    // Execute query
    $res=mysqli_query($link,$query) or die('Error: '.mysqli_error($link)."<br>Query: $query");
    // Add all columns if none was specified
    if(count($this->aCols)==0)
    {
        $nb=mysqli_num_fields($res);
        for($i=0;$i<$nb;$i++)
            $this->AddCol();
    }
    // Retrieve column names when not specified
    foreach($this->aCols as $i=>$col)
    {
        if($col['c']=='')
        {
            if(is_string($col['f']))
                $this->aCols[$i]['c']=ucfirst($col['f']);
            else
                $this->aCols[$i]['c']=ucfirst(mysqli_fetch_field_direct($res,$col['f'])->name);
        }
    }
    // Handle properties
    if(!isset($prop['width']))
        $prop['width']=0;
    if($prop['width']==0)
        $prop['width']=$this->w-$this->lMargin-$this->rMargin;
    if(!isset($prop['align']))
        $prop['align']='C';
    if(!isset($prop['padding']))
        $prop['padding']=$this->cMargin;
    $cMargin=$this->cMargin;
    $this->cMargin=$prop['padding'];
    if(!isset($prop['HeaderColor']))
        $prop['HeaderColor']=array();
    $this->HeaderColor=$prop['HeaderColor'];
    if(!isset($prop['color1']))
        $prop['color1']=array();
    if(!isset($prop['color2']))
        $prop['color2']=array();
    $this->RowColors=array($prop['color1'],$prop['color2']);
    // Compute column widths
    $this->CalcWidths($prop['width'] - 2*$this->rMargin,$prop['align']);
    // Print header
    $this->TableHeader();
    // Print rows
    $this->SetFont('Arial','',11);
    $this->ColorIndex=0;
    $this->ProcessingTable=true;
    while($row=mysqli_fetch_array($res))
        $this->Row($row);
    $this->ProcessingTable=false;
    $this->cMargin=$cMargin;
    $this->aCols=array();
}

// Informations du patient
	function getInfosVisiteur() {
        $prenomVisiteur = 'Louis';
        $nomVisiteur = 'VILLECHALANE';
        $mois = 'Juillet';
        $annee = '2022';

        $this->SetX(2 * $this->lMargin);
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 0, 'Visiteur', 0, 0, 'L');
        $this->SetX($this->lMargin);
        $this->Cell(0, 0, 'NRD/A-131', 0, 0, 'C');
        $this->SetX(-2*$this->lMargin);
        $this->Cell(0, 0, $prenomVisiteur . ' ' . $nomVisiteur, 0, 0, 'R');

        $this->setY($this->GetY() + 15);

        $this->SetX(2 * $this->lMargin);
        $this->Cell(0, 0, 'Mois', 0, 0, 'L');
        $this->SetX($this->lMargin);
        $this->Cell(0, 0, $mois . ' ' . $annee, 0, 10, 'C');
    }

}

// -----------------------------------------------------------------------

class PDF extends PDF_MySQL_Table
{
    
function Header() {
    	$this->Ln(35);
    	$this->SetFillColor(255, 255, 255);
    	$this->SetTextColor(30, 73, 125);
    	$this->SetFont('Arial', 'B', 14);
   	 
    	$this->SetXY($this->lMargin, 50);
   	 
    	// Encadré
    	$this->SetDrawColor(30, 73, 125);
    	$this->SetLineWidth(.3);
    	$this->Rect($this->GetX(), $this->GetY(), $this->GetPageWidth() - 2 * $this->lMargin, $this->GetY()+110);
   	 
    	$this->Cell(0, 6, utf8_decode('REMBOURSEMENT DE FRAIS ENGAGÉS'), 0, 1, 'C');
        $this->Line($this->GetX(), $this->GetY(), $this->GetX()+190, $this->GetY());
    	$this->Ln(10);
    	// Imprime l'en-tête du tableau si nécessaire
    	parent::Header();
	}

}

// Connexion à la base
$link = mysqli_connect('localhost','userGsb','secret','gsb_frais');

$pdf = new PDF();

$pdf->AddPage();

$pdf->SetTitle('Fiche de frais de XXX');

$pdf->Ln(5);

$pdf->getInfosVisiteur();

$pdf->Ln(15);
// Premier tableau : imprime toutes les colonnes de la requête
$pdf->Table($link,'select fraisforfait.libelle as "Frais forfait", lignefraisforfait.quantite as "Quantité", fraisforfait.montant as "Montant unitaire", lignefraisforfait.quantite * fraisforfait.montant as "Total"
            from fraisforfait inner join lignefraisforfait
            on fraisforfait.id = lignefraisforfait.idfraisforfait');
// Deuxième tableau : imprime toutes les colonnes de la requête

$pdf->SetTextColor(30, 73, 125);
$pdf->SetFont('', 'B');
$text = 'Autres frais';
$pdf->Text(($pdf->GetPageWidth() / 2) - $pdf->GetStringWidth($text) / 2, $pdf->GetY() + 11, $text);

$pdf->Ln(20);

$pdf->Table($link,'select mois as "Date", libelle as "Libellé", montant from lignefraishorsforfait');

$pdf->Ln(15);



$pdf->Table($link,'select (lignefraisforfait.quantite * fraisforfait.montant) + sum(lignefraishorsforfait.montant)  as "Total (MM/YYYY)"
            from fraisforfait inner join lignefraisforfait
            on fraisforfait.id = lignefraisforfait.idfraisforfait
            inner join lignefraishorsforfait
            on lignefraisforfait.idvisiteur = lignefraishorsforfait.idvisiteur');


$pdf->Ln(30);

$pdf->Output();
?>