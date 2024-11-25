<?php
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_POST['confirm_edit'])) {
    $id = $_POST['id'];
    $violation_name = $_POST['violation_name'];
    $offense_number = $_POST['offense_number'];
    $penalty = $_POST['penalty'];

    $query = "UPDATE violation_rules SET violation_name='$violation_name', offense_number='$offense_number', penalty='$penalty' WHERE id='$id'";
    mysqli_query($connection, $query);
    mysqli_close($connection);

    // Redirect to violations and offenses page
    echo "<script>window.location.href='violation.php';</script>";
}
?>
