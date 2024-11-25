<?php
include('dbconfig.php'); // Ensure this is the correct path
include('includes/header.php'); 
include('includes/navbar.php'); 

// Establish a connection to the database
$connection = mysqli_connect("localhost", "root", "", "todanato");

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Violation</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Add Violation</h5>
                </div>
                <div class="card-body">
                    <form action="preview_violation.php" method="POST">
                        <div class="form-group">
                            <label for="driver_id">Driver ID</label>
                            <select class="form-control" id="driver_id" name="driver_id" required>
                                <option value="" disabled selected>Select Driver</option>
                                <?php
                                // Fetch driver IDs and names
                                $query = "SELECT driver_id, CONCAT(first_name, ' ', middle_name, ' ', last_name) as full_name FROM drivers ORDER BY full_name ASC";
                                $query_run = mysqli_query($connection, $query);

                                if ($query_run) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                        echo '<option value="' . htmlspecialchars($row['driver_id']) . '" data-name="' . htmlspecialchars($row['full_name']) . '">' . htmlspecialchars($row['driver_id']) . ' - ' . htmlspecialchars($row['full_name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="" disabled>Error loading drivers</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="driver_name">Driver Name</label>
                            <input type="text" class="form-control" id="driver_name" name="driver_name" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="violation_type">Violation Type</label>
                            <select class="form-control" id="violation_type" name="violation_type" required>
                                <option value="" disabled selected>Select Violation Type</option>
                                <?php
                                // Fetch distinct violation types
                                $query = "SELECT DISTINCT violation_name FROM violation_rules ORDER BY violation_name ASC";
                                $query_run = mysqli_query($connection, $query);

                                if ($query_run) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                        echo '<option value="' . htmlspecialchars($row['violation_name']) . '">' . htmlspecialchars($row['violation_name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="" disabled>Error loading violation types</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="violation_date">Violation Date</label>
                            <input type="date" class="form-control" id="violation_date" name="violation_date" required>
                        </div>

                        <div class="form-group">
                            <label for="offense">Offense</label>
                            <select class="form-control" id="offense" name="offense" required>
                                <option value="First Offense">First Offense</option>
                                <option value="Second Offense">Second Offense</option>
                                <option value="Third Offense">Third Offense</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="penalty">Penalty</label>
                            <input type="text" class="form-control" id="penalty" name="penalty" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" name="preview_btn" class="btn btn-primary">Preview</button>
                            <a href="violation.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>

<!-- JavaScript to auto-fill driver name and fetch penalty -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Populate driver name based on selected driver ID
        $('#driver_id').change(function() {
            var selectedDriverName = $(this).find(':selected').data('name');
            $('#driver_name').val(selectedDriverName);
        });

        // Fetch penalty based on violation type and offense
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
                $('#penalty').val(''); // Clear penalty field if no values selected
            }
        });
    });
</script>

</body>
</html>
