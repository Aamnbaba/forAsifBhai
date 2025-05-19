<?php

require('lib/fpdf/fpdf.php'); // Adjust the path to fpdf.php if necessary

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $date = $_POST['date'] ?? '';
    $productName = $_POST['productName'] ?? '';
    $batchPoNumber = $_POST['batchPoNumber'] ?? '';
    $inspectedBy = $_POST['inspectedBy'] ?? '';
    $specifications = $_POST['specifications'] ?? '';
    $packaging = $_POST['packaging'] ?? '';
    $labeling = $_POST['labeling'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $damage = $_POST['damage'] ?? '';
    $functionalTest = $_POST['functionalTest'] ?? '';
    $documentation = $_POST['documentation'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    $status = $_POST['status'] ?? '';

    // Create a new FPDF object (Portrait orientation, A4 size, in mm)
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetMargins(15, 15, 15); // Set page margins
    $pdf->AddPage();

    // --- Centered Logo in PDF ---
    $solehriColor = [88, 180, 174];   // #58B4AE in RGB
    $brothersColor = [0, 0, 0];       // #000000 in RGB
    $logoFontSize = 18;

    // Calculate total width of the logo text
    $pdf->SetFont('Arial', 'B', $logoFontSize);
    $solehriWidth = $pdf->GetStringWidth('SOLEHRI');
    $pdf->SetFont('Arial', '', $logoFontSize);
    $brothersWidth = $pdf->GetStringWidth('BROTHERS');
    $totalLogoWidth = $solehriWidth + $brothersWidth + 2; // Add a small space between words

    // Calculate the starting X position to center the logo
    $logoStartX = ($pdf->GetPageWidth() - $totalLogoWidth) / 2;
    $pdf->SetX($logoStartX);

    // Output "SOLEHRI"
    $pdf->SetFont('Arial', 'B', $logoFontSize);
    $pdf->SetTextColor($solehriColor[0], $solehriColor[1], $solehriColor[2]);
    $pdf->Cell($solehriWidth, 10, 'SOLEHRI', 0, 0);

    // Output "BROTHERS" with a space
    $pdf->SetFont('Arial', '', $logoFontSize);
    $pdf->SetTextColor($brothersColor[0], $brothersColor[1], $brothersColor[2]);
    $pdf->Cell($brothersWidth + 2, 10, ' BROTHERS', 0, 1);

    $pdf->SetTextColor(0, 0, 0); // Reset text color to black
    $pdf->Ln(5); // Add some vertical space after the logo

    // --- Report Header ---
    $titleColor = [12, 104, 98]; // #0C6862 in RGB
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor($titleColor[0], $titleColor[1], $titleColor[2]);
    $pdf->Cell(0, 10, 'QA Check Report', 0, 1, 'C');
    $pdf->SetTextColor(0, 0, 0); // Reset text color
    $pdf->Ln(10); // Add some vertical space

    // --- Report Details ---
    $labelColor = [143, 82, 21]; // #8F5215 in RGB
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor($labelColor[0], $labelColor[1], $labelColor[2]);
    $pdf->Cell(40, 8, 'Date:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, $date, 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor($labelColor[0], $labelColor[1], $labelColor[2]);
    $pdf->Cell(40, 8, 'Product/Item:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, $productName, 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor($labelColor[0], $labelColor[1], $labelColor[2]);
    $pdf->Cell(40, 8, 'Batch/PO #:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, $batchPoNumber, 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor($labelColor[0], $labelColor[1], $labelColor[2]);
    $pdf->Cell(40, 8, 'Inspected By:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, $inspectedBy, 0, 1);

    $pdf->Ln(8); // Add some space before the checklist

    // --- QA Checklist in Two Columns with Borders ---
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor($labelColor[0], $labelColor[1], $labelColor[2]);
    $pdf->Cell(0, 8, 'QA Checklist:', 0, 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);

    $checklistData = [
        'Product matches specifications?' => $specifications,
        'Packaging is intact and clean?' => $packaging,
        'Labeling is correct?' => $labeling,
        'Quantity matches PO?' => $quantity,
        'No visible damage or defects?' => $damage,
        'Functional test passed (if applicable)?' => $functionalTest,
        'Proper documentation attached?' => $documentation,
    ];

    $colWidth = 85; // Half of the remaining width (approx.)
    foreach ($checklistData as $question => $answer) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($colWidth, 8, $question, 1, 0); // Border on all sides, align left
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell($colWidth, 8, strtoupper($answer), 1, 1, 'C'); // Border on all sides, align center, new line
    }

    $pdf->Ln(5); // Add some space after the checklist

    // --- Remarks/Comments ---
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor($labelColor[0], $labelColor[1], $labelColor[2]);
    $pdf->Cell(0, 8, 'Remarks / Comments:', 0, 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, $remarks, 0, 1);
    $pdf->Ln(5);

    // --- Status in the Center ---
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor($labelColor[0], $labelColor[1], $labelColor[2]);
    $pdf->Cell(0, 8, 'Status:', 0, 1, 'C'); // Centered label
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 16); // Slightly larger font for status
    $pdf->Cell(0, 10, strtoupper($status), 0, 1, 'C'); // Centered status value
    $pdf->Ln(15);

    // --- Signature at the Right Bottom ---
    $pdf->SetX(145); // Set X position towards the right
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, '____________', 0, 1, 'L'); // Underline effect with underscores, Left align
    $pdf->SetX(150);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, 'Signature', 0, 0, 'L');

    // --- Output the PDF for inline display in a new tab ---
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="QA_Check_Report_' . date('Ymd_His') . '.pdf"');
    $pdf->Output();
    exit();

} else {
    // If the form was not submitted, redirect back to the form
    header('Location: qa_form.html'); // Adjust the filename if needed
    exit();
}

?>