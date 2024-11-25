<?php
include('includes/dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php'); 

// Get the current owner information from POST data
$current_owner_id = $_POST['current_owner'];
$transfer_type = $_POST['transfer_type'];

// Fetch current owner details
$current_owner_query = "SELECT * FROM drivers WHERE driver_id = '$current_owner_id'";
$current_owner_result = mysqli_query($connection, $current_owner_query);
$current_owner = mysqli_fetch_assoc($current_owner_result);

// Fetch new owner details based on transfer type
$new_owner_info = null;
if ($transfer_type === 'Member') {
    $new_owner_id = $_POST['new_owner'];
    $new_owner_query = "SELECT * FROM drivers WHERE driver_id = '$new_owner_id'";
    $new_owner_result = mysqli_query($connection, $new_owner_query);
    $new_owner_info = mysqli_fetch_assoc($new_owner_result);
} else if ($transfer_type === 'Non-Member') {
    $new_owner_info = [
        'first_name' => $_POST['first_name'],
        'middle_name' => $_POST['middle_name'],
        'last_name' => $_POST['last_name'],
        'date_of_birth' => $_POST['date_of_birth'],
        'place_of_birth' => $_POST['place_of_birth'],
        'current_address' => $_POST['current_address'],
        'license_number' => $_POST['license_number'],
        'membership_application_form_copy' => $_FILES['membership_application_form_copy']['name'],
        'proof_of_membership_payment' => $_FILES['proof_of_membership_payment']['name'],
        'drivers_license_copy' => $_FILES['drivers_license_copy']['name']
    ];

    // Handle file uploads
    $upload_dir = "uploads/";
    move_uploaded_file($_FILES['membership_application_form_copy']['tmp_name'], $upload_dir . $new_owner_info['membership_application_form_copy']);
    move_uploaded_file($_FILES['proof_of_membership_payment']['tmp_name'], $upload_dir . $new_owner_info['proof_of_membership_payment']);
    move_uploaded_file($_FILES['drivers_license_copy']['tmp_name'], $upload_dir . $new_owner_info['drivers_license_copy']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview Transfer</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5>Preview Transfer</h5></div>
                <div class="card-body">
                    <h6>Current Owner Information</h6>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($current_owner['first_name'] . " " . $current_owner['middle_name'] . " " . $current_owner['last_name']); ?></p>
                    <p><strong>Tricycle Information:</strong> <?php echo htmlspecialchars($current_owner['body_number'] . ", " . $current_owner['motor_plate_no'] . ", " . $current_owner['make_model']); ?></p>
                    
                    <h6 class="mt-4">New Owner Information</h6>
                    <?php if ($transfer_type === 'Member') { ?>
                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($new_owner_info['first_name'] . " " . $new_owner_info['middle_name'] . " " . $new_owner_info['last_name']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($new_owner_info['date_of_birth']); ?></p>
                    <?php } else if ($transfer_type === 'Non-Member') { ?>
                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($new_owner_info['first_name'] . " " . $new_owner_info['middle_name'] . " " . $new_owner_info['last_name']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($new_owner_info['date_of_birth']); ?></p>
                        <p><strong>Current Address:</strong> <?php echo htmlspecialchars($new_owner_info['current_address']); ?></p>
                        <p><strong>Driver’s License Number:</strong> <?php echo htmlspecialchars($new_owner_info['license_number']); ?></p>
                    <?php } ?>

                    <!-- Confirm Transfer Button -->
                    <form action="confirm_transfer.php" method="POST">
                        <input type="hidden" name="current_owner_id" value="<?php echo $current_owner_id; ?>">
                        <input type="hidden" name="transfer_type" value="<?php echo $transfer_type; ?>">
                        
                        <?php if ($transfer_type === 'Member') { ?>
                            <input type="hidden" name="new_owner_id" value="<?php echo $new_owner_id; ?>">
                        <?php } else { ?>
                            <?php foreach ($new_owner_info as $key => $value) { ?>
                                <input type="hidden" name="new_owner_<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
                            <?php } ?>
                        <?php } ?>
                        
                        <button type="submit" class="btn btn-primary mt-3">Confirm Transfer</button>
                        <a href="transfer_ownership_form.php" class="btn btn-secondary mt-3">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/scripts.php'); include('includes/footer.php'); ?>
</body>
</html>
