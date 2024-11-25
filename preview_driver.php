<!-- preview_driver.php -->
<?php 
include('includes/header.php'); 
include('includes/navbar.php'); 

if (isset($_POST['preview_driver_btn'])) {
    // Collect and sanitize form data
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
    
    // Handle file uploads
    $upload_dir = 'uploads/';
    $membership_application_form_copy = uniqid() . '_' . basename($_FILES['membership_application_form_copy']['name']);
    $proof_of_membership_payment = uniqid() . '_' . basename($_FILES['proof_of_membership_payment']['name']);
    $drivers_license_copy = uniqid() . '_' . basename($_FILES['drivers_license_copy']['name']);

    move_uploaded_file($_FILES['membership_application_form_copy']['tmp_name'], $upload_dir . $membership_application_form_copy);
    move_uploaded_file($_FILES['proof_of_membership_payment']['tmp_name'], $upload_dir . $proof_of_membership_payment);
    move_uploaded_file($_FILES['drivers_license_copy']['tmp_name'], $upload_dir . $drivers_license_copy);
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
                    <p><strong>Membership Application Form Copy:</strong> <?php echo $membership_application_form_copy; ?></p>
                    <p><strong>Proof of Membership Payment:</strong> <?php echo $proof_of_membership_payment; ?></p>
                    <p><strong>Driver’s License Copy:</strong> <?php echo $drivers_license_copy; ?></p>

                    <!-- Confirm and Go Back buttons -->
                    <form action="save_driver.php" method="POST">
                        <!-- Hidden fields to carry data to save_driver.php -->
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
                        <input type="hidden" name="membership_application_form_copy" value="<?php echo $membership_application_form_copy; ?>">
                        <input type="hidden" name="proof_of_membership_payment" value="<?php echo $proof_of_membership_payment; ?>">
                        <input type="hidden" name="drivers_license_copy" value="<?php echo $drivers_license_copy; ?>">

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
