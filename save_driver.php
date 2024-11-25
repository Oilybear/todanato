<?php
include('dbconfig.php');
include('includes/header.php'); 
include('includes/navbar.php'); 

if (isset($_POST['add_driver_btn'])) {
    // Sanitize and assign form data
    $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($connection, $_POST['middle_name']);
    $date_of_birth = mysqli_real_escape_string($connection, $_POST['date_of_birth']);
    $place_of_birth = mysqli_real_escape_string($connection, $_POST['place_of_birth']);
    $current_address = mysqli_real_escape_string($connection, $_POST['current_address']);
    $license_number = mysqli_real_escape_string($connection, $_POST['license_number']);
    $body_number = mysqli_real_escape_string($connection, $_POST['body_number']);
    $motor_plate_no = mysqli_real_escape_string($connection, $_POST['motor_plate_no']);
    $make_model = mysqli_real_escape_string($connection, $_POST['make_model']);
    $ownership_status = mysqli_real_escape_string($connection, $_POST['ownership_status']);
    $membership_application_form_copy = mysqli_real_escape_string($connection, $_POST['membership_application_form_copy']);
    $proof_of_membership_payment = mysqli_real_escape_string($connection, $_POST['proof_of_membership_payment']);
    $drivers_license_copy = mysqli_real_escape_string($connection, $_POST['drivers_license_copy']);

    // Insert data into drivers table
    $query = "INSERT INTO drivers (last_name, first_name, middle_name, date_of_birth, place_of_birth, current_address, license_number, body_number, motor_plate_no, make_model, ownership_status, membership_application_form_copy, proof_of_membership_payment, drivers_license_copy) 
              VALUES ('$last_name', '$first_name', '$middle_name', '$date_of_birth', '$place_of_birth', '$current_address', '$license_number', '$body_number', '$motor_plate_no', '$make_model', '$ownership_status', '$membership_application_form_copy', '$proof_of_membership_payment', '$drivers_license_copy')";
    
    if (mysqli_query($connection, $query)) {
        // Fetch the last inserted driver_id
        $driver_id = mysqli_insert_id($connection);

        // Generate username and password
        $username = strtolower($first_name[0] . $last_name); // Example: jdoe
        $password = password_hash('defaultpassword', PASSWORD_BCRYPT); // Default password (hashed)

        // Insert into app_drivers table
        $app_query = "INSERT INTO app_drivers (driver_id, full_name, make_model, motor_plate_no, username, password) 
                      VALUES ('$driver_id', '$first_name $middle_name $last_name', '$make_model', '$motor_plate_no', '$username', '$password')";

        if (mysqli_query($connection, $app_query)) {
            echo "<div class='container mt-4'><p class='alert alert-success'>Driver and App Driver Added Successfully!</p>";
            echo "<a href='drivers.php' class='btn btn-primary'>Go to Drivers Page</a></div>";
        } else {
            echo "<div class='container mt-4'><p class='alert alert-danger'>Failed to add app driver: " . mysqli_error($connection) . "</p></div>";
        }
    } else {
        echo "<div class='container mt-4'><p class='alert alert-danger'>Driver Addition Failed: " . mysqli_error($connection) . "</p></div>";
    }
}
?>
