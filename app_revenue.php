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
                <button class="btn btn-primary" data-toggle="modal" data-target="#generateReportModal">Generate Report</button>
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

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1" role="dialog" aria-labelledby="generateReportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateReportModalLabel">Generate Revenue Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="start_month">Start Month</label>
                        <select name="start_month" id="start_month" class="form-control" required>
                            <option value="">Select Month</option>
                            <?php for ($m = 1; $m <= 12; $m++) {
                                echo '<option value="' . $m . '">' . date('F', mktime(0, 0, 0, $m, 1)) . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_year">Start Year</label>
                        <select name="start_year" id="start_year" class="form-control" required>
                            <option value="">Select Year</option>
                            <?php
                            $current_year = date("Y");
                            for ($y = $current_year; $y >= $current_year - 10; $y--) {
                                echo "<option value='$y'>$y</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="end_month">End Month</label>
                        <select name="end_month" id="end_month" class="form-control" required>
                            <option value="">Select Month</option>
                            <?php for ($m = 1; $m <= 12; $m++) {
                                echo '<option value="' . $m . '">' . date('F', mktime(0, 0, 0, $m, 1)) . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="end_year">End Year</label>
                        <select name="end_year" id="end_year" class="form-control" required>
                            <option value="">Select Year</option>
                            <?php
                            $current_year = date("Y");
                            for ($y = $current_year; $y >= $current_year - 10; $y--) {
                                echo "<option value='$y'>$y</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="generate_report" class="btn btn-success">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['generate_report'])) {
    $start_month = $_POST['start_month'];
    $start_year = $_POST['start_year'];
    $end_month = $_POST['end_month'];
    $end_year = $_POST['end_year'];

    require_once __DIR__ . '/vendor/autoload.php'; // Include TCPDF library

    $start_date = "$start_year-$start_month-01";
    $end_date = date("Y-m-t", strtotime("$end_year-$end_month-01"));

    $query = "SELECT 
                DATE_FORMAT(revenue_date, '%M %Y') as month_year, 
                SUM(total_revenue) as total_revenue 
              FROM app_revenue 
              WHERE revenue_date BETWEEN '$start_date' AND '$end_date'
              GROUP BY YEAR(revenue_date), MONTH(revenue_date)
              ORDER BY YEAR(revenue_date), MONTH(revenue_date)";
    $query_run = mysqli_query($connection, $query);

    if ($query_run && mysqli_num_rows($query_run) > 0) {
        $pdf = new \TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        // Title
        $pdf->Cell(0, 10, "Revenue Report ($start_month-$start_year to $end_month-$end_year)", 0, 1, 'C');
        $pdf->Ln(10);

        // Table Header
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(80, 10, "Month-Year", 1);
        $pdf->Cell(80, 10, "Total Revenue", 1);
        $pdf->Ln();

        // Table Data
        $pdf->SetFont('helvetica', '', 10);
        while ($row = mysqli_fetch_assoc($query_run)) {
            $pdf->Cell(80, 10, $row['month_year'], 1);
            $pdf->Cell(80, 10, "Php " . number_format($row['total_revenue'], 2), 1);
            $pdf->Ln();
        }

        // Save the PDF
        $file_name = "app_revenue_report_" . $start_month . "_" . $start_year . "_to_" . $end_month . "_" . $end_year . ".pdf";
        $file_path = __DIR__ . "/uploads/" . $file_name;

        if (!is_dir(__DIR__ . "/uploads")) {
            mkdir(__DIR__ . "/uploads", 0777, true);
        }
        $pdf->Output($file_path, 'F'); // Save file to the uploads directory

        // Trigger File Download
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        echo "<script>alert('No data found for the selected date range.');</script>";
    }
}
?>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
