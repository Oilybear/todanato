<?php
ob_start(); // Start output buffering
include('includes/header.php'); 
include('includes/navbar.php'); 

$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM fare_matrix WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        $row = mysqli_fetch_assoc($query_run);
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

if (isset($_POST['update_fare'])) {
    $id = $_POST['id'];
    $gas_price_range = $_POST['gas_price_range'];
    $provisional_minimum_fare = $_POST['provisional_minimum_fare'];
    $special_trip = $_POST['special_trip'];
    $min_fare_senior = $_POST['min_fare_senior'];
    $special_trip_senior = $_POST['special_trip_senior'];

    $query = "UPDATE fare_matrix SET 
                gas_price_range='$gas_price_range', 
                provisional_minimum_fare='$provisional_minimum_fare', 
                special_trip='$special_trip', 
                min_fare_senior='$min_fare_senior', 
                special_trip_senior='$special_trip_senior' 
              WHERE id='$id'";

    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        $_SESSION['success'] = "Fare Matrix Updated Successfully!";
        header("Location: fare_matrix.php"); // Redirect back to the fare matrix page
        exit();
    } else {
        $_SESSION['status'] = "Failed to Update Fare Matrix!";
        echo "Error: " . mysqli_error($connection);
    }
}
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Fare Matrix</h6>
        </div>
        <div class="card-body">

            <form action="edit_fare.php?id=<?php echo $row['id']; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <div class="form-group">
                    <label for="gas_price_range">Gas Price Range</label>
                    <input type="text" name="gas_price_range" class="form-control" value="<?php echo $row['gas_price_range']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="provisional_minimum_fare">Provisional Minimum Fare</label>
                    <input type="number" step="0.01" name="provisional_minimum_fare" class="form-control" value="<?php echo $row['provisional_minimum_fare']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="special_trip">Special Trip</label>
                    <input type="number" step="0.01" name="special_trip" class="form-control" value="<?php echo $row['special_trip']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="min_fare_senior">Minimum Fare (Senior/PWD/Students)</label>
                    <input type="number" step="0.01" name="min_fare_senior" class="form-control" value="<?php echo $row['min_fare_senior']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="special_trip_senior">Special Trip (Senior/PWD/Students)</label>
                    <input type="number" step="0.01" name="special_trip_senior" class="form-control" value="<?php echo $row['special_trip_senior']; ?>" required>
                </div>

                <button type="submit" name="update_fare" class="btn btn-primary">Update Fare</button>
            </form>

        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php'); 
ob_end_flush(); // End buffering and flush output
?>
