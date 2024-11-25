<?php
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['driver_id'])) {
    $driver_id = $_POST['driver_id'];
    $query = "SELECT full_name FROM drivers WHERE driver_id = '$driver_id' LIMIT 1";
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo htmlspecialchars($row['full_name']);
    } else {
        echo 'Driver not found';
    }
}
?>
