<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
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

    <!-- Fare Matrix Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Fare Matrix</h6>
            </div>
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
                $connection = mysqli_connect("localhost", "root", "", "todanato");

                $query = "SELECT * FROM fare_matrix";
                $query_run = mysqli_query($connection, $query);

                if ($query_run === false) {
                    echo "Error: " . mysqli_error($connection);
                } else {
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Gas Price Range</th>
                            <th>Provisional Minimum Fare</th>
                            <th>Special Trip</th>
                            <th>Minimum Fare (Senior/PWD/Students)</th>
                            <th>Special Trip (Senior/PWD/Students)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                                <tr>
                                    <td><?php echo $row["gas_price_range"]; ?></td>
                                    <td><?php echo "Php " . number_format($row["provisional_minimum_fare"], 2); ?></td>
                                    <td><?php echo "Php " . number_format($row["special_trip"], 2); ?></td>
                                    <td><?php echo "Php " . number_format($row["min_fare_senior"], 2); ?></td>
                                    <td><?php echo "Php " . number_format($row["special_trip_senior"], 2); ?></td>
                                    <td><?php echo $row["status"]; ?></td>
                                    <td>
                                        <a href="edit_fare.php?id=<?php echo $row["id"]; ?>" class="btn btn-primary">Edit</a>
                                        <form action="set_gas_price.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                            <button type="submit" class="btn btn-success">Set Gas Price</button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' class='no-record'>No Record Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

</div>
<!-- End of Main Content -->

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
