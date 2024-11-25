<?php
include('includes/dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php');     
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Driver Management</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table th, .table td { text-align: center; }
        .table thead th { background-color: #aa3131; color: white; }
        .table tbody tr:nth-child(even) { background-color: #f8f9fc; }
        .table tbody tr:hover { background-color: #e2e6ea; }
        .custom-btn { background-color: #aa3131; color: white; border: none; border-radius: 0.25rem; padding: 0.375rem 0.75rem; cursor: pointer; }
        .custom-btn:hover { background-color: #2e59d9; }
        .btn-edit { background-color: #4e73df; color: white; }
        .btn-delete { background-color: #e74a3b; color: white; }
    </style>
</head>

<body>

<div class="container-fluid">
    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6><strong>Driver Management</strong></h6>
            <div>
                <a href="add_driver.php" class="btn btn-primary mr-2">Add Driver</a>
                <a href="transfer_ownership_form.php" class="btn btn-primary">Transfer Ownership</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <?php
                $query = "SELECT * FROM drivers";
                $query_run = mysqli_query($connection, $query);
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Motor Plate Number</th>
                            <th>Make/Model</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["motor_plate_no"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["make_model"]); ?></td>
                                    <td>
                                        <!-- View Full Details Button -->
                                        <button type="button" class="custom-btn btn-sm" data-toggle="modal" data-target="#detailsModal"
                                                data-id="<?php echo htmlspecialchars($row["driver_id"]); ?>"
                                                data-fullname="<?php echo htmlspecialchars($row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"]); ?>"
                                                data-license="<?php echo htmlspecialchars($row["license_number"]); ?>"
                                                data-birth="<?php echo htmlspecialchars($row["date_of_birth"]); ?>"
                                                data-placebirth="<?php echo htmlspecialchars($row["place_of_birth"]); ?>"
                                                data-address="<?php echo htmlspecialchars($row["current_address"]); ?>"
                                                data-body="<?php echo htmlspecialchars($row["body_number"]); ?>"
                                                data-plate="<?php echo htmlspecialchars($row["motor_plate_no"]); ?>"
                                                data-make="<?php echo htmlspecialchars($row["make_model"]); ?>"
                                                data-ownership="<?php echo htmlspecialchars($row["ownership_status"]); ?>"
                                                data-membership="<?php echo htmlspecialchars($row["membership_application_form_copy"]); ?>"
                                                data-paymentproof="<?php echo htmlspecialchars($row["proof_of_membership_payment"]); ?>"
                                                data-licensecopy="<?php echo htmlspecialchars($row["drivers_license_copy"]); ?>">
                                            View Full Details
                                        </button>

                                        <!-- Edit Button -->
                                        <a href="driver_edit.php?id=<?php echo $row['driver_id']; ?>" class="btn btn-edit btn-sm">Edit</a>
                                        
                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-delete btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo $row['driver_id']; ?>">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='no-record'>No Record Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Driver Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Full Name:</strong> <span id="modal-fullname"></span></p>
                    <p><strong>License Number:</strong> <span id="modal-license"></span></p>
                    <p><strong>Date of Birth:</strong> <span id="modal-birth"></span></p>
                    <p><strong>Place of Birth:</strong> <span id="modal-placebirth"></span></p>
                    <p><strong>Current Address:</strong> <span id="modal-address"></span></p>
                    <p><strong>Body Number:</strong> <span id="modal-body"></span></p>
                    <p><strong>Motor Plate Number:</strong> <span id="modal-plate"></span></p>
                    <p><strong>Make/Model:</strong> <span id="modal-make"></span></p>
                    <p><strong>Ownership Status:</strong> <span id="modal-ownership"></span></p>
                    <p><strong>Membership Form Copy:</strong> <span id="modal-membership"></span></p>
                    <p><strong>Proof of Membership Payment:</strong> <span id="modal-paymentproof"></span></p>
                    <p><strong>Driver’s License Copy:</strong> <span id="modal-licensecopy"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this driver? This action cannot be undone.</p>
                <p>Related Records:</p>
                <ul id="related-records-list">
                    <li>Customer Reports: <span id="reports-count">0</span></li>
                    <li>Violations: <span id="violations-count">0</span></li>
                    <li>Messages: <span id="messages-count">0</span></li>
                    <li>App Driver Records: <span id="app-drivers-count">0</span></li>
                </ul>
                <input type="hidden" name="delete_id" id="delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="driver_code.php" method="POST" id="deleteForm">
                    <input type="hidden" name="delete_id" id="delete_id_form">
                    <button type="submit" name="delete_btn" class="btn btn-danger">Delete</button>
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
</body>
</html>

<script>
$(document).ready(function() {
    // View Details Modal
    $('#detailsModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        // Populate modal fields with data
        modal.find('#modal-fullname').text(button.data('fullname'));
        modal.find('#modal-license').text(button.data('license'));
        modal.find('#modal-birth').text(button.data('birth'));
        modal.find('#modal-placebirth').text(button.data('placebirth'));
        modal.find('#modal-address').text(button.data('address'));
        modal.find('#modal-body').text(button.data('body'));
        modal.find('#modal-plate').text(button.data('plate'));
        modal.find('#modal-make').text(button.data('make'));
        modal.find('#modal-ownership').text(button.data('ownership'));

        // File links
        if (button.data('membership')) {
            modal.find('#modal-membership').html('<a href="uploads/' + button.data('membership') + '" target="_blank">View Membership Form</a>');
        } else {
            modal.find('#modal-membership').text('Not Available');
        }
        
        if (button.data('paymentproof')) {
            modal.find('#modal-paymentproof').html('<a href="uploads/' + button.data('paymentproof') + '" target="_blank">View Payment Proof</a>');
        } else {
            modal.find('#modal-paymentproof').text('Not Available');
        }
        
        if (button.data('licensecopy')) {
            modal.find('#modal-licensecopy').html('<a href="uploads/' + button.data('licensecopy') + '" target="_blank">View License Copy</a>');
        } else {
            modal.find('#modal-licensecopy').text('Not Available');
        }
    });

    // Delete Modal
$('#deleteModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var driverId = button.data('id'); // Extract driver ID

    // Set the hidden input in the form to the driver ID
    $('#delete_id_form').val(driverId);

    // Perform AJAX request to get record counts (if necessary)
    $.ajax({
        url: 'fetch_related_counts.php', // This file will return the counts for the driver
        method: 'POST',
        data: { driver_id: driverId },
        dataType: 'json',
        success: function(data) {
            // Update the modal with the fetched counts
            $('#reports-count').text(data.reports);
            $('#violations-count').text(data.violations);
            $('#messages-count').text(data.messages);
            $('#app-drivers-count').text(data.app_drivers);
        }
    });
});

});
</script>
