<?php
include('includes/dbconfig.php'); // Include database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['driver_id'])) {
    $driver_id = $_POST['driver_id'];

    // Debug: Check if driver_id is being passed
    if (empty($driver_id)) {
        die("Driver ID is missing.");
    }

    // Delete query
    $query = "DELETE FROM drivers WHERE driver_id = ?";
    $stmt = $connection->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("i", $driver_id);

    if ($stmt->execute()) {
        // Success message
        echo "<script>alert('Driver successfully deleted.'); window.location.href = 'drivers.php';</script>";
    } else {
        // Error message
        echo "<script>alert('Error deleting driver: " . $stmt->error . "'); window.location.href = 'drivers.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request. Please try again.'); window.location.href = 'drivers.php';</script>";
}
?>
