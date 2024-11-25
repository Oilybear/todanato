<?php
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_POST['confirm_delete'])) {
    $id = $_POST['delete_id'];

    // Delete query
    $query = "DELETE FROM violation_rules WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        echo "<script>alert('Violation deleted successfully.');</script>";
    } else {
        echo "<script>alert('Failed to delete violation.');</script>";
    }
}

// Redirect back to the violations and offenses page
echo "<script>window.location.href='violation.php';</script>";
?>
