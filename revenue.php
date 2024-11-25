<?php
include('includes/dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php'); 

// Query to fetch the total revenue for the current month
$current_month_query = "
    SELECT SUM(amount) AS total_revenue 
    FROM payments 
    WHERE MONTH(payment_date) = MONTH(CURRENT_DATE()) 
    AND YEAR(payment_date) = YEAR(CURRENT_DATE())
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
        p.payment_date, 
        SUM(p.amount) AS total_payment 
    FROM 
        payments p
    JOIN 
        drivers d ON p.driver_id = d.driver_id
    GROUP BY 
        d.driver_id, p.payment_date
    ORDER BY 
        p.payment_date DESC
";
$query_run = mysqli_query($connection, $query);

// Query to get list of drivers for the dropdown in the modal
$drivers_query = "SELECT driver_id, last_name, first_name, middle_name FROM drivers";
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
        <button class="btn btn-primary" data-toggle="modal" data-target="#addPaymentModal">Add Payment</button>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($query_run) > 0) {
                            while($row = mysqli_fetch_assoc($query_run)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['driver_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['payment_date']) . "</td>";
                                echo "<td>" . htmlspecialchars(number_format($row['total_payment'], 2)) . "</td>";
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
            <form action="process_payment.php" method="POST" enctype="multipart/form-data">
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
                            <?php while($driver = mysqli_fetch_assoc($drivers_result)) { 
                                $driver_name = $driver['last_name'] . ", " . $driver['first_name'] . " " . $driver['middle_name'];
                            ?>
                                <option value="<?php echo htmlspecialchars($driver['driver_id']); ?>">
                                    <?php echo htmlspecialchars($driver_name); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Payment Date Input -->
                    <div class="form-group">
                        <label for="payment_date">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                    </div>

                    <!-- Payment Amount Input -->
                    <div class="form-group">
                        <label for="amount">Total Payment</label>
                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                    </div>

                    <!-- Payment Proof Upload -->
                    <div class="form-group">
                        <label for="payment_proof">Payment Proof</label>
                        <input type="file" name="payment_proof" id="payment_proof" class="form-control-file" required>
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

<?php include('includes/scripts.php'); include('includes/footer.php'); ?>
</body>
</html>
