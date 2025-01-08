<?php
ob_start(); // Start output buffering to prevent accidental output

include('includes/dbconfig.php');
require_once __DIR__ . '/vendor/autoload.php';

function wrapText($pdf, $text, $maxWidth) {
    $wrappedText = '';
    $words = explode(' ', $text);
    $line = '';

    foreach ($words as $word) {
        $lineWidth = $pdf->GetStringWidth($line . ' ' . $word);
        if ($lineWidth <= $maxWidth) {
            $line .= ($line === '' ? '' : ' ') . $word;
        } else {
            $wrappedText .= ($wrappedText === '' ? '' : "\n") . $line;
            $line = $word;
        }
    }

    return $wrappedText . ($line !== '' ? ($wrappedText === '' ? '' : "\n") . $line : '');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['driver_id'])) {
    $driver_id = $_POST['driver_id'];

    if ($driver_id === 'all') {
        // Fetch all violations for all drivers
        $query = "SELECT * FROM violations ORDER BY driver_id";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $violations = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Create PDF
            $pdf = new \TCPDF('L', 'mm', 'A4'); // 'L' for landscape orientation
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);

            // Add Report Title
            $pdf->Cell(0, 10, "Violation Summary Report - All Drivers", 0, 1, 'C');
            $pdf->Ln(10);

            // Add Violation Table Headers
            $pdf->SetFont('helvetica', 'B', 10);
            $headers = ["Driver Name", "Violation Type", "Violation Date", "Offense", "Penalty", "Status"];
            $columnWidths = [50, 60, 40, 30, 30, 40];

            foreach ($headers as $index => $header) {
                $pdf->Cell($columnWidths[$index], 10, $header, 1, 0, 'C');
            }
            $pdf->Ln();

            // Add Violations
            $pdf->SetFont('helvetica', '', 10);
            foreach ($violations as $violation) {
                $pdf->MultiCell($columnWidths[0], 10, wrapText($pdf, $violation['name'], $columnWidths[0]), 1, 'C', 0, 0);
                $pdf->MultiCell($columnWidths[1], 10, wrapText($pdf, $violation['violation_type'], $columnWidths[1]), 1, 'C', 0, 0);
                $pdf->MultiCell($columnWidths[2], 10, wrapText($pdf, $violation['violation_date'], $columnWidths[2]), 1, 'C', 0, 0);
                $pdf->MultiCell($columnWidths[3], 10, wrapText($pdf, $violation['offense'], $columnWidths[3]), 1, 'C', 0, 0);
                $pdf->MultiCell($columnWidths[4], 10, wrapText($pdf, $violation['penalty'], $columnWidths[4]), 1, 'C', 0, 0);
                $pdf->MultiCell($columnWidths[5], 10, wrapText($pdf, $violation['violation_status'], $columnWidths[5]), 1, 'C', 0, 1);
            }

            // Output PDF
            ob_end_clean(); // Clear any previous output buffer
            $pdf->Output("Violation_Report_All_Drivers.pdf", 'D'); // Force download
            exit;
        } else {
            ob_end_clean();
            echo "<script>alert('No violations found.'); window.history.back();</script>";
        }
    } else {
        // Fetch the selected driver's violations
        $query = "SELECT * FROM violations WHERE driver_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $driver_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $violations = $result->fetch_all(MYSQLI_ASSOC);

            // Fetch driver name
            $driver_name = $violations[0]['name'];

            // Create PDF
            $pdf = new \TCPDF('L', 'mm', 'A4'); // 'L' for landscape orientation
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);

            // Add Report Title
            $pdf->Cell(0, 10, "Violation Summary Report", 0, 1, 'C');
            $pdf->Ln(10);

            // Add Driver Information
            $pdf->Cell(0, 10, "Driver: " . htmlspecialchars($driver_name), 0, 1);
            $pdf->Ln(5);

            // Add Violation Table Headers
            $headers = ["Violation Type", "Violation Date", "Offense", "Penalty", "Status"];
            $columnWidths = [60, 40, 30, 30, 50];

            foreach ($headers as $index => $header) {
                $pdf->Cell($columnWidths[$index], 10, $header, 1, 0, 'C');
            }
            $pdf->Ln();

            // Add Violations
            $pdf->SetFont('helvetica', '', 10);
            foreach ($violations as $violation) {
                $pdf->MultiCell($columnWidths[0], 10, wrapText($pdf, $violation['violation_type'], $columnWidths[0]), 1, 'C', 0, 0);
                $pdf->MultiCell($columnWidths[1], 10, wrapText($pdf, $violation['violation_date'], $columnWidths[1]), 1, 'C', 0, 0);
                $pdf->MultiCell($columnWidths[2], 10, wrapText($pdf, $violation['offense'], $columnWidths[2]), 1, 'C', 0, 0);
                $pdf->MultiCell($columnWidths[3], 10, wrapText($pdf, $violation['penalty'], $columnWidths[3]), 1, 'C', 0, 0);
                $pdf->MultiCell($columnWidths[4], 10, wrapText($pdf, $violation['violation_status'], $columnWidths[4]), 1, 'C', 0, 1);
            }

            // Output PDF
            ob_end_clean(); // Clear any previous output buffer
            $pdf->Output("Violation_Report_{$driver_id}.pdf", 'D'); // Force download
            exit;
        } else {
            ob_end_clean();
            echo "<script>alert('No violations found for the selected driver.'); window.history.back();</script>";
        }
    }
} else {
    ob_end_clean();
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
?>
