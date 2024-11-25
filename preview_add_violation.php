<?php
include('includes/header.php');
include('includes/navbar.php');
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_POST['preview_violation'])) {
    // Retrieve form data from the previous page
    $violation_name = $_POST['violation_name'];
    $offense_number = $_POST['offense_number'];
    $penalty = $_POST['penalty'];
}
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Preview Violation</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="save_violation_rule.php">
                <div class="form-group">
                    <label>Violation Name:</label>
                    <p><?php echo htmlspecialchars($_POST['violation_name']); ?></p>
                    <input type="hidden" name="violation_name" value="<?php echo htmlspecialchars($_POST['violation_name']); ?>">
                </div>
                <div class="form-group">
                    <label>Offense Number:</label>
                    <p><?php echo htmlspecialchars($_POST['offense_number']); ?></p>
                    <input type="hidden" name="offense_number" value="<?php echo htmlspecialchars($_POST['offense_number']); ?>">
                </div>
                <div class="form-group">
                    <label>Penalty:</label>
                    <p><?php echo htmlspecialchars($_POST['penalty']); ?></p>
                    <input type="hidden" name="penalty" value="<?php echo htmlspecialchars($_POST['penalty']); ?>">
                </div>
                <button type="submit" name="confirm_save" class="btn btn-success">Confirm Save</button>
                <a href="add_violation_rule.php" class="btn btn-secondary">Edit</a>
            </form>
        </div>
    </div>
</div>


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
