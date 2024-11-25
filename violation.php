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
    .btn-add-violation {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.9rem;
    }
    .card-body {
        padding: 2rem;
    }
    .card-title {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        text-align: center;
    }
    .modal-header {
        background-color: #aa3131;
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

    .custom-btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .custom-btn.edit-btn {
        background-color: #1cc88a;
    }

    .custom-btn.edit-btn:hover {
        background-color: #17a673;
    }

    .custom-btn.delete-btn {
        background-color: #e74a3b;
    }

    .custom-btn.delete-btn:hover {
        background-color: #c82333;
    }

    .action-btns {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .custom-btn.view-btn {
    background-color: #4e73df;
}

.custom-btn.view-btn:hover {
    background-color: #2e59d9;
}

</style>



<div class="container-fluid">
    <!-- Add Violation Button -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Violations</h6>
            <a href="add_violation.php" class="btn btn-primary">
    Add Violation
</a>


        </div>

        <!-- Violations Table -->
        <!-- Violations Table -->
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
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["violation_type"] . "</td>";
        echo "<td>" . $row["violation_date"] . "</td>";
        echo "<td>" . $row["offense"] . "</td>";
        echo "<td>" . $row["penalty"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["violation_status"] . "</td>";
        echo "<td class='action-btns'>";

        // Check if proof of resolution exists
        if (!empty($row["proof_of_resolution"])) {
            // Display "View Proof of Resolution" button
            echo "<a href='uploads/" . $row["proof_of_resolution"] . "' target='_blank' class='custom-btn view-btn'>View Proof of Resolution</a>";
        } else {
            // Display "Resolve" button if no proof of resolution
            echo "<button type='button' class='custom-btn resolve-btn' data-toggle='modal' data-target='#resolveModal' data-id='" . $row["violation_id"] . "'>Resolve</button>";
        }

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
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete Violation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Violation Name:</strong> <span id="modalViolationName"></span></p>
                <p><strong>Offense Number:</strong> <span id="modalOffenseNumber"></span></p>
                <p><strong>Penalty:</strong> <span id="modalPenalty"></span></p>
                <p>Are you sure you want to delete this violation?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="delete_violation_rule.php">
                    <input type="hidden" name="delete_id" id="delete_id" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="confirm_delete" class="btn btn-danger">Confirm Delete</button>
                </form>
            </div>
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


<div class="container-fluid">

    <!-- Violations and Offenses Table -->
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Violations and Offenses</h6>
    <a href="add_violation_rule.php" class="btn btn-primary">Add New Violation</a>
</div>

        <div class="card-body">
            <div class="table-responsive">
                <?php
                $query = "SELECT * FROM violation_rules ORDER BY violation_name, id";
                $query_run = mysqli_query($connection, $query);

                $current_violation = null;
                $rowspan = 0;
                $rows = [];

                while ($row = mysqli_fetch_assoc($query_run)) {
                    if ($row['violation_name'] != $current_violation) {
                        if ($current_violation !== null) {
                            $rows[] = ['violation_name' => $current_violation, 'rowspan' => $rowspan];
                        }
                        $current_violation = $row['violation_name'];
                        $rowspan = 1;
                    } else {
                        $rowspan++;
                    }
                }
                if ($current_violation !== null) {
                    $rows[] = ['violation_name' => $current_violation, 'rowspan' => $rowspan];
                }

                // Reset the query to fetch data again for table rendering
                $query_run = mysqli_query($connection, "SELECT * FROM violation_rules ORDER BY violation_name, id");
                ?>
                <table class="table table-bordered" id="dataTableViolations" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Violation</th>
            <th>Offense</th>
            <th>Penalty</th>
            <th>Action</th> <!-- New Action Column -->
        </tr>
    </thead>
    <tbody>
        <?php
        $current_violation = null;
        $violation_index = 0;

        while ($row = mysqli_fetch_assoc($query_run)) {
            echo '<tr>';
            if ($row['violation_name'] != $current_violation) {
                echo '<td rowspan="' . $rows[$violation_index]['rowspan'] . '">' . $row['violation_name'] . '</td>';
                $current_violation = $row['violation_name'];
                $violation_index++;
            }
            echo '<td>' . $row["offense_number"] . '</td>';
            echo '<td>' . $row["penalty"] . '</td>';
            echo '<td class="action-btns">'; // Action buttons
            echo '<a href="edit_violation_rule.php?id=' . $row["id"] . '" class="custom-btn edit-btn">Edit</a>';
            echo '<button type="button" class="custom-btn delete-btn" data-toggle="modal" data-target="#deleteModal" data-id="' . $row["id"] . '">Delete</button>';
            echo '</td>';
            echo '</tr>';
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('#resolveModal').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        var violationId = button.data('id');
        var modal = $(this);
        modal.find('#resolve_id').val(violationId);
    });
});
</script>


<script>
$(document).ready(function() {
    $('#deleteModal').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        var violationId = button.data('id');

        // Set the hidden input for deletion
        $('#delete_id').val(violationId);

        // Fetch violation details via AJAX
        $.ajax({
            url: 'fetch_violation_details.php',
            type: 'POST',
            data: { id: violationId },
            dataType: 'json',
            success: function(response) {
                // Populate the modal fields
                $('#modalViolationName').text(response.violation_name);
                $('#modalOffenseNumber').text(response.offense_number);
                $('#modalPenalty').text(response.penalty);
            }
        });
    });
});
</script>


<script>
$(document).ready(function() {
    $('#driver_id').keyup(function() {
        var query = $(this).val();
        if (query != '') {
            $.ajax({
                url: "fetch_driver.php",
                method: "POST",
                data: {query: query},
                success: function(data) {
                    $('#driver_id_list').fadeIn();
                    $('#driver_id_list').html(data);
                }
            });
        } else {
            $('#driver_id_list').fadeOut();
            $('#driver_id_list').html("");   
        }
    });

    $(document).on('click', 'li', function() {
        var driver_id = $(this).data('id');
        var driver_name = $(this).data('name');
        
        $('#driver_id').val(driver_id);
        $('#driver_name').val(driver_name);
        $('#driver_id_list').fadeOut();
    });
});
</script>


<script>$(document).ready(function() {
    $('#violation_type, #offense').change(function() {
        var violationType = $('#violation_type').val();
        var offense = $('#offense').val();
        if (violationType && offense) {
            $.ajax({
                url: "fetch_penalty.php",
                method: "POST",
                data: { violation_type: violationType, offense: offense },
                success: function(data) {
                    $('#penalty').val(data);
                }
            });
        }
    });
});
</script>

<script>$(document).ready(function() {
    $('#violation_type, #offense').change(function() {
        var violationType = $('#violation_type').val();
        var offense = $('#offense').val();
        if (violationType && offense) {
            $.ajax({
                url: "fetch_penalty.php",
                method: "POST",
                data: { violation_type: violationType, offense: offense },
                success: function(data) {
                    $('#penalty').val(data);
                }
            });
        } else {
            $('#penalty').val(''); // Clear the penalty field if either is not selected
        }
    });
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('driver_id').addEventListener('change', function() {
        // Get the selected option
        var selectedOption = this.options[this.selectedIndex];
        // Get the driver name from the data attribute
        var driverName = selectedOption.getAttribute('data-name');
        // Set the value of the driver_name input
        document.getElementById('driver_name').value = driverName;
    });
});
</script>


</script>
<script>$(document).ready(function() {
    $('#deleteModal').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget); // Button that triggered the modal
        var violationId = button.data('id'); // Extract info from data-* attributes
        var modal = $(this);
        modal.find('#delete_id').val(violationId); // Set the value in the hidden input
    });
});
</script>