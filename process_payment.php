<?php
include('includes/dbconfig.php'); // Ensure this is the correct path

// Check if form is submitted
if (isset($_POST['add_payment_btn'])) {
    // Get form data
    $driver_id = $_POST['driver_id'];
    $payment_date = $_POST['payment_date'];
    $amount = $_POST['amount'];
    
    // Handle file upload for payment proof
    $upload_dir = 'uploads/';
    $file_name = $_FILES['payment_proof']['name'];
    $file_tmp_name = $_FILES['payment_proof']['tmp_name'];
    $file_path = $upload_dir . basename($file_name);

    // Check if the uploads directory exists, if not, create it
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($file_tmp_name, $file_path)) {
        // Prepare and execute the query to insert payment data into the payments table
        $query = "INSERT INTO payments (driver_id, payment_date, amount, payment_proof) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $query);

        if ($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "isds", $driver_id, $payment_date, $amount, $file_name);
            mysqli_stmt_execute($stmt);

            // Check if insertion was successful
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['success'] = "Payment added successfully!";
                header("Location: revenue.php");
            } else {
                $_SESSION['error'] = "Failed to add payment.";
                header("Location: revenue.php");
            }
            mysqli_stmt_close($stmt);
        } else {
            // Query preparation failed
            $_SESSION['error'] = "Database error: Could not prepare statement.";
            header("Location: revenue.php");
        }
    } else {
        // File upload failed
        $_SESSION['error'] = "Failed to upload payment proof.";
        header("Location: revenue.php");
    }
} else {
    // If the form wasn't submitted correctly
    $_SESSION['error'] = "Invalid request.";
    header("Location: revenue.php");
}

mysqli_close($connection);
exit;
?>
