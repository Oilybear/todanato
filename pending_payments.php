<?php
if (isset($_GET['download']) && file_exists($_GET['download'])) {
    $file = $_GET['download'];

    // Set headers for file download
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Length: ' . filesize($file));
    readfile($file);

    // Delete the file after download
    unlink($file);

    exit; // Ensure no further output
}
?>

<?php
include('includes/dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php'); 

// Fetch approved and unpaid applications
$query_unpaid = "SELECT application_id, first_name, middle_name, last_name, date_approved, payment_status 
                 FROM driver_applications 
                 WHERE application_status = 'approved' AND payment_status = 'unpaid'";
$result_unpaid = mysqli_query($connection, $query_unpaid);

// Fetch approved and paid applications
$query_paid = "SELECT first_name, middle_name, last_name, date_approved 
               FROM driver_applications 
               WHERE application_status = 'approved' AND payment_status = 'paid'";
$result_paid = mysqli_query($connection, $query_paid);

if (!$result_unpaid || !$result_paid) {
    die("Query Failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pending Payments</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .table th, .table td { text-align: center; }
        .table thead th { background-color: #aa3131; color: white; }
        .btn-payment {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.3rem 0.7rem;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .btn-payment:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <!-- Pending Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6><strong>Pending Payments</strong></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Date of Approval</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_unpaid) > 0) {
                            while ($row = mysqli_fetch_assoc($result_unpaid)) {
                                $full_name = htmlspecialchars($row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"]);
                                $date_approved = htmlspecialchars($row["date_approved"]);
                                $payment_status = htmlspecialchars(ucfirst($row["payment_status"]));
                                ?>
                                <tr>
                                    <td><?php echo $full_name; ?></td>
                                    <td><?php echo $date_approved; ?></td>
                                    <td><?php echo $payment_status; ?></td>
                                    <td>
                                        <button type="button" class="btn-payment" 
                                                data-toggle="modal" 
                                                data-target="#paymentModal"
                                                data-id="<?php echo $row['application_id']; ?>"
                                                data-fullname="<?php echo $full_name; ?>"
                                                data-dateapproved="<?php echo $date_approved; ?>">
                                            Receive Payment
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>No Pending Payments Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Approved and Paid Drivers Table -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6><strong>Approved and Paid Drivers</strong></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTablePaid" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Date of Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_paid) > 0) {
                            while ($row = mysqli_fetch_assoc($result_paid)) {
                                $full_name = htmlspecialchars($row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"]);
                                $date_approved = htmlspecialchars($row["date_approved"]);
                                ?>
                                <tr>
                                    <td><?php echo $full_name; ?></td>
                                    <td><?php echo $date_approved; ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='2'>No Paid Drivers Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Receive Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="payment_process.php">
                <div class="modal-body">
                    <p><strong>Full Name:</strong> <span id="modal-fullname"></span></p>
                    <p><strong>Date Approved:</strong> <span id="modal-dateapproved"></span></p>
                    <div class="form-group">
                        <label for="amount">Amount Paid:</label>
                        <input type="number" class="form-control" name="amount" id="amount" required>
                    </div>
                    <!-- Hidden inputs for processing -->
                    <input type="hidden" name="application_id" id="modal-applicationid">
                    <input type="hidden" name="fullname" id="modal-hidden-fullname">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="receive_payment" class="btn btn-success">Receive and Generate Receipt</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>

<script>
$(document).ready(function() {
    $('#paymentModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this);

        // Populate modal fields with data attributes
        modal.find('#modal-fullname').text(button.data('fullname'));
        modal.find('#modal-dateapproved').text(button.data('dateapproved'));
        modal.find('#modal-applicationid').val(button.data('id'));
        modal.find('#modal-hidden-fullname').val(button.data('fullname'));
    });
});
</script>
</body>
</html>
