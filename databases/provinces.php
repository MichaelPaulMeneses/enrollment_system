<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT * FROM provinces ORDER BY province_name ASC";
$result = $conn->query($sql);

$provinces = array();
while ($row = $result->fetch_assoc()) {
    $provinces[] = $row;
}

echo json_encode($provinces); // Return JSON response
$conn->close();
?>
