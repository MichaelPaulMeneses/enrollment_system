<?php
// Include database connection
include('db_connection.php');

// SQL Query to fetch student data, including the school year
$sql = "SELECT * 
        FROM curriculums 
        ORDER BY curriculum_name ASC;";
        
$result = $conn->query($sql);

$applications = array();

// Check if there are any results and fetch them
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return the data as a JSON response
echo json_encode($applications);
?>

