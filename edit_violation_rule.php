<?php
include('includes/header.php');
include('includes/navbar.php');
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM violation_rules WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($query_run);
}
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Violation</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="preview_edit_violation.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <div class="form-group">
                    <label>Violation Name</label>
                    <input type="text" name="violation_name" value="<?php echo $row['violation_name']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Offense Number</label>
                    <input type="text" name="offense_number" value="<?php echo $row['offense_number']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Penalty</label>
                    <input type="text" name="penalty" value="<?php echo $row['penalty']; ?>" class="form-control" required>
                </div>
                <button type="submit" name="preview_edit" class="btn btn-primary">Preview Changes</button>
                <a href="violations_and_offenses.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
