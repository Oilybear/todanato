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

    <!-- App Revenue Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6>App Revenue Per Month</h6>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $connection = mysqli_connect("localhost", "root", "", "todanato");

                        $query = "SELECT 
                                    DATE_FORMAT(revenue_date, '%M') as month, 
                                    DATE_FORMAT(revenue_date, '%Y') as year, 
                                    SUM(total_revenue) as total_revenue 
                                  FROM app_revenue 
                                  GROUP BY YEAR(revenue_date), MONTH(revenue_date) 
                                  ORDER BY YEAR(revenue_date), MONTH(revenue_date)";
                        
                        $query_run = mysqli_query($connection, $query);

                        if ($query_run === false) {
                            echo "Error: " . mysqli_error($connection);
                        } else {
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                                    <tr>
                                        <td><?php echo $row["month"]; ?></td>
                                        <td><?php echo $row["year"]; ?></td>
                                        <td><?php echo "Php " . number_format($row["total_revenue"], 2); ?></td>
                                    </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='3' class='no-record'>No Record Found</td></tr>";
                            }
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
