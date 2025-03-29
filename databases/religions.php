<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT * FROM religions ORDER BY religion_id ASC";
$result = $conn->query($sql);

$religions = array();
while ($row = $result->fetch_assoc()) {
    $religions[] = $row;
}

echo json_encode($religions); // Return JSON response
$conn->close();
?>
