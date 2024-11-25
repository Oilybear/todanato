<?php
include('includes/dbconfig.php'); // Database connection

if (isset($_POST['update_driver_btn'])) {
    $id = $_POST['edit_id'];
    $current_address = mysqli_real_escape_string($connection, $_POST['edit_current_address']);

    // Handle file uploads
    $membership_application_form_copy = $_FILES['edit_membership_application_form_copy']['name'];
    $proof_of_membership_payment = $_FILES['edit_proof_of_membership_payment']['name'];
    $drivers_license_copy = $_FILES['edit_drivers_license_copy']['name'];

    // Prepare update query
    $query = "UPDATE drivers SET current_address='$current_address'";

    if ($membership_application_form_copy) {
        move_uploaded_file($_FILES['edit_membership_application_form_copy']['tmp_name'], "uploads/$membership_application_form_copy");
        $query .= ", membership_application_form_copy='$membership_application_form_copy'";
    }

    if ($proof_of_membership_payment) {
        move_uploaded_file($_FILES['edit_proof_of_membership_payment']['tmp_name'], "uploads/$proof_of_membership_payment");
        $query .= ", proof_of_membership_payment='$proof_of_membership_payment'";
    }

    if ($drivers_license_copy) {
        move_uploaded_file($_FILES['edit_drivers_license_copy']['tmp_name'], "uploads/$drivers_license_copy");
        $query .= ", drivers_license_copy='$drivers_license_copy'";
    }

    // Complete the update query
    $query .= " WHERE driver_id='$id'";

    // Execute the update query
    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "Driver updated successfully.";
    } else {
        $_SESSION['status'] = "Driver update failed: " . mysqli_error($connection);
    }

    header("Location: drivers.php");
    exit();
}
