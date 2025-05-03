<?php
require 'db_connection.php'; // Adjust path as needed

$sql = "SELECT folder_id, folder_name FROM folders ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $folders = [];
    while ($row = $result->fetch_assoc()) {
        $folders[] = $row;
    }
    echo json_encode(["status" => "success", "folders" => $folders]);
} else {
    echo json_encode(["status" => "error", "message" => "No folders found."]);
}
?>
