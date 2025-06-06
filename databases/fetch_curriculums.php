<?php
// Include database connection
include('db_connection.php');

// SQL Query to fetch student data, including the school year
$sql = "SELECT 
            curriculum_id, 
            curriculum_name, 
            curriculum_is_active,
            CASE 
                WHEN curriculum_is_active = 1 THEN 'Active'
                ELSE 'Inactive'
            END AS status
        FROM curriculums
        ORDER BY curriculum_is_active DESC;
        ";
        
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

