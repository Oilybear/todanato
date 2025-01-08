<?php
include('includes/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];

    if (isset($_POST['approve'])) {
        $query = "UPDATE driver_applications 
                  SET application_status = 'approved', 
                      payment_status = 'unpaid', 
                      date_declined = NULL, 
                      date_approved = NOW() 
                  WHERE application_id = ?";
        $action = "approved";
    } elseif (isset($_POST['decline'])) {
        $query = "UPDATE driver_applications 
                  SET application_status = 'declined', 
                      date_declined = NOW() 
                  WHERE application_id = ?";
        $action = "declined";
    }

    if ($stmt = $connection->prepare($query)) {
        $stmt->bind_param("i", $application_id);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Driver application has been {$action} successfully.');
                    window.location.href = 'pending_applications.php';
                  </script>";
        } else {
            echo "<script>
                    alert('An error occurred while processing the request. Please try again.');
                    window.location.href = 'pending_applications.php';
                  </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
                alert('Error preparing the request. Please try again.');
                window.location.href = 'pending_applications.php';
              </script>";
    }
}
?>
