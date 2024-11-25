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

    <!-- App Trips Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>App Trips</h6>
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
                $query = "SELECT ride_id, origin, destination, fare, ride_date, rider_username, driver_username, tricycle_id, make_and_model FROM ride_details ORDER BY ride_date DESC";
                $query_run = mysqli_query($conn, $query);
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Fare</th>
                            <th>Ride Date</th>
                            <th>Rider Username</th>
                            <th>Driver Username</th>
                            <th>Tricycle ID</th>
                            <th>Make and Model</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row["origin"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["destination"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["fare"]); ?></td>
                                    <td><?php echo date('F j, Y, g:i a', strtotime($row["ride_date"])); ?></td>
                                    <td><?php echo htmlspecialchars($row["rider_username"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["driver_username"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["tricycle_id"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["make_and_model"]); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='9' class='no-record'>No Record Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- End of Main Content -->

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
