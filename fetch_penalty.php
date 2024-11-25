<?php
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_POST['violation_type']) && isset($_POST['offense'])) {
    $violation_type = mysqli_real_escape_string($connection, $_POST['violation_type']);
    $offense = mysqli_real_escape_string($connection, $_POST['offense']);

    $query = "SELECT penalty FROM violation_rules WHERE violation_name = '$violation_type' AND offense_number = '$offense' LIMIT 1";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['penalty'];
    } else {
        echo "No penalty found";
    }
}
?>