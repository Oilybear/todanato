<?php
include('includes/dbconfig.php');

// Helper function to run queries and handle errors
function run_query($connection, $query) {
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    return $result;
}

// Fetch counts for summary cards
$driver_count_query = "SELECT COUNT(*) AS total FROM drivers";
$driver_count_result = run_query($connection, $driver_count_query);
$driver_count = mysqli_fetch_assoc($driver_count_result)['total'];

$violation_count_query = "SELECT COUNT(*) AS total FROM violations";
$violation_count_result = run_query($connection, $violation_count_query);
$violation_count = mysqli_fetch_assoc($violation_count_result)['total'];

$rider_count_query = "SELECT COUNT(*) AS total FROM app_riders";
$rider_count_result = run_query($connection, $rider_count_query);
$rider_count = mysqli_fetch_assoc($rider_count_result)['total'];

// Fetch total drivers registered in TODAnaTo App
$app_drivers_query = "SELECT COUNT(*) AS total_app_drivers FROM app_drivers";
$app_drivers_result = mysqli_query($connection, $app_drivers_query);
$app_drivers_data = mysqli_fetch_assoc($app_drivers_result);
$total_app_drivers = $app_drivers_data['total_app_drivers'];

?>
