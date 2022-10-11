<?php
require('../../vendor/fpdf184/fpdf.php');
  
class PDF extends FPDF {
    
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
  
    // Get styled table
    function getStyledTable($header, $data) {
        
        // Colors, line width and bold font
        $this->SetFillColor(30, 73, 125);
        $this->SetTextColor(255);
        $this->SetDrawColor(30, 73, 125);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        
        // Header
        $colWidth = array(40, 35, 40, 45);
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
    
    // Column titles given by the programmer
    $header = array('Frais forfaitaires', utf8_decode('Quantité'),'Montant unitaire','Total');
    
    // Get data from the text files
    $data = $pdf->getDataFrmFile('../../tests/test.txt');
  
    // Set the font as required
    $pdf->SetFont('Arial', '', 14);
  
    // Add a new page
    // $pdf->AddPage();
    // $pdf->getSimpleTable($header,$data);
    $pdf->AddPage();
    
    $pdf->Image('../../public/images/logo.jpg', 80, 0);
    
    $pdf->getStyledTable($header,$data);
    $pdf->Output();
?>
