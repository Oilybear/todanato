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
    .custom-btn {
        background-color: #aa3131;
        color: white;
        border: none;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .custom-btn:hover {
        background-color: #2e59d9;
        color: white;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }
    .custom-btn.view-btn {
        background-color: #4e73df;
    }
    .custom-btn.view-btn:hover {
        background-color: #2e59d9;
    }
</style>

<!-- Violations Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Violations</h6>
        <div>
            <a href="add_violation.php" class="btn btn-primary">Add Violation</a>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateReportModal">
                Generate Report
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <?php
            $connection = mysqli_connect("localhost", "root", "", "todanato");

            $query = "SELECT * FROM violations";
            $query_run = mysqli_query($connection, $query);

            if (mysqli_num_rows($query_run) > 0) {
            ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Driver Name</th>
                            <th>Violation Type</th>
                            <th>Violation Date</th>
                            <th>Offense</th>
                            <th>Penalty</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["violation_type"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["violation_date"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["offense"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["penalty"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["violation_status"]) . "</td>";
                            echo "<td>";
                            echo "<button type='button' class='custom-btn view-btn' data-toggle='modal' data-target='#resolveModal' data-id='" . htmlspecialchars($row["violation_id"]) . "'>Resolve</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            } else {
                echo "<p class='no-record'>No Record Found</p>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1" role="dialog" aria-labelledby="generateReportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateReportModalLabel">Generate Violation Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="generate_violation_report.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="driver_id_select">Select Driver</label>
                        <select class="form-control" id="driver_id_select" name="driver_id" required>
                            <option value="all">All Drivers</option>
                            <?php
                            $query_drivers = "SELECT DISTINCT driver_id, name FROM violations";
                            $result_drivers = mysqli_query($connection, $query_drivers);
                            while ($driver = mysqli_fetch_assoc($result_drivers)) {
                                echo "<option value='" . $driver['driver_id'] . "'>" . htmlspecialchars($driver['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Resolve Violation Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1" role="dialog" aria-labelledby="resolveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resolveModalLabel">Resolve Violation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="resolve_violation.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="resolve_id" id="resolve_id" value="">
                    <div class="form-group">
                        <label for="proof_of_resolution">Proof of Resolution (Upload Document)</label>
                        <input type="file" name="proof_of_resolution" id="proof_of_resolution" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="resolve_btn" class="btn btn-success">Confirm Resolve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#resolveModal').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var violationId = button.data('id');
            $('#resolve_id').val(violationId);
        });
    });
</script>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
