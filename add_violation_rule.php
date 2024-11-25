<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Violation</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="preview_add_violation.php">
                <div class="form-group">
                    <label>Violation Name</label>
                    <input type="text" name="violation_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Offense Number</label>
                    <input type="text" name="offense_number" class="form-control" placeholder="e.g., First Offense, Second Offense, Third Offense" required>
                </div>
                <div class="form-group">
                    <label>Penalty</label>
                    <input type="text" name="penalty" class="form-control" required>
                </div>
                <button type="submit" name="preview_violation" class="btn btn-primary">Preview Violation</button>
                <a href="violations_and_offenses.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
