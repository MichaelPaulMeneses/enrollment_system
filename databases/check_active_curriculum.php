<?php
include("db_connection.php");

$sql = "SELECT COUNT(*) as active_count FROM curriculums WHERE curriculum_is_active = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode(['hasActive' => $row['active_count'] > 0]);

$conn->close();
?>
