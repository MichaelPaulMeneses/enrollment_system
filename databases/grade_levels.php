<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT * FROM grade_levels ORDER BY grade_level_id ASC"; // Order by ID
$result = $conn->query($sql);

$grade_levels = array();
while ($row = $result->fetch_assoc()) {
    $grade_levels[] = $row;
}

echo json_encode($grade_levels); // Return JSON response
$conn->close();
?>
