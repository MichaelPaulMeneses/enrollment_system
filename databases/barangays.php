<?php
include 'db_connection.php'; // Include database connection

if (isset($_GET['municipality_id'])) {
    $municipality_id = $_GET['municipality_id'];
    $sql = "SELECT * FROM barangays WHERE municipality_id = ? ORDER BY barangay_name ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $municipality_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $barangays = array();
    while ($row = $result->fetch_assoc()) {
        $barangays[] = $row;
    }

    echo json_encode($barangays); // Return JSON response
    $stmt->close();
}
$conn->close();
?>
