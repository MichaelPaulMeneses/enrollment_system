<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT * FROM school_year ORDER BY is_active DESC, school_year DESC"; // Order by latest first
$result = $conn->query($sql);

$school_years = array();
while ($row = $result->fetch_assoc()) {
    $school_years[] = [
        'school_year_id' => $row['school_year_id'], // Fetch school_year_id
        'school_year' => $row['school_year']       // Fetch school_year
    ];
}

// Return JSON response
echo json_encode([
    "status" => "success",
    "schoolYears" => $school_years
]);

$conn->close();
?>
