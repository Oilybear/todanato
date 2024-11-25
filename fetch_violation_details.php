<?php
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Query to fetch violation details
    $query = "SELECT violation_name, offense_number, penalty FROM violation_rules WHERE id='$id'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
}
?>
