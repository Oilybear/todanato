<?php
include('includes/header.php');
include('includes/navbar.php');
include 'dbconfig.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$query = "SELECT drivers.id, drivers.full_name, AVG(driver_ratings.rating) as average_rating, COUNT(driver_ratings.rating) as total_reviews 
          FROM drivers 
          LEFT JOIN driver_ratings ON drivers.id = driver_ratings.driver_id 
          GROUP BY drivers.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Performance and Ratings</title>
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


    <?php
    include('includes/scripts.php');
    include('includes/footer.php');
    ?>
</body>
</html>
