<?php
ob_start(); // Start output buffering to prevent any output before PDF generation

include('includes/dbconfig.php');
require_once __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receive_payment'])) {
    $application_id = $_POST['application_id'];
    $fullname = $_POST['fullname'];
    $amount = $_POST['amount'];

    // Define the path to the temp folder
    $temp_dir = __DIR__ . '/temp';

    // Start a transaction to ensure atomicity
    $connection->begin_transaction();

    try {
        // Update payment status in the driver_applications table
        $query_update_payment = "UPDATE driver_applications SET payment_status = 'paid' WHERE application_id = ?";
        $stmt_update_payment = $connection->prepare($query_update_payment);
        $stmt_update_payment->bind_param("i", $application_id);
        $stmt_update_payment->execute();
        $stmt_update_payment->close();

        // Fetch driver details from driver_applications
        $query_fetch_driver = "SELECT last_name, first_name, middle_name, date_of_birth, 
                                      place_of_birth, current_address, license_number, 
                                      body_number, motor_plate_no, make_model, ownership_status
                               FROM driver_applications 
                               WHERE application_id = ?";
        $stmt_fetch_driver = $connection->prepare($query_fetch_driver);
        $stmt_fetch_driver->bind_param("i", $application_id);
        $stmt_fetch_driver->execute();
        $result = $stmt_fetch_driver->get_result();
        $driver = $result->fetch_assoc();
        $stmt_fetch_driver->close();

        // Insert driver into the drivers table
        $query_insert_driver = "INSERT INTO drivers (last_name, first_name, middle_name, date_of_birth, 
                                                      place_of_birth, current_address, license_number, 
                                                      body_number, motor_plate_no, make_model, ownership_status)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_driver = $connection->prepare($query_insert_driver);
        $stmt_insert_driver->bind_param(
            "sssssssssss",
            $driver['last_name'],
            $driver['first_name'],
            $driver['middle_name'],
            $driver['date_of_birth'],
            $driver['place_of_birth'],
            $driver['current_address'],
            $driver['license_number'],
            $driver['body_number'],
            $driver['motor_plate_no'],
            $driver['make_model'],
            $driver['ownership_status']
        );
        $stmt_insert_driver->execute();
        $driver_id = $stmt_insert_driver->insert_id; // Get the ID of the newly inserted driver
        $stmt_insert_driver->close();

        // Insert data into the app_drivers table
        $username = strtolower($driver['first_name']) . '.' . strtolower($driver['last_name']); // Example username
        $password = password_hash('defaultpassword', PASSWORD_DEFAULT); // Example hashed password
        $query_insert_app_driver = "INSERT INTO app_drivers (driver_id, full_name, make_model, username, password, created_at, motor_plate_no)
                                    VALUES (?, ?, ?, ?, ?, NOW(), ?)";
        $stmt_insert_app_driver = $connection->prepare($query_insert_app_driver);
        $stmt_insert_app_driver->bind_param(
            "isssss",
            $driver_id,
            $fullname,
            $driver['make_model'],
            $username,
            $password,
            $driver['motor_plate_no']
        );
        $stmt_insert_app_driver->execute();
        $stmt_insert_app_driver->close();

        // Insert payment into the payments table
        $query_insert_payment = "INSERT INTO payments (driver_id, amount, date_of_payment, payment_for, payment_proof) 
                                 VALUES (?, ?, NOW(), ?, ?)";
        $payment_for = "Application/Membership";
        $payment_proof = 'Receipt_' . $application_id . '.pdf';
        $stmt_insert_payment = $connection->prepare($query_insert_payment);
        $stmt_insert_payment->bind_param(
            "idss",
            $driver_id,
            $amount,
            $payment_for,
            $payment_proof
        );
        $stmt_insert_payment->execute();
        $stmt_insert_payment->close();

        // Commit the transaction
        $connection->commit();

        // Generate receipt using TCPDF
        $pdf = new \TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        // Add receipt content
        $pdf->Cell(0, 10, "Acknowledgment Receipt of Payment", 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 10, "Driver Name: " . htmlspecialchars($fullname), 0, 1);
        $pdf->Cell(0, 10, "Amount Paid: PHP " . number_format($amount, 2), 0, 1);
        $pdf->Cell(0, 10, "Date: " . date('Y-m-d H:i:s'), 0, 1);

        // Save the PDF to the temp folder
        $file_path = $temp_dir . '/Receipt_' . $application_id . '.pdf';
        $pdf->Output($file_path, 'F'); // Save the file

        ob_end_clean();

        // Redirect to pending_payments.php with a link to the receipt
        echo "<script>
                window.location.href = 'pending_payments.php?download=" . urlencode($file_path) . "'; 
              </script>";
        exit;

    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $connection->rollback();
        ob_end_clean();
        echo "<script>alert('Error processing payment: {$e->getMessage()}. Please try again.'); window.location.href = 'pending_payments.php';</script>";
    }
} else {
    // Handle invalid request
    ob_end_clean();
    echo "<script>alert('Invalid request.'); window.location.href = 'pending_payments.php';</script>";
}
?>
