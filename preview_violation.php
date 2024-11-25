<?php
include('dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php'); 

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data from POST request
    $driver_id = htmlspecialchars($_POST['driver_id']);
    $driver_name = htmlspecialchars($_POST['driver_name']);
    $violation_type = htmlspecialchars($_POST['violation_type']);
    $violation_date = htmlspecialchars($_POST['violation_date']);
    $offense = htmlspecialchars($_POST['offense']);
    $penalty = htmlspecialchars($_POST['penalty']);
    $description = htmlspecialchars($_POST['description']);
} else {
    // If the form was not submitted, redirect to the add violation page
    echo '<script>alert("Invalid access!"); window.location.href = "add_violation.php";</script>';
    exit();
}

// Handle saving to the database
if (isset($_POST['confirm_btn'])) {
    $query = "INSERT INTO violations (driver_id, name, violation_type, violation_date, offense, penalty, description) 
              VALUES ('$driver_id', '$driver_name', '$violation_type', '$violation_date', '$offense', '$penalty', '$description')";
    
    if (mysqli_query($connection, $query)) {
        // Redirect to violations page after successful insertion using JavaScript
        echo '<script>alert("Violation added successfully!"); window.location.href = "violation.php";</script>';
        exit();
    } else {
        // Handle error
        echo '<script>alert("Error: ' . mysqli_error($connection) . '"); window.location.href = "violation.php";</script>';
        exit();
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Preview Violation</h5>
                </div>
                <div class="card-body">
                    <p><strong>Driver ID:</strong> <?php echo $driver_id; ?></p>
                    <p><strong>Driver Name:</strong> <?php echo $driver_name; ?></p>
                    <p><strong>Violation Type:</strong> <?php echo $violation_type; ?></p>
                    <p><strong>Violation Date:</strong> <?php echo $violation_date; ?></p>
                    <p><strong>Offense:</strong> <?php echo $offense; ?></p>
                    <p><strong>Penalty:</strong> <?php echo $penalty; ?></p>
                    <p><strong>Description:</strong> <?php echo $description; ?></p>
                    
                    <form action="preview_violation.php" method="POST">
                        <input type="hidden" name="driver_id" value="<?php echo $driver_id; ?>">
                        <input type="hidden" name="driver_name" value="<?php echo $driver_name; ?>">
                        <input type="hidden" name="violation_type" value="<?php echo $violation_type; ?>">
                        <input type="hidden" name="violation_date" value="<?php echo $violation_date; ?>">
                        <input type="hidden" name="offense" value="<?php echo $offense; ?>">
                        <input type="hidden" name="penalty" value="<?php echo $penalty; ?>">
                        <input type="hidden" name="description" value="<?php echo $description; ?>">

                        <div class="form-group mt-3">
                            <button type="submit" name="confirm_btn" class="btn btn-success">Confirm and Save</button>
                            <a href="add_violation.php" class="btn btn-secondary">Go Back to Edit</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
