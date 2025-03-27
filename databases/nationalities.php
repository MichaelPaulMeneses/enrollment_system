<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT * FROM nationalities ORDER BY nationality_name ASC";
$result = $conn->query($sql);

$nationalities = array();
while ($row = $result->fetch_assoc()) {
    $nationalities[] = $row;
}

echo json_encode($nationalities); // Return JSON response
$conn->close();
?>
