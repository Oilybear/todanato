<?php
include('includes/dbconfig.php'); // Ensure this is the correct path

$current_owner_id = $_POST['current_owner_id'];
$transfer_type = $_POST['transfer_type'];

if ($transfer_type === 'Member') {
    $new_owner_id = $_POST['new_owner_id'];

    // Fetch new owner's details and update current owner’s record
    $new_owner_query = "SELECT first_name, middle_name, last_name, date_of_birth, current_address, license_number FROM drivers WHERE driver_id = '$new_owner_id'";
    $new_owner_result = mysqli_query($connection, $new_owner_query);
    $new_owner_data = mysqli_fetch_assoc($new_owner_result);

    $update_query = "UPDATE drivers SET 
        first_name = '{$new_owner_data['first_name']}',
        middle_name = '{$new_owner_data['middle_name']}',
        last_name = '{$new_owner_data['last_name']}',
        date_of_birth = '{$new_owner_data['date_of_birth']}',
        current_address = '{$new_owner_data['current_address']}',
        license_number = '{$new_owner_data['license_number']}'
        WHERE driver_id = '$current_owner_id'";

    mysqli_query($connection, $update_query);

} elseif ($transfer_type === 'Non-Member') {
    $new_owner_data = [
        'first_name' => $_POST['new_owner_first_name'],
        'middle_name' => $_POST['new_owner_middle_name'],
        'last_name' => $_POST['new_owner_last_name'],
        'date_of_birth' => $_POST['new_owner_date_of_birth'],
        'current_address' => $_POST['new_owner_current_address'],
        'license_number' => $_POST['new_owner_license_number']
    ];

    // Copy tricycle details from current owner
    $current_owner_query = "SELECT body_number, motor_plate_no, make_model FROM drivers WHERE driver_id = '$current_owner_id'";
    $current_owner_result = mysqli_query($connection, $current_owner_query);
    $current_owner_info = mysqli_fetch_assoc($current_owner_result);

    $insert_query = "INSERT INTO drivers (first_name, middle_name, last_name, date_of_birth, current_address, license_number, body_number, motor_plate_no, make_model, ownership_status)
                     VALUES ('{$new_owner_data['first_name']}', '{$new_owner_data['middle_name']}', '{$new_owner_data['last_name']}', '{$new_owner_data['date_of_birth']}', '{$new_owner_data['current_address']}', '{$new_owner_data['license_number']}', '{$current_owner_info['body_number']}', '{$current_owner_info['motor_plate_no']}', '{$current_owner_info['make_model']}', 'Operator')";

    mysqli_query($connection, $insert_query);

    // Remove ownership status from current owner
    $update_old_owner_query = "UPDATE drivers SET ownership_status = '' WHERE driver_id = '$current_owner_id'";
    mysqli_query($connection, $update_old_owner_query);
}

// Success message and redirect
echo "<p style='text-align: center; color: green;'>Transfer completed successfully! Redirecting to drivers page...</p>";
header("refresh:3;url=drivers.php");
exit();
?>
