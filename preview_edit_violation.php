<?php
include('includes/header.php');
include('includes/navbar.php');

if (isset($_POST['preview_edit'])) {
    $id = $_POST['id'];
    $violation_name = $_POST['violation_name'];
    $offense_number = $_POST['offense_number'];
    $penalty = $_POST['penalty'];
}
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Preview Changes</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="save_edit_violation.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label>Violation Name:</label>
                    <p><?php echo htmlspecialchars($violation_name); ?></p>
                    <input type="hidden" name="violation_name" value="<?php echo htmlspecialchars($violation_name); ?>">
                </div>
                <div class="form-group">
                    <label>Offense Number:</label>
                    <p><?php echo htmlspecialchars($offense_number); ?></p>
                    <input type="hidden" name="offense_number" value="<?php echo htmlspecialchars($offense_number); ?>">
                </div>
                <div class="form-group">
                    <label>Penalty:</label>
                    <p><?php echo htmlspecialchars($penalty); ?></p>
                    <input type="hidden" name="penalty" value="<?php echo htmlspecialchars($penalty); ?>">
                </div>
                <button type="submit" name="confirm_edit" class="btn btn-success">Confirm Save</button>
                <a href="edit_violation_rule.php?id=<?php echo $id; ?>" class="btn btn-secondary">Edit</a>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
