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
</style>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Riders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>Riders</h6>
        </div>
        <div class="card-body">

            <?php
            if (isset($_SESSION['success']) && $_SESSION['success'] != '') {
                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }

            if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                echo '<div class="alert alert-danger">' . $_SESSION['status'] . '</div>';
                unset($_SESSION['status']);
            }
            ?>

            <div class="table-responsive">
                <?php
                $connection = mysqli_connect("localhost", "root", "", "todanato");

                $query = "SELECT rider_id, username, email FROM app_riders";
                $query_run = mysqli_query($connection, $query);

                if ($query_run === false) {
                    // Log or display the error message
                    echo "Error: " . mysqli_error($connection);
                } else {
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                                <tr>
                                    <td><?php echo $row["username"]; ?></td>
                                    <td><?php echo $row["email"]; ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='3' class='no-record'>No Record Found</td></tr>"; // Adjust colspan to match the new column count
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

</div>
<!-- End of Main Content -->

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
