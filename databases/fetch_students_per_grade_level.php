<?php
// Include database connection
include('db_connection.php');

// Fetch the school_year_id from the POST request
$school_year_id = $_POST['school_year_id'] ?? null;

if ($school_year_id) {
    // Query to fetch grade levels and student counts for the specific school year
    $query = "
        SELECT 
            g.grade_name, 
            COUNT(s.student_id) AS student_count
        FROM 
            students s
        JOIN 
            grade_levels g ON s.grade_applying_for = g.grade_level_id
        WHERE 
            s.school_year_id = ?  -- Filter by the school_year_id
        GROUP BY 
            g.grade_name
        ORDER BY 
            FIELD(g.grade_name, 'Prekindergarten', 'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 
                    'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 
                    'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12');
    ";

    // Prepare and execute the query
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $school_year_id);  // Bind the school_year_id parameter
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $grades = [];
        $studentCounts = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $grades[] = $row['grade_name'];
            $studentCounts[] = (int)$row['student_count'];
        }

        // Send the data as JSON
        echo json_encode(['grades' => $grades, 'studentCounts' => $studentCounts]);
        
        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // Error with the query
        echo json_encode(['error' => 'Failed to prepare the query.']);
    }
} else {
    echo json_encode(['error' => 'School year ID is required.']);
}

// Close the database connection
mysqli_close($conn);
?>
