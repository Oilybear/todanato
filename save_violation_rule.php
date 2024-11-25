<?php
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_POST['confirm_save'])) {
    $violation_name = $_POST['violation_name'];
    $offense_number = $_POST['offense_number'];
    $penalty = $_POST['penalty'];

    $query = "INSERT INTO violation_rules (violation_name, offense_number, penalty) VALUES ('$violation_name', '$offense_number', '$penalty')";
    mysqli_query($connection, $query);
    mysqli_close($connection);

    // Redirect to violations and offenses page
    echo "<script>window.location.href='violation.php';</script>";
}
?>
