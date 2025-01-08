<?php
ob_start(); // Start output buffering
require_once __DIR__ . '/vendor/autoload.php';
include('includes/dbconfig.php');

// Check if a specific driver or all drivers were selected
$driver_id = isset($_POST['driver_id']) ? $_POST['driver_id'] : 'all';

// Modify query based on selection
if ($driver_id === 'all') {
    $query = "
        SELECT 
            CONCAT(d.last_name, ', ', d.first_name, ' ', d.middle_name) AS driver_name, 
            p.date_of_payment, 
            p.amount, 
            p.payment_for
        FROM 
            payments p
        JOIN 
            drivers d ON p.driver_id = d.driver_id
        ORDER BY 
            d.driver_id, p.date_of_payment
    ";
} else {
    $query = "
        SELECT 
            CONCAT(d.last_name, ', ', d.first_name, ' ', d.middle_name) AS driver_name, 
            p.date_of_payment, 
            p.amount, 
            p.payment_for
        FROM 
            payments p
        JOIN 
            drivers d ON p.driver_id = d.driver_id
        WHERE 
            d.driver_id = $driver_id
        ORDER BY 
            p.date_of_payment
    ";
}

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($connection));
}

// Initialize TCPDF
$pdf = new \TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Add title
$pdf->Cell(0, 10, "Payment Summary Report", 0, 1, 'C');
$pdf->Ln(10);

// Add table headers
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 10, "Driver Name", 1, 0, 'C');
$pdf->Cell(40, 10, "Payment Date", 1, 0, 'C');
$pdf->Cell(50, 10, "Payment For", 1, 0, 'C');
$pdf->Cell(40, 10, "Total Amount", 1, 0, 'C');
$pdf->Ln();

// Add table rows
$pdf->SetFont('helvetica', '', 10);
$total_amount = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->MultiCell(60, 10, $row['driver_name'], 1, 'L', false, 0);
    $pdf->MultiCell(40, 10, $row['date_of_payment'], 1, 'C', false, 0);
    $pdf->MultiCell(50, 10, $row['payment_for'], 1, 'L', false, 0);
    $pdf->MultiCell(40, 10, number_format($row['amount'], 2), 1, 'R', false, 1);
    $total_amount += $row['amount'];
}

// Add total row
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(150, 10, "Total", 1, 0, 'R');
$pdf->Cell(40, 10, number_format($total_amount, 2), 1, 0, 'R');
$pdf->Ln();

// Output PDF
ob_end_clean(); // Clear any output before this point
$pdf->Output('Payment_Summary_Report.pdf', 'D');
exit;
