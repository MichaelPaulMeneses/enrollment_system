<?php
// Include DB connection
require_once 'db_connection.php'; // Adjust path if needed

// Set response header to JSON
header('Content-Type: application/json');

// Check if curriculum_id is provided
if (!isset($_GET['curriculum_id']) || empty($_GET['curriculum_id'])) {
    echo json_encode([]);
    exit;
}

$curriculum_id = intval($_GET['curriculum_id']);

// Prepare and execute query
$sql = "SELECT s.subject_id, s.subject_code, s.subject_name
        FROM subjects s
        LEFT JOIN curriculum_subjects cs 
            ON s.subject_id = cs.subject_id AND cs.curriculum_id = ?
        WHERE cs.curriculum_id IS NULL
        ORDER BY s.subject_code ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $curriculum_id);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];

while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

// Return JSON response
echo json_encode($subjects);

// Clean up
$stmt->close();
$conn->close();
?>
