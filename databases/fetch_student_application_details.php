<?php
include "databases/db_connection.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["student_id"])) {
    $student_id = $_POST["student_id"];

    $query = "SELECT 
            s.student_id,
            s.last_name,
            s.first_name,
            s.middle_name,
            s.suffix,
            r.regDesc AS region_name,
            p.provDesc AS province_name,
            c.citymunDesc AS municipality_name,
            b.brgyDesc AS barangay_name,
            s.street_address,
            s.zip_code,
            s.date_of_birth,
            s.place_of_birth,
            s.gender,
            n.nationality_name,
            rel.religion_name,
            g.grade_name AS previous_grade_level,
            s.email,
            s.contact,
            s.school_last_attended,
            s.school_address,
            s.father_name,
            s.father_occupation,
            s.father_contact_number,
            s.mother_name,
            s.mother_occupation,
            s.mother_contact_number,
            s.guardian_name,
            s.guardian_relationship,
            s.guardian_contact_number,
            sy.school_year,
            s.type_of_student,
            s.grade_applying_for,
            s.academic_track,
            s.academic_semester,
            s.appointment_date,
            s.appointment_time,
            s.birth_certificate,
            s.report_card,
            s.id_picture,
            s.enrollment_status,
            s.created_at
        FROM students s
        JOIN refregion r ON s.region_id = r.id
        JOIN refprovince p ON s.province_id = p.id
        JOIN refcitymun c ON s.municipality_id = c.id
        JOIN refbrgy b ON s.barangay_id = b.id
        JOIN nationalities n ON s.nationality_id = n.nationality_id
        JOIN religions rel ON s.religion_id = rel.religion_id
        JOIN grade_levels g ON s.prev_grade_lvl = g.grade_level_id
        JOIN school_year sy ON s.school_year_id = sy.school_year_id
        WHERE s.student_id = ?";
        
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        die("Student not found.");
    }
    $stmt->close();
} else {
    die("Access denied.");
}

?>