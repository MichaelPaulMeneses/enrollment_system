<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT * FROM grade_levels WHERE grade_level_id != 1 AND grade_name != 'N/A' ORDER BY grade_level_id ASC"; // Filter and order
$result = $conn->query($sql);

$grade_levels = array();
while ($row = $result->fetch_assoc()) {
    $grade_levels[] = $row;
}

echo json_encode($grade_levels); // Return JSON response
$conn->close();
?>
