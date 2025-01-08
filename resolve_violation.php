<?php
session_start(); // Start the session

// Database connection
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['resolve_btn'])) {
    $violation_id = mysqli_real_escape_string($connection, $_POST['resolve_id']); // Sanitize input
    $proof_of_resolution = $_FILES['proof_of_resolution']['name'];
    $target_dir = "uploads/"; // Directory to store uploaded files
    $target_file = $target_dir . basename($proof_of_resolution);

    // Check for file upload errors
    if ($_FILES['proof_of_resolution']['error'] === UPLOAD_ERR_OK) {
        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES['proof_of_resolution']['tmp_name'], $target_file)) {
            // Prepare SQL query to update the violation status and save the proof
            $query = "UPDATE violations SET 
                        violation_status = 'resolved', 
                        proof_of_resolution = ? 
                      WHERE violation_id = ?";
            $stmt = mysqli_prepare($connection, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $proof_of_resolution, $violation_id);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    $_SESSION['success'] = "Violation resolved successfully!";
                } else {
                    $_SESSION['status'] = "Error updating violation in database.";
                }
                mysqli_stmt_close($stmt);
            } else {
                $_SESSION['status'] = "Failed to prepare the database query.";
            }
        } else {
            $_SESSION['status'] = "Failed to upload the proof of resolution.";
        }
    } else {
        $_SESSION['status'] = "File upload error: " . $_FILES['proof_of_resolution']['error'];
    }

    // Redirect back to the violations page
    header("Location: violation.php");
    exit();
} else {
    $_SESSION['status'] = "Invalid request.";
    header("Location: violation.php");
    exit();
}

// Close the database connection
mysqli_close($connection);
?>
