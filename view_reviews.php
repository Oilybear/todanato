<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<?php
include 'dbconfig.php';
$driver_id = $_GET['driver_id'];

$query = "SELECT * FROM driver_ratings WHERE driver_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $driver_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Reviews</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
        }
        .card-body {
            padding: 2rem;
        }
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="card-title">Reviews for Driver ID: <?php echo htmlspecialchars($driver_id); ?></h1>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Rated By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['rating']); ?></td>
                                <td><?php echo htmlspecialchars($row['review']); ?></td>
                                <td><?php echo htmlspecialchars($row['rated_by']); ?></td>
                                <td><?php echo htmlspecialchars($row['rating_date']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="no-record">No Reviews Found</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>