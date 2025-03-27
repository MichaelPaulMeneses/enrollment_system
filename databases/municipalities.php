<?php
include 'db_connection.php'; // Include database connection

if (isset($_GET['province_id'])) {
    $province_id = $_GET['province_id'];
    $sql = "SELECT * FROM municipalities WHERE province_id = ? ORDER BY municipality_name ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $province_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $municipalities = array();
    while ($row = $result->fetch_assoc()) {
        $municipalities[] = $row;
    }

    echo json_encode($municipalities); // Return JSON response
    $stmt->close();
}
$conn->close();
?>
