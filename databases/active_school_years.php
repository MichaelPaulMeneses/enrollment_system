<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT * FROM school_year WHERE is_active = 1"; // Latest school year first
$result = $conn->query($sql);

$school_years = array();
while ($row = $result->fetch_assoc()) {
    $school_years[] = $row;
}

echo json_encode($school_years); // Return JSON response
$conn->close();
?>
