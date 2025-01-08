<!-- add_driver.php -->
<?php include('includes/header.php'); include('includes/navbar.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Driver</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5>New Application</h5></div>
                <div class="card-body">
                    <form action="preview_driver.php" method="POST">
                        
                        <!-- Driver Information Section -->
                        <h6>Driver Information</h6>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Place of Birth</label>
                            <input type="text" name="place_of_birth" class="form-control" placeholder="Enter Place of Birth" required>
                        </div>
                        <div class="form-group">
                            <label>Current Address</label>
                            <input type="text" name="current_address" class="form-control" placeholder="Enter Current Address" required>
                        </div>
                        <div class="form-group">
                            <label>Driver’s License Number</label>
                            <input type="text" name="license_number" class="form-control" placeholder="Enter License Number" required>
                        </div>

                        <!-- Tricycle Information Section -->
                        <h6 class="mt-3">Tricycle Information</h6>
                        <div class="form-group">
                            <label>Body Number</label>
                            <input type="text" name="body_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Motor Plate Number</label>
                            <input type="text" name="motor_plate_no" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Make/Model</label>
                            <input type="text" name="make_model" class="form-control" required>
                        </div>

                        <!-- Ownership Status -->
                        <div class="form-group mt-4">
                            <label>Ownership Status</label>
                            <select name="ownership_status" class="form-control" required>
                                <option value="Operator">Operator</option>
                                <option value="Driver">Driver</option>
                            </select>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-group mt-3">
                            <button type="submit" name="preview_driver_btn" class="btn btn-primary">Preview</button>
                            <a href="drivers.php" class="btn btn-secondary">Cancel</a>
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
