<?php
include('includes/dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php'); 

// Query to fetch the total revenue for the current month
$current_month_query = "
    SELECT SUM(amount) AS total_revenue 
    FROM payments 
    WHERE MONTH(date_of_payment) = MONTH(CURRENT_DATE()) 
    AND YEAR(date_of_payment) = YEAR(CURRENT_DATE())
";
$current_month_result = mysqli_query($connection, $current_month_query);
$current_month_revenue = 0;

if ($current_month_result && mysqli_num_rows($current_month_result) > 0) {
    $row = mysqli_fetch_assoc($current_month_result);
    $current_month_revenue = $row['total_revenue'] ? $row['total_revenue'] : 0;
}

// Query to fetch driver revenue data
$query = "
    SELECT 
        CONCAT(d.last_name, ', ', d.first_name, ' ', d.middle_name) AS driver_name, 
        p.date_of_payment, 
        p.amount, 
        p.payment_proof, 
        p.payment_for
    FROM 
        payments p
    JOIN 
        drivers d ON p.driver_id = d.driver_id
    ORDER BY 
        p.date_of_payment DESC
";
$query_run = mysqli_query($connection, $query);

// Query to get unique drivers from payments table
$drivers_query = "
    SELECT DISTINCT d.driver_id, CONCAT(d.last_name, ', ', d.first_name, ' ', d.middle_name) AS driver_name
    FROM payments p
    JOIN drivers d ON p.driver_id = d.driver_id
    ORDER BY driver_name
";
$drivers_result = mysqli_query($connection, $drivers_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Revenue Report</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="m-0 font-weight-bold text-primary">Driver Revenue Report</h6>
        <div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addPaymentModal">Add Payment</button>
            <button class="btn btn-success" data-toggle="modal" data-target="#generateReportModal">Generate Report</button>
        </div>
    </div>

    <!-- Display Total Revenue for the Current Month -->
    <div class="alert alert-info">
        <strong>Total Revenue for <?php echo date("F Y"); ?>:</strong> 
        <?php echo number_format($current_month_revenue, 2); ?>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Driver Name</th>
                            <th>Payment Date</th>
                            <th>Total Payment</th>
                            <th>Payment For</th>
                            <th>Proof of Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['driver_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date_of_payment']) . "</td>";
                                echo "<td>" . htmlspecialchars(number_format($row['amount'], 2)) . "</td>";
                                echo "<td>" . htmlspecialchars($row['payment_for']) . "</td>";
                                echo "<td>";
                                
                                // Define file paths
                                $receipt_path = 'receipts/' . htmlspecialchars($row['payment_proof']);
                                $upload_path = 'uploads/' . htmlspecialchars($row['payment_proof']);

                                // Check if the proof exists in the receipts folder or uploads folder
                                if (file_exists($receipt_path)) {
                                    echo "<a href='" . $receipt_path . "' target='_blank' class='btn btn-info btn-sm'>View Receipt</a>";
                                } elseif (file_exists($upload_path)) {
                                    echo "<a href='" . $upload_path . "' target='_blank' class='btn btn-info btn-sm'>View Proof</a>";
                                } else {
                                    echo "No Proof Available";
                                }

                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="process_payment.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Driver Selection Dropdown -->
                    <div class="form-group">
                        <label for="driver_id">Select Driver</label>
                        <select name="driver_id" id="driver_id" class="form-control" required>
                            <option value="">Choose a driver</option>
                            <?php while ($driver = mysqli_fetch_assoc($drivers_result)) { ?>
                                <option value="<?php echo htmlspecialchars($driver['driver_id']); ?>">
                                    <?php echo htmlspecialchars($driver['driver_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Payment Date Input -->
                    <div class="form-group">
                        <label for="date_of_payment">Payment Date</label>
                        <input type="date" name="date_of_payment" id="date_of_payment" class="form-control" required>
                    </div>

                    <!-- Payment Amount Input -->
                    <div class="form-group">
                        <label for="amount">Total Payment</label>
                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                    </div>

                    <!-- Payment For Input -->
                    <div class="form-group">
                        <label for="payment_for">Payment For</label>
                        <input type="text" name="payment_for" id="payment_for" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_payment_btn" class="btn btn-primary">Add Payment</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1" role="dialog" aria-labelledby="generateReportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="generate_payment_report.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateReportModalLabel">Generate Payment Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Driver Selection -->
                    <div class="form-group">
                        <label for="driver_selection">Select Driver</label>
                        <select name="driver_id" id="driver_selection" class="form-control">
                            <option value="all">All Drivers</option>
                            <?php
                            $drivers_result = mysqli_query($connection, $drivers_query); // Reset result set
                            while ($driver = mysqli_fetch_assoc($drivers_result)) { ?>
                                <option value="<?php echo htmlspecialchars($driver['driver_id']); ?>">
                                    <?php echo htmlspecialchars($driver['driver_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Generate Report</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/scripts.php'); include('includes/footer.php'); ?>
</body>
</html>
