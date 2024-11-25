<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Set all statuses to 'Inactive'
    $query_deactivate = "UPDATE fare_matrix SET status='Inactive'";
    $query_run_deactivate = mysqli_query($connection, $query_deactivate);

    if ($query_run_deactivate) {
        // Set the selected row to 'Active'
        $query_activate = "UPDATE fare_matrix SET status='Active' WHERE id='$id'";
        $query_run_activate = mysqli_query($connection, $query_activate);

        if ($query_run_activate) {
            $_SESSION['success'] = "Gas price set to Active successfully.";
        } else {
            $_SESSION['status'] = "Failed to set gas price to Active.";
        }
    } else {
        $_SESSION['status'] = "Failed to deactivate other gas prices.";
    }
}

header("Location: fare_matrix.php"); // Redirect back to the fare matrix page
exit();
?>
