<?php
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_POST['resolve_btn'])) {
    $violation_id = $_POST['resolve_id'];
    $proof_of_resolution = $_FILES['proof_of_resolution']['name'];
    $target_dir = "uploads/"; // Specify the directory to store uploaded files
    $target_file = $target_dir . basename($proof_of_resolution);

    // Move uploaded file to target directory
    if (move_uploaded_file($_FILES["proof_of_resolution"]["tmp_name"], $target_file)) {
        // Update violation status to 'resolved' and save the proof of resolution
        $query = "UPDATE violations SET violation_status = 'resolved', proof_of_resolution = '$proof_of_resolution' WHERE violation_id = '$violation_id'";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            $_SESSION['success'] = "Violation resolved successfully!";
            header("Location: violation.php");
        } else {
            $_SESSION['status'] = "Error resolving violation.";
            header("Location: violation.php");
        }
    } else {
        $_SESSION['status'] = "File upload failed.";
        header("Location: violation.php");
    }
}
?>
