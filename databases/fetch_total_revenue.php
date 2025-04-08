<?php
// Include your database connection here (replace with your actual DB connection)
include('db_connection.php');

// Check if 'school_year_id' is passed via POST
if (isset($_POST['school_year_id'])) {
    $school_year_id = $_POST['school_year_id'];

    // Prepare SQL query to fetch the total revenue from the payment_history table
    $query = "SELECT SUM(payment_amount) AS total_revenue 
                FROM payment_history 
                WHERE student_id IN (SELECT student_id FROM students WHERE school_year_id = ?)";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $school_year_id); // Bind the school_year_id to the query
        $stmt->execute();
        $stmt->bind_result($total_revenue);
        
        // Fetch the result
        if ($stmt->fetch()) {
            // Return the total revenue as a response
            echo number_format($total_revenue, 2); // Format as a currency (optional)
        } else {
            echo '0.00'; // No revenue found
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo 'Error: Could not prepare statement.';
    }
    
} else {
    echo 'Error: school_year_id not set.';
}

// Close the database connection
$conn->close();
?>
