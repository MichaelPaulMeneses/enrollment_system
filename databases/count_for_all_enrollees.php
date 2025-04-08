<?php
include 'db_connection.php'; // Your DB connection file

// Get the school year ID from the POST request
$school_year_id = isset($_POST['school_year_id']) ? $_POST['school_year_id'] : null;

// Modify the query to filter by school year if needed
$query = "SELECT COUNT(*) AS total FROM students WHERE enrollment_status = 'Fully Enrolled' AND school_year_id = '$school_year_id'";

$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo $row['total'];
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
