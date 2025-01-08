<?php
include('includes/dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php');     

// Fetch the count of pending applications
$query_pending_apps = "SELECT COUNT(*) as pending_count FROM driver_applications WHERE application_status = 'pending'";
$result_pending_apps = mysqli_query($connection, $query_pending_apps);
$row_pending_apps = mysqli_fetch_assoc($result_pending_apps);
$pending_count = $row_pending_apps['pending_count'] ?? 0; // Default to 0 if no result

// Fetch the count of pending payments
$query_pending_payments = "SELECT COUNT(*) as pending_payment_count 
                           FROM driver_applications 
                           WHERE application_status = 'approved' AND payment_status = 'unpaid'";
$result_pending_payments = mysqli_query($connection, $query_pending_payments);
$row_pending_payments = mysqli_fetch_assoc($result_pending_payments);
$pending_payment_count = $row_pending_payments['pending_payment_count'] ?? 0; // Default to 0 if no result

// Fetch current drivers from the drivers table
$query_current_drivers = "SELECT * FROM drivers";
$result_current_drivers = mysqli_query($connection, $query_current_drivers);

if (!$result_current_drivers) {
    die("Query Failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Driver Management</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .card-header {
            background-color: #aa3131;
            color: white;
            text-align: center;
        }
        .card-body {
            text-align: center;
        }
        .card-body .count {
            font-size: 2rem;
            font-weight: bold;
        }
        .btn-view, .btn-add, .btn-payments, .btn-details {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            margin: 0.5rem;
        }
        .btn-view:hover, .btn-add:hover, .btn-payments:hover, .btn-details:hover {
            background-color: #0056b3;
        }
        .table th, .table td {
            text-align: center;
        }
        .table thead th {
            background-color: #aa3131;
            color: white;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- Pending Applications Card -->
        <div class="col-lg-6 col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header text-center">
                    <h6><strong>Pending Applications</strong></h6>
                </div>
                <div class="card-body text-center">
                    <p class="count"><?php echo $pending_count; ?></p>
                    <a href="pending_applications.php" class="btn-view">View Pending Applications</a>
                    <a href="add_driver.php" class="btn-add">Add Application</a>
                </div>
            </div>
        </div>

        <!-- Pending Payments Card -->
        <div class="col-lg-6 col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header text-center">
                    <h6><strong>Pending Application Payments</strong></h6>
                </div>
                <div class="card-body text-center">
                    <p class="count"><?php echo $pending_payment_count; ?></p>
                    <a href="pending_payments.php" class="btn-payments">View Pending Application Payments</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Drivers Table -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6><strong>Current Drivers in Fleet</strong></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Body Number</th>
                            <th>Plate Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_current_drivers) > 0) {
                            while ($row = mysqli_fetch_assoc($result_current_drivers)) {
                                $full_name = htmlspecialchars($row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"]);
                                $body_number = htmlspecialchars($row["body_number"]);
                                $plate_number = htmlspecialchars($row["motor_plate_no"]);
                                ?>
                                <tr>
                                    <td><?php echo $full_name; ?></td>
                                    <td><?php echo $body_number; ?></td>
                                    <td><?php echo $plate_number; ?></td>
                                    <td>
                                    <button type="button" class="btn-details" 
        data-toggle="modal" 
        data-target="#detailsModal"
        data-id="<?php echo htmlspecialchars($row['driver_id']); ?>"
        data-fullname="<?php echo htmlspecialchars($row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"]); ?>"
        data-license="<?php echo htmlspecialchars($row['license_number']); ?>"
        data-address="<?php echo htmlspecialchars($row['current_address']); ?>"
        data-body="<?php echo htmlspecialchars($row['body_number']); ?>"
        data-plate="<?php echo htmlspecialchars($row['motor_plate_no']); ?>"
        data-make="<?php echo htmlspecialchars($row['make_model']); ?>">
    View Full Details
</button>

                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>No Current Drivers Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Driver Details Modal -->
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
                <p><strong>Driver’s License:</strong> <span id="modal-license"></span></p>
                <p><strong>Current Address:</strong> <span id="modal-address"></span></p>
                <p><strong>Body Number:</strong> <span id="modal-body"></span></p>
                <p><strong>Motor Plate Number:</strong> <span id="modal-plate"></span></p>
                <p><strong>Make/Model:</strong> <span id="modal-make"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form method="POST" action="delete_driver.php" class="d-inline">
                    <input type="hidden" name="driver_id" id="modal-driver-id">
                    <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Are you sure you want to delete this driver? This action cannot be undone.');">
                        Delete Driver
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>

<script>
$(document).ready(function() {
    $('#detailsModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this);

        // Populate modal fields with data attributes
        modal.find('#modal-fullname').text(button.data('fullname'));
        modal.find('#modal-license').text(button.data('license'));
        modal.find('#modal-address').text(button.data('address'));
        modal.find('#modal-body').text(button.data('body'));
        modal.find('#modal-plate').text(button.data('plate'));
        modal.find('#modal-make').text(button.data('make'));

        // Set driver ID for the delete action
        modal.find('#modal-driver-id').val(button.data('id'));
    });
});
</script>


</body>
</html>
