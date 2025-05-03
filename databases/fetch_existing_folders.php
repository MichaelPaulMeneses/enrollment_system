<?php
// Include the database connection
include('db_connection.php');

// Fetch existing folders from the database
$sql = "SELECT folder_id, folder_name FROM folders ORDER BY created_at DESC";
$result = $conn->query($sql);

$folders = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $folders[] = $row;
    }
}

echo json_encode($folders);

$conn->close();
?>
