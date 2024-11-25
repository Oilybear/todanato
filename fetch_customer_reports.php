<?php
include('db_connection.php');

$query = "SELECT * FROM customer_reports";
$result = mysqli_query($conn, $query);

$customer_reports = array();
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $customer_reports[] = $row;
    }
}
echo json_encode($customer_reports);
?>
