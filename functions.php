<?php
// functions.php

function connectDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "todanato";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function getCustomerReports($conn) {
    $sql = "SELECT * FROM customer_reports";
    $result = $conn->query($sql);

    $reports = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
    }
    return $reports;
}

function getDriverProgress($driverId) {
    global $conn;
    $query = "SELECT * FROM driver_progress WHERE driver_id = $driverId";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}
?>