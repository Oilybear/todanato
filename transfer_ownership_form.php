<?php
include('includes/dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php'); 

// Query to fetch drivers with "Operator" ownership status
$query_operators = "SELECT driver_id, first_name, middle_name, last_name FROM drivers WHERE ownership_status = 'Operator'";
$query_run_operators = mysqli_query($connection, $query_operators);

// Query to fetch all drivers for the 'New Owner' dropdown
$query_drivers = "SELECT driver_id, first_name, middle_name, last_name FROM drivers";
$query_run_drivers = mysqli_query($connection, $query_drivers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Transfer Ownership</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transfer Ownership</h6>
        </div>
        <div class="card-body">
            <form action="preview_transfer.php" method="POST" enctype="multipart/form-data">
                
                <!-- Current Owner Dropdown -->
                <div class="form-group">
                    <label for="currentOwner">Select Current Owner (Operator)</label>
                    <select name="current_owner" id="currentOwner" class="form-control" required>
                        <option value="">Select an Operator</option>
                        <?php
                        // Populate dropdown with operators
                        while ($row = mysqli_fetch_assoc($query_run_operators)) {
                            $full_name = htmlspecialchars($row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']);
                            echo "<option value='" . htmlspecialchars($row['driver_id']) . "'>$full_name</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Transfer Type Dropdown -->
                <div class="form-group">
                    <label for="transferType">Transfer Type</label>
                    <select name="transfer_type" id="transferType" class="form-control" required>
                        <option value="">Select Transfer Type</option>
                        <option value="Member">Member</option>
                        <option value="Non-Member">Non-Member</option>
                    </select>
                </div>

                <!-- New Owner Dropdown for Member Type -->
                <div class="form-group" id="newOwnerMember" style="display: none;">
                    <label for="newOwner">Select New Owner (Member)</label>
                    <select name="new_owner" id="newOwner" class="form-control">
                        <option value="">Select a New Owner</option>
                        <?php
                        // Populate dropdown with all drivers except the selected current owner
                        while ($row = mysqli_fetch_assoc($query_run_drivers)) {
                            $full_name = htmlspecialchars($row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']);
                            echo "<option class='driver-option' value='" . htmlspecialchars($row['driver_id']) . "'>$full_name</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Non-Member New Owner Information Form -->
                <div id="newOwnerNonMember" style="display: none;">
                    <h6>New Owner Information (Non-Member)</h6>
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
                        <input type="text" name="middle_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Place of Birth</label>
                        <input type="text" name="place_of_birth" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Current Address</label>
                        <input type="text" name="current_address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Driver’s License Number</label>
                        <input type="text" name="license_number" class="form-control" required>
                    </div>

                    <!-- File Uploads -->
                    <div class="form-group">
                        <label>Membership Application Form Copy</label>
                        <input type="file" name="membership_application_form_copy" class="form-control-file" required>
                    </div>
                    <div class="form-group">
                        <label>Proof of Membership Payment</label>
                        <input type="file" name="proof_of_membership_payment" class="form-control-file" required>
                    </div>
                    <div class="form-group">
                        <label>Driver’s License Copy</label>
                        <input type="file" name="drivers_license_copy" class="form-control-file" required>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-group mt-3">
                    <button type="submit" name="preview_transfer_btn" class="btn btn-primary">Preview Transfer</button>
                    <a href="drivers.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/scripts.php'); include('includes/footer.php'); ?>

<script>
$(document).ready(function() {
    // Show or hide the 'New Owner' dropdown or form based on the selected transfer type
    $('#transferType').change(function() {
        if ($(this).val() === 'Member') {
            $('#newOwnerMember').show();
            $('#newOwnerNonMember').hide();
        } else if ($(this).val() === 'Non-Member') {
            $('#newOwnerMember').hide();
            $('#newOwnerNonMember').show();
        } else {
            $('#newOwnerMember').hide();
            $('#newOwnerNonMember').hide();
        }
    });

    // Filter out the current owner from the 'New Owner' dropdown
    $('#currentOwner').change(function() {
        var selectedOwnerId = $(this).val();
        $('#newOwner option').each(function() {
            if ($(this).val() === selectedOwnerId) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
});
</script>
</body>
</html>
