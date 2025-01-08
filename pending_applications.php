<?php
include('includes/dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php'); 

// Fetch pending applications
$query_pending = "SELECT application_id, first_name, middle_name, last_name, application_date, application_status, license_number, current_address, body_number, motor_plate_no, make_model 
          FROM driver_applications 
          WHERE application_status = 'pending'";
$result_pending = mysqli_query($connection, $query_pending);

if (!$result_pending) {
    die("Query Failed: " . mysqli_error($connection));
}

// Fetch declined applications
$query_declined = "SELECT first_name, middle_name, last_name, application_date, date_declined 
          FROM driver_applications 
          WHERE application_status = 'declined'";
$result_declined = mysqli_query($connection, $query_declined);

if (!$result_declined) {
    die("Query Failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pending and Declined Applications</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table th, .table td { text-align: center; }
        .table thead th { background-color: #aa3131; color: white; }
        .btn-preview {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.3rem 0.7rem;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .btn-preview:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <!-- Pending Applications Table -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6><strong>Pending Applications</strong></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Date Applied</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_pending) > 0) {
                            while ($row = mysqli_fetch_assoc($result_pending)) {
                                $full_name = htmlspecialchars($row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"]);
                                $application_date = htmlspecialchars($row["application_date"]);
                                $application_status = htmlspecialchars($row["application_status"]);
                                ?>
                                <tr>
                                    <td><?php echo $full_name; ?></td>
                                    <td><?php echo $application_date; ?></td>
                                    <td><?php echo ucfirst($application_status); ?></td>
                                    <td>
                                        <button type="button" class="btn-preview" 
                                                data-toggle="modal" 
                                                data-target="#previewModal"
                                                data-id="<?php echo $row['application_id']; ?>"
                                                data-fullname="<?php echo $full_name; ?>"
                                                data-applicationdate="<?php echo $application_date; ?>"
                                                data-license="<?php echo htmlspecialchars($row['license_number']); ?>"
                                                data-address="<?php echo htmlspecialchars($row['current_address']); ?>"
                                                data-bodynumber="<?php echo htmlspecialchars($row['body_number']); ?>"
                                                data-plate="<?php echo htmlspecialchars($row['motor_plate_no']); ?>"
                                                data-makemodel="<?php echo htmlspecialchars($row['make_model']); ?>">
                                            Preview
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>No Pending Applications Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Declined Applications Table -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6><strong>Declined Applications</strong></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="declinedTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Date Applied</th>
                            <th>Date Declined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_declined) > 0) {
                            while ($row = mysqli_fetch_assoc($result_declined)) {
                                $full_name = htmlspecialchars($row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"]);
                                $application_date = htmlspecialchars($row["application_date"]);
                                $date_declined = htmlspecialchars($row["date_declined"]);
                                ?>
                                <tr>
                                    <td><?php echo $full_name; ?></td>
                                    <td><?php echo $application_date; ?></td>
                                    <td><?php echo $date_declined; ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='3'>No Declined Applications Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Driver Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Full Name:</strong> <span id="modal-fullname"></span></p>
                <p><strong>Date Applied:</strong> <span id="modal-applicationdate"></span></p>
                <p><strong>License Number:</strong> <span id="modal-license"></span></p>
                <p><strong>Current Address:</strong> <span id="modal-address"></span></p>
                <p><strong>Body Number:</strong> <span id="modal-bodynumber"></span></p>
                <p><strong>Motor Plate Number:</strong> <span id="modal-plate"></span></p>
                <p><strong>Make/Model:</strong> <span id="modal-makemodel"></span></p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="update_driver_status.php">
                    <input type="hidden" name="application_id" id="modal-applicationid">
                    <button type="submit" name="approve" class="btn btn-success">Approve</button>
                    <button type="submit" name="decline" class="btn btn-danger">Decline</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
    $('#previewModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this);

        // Populate modal fields with data attributes
        modal.find('#modal-fullname').text(button.data('fullname'));
        modal.find('#modal-applicationdate').text(button.data('applicationdate'));
        modal.find('#modal-license').text(button.data('license'));
        modal.find('#modal-address').text(button.data('address'));
        modal.find('#modal-bodynumber').text(button.data('bodynumber'));
        modal.find('#modal-plate').text(button.data('plate'));
        modal.find('#modal-makemodel').text(button.data('makemodel'));
        modal.find('#modal-applicationid').val(button.data('id'));
    });
});
</script>
</body>
</html>
