<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
include('includes/dbconfig.php');  // Include your database connection file

// Fetch drivers from the database
$query = "SELECT driver_id, full_name FROM app_drivers ORDER BY full_name ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

// Handle form submission for sending messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_content'])) {
    $driver_id = $_POST['driver_id'];
    $message_content = $_POST['message_content'];
    $is_global = ($driver_id == 'all') ? 1 : 0;

    // If it's a global message, set driver_id to NULL
    $driver_id_value = ($is_global == 1) ? NULL : $driver_id;

    // Insert the message into the messages table
    $query = "INSERT INTO messages (driver_id, message_content, is_global) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $driver_id_value, $message_content, $is_global);

    if ($stmt->execute()) {
        $success_message = "Message sent successfully!";
    } else {
        $error_message = "Failed to send message: " . $stmt->error;
    }

    $stmt->close();
}

// Handle delete message action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message_id'])) {
    $delete_message_id = $_POST['delete_message_id'];

    // Delete the message from the messages table
    $delete_query = "DELETE FROM messages WHERE message_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $delete_message_id);

    if ($delete_stmt->execute()) {
        $success_message = "Message deleted successfully!";
    } else {
        $error_message = "Failed to delete message: " . $delete_stmt->error;
    }

    $delete_stmt->close();
}

// Fetch previously sent messages
$messages_query = "SELECT m.message_id, m.message_content, m.created_at, IF(m.is_global = 1, 'All Drivers', d.full_name) as recipient 
                   FROM messages m 
                   LEFT JOIN app_drivers d ON m.driver_id = d.driver_id 
                   ORDER BY m.created_at DESC";
$messages_result = mysqli_query($conn, $messages_query);

if (!$messages_result) {
    die("Failed to fetch messages: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message to Driver</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #aa3131;
            color: white;
            font-weight: bold;
            text-align: center;
            border-radius: 15px 15px 0 0;
            padding: 15px;
            font-size: 24px;
        }
        .form-group label {
            font-weight: bold;
            font-size: 16px;
        }
        .btn-custom {
            background-color: #aa3131;
            color: white;
            border-radius: 10px;
            font-weight: bold;
            padding: 10px 20px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #aa3131;
        }
        .table-responsive {
            margin-top: 50px;
        }
        .table th, .table td {
            vertical-align: middle;
            font-size: 16px;
        }
        .table th {
            background-color: #aa3131;
            color: white;
        }
        .alert {
            margin-top: 20px;
        }
        .btn-delete {
            background-color: #d9534f;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            padding: 5px 10px;
        }
        .btn-delete:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            Send Message to Driver
        </div>
        <div class="card-body">
            <?php if(isset($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php elseif(isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST">
                <div class="form-group">
                    <label for="driver_id">Select Driver:</label>
                    <select name="driver_id" id="driver_id" class="form-control" required>
                        <option value="all">All Drivers</option>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <option value="<?php echo $row['driver_id']; ?>"><?php echo $row['full_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message_content">Message:</label>
                    <textarea name="message_content" id="message_content" class="form-control" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-custom">Send Message</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <h2 class="text-center mt-5">Previously Sent Messages</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Message Content</th>
                    <th>Recipient</th>
                    <th>Sent On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($message_row = mysqli_fetch_assoc($messages_result)): ?>
                    <tr>
                        <td><?php echo $message_row['message_content']; ?></td>
                        <td><?php echo $message_row['recipient']; ?></td>
                        <td><?php echo date('F j, Y, g:i a', strtotime($message_row['created_at'])); ?></td>
                        <td>
                            <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#deleteModal" data-messageid="<?php echo $message_row['message_id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this message?
      </div>
      <div class="modal-footer">
        <form action="" method="POST" id="deleteForm">
          <input type="hidden" name="delete_message_id" id="delete_message_id">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
// Close the database connection
mysqli_close($conn);
?>

<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var messageId = button.data('messageid') // Extract info from data-* attributes
        var modal = $(this)
        modal.find('#delete_message_id').val(messageId)
    })
</script>
</body>
</html>
