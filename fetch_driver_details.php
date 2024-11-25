<?php
include('dbconfig.php'); // Ensure this file contains $connection setup

if (isset($_POST['driver_id'])) {
    $driver_id = $_POST['driver_id'];

    $query = "SELECT driver_id, full_name, license_number, date_of_birth, address, license_copy, contact_number FROM drivers WHERE driver_id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $driver_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $details = mysqli_fetch_assoc($result);
        echo json_encode($details);
    } else {
        echo json_encode([]);
    }
}
?>
