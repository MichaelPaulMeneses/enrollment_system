<?php
include 'db_connection.php'; // Your DB connection file

// Modify the query to filter by school year if needed
$query = "SELECT COUNT(*) AS total FROM users";

$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo $row['total'];
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
