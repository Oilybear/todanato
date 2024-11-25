<?php
include('includes/dbconfig.php'); // Database connection
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<div class="container-fluid">
    <!-- Edit Driver Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Driver</h6>
        </div>
        <div class="card-body">
            <?php
            if (isset($_GET['id'])) {
                $id = mysqli_real_escape_string($connection, $_GET['id']); // Sanitize the input

                // Fetch current driver details
                $query = "SELECT * FROM drivers WHERE driver_id='$id'";
                $query_run = mysqli_query($connection, $query);

                if ($query_run) {
                    $row = mysqli_fetch_assoc($query_run); // Fetch the associative array directly

                    if ($row) {
                        ?>
                        <form action="update_driver.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="edit_id" value="<?php echo $row['driver_id']; ?>">

                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" name="edit_full_name" id="full_name" value="<?php echo htmlspecialchars($row["first_name"] . ' ' . $row["middle_name"] . ' ' . $row["last_name"]); ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="license_number">Driver’s License Number</label>
                                <input type="text" name="edit_license_number" id="license_number" value="<?php echo htmlspecialchars($row["license_number"]); ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" name="edit_date_of_birth" id="date_of_birth" value="<?php echo htmlspecialchars($row["date_of_birth"]); ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="current_address">Current Address</label>
                                <input type="text" name="edit_current_address" id="current_address" value="<?php echo htmlspecialchars($row["current_address"]); ?>" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="motor_plate_no">Motor Plate Number</label>
                                <input type="text" name="edit_motor_plate_no" id="motor_plate_no" value="<?php echo htmlspecialchars($row["motor_plate_no"]); ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="make_model">Make and Model</label>
                                <input type="text" name="edit_make_model" id="make_model" value="<?php echo htmlspecialchars($row["make_model"]); ?>" class="form-control" readonly>
                            </div>

                            <!-- Document Uploads -->
                            <div class="form-group">
                                <label for="membership_application_form_copy">Membership Application Form Copy</label>
                                <input type="file" name="edit_membership_application_form_copy" class="form-control-file">
                                <?php if ($row["membership_application_form_copy"]) { ?>
                                    <p>Current File: <a href="uploads/<?php echo htmlspecialchars($row["membership_application_form_copy"]); ?>" target="_blank">View File</a></p>
                                <?php } else { echo "<p>No file uploaded.</p>"; } ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="proof_of_membership_payment">Proof of Membership Payment</label>
                                <input type="file" name="edit_proof_of_membership_payment" class="form-control-file">
                                <?php if ($row["proof_of_membership_payment"]) { ?>
                                    <p>Current File: <a href="uploads/<?php echo htmlspecialchars($row["proof_of_membership_payment"]); ?>" target="_blank">View File</a></p>
                                <?php } else { echo "<p>No file uploaded.</p>"; } ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="drivers_license_copy">Driver’s License Copy</label>
                                <input type="file" name="edit_drivers_license_copy" class="form-control-file">
                                <?php if ($row["drivers_license_copy"]) { ?>
                                    <p>Current File: <a href="uploads/<?php echo htmlspecialchars($row["drivers_license_copy"]); ?>" target="_blank">View File</a></p>
                                <?php } else { echo "<p>No file uploaded.</p>"; } ?>
                            </div>

                            <button type="submit" name="update_driver_btn" class="btn btn-primary">Update</button>
                            <a href="drivers.php" class="btn btn-danger">Cancel</a>
                        </form>
                        <?php
                    } else {
                        echo "No driver found.";
                    }
                } else {
                    echo "Error fetching data.";
                }
            } else {
                echo "Invalid request.";
            }
            ?>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
