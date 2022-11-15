<?php


require('../../vendor/fpdf184/fpdf.php');
  
class PDF extends FPDF {
    
    // Header de la page
    function Header() {
        $linkLogoGSB = '../../public/images/logo.jpg';
        // Logo GSB
        $this->Image($linkLogoGSB, ($this->GetPageWidth()/2) - 20, $this->lMargin, 40);
    }
    
    function getSignatureComptable() {
        
        // Date du jour
        setlocale(LC_TIME, "fr_FR");
        date_default_timezone_set('Europe/Paris');
        $dateJour = DateTimeImmutable::createFromFormat('U', time());
 
        //$date_du_jour = utf8_encode(strftime('%A %d %B %Y'));
        
        $this->SetY(230);
        $this->Cell(0, 10, utf8_decode('Fait à ' . date_default_timezone_get() . ', le '. $dateJour->format('j F Y')), 0, 0, 'R');
        
        $this->SetY($this->GetY()+10);
        $this->Cell(0, 10, "Vu l'agent comptable", 0, 0, 'R');
        
        // Ajout de la signature du comptable
        $imgSignatureComptable = '../../public/images/signatureComptable.png';
        $this->Image($imgSignatureComptable, 150, 250, 50);
    }
    
    // Informations du patient
    function getInfosVisiteur() 
    {
        $this->SetTextColor(0);
        
        $prenomVisiteur = 'Louis';
        $nomVisiteur = 'VILLECHALANE';
        $mois = 'Juillet';
        $annee = '2022';
        
        $this->SetX(2*$this->lMargin);
        $this->SetFont('Arial', '', 10); 
        $this->Cell(0,0,'Visiteur',0,0,'L');
        $this->SetX($this->lMargin);
        $this->Cell(0,0,'NRD/A-131',0,0,'C');
        //$this->SetX($this->lMargin);
        $this->Cell(0, 0, $prenomVisiteur . ' ' . $nomVisiteur, 0, 0, 'R'); 
       
        $this->setY($this->GetY()+15);
        
        $this->SetX(2*$this->lMargin);
        $this->Cell(0, 0,'Mois',0,0,'L');
        $this->SetX($this->lMargin);
        $this->Cell(0, 0, $mois . ' ' . $annee, 0, 0, 'C');
    }

    // Get data from the text file
    function getDataFrmFile($file) {
  
          // Read file lines
        $lines = file($file);
        
        // Get a array for returning output data
        $data = array();
        
        // Read each line and separate the semicolons
        foreach($lines as $line)
            $data[] = explode(';', chop($line));
        return $data;
    }
  
    // Simple table
    /*function getSimpleTable($header, $data) {
        
        // Header
        foreach($header as $column)
            $this->Cell(40, 7, $column, 1);
        $this->Ln(); // Set current position
        
        // Data
        foreach($data as $row) {
            foreach($row as $col)
                $this->Cell(40, 6, $col, 1);
            $this->Ln(); // Set current position
        }
    }*/
    
        function getPrimaryTable($header, $data) {
        
        // Colors, line width and bold font
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(30, 73, 125);
        $this->SetDrawColor(30, 73, 125);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        
        // Header
        $calculColonneCenter = ($this->GetPageWidth()-2*$this->lMargin);
        $colWidth = array($calculColonneCenter);
        for($i = 0; $i < count($header); $i++)
            $this->Cell($colWidth[$i], 7, 
                        $header[$i], 1, 0, 'C', 1);
        $this->Ln();
        
        $this->SetXY($this->lMargin, 50);
        // Encadré
        $this->Rect($this->GetX(), $this->GetY(), $this->GetPageWidth() - 2 * $this->lMargin, $this->GetY()+100);

        $this->SetLeftMargin(20);
        $this->SetRightMargin(20);
        
    }
    
    // Get styled table
    function getStyledTable($header, $data) {
        
        // Colors, line width and bold font
        $this->SetFillColor(30, 73, 125);
        $this->SetTextColor(255);
        $this->SetDrawColor(30, 73, 125);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        
        // Marge
        $this->SetLeftMargin(20);
        $this->SetRightMargin(20);
        
        // Header
        $calculColonneCenter = ($this->GetPageWidth() - 2 * $this->lMargin) / count($header);
        $colWidth = array($calculColonneCenter, $calculColonneCenter, $calculColonneCenter, $calculColonneCenter);
        for($i = 0; $i < count($header); $i++)
            $this->Cell($colWidth[$i], 7, 
                        $header[$i], 1, 0, 'C', 1);
        $this->Ln();
        
        // Setting text color and color fill
        // for the background
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        
        // Data
        $fill = 0;
        foreach($data as $row) {
            
            // Prints a cell, first 2 columns  are left aligned
            $this->Cell($colWidth[0], 6, $row[0], 'LR', 0, 'C', $fill);
            $this->Cell($colWidth[1], 6, $row[1], 'LR', 0, 'C', $fill);
            
            // Prints a cell,last 2 columns  are right aligned
            $this->Cell($colWidth[2], 6, number_format($row[2]), 
                        'LR', 0, 'C', $fill);
            $this->Cell($colWidth[3], 6, number_format($row[3]), 
                        'LR', 0, 'C', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($colWidth), 0, '', 'T');
    }
}
    
    // Instantiate a PDF object
    $pdf = new PDF();
  
    $pdf->SetTitle('Fiche de frais de XXX');

    // Get data from the text files
    $data = $pdf->getDataFrmFile('../../tests/test.txt');
  
    // Set the font as required
    $pdf->SetFont('Arial', 'B', 14);
  
    // Add a new page
    // $pdf->AddPage();
    // $pdf->getSimpleTable($header,$data);
    $pdf->AddPage();
    
    $pdf->SetXY(10, 70);
    
    $pdf->getInfosVisiteur();
    
    $pdf->SetXY(10, 50);
    

    // Column titles given by the programmer
    $header = array(utf8_decode('REMBOURSEMENT DE FRAIS ENGAGÉS'));
    $pdf->getPrimaryTable($header, $data);
    
    $pdf->SetXY(10, 100);
    
    // Column titles given by the programmer
    $header = array('Frais forfaitaires', utf8_decode('Quantité'),'Montant unitaire','Total');
    $pdf->getStyledTable($header, $data);
    
    $pdf->SetTextColor(30, 73, 125);
    $pdf->SetFont('', 'B');
    $text = 'Autres frais';
    $pdf->Text(($pdf->GetPageWidth()/2) - $pdf->GetStringWidth($text) / 2, $pdf->GetY() + 11, $text);
    
    $header = array('Date', utf8_decode('Libellé'),'Montant');
    $pdf->SetXY(10, $pdf->getY()+20);
    $pdf->getStyledTable($header,$data);

    
    $pdf->getSignatureComptable();
    
    $pdf->Footer();
    
    $pdf->Output();
?>
