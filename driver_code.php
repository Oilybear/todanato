<?php
session_start();
include('dbconfig.php');

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['delete_btn'])) {
    $id = mysqli_real_escape_string($connection, $_POST['delete_id']);

    // Delete related records in customer_reports
    $delete_reports_query = "DELETE FROM customer_reports WHERE driver_id='$id'";
    $delete_reports_run = mysqli_query($connection, $delete_reports_query);

    // Delete related records in violations
    $delete_violations_query = "DELETE FROM violations WHERE driver_id='$id'";
    $delete_violations_run = mysqli_query($connection, $delete_violations_query);

    // Delete related records in messages
    $delete_messages_query = "DELETE FROM messages WHERE driver_id='$id'";
    $delete_messages_run = mysqli_query($connection, $delete_messages_query);

    // Delete related records in app_drivers
    $delete_app_drivers_query = "DELETE FROM app_drivers WHERE driver_id='$id'";
    $delete_app_drivers_run = mysqli_query($connection, $delete_app_drivers_query);

    if ($delete_reports_run && $delete_violations_run && $delete_messages_run && $delete_app_drivers_run) {
        // Now delete the driver
        $query = "DELETE FROM drivers WHERE driver_id='$id'";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            $_SESSION['success'] = "Driver and all related records deleted successfully";
        } else {
            $_SESSION['status'] = "Driver Deletion Failed: " . mysqli_error($connection);
        }
    } else {
        $_SESSION['status'] = "Related records deletion failed: " . mysqli_error($connection);
    }

    header("Location: drivers.php");
    exit();
}


/* Add Driver
if (isset($_POST['add_driver_btn'])) {
    $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($connection, $_POST['middle_name']);
    $date_of_birth = mysqli_real_escape_string($connection, $_POST['date_of_birth']);
    $place_of_birth = mysqli_real_escape_string($connection, $_POST['place_of_birth']);
    $current_address = mysqli_real_escape_string($connection, $_POST['current_address']);
    $body_number = mysqli_real_escape_string($connection, $_POST['body_number']);
    $license_number = mysqli_real_escape_string($connection, $_POST['license_number']);
    $motor_plate_no = mysqli_real_escape_string($connection, $_POST['motor_plate_no']);
    $make_model = mysqli_real_escape_string($connection, $_POST['make_model']);
    $ownership_status = mysqli_real_escape_string($connection, $_POST['ownership_status']);
    
    // File upload for Membership Application Form Copy
    $membership_application_form_copy = $_FILES['membership_application_form_copy']['name'];
    $membership_application_form_copy_tmp = $_FILES['membership_application_form_copy']['tmp_name'];

    if ($_FILES['membership_application_form_copy']['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($membership_application_form_copy_tmp, "uploads/$membership_application_form_copy")) {
            echo "File uploaded successfully.<br>";
        } else {
            echo "Failed to move uploaded file.<br>";
        }
    } else {
        echo "File upload error code: " . $_FILES['membership_application_form_copy']['error'] . "<br>";
    }

    // Insert data into drivers table
    $query = "INSERT INTO drivers (last_name, first_name, middle_name, date_of_birth, place_of_birth, current_address, body_number, license_number, motor_plate_no, make_model, ownership_status, membership_application_form_copy) 
              VALUES ('$last_name', '$first_name', '$middle_name', '$date_of_birth', '$place_of_birth', '$current_address', '$body_number', '$license_number', '$motor_plate_no', '$make_model', '$ownership_status', '$membership_application_form_copy')";
    
    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "Driver Added Successfully";

        // Generate username and password
        $full_name = $first_name . ' ' . $last_name;
        $username = strtolower(str_replace(' ', '_', $full_name)) . rand(100, 999);
        $password = substr(md5(uniqid(rand(), true)), 0, 8); 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into app_drivers
        $last_id = mysqli_insert_id($connection);
        $app_driver_query = "INSERT INTO app_drivers (driver_id, full_name, make_and_model, tricycle_id, username, password, created_at) 
                             VALUES ('$last_id', '$full_name', '$make_model', '$body_number', '$username', '$hashed_password', NOW())";
        
        if (mysqli_query($connection, $app_driver_query)) {
            $_SESSION['app_username'] = $username;
            $_SESSION['app_password'] = $password;
            $_SESSION['show_modal'] = true;
        } else {
            echo "Error inserting into app_drivers: " . mysqli_error($connection) . "<br>";
        }
    } else {
        echo "Driver Addition Failed: " . mysqli_error($connection);
    }

    header("Location: drivers.php");
    exit();
}
*/


/* Edit Driver
if (isset($_POST['update_driver_btn'])) {
    $id = mysqli_real_escape_string($connection, $_POST['edit_id']);
    $contact_number = mysqli_real_escape_string($connection, $_POST['edit_contact_number']);
    $address = mysqli_real_escape_string($connection, $_POST['edit_address']);

    // Update data in database
    $query = "UPDATE drivers SET 
        contact_number='$contact_number',
        address='$address'
        WHERE driver_id='$id'";

    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        $_SESSION['success'] = "Driver Updated Successfully";
    } else {
        $_SESSION['status'] = "Driver Update Failed: " . mysqli_error($connection);
    }

    header("Location: drivers.php");
    exit();
}
*/

// Delete Driver
if (isset($_POST['delete_btn'])) {
    $id = mysqli_real_escape_string($connection, $_POST['delete_id']);
    $query = "DELETE FROM drivers WHERE driver_id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        $_SESSION['success'] = "Driver Deleted Successfully";
    } else {
        $_SESSION['status'] = "Driver Deletion Failed";
    }

    header("Location: drivers.php");
    exit();
}
?>
