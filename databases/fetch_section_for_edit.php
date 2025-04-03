<?php
require_once "db_connection.php"; // Ensure this file connects to your database

header('Content-Type: application/json'); // Set the content type to JSON

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['section_id']) && !empty($_GET['section_id'])) {
        $sectionId = intval($_GET['section_id']); // Get the section ID from the URL

        // Fetch the section details based on the section ID
        $stmt = $conn->prepare("SELECT * FROM sections WHERE section_id = ?");
        $stmt->bind_param("i", $sectionId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the row
        $section = $result->fetch_assoc(); // Fetch a single row as an associative array

        // If a section is found, return the section data as JSON
        if ($section) {
            echo json_encode([
                "status" => "success",
                "section_id" => $section['section_id'],
                "section_name" => $section['section_name'],
                "grade_level_id" => $section['grade_level_id'],
                "school_year_id" => $section['school_year_id']
            ]);
        } else {
            // If no section is found, return an error message
            echo json_encode([
                "status" => "error",
                "message" => "Section not found."
            ]);
        }
    } else {
        // If section_id is not set or is invalid
        echo json_encode([
            "status" => "error",
            "message" => "Invalid section ID."
        ]);
    }
}
?>
