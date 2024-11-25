<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
include('includes/dbconfig.php');  // Include your database connection file
?>

<style>
    .table th, .table td {
        text-align: center;
    }
    .table thead th {
        background-color: #aa3131;
        color: white;
    }
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fc;
    }
    .table tbody tr:hover {
        background-color: #e2e6ea;
    }
    .no-record {
        text-align: center;
        font-size: 1.2rem;
        color: #6c757d;
    }
    .card-header {
        background-color: #aa3131;
        color: white;
        position: relative;
    }
    .card-header h6 {
        font-weight: bold;
        margin: 0;
        color: white;
    }
    .card-body {
        padding: 2rem;
    }
    .card-title {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        text-align: center;
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Customer Reports Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>Customer Reports</h6>
        </div>
        <div class="card-body">

            <?php
            if (isset($_SESSION['success']) && $_SESSION['success'] != '') {
                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }

            if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                echo '<div class="alert alert-danger">' . $_SESSION['status'] . '</div>';
                unset($_SESSION['status']);
            }
            ?>

            <div class="table-responsive">
                <?php
                // Define the SQL query to fetch data from the customer reports
                $query = "
                    SELECT cr.report_id, cr.rider_id, ar.username AS rider_username, cr.driver_id, 
                           CONCAT(d.first_name, ' ', COALESCE(d.middle_name, ''), ' ', d.last_name) AS driver_name, 
                           cr.type_of_report, cr.description, cr.created_at 
                    FROM customer_reports cr 
                    LEFT JOIN app_riders ar ON cr.rider_id = ar.rider_id 
                    LEFT JOIN drivers d ON cr.driver_id = d.driver_id 
                    ORDER BY cr.created_at DESC
                ";

                // Execute the query and check for errors
                $query_run = mysqli_query($conn, $query);

                if ($query_run === false) {
                    // Display error message if the query fails
                    echo "<p class='no-record'>Error: Could not execute query. " . mysqli_error($conn) . "</p>";
                } else {
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Rider Username</th>
                            <th>Driver Name</th>
                            <th>Type of Report</th>
                            <th>Description</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            // Fetch and display each row of data
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row["rider_username"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["driver_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["type_of_report"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["description"]); ?></td>
                                    <td><?php echo date('F j, Y, g:i a', strtotime($row["created_at"])); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            // Display message if no records found
                            echo "<tr><td colspan='5' class='no-record'>No Record Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php } // End of else block ?>
            </div>
        </div>
    </div>

</div>
<!-- End of Main Content -->

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
