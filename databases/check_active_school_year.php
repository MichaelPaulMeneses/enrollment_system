<?php
include("db_connection.php");

$sql = "SELECT COUNT(*) as active_count FROM school_year WHERE is_active = 1";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(['hasActive' => $row['active_count'] > 0]);
} else {
    echo json_encode(['error' => 'Query failed']);
}

$conn->close();
?>
