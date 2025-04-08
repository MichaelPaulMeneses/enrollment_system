<?php
include 'db_connection.php'; // Include database connection

// Query to get the active school year
$sql = "SELECT * FROM school_year WHERE is_active = 1 LIMIT 1"; // Ensure only one active school year
$result = $conn->query($sql);

// Check if the query was successful and if any result is returned
if ($result) {
    // Fetch the active school year data
    if ($row = $result->fetch_assoc()) {
        // Return the school year data as JSON
        echo json_encode([$row]); // Wrapping the row in an array as expected by the frontend
    } else {
        // No active school year found
        echo json_encode([]); // Return an empty array if no active school year
    }
} else {
    // Query failed
    echo json_encode(['error' => 'Query failed: ' . $conn->error]); // Return an error message in JSON
}

// Close the database connection
$conn->close();
?>
