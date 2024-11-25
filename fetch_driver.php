<!-- for transferOwnerModal -->

<?php
$connection = mysqli_connect("localhost", "root", "", "todanato");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['query'])) {
    $query = "SELECT driver_id, full_name FROM drivers WHERE driver_id LIKE '%" . $_POST['query'] . "%' OR full_name LIKE '%" . $_POST['query'] . "%' LIMIT 10";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $output = '<ul class="list-unstyled">';
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= '<li data-id="' . htmlspecialchars($row['driver_id']) . '" data-name="' . htmlspecialchars($row['full_name']) . '">' . htmlspecialchars($row['driver_id']) . ' - ' . htmlspecialchars($row['full_name']) . '</li>';
        }
        $output .= '</ul>';
        echo $output;
    } else {
        echo '<li>No match found</li>';
    }
}
?>
