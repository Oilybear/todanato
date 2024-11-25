<?php
include('includes/dbconfig.php'); // Ensure the path is correct

if (isset($_POST['driver_id'])) {
    $driver_id = mysqli_real_escape_string($connection, $_POST['driver_id']);

    // Fetch related record counts
    $reports_count_query = "SELECT COUNT(*) as count FROM customer_reports WHERE driver_id = '$driver_id'";
    $reports_count_result = mysqli_query($connection, $reports_count_query);
    $reports_count = mysqli_fetch_assoc($reports_count_result)['count'];

    $violations_count_query = "SELECT COUNT(*) as count FROM violations WHERE driver_id = '$driver_id'";
    $violations_count_result = mysqli_query($connection, $violations_count_query);
    $violations_count = mysqli_fetch_assoc($violations_count_result)['count'];

    $messages_count_query = "SELECT COUNT(*) as count FROM messages WHERE driver_id = '$driver_id'";
    $messages_count_result = mysqli_query($connection, $messages_count_query);
    $messages_count = mysqli_fetch_assoc($messages_count_result)['count'];

    $app_drivers_count_query = "SELECT COUNT(*) as count FROM app_drivers WHERE driver_id = '$driver_id'";
    $app_drivers_count_result = mysqli_query($connection, $app_drivers_count_query);
    $app_drivers_count = mysqli_fetch_assoc($app_drivers_count_result)['count'];

    // Return data as JSON
    echo json_encode([
        'reports' => $reports_count,
        'violations' => $violations_count,
        'messages' => $messages_count,
        'app_drivers' => $app_drivers_count,
    ]);
}
?>
