<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT * FROM users"; // Order by user_id
$result = $conn->query($sql);

$users = array();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users); // Return JSON response
$conn->close();
?>
