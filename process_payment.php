<?php
include('includes/dbconfig.php'); // Ensure this is the correct path
require_once __DIR__ . '/vendor/autoload.php'; // Ensure the autoloader is included

if (isset($_POST['add_payment_btn'])) {
    $driver_id = $_POST['driver_id'];
    $date_of_payment = $_POST['date_of_payment'];
    $amount = $_POST['amount'];
    $payment_for = $_POST['payment_for'];

    // Define the absolute path for the uploads directory
    $uploads_dir = __DIR__ . '/uploads/';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true); // Create directory if it doesn't exist
    }

    // Generate the receipt file path
    $receipt_filename = "Receipt_" . $driver_id . "_" . time() . ".pdf";
    $receipt_filepath = $uploads_dir . $receipt_filename;

    try {
        // Generate the receipt PDF using TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        // Add receipt content
        $pdf->Cell(0, 10, "Payment Receipt", 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 10, "Driver ID: " . $driver_id, 0, 1);
        $pdf->Cell(0, 10, "Payment For: " . $payment_for, 0, 1);
        $pdf->Cell(0, 10, "Amount: PHP " . number_format($amount, 2), 0, 1);
        $pdf->Cell(0, 10, "Date of Payment: " . $date_of_payment, 0, 1);

        // Save the receipt as a PDF file
        $pdf->Output($receipt_filepath, 'F'); // Save to file

        // Insert payment data into the database
        $query = "INSERT INTO payments (driver_id, date_of_payment, amount, payment_for, payment_proof) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isdss", $driver_id, $date_of_payment, $amount, $payment_for, $receipt_filename);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['success'] = "Payment added successfully, and receipt generated!";
                header("Location: revenue.php");
            } else {
                $_SESSION['error'] = "Failed to add payment.";
                header("Location: revenue.php");
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "Database error: Could not prepare statement.";
            header("Location: revenue.php");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error generating receipt: " . $e->getMessage();
        header("Location: revenue.php");
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: revenue.php");
}

mysqli_close($connection);
exit;
?>
