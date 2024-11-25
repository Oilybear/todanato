<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
include('fetch_data.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="dashboard.js"></script>
</head>
<body>
<div class="container-fluid">
    <!-- Dashboard Cards -->
    <div class="row justify-content-center">
        <!-- Total Drivers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Drivers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $driver_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Drivers Registered in TODAnaTo App Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Drivers Registered in TODAnaTo App</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_app_drivers; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mobile-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Violations Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Drivers with Violations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $violation_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Riders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Riders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $rider_query = "SELECT COUNT(*) AS total_riders FROM app_riders";
                                $rider_result = mysqli_query($connection, $rider_query);
                                $rider_data = mysqli_fetch_assoc($rider_result);
                                echo $rider_data['total_riders'];
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Violations and Revenue Tables -->
    <div class="row justify-content-center">
        <!-- Driver Violations Summary Table -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">Driver Violations Summary</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Driver Name</th>
                                    <th>Total Violations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $violations_summary_query = "
                                SELECT CONCAT(d.first_name, ' ', COALESCE(d.middle_name, ''), ' ', d.last_name) AS driver_name, 
                                       COUNT(v.violation_id) AS total_violations 
                                FROM violations v
                                JOIN drivers d ON v.driver_id = d.driver_id
                                GROUP BY v.driver_id
                            ";
                            
                                $violations_summary_result = mysqli_query($connection, $violations_summary_query);
                                while ($row = mysqli_fetch_assoc($violations_summary_result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['driver_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['total_violations']) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- App Revenue Table -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">App Revenue Per Month</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $revenue_query = "SELECT 
                                                    DATE_FORMAT(revenue_date, '%M') as month, 
                                                    DATE_FORMAT(revenue_date, '%Y') as year, 
                                                    SUM(total_revenue) as total_revenue 
                                                  FROM app_revenue 
                                                  GROUP BY YEAR(revenue_date), MONTH(revenue_date) 
                                                  ORDER BY YEAR(revenue_date), MONTH(revenue_date)";
                                
                                $revenue_result = mysqli_query($connection, $revenue_query);
                                while ($row = mysqli_fetch_assoc($revenue_result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['month']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                                    echo "<td>" . "Php " . number_format($row['total_revenue'], 2) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



    </div>
</body>
</html>

<?php
include('includes/scripts.php');
?>
