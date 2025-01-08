<!-- preview_driver.php -->
<?php 
include('includes/header.php'); 
include('includes/navbar.php'); 
include('dbconfig.php'); // Include your database connection file

if (isset($_POST['add_driver_btn'])) {
    // Store data into the driver_applications table
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $place_of_birth = $_POST['place_of_birth'];
    $current_address = $_POST['current_address'];
    $license_number = $_POST['license_number'];
    $body_number = $_POST['body_number'];
    $motor_plate_no = $_POST['motor_plate_no'];
    $make_model = $_POST['make_model'];
    $ownership_status = $_POST['ownership_status'];
    $application_status = 'pending'; // Default value for new applications

    // SQL query to insert data
    $query = "INSERT INTO driver_applications (
        last_name, 
        first_name, 
        middle_name, 
        date_of_birth, 
        place_of_birth, 
        current_address, 
        license_number, 
        body_number, 
        motor_plate_no, 
        make_model, 
        ownership_status, 
        application_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param(
            "ssssssssssss", 
            $last_name, 
            $first_name, 
            $middle_name, 
            $date_of_birth, 
            $place_of_birth, 
            $current_address, 
            $license_number, 
            $body_number, 
            $motor_plate_no, 
            $make_model, 
            $ownership_status,
            $application_status
        );

        if ($stmt->execute()) {
            echo "<script>alert('Driver information successfully saved!'); window.location.href='drivers.php';</script>";
        } else {
            echo "<script>alert('Error saving driver information. Please try again.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement.');</script>";
    }
}

if (isset($_POST['preview_driver_btn'])) {
    // Collect and sanitize form data for preview
    $last_name = htmlspecialchars($_POST['last_name']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $middle_name = htmlspecialchars($_POST['middle_name']);
    $date_of_birth = htmlspecialchars($_POST['date_of_birth']);
    $place_of_birth = htmlspecialchars($_POST['place_of_birth']);
    $current_address = htmlspecialchars($_POST['current_address']);
    $license_number = htmlspecialchars($_POST['license_number']);
    $body_number = htmlspecialchars($_POST['body_number']);
    $motor_plate_no = htmlspecialchars($_POST['motor_plate_no']);
    $make_model = htmlspecialchars($_POST['make_model']);
    $ownership_status = htmlspecialchars($_POST['ownership_status']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Driver Information</title>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5>Preview Driver Information</h5></div>
                <div class="card-body">
                    <!-- Display driver information preview -->
                    <p><strong>Last Name:</strong> <?php echo $last_name; ?></p>
                    <p><strong>First Name:</strong> <?php echo $first_name; ?></p>
                    <p><strong>Middle Name:</strong> <?php echo $middle_name; ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo $date_of_birth; ?></p>
                    <p><strong>Place of Birth:</strong> <?php echo $place_of_birth; ?></p>
                    <p><strong>Current Address:</strong> <?php echo $current_address; ?></p>
                    <p><strong>Driver’s License Number:</strong> <?php echo $license_number; ?></p>
                    <p><strong>Body Number:</strong> <?php echo $body_number; ?></p>
                    <p><strong>Motor Plate Number:</strong> <?php echo $motor_plate_no; ?></p>
                    <p><strong>Make/Model:</strong> <?php echo $make_model; ?></p>
                    <p><strong>Ownership Status:</strong> <?php echo $ownership_status; ?></p>

                    <!-- Confirm and Go Back buttons -->
                    <form action="preview_driver.php" method="POST">
                        <!-- Hidden fields to carry data for storage -->
                        <input type="hidden" name="last_name" value="<?php echo $last_name; ?>">
                        <input type="hidden" name="first_name" value="<?php echo $first_name; ?>">
                        <input type="hidden" name="middle_name" value="<?php echo $middle_name; ?>">
                        <input type="hidden" name="date_of_birth" value="<?php echo $date_of_birth; ?>">
                        <input type="hidden" name="place_of_birth" value="<?php echo $place_of_birth; ?>">
                        <input type="hidden" name="current_address" value="<?php echo $current_address; ?>">
                        <input type="hidden" name="license_number" value="<?php echo $license_number; ?>">
                        <input type="hidden" name="body_number" value="<?php echo $body_number; ?>">
                        <input type="hidden" name="motor_plate_no" value="<?php echo $motor_plate_no; ?>">
                        <input type="hidden" name="make_model" value="<?php echo $make_model; ?>">
                        <input type="hidden" name="ownership_status" value="<?php echo $ownership_status; ?>">

                        <div class="form-group mt-3">
                            <button type="submit" name="add_driver_btn" class="btn btn-success">Confirm</button>
                            <a href="add_driver.php" class="btn btn-secondary">Go Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/scripts.php'); include('includes/footer.php'); ?>
</body>
</html>
