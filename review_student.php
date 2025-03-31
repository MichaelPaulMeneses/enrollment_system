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
            sy.school_year AS school_year,  
            s.type_of_student,
            ga.grade_name AS grade_applying_for,
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
        JOIN grade_levels g ON s.prev_grade_lvl = g.grade_level_id  -- Joining for previous grade level
        JOIN grade_levels ga ON s.grade_applying_for = ga.grade_level_id  -- Joining again for applying grade
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
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Review Student Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .student-info {
            border: 1px solid #ddd;
            padding: 15px;
            width: 600px;
        }
        .student-info img {
            max-width: 200px;
            display: block;
        }
    </style>
</head>
<body>
    <h2>Student Details</h2>
    <?php if (!empty($student['id_picture'])): ?>
        <p><strong>ID Picture:</strong></p>
        <img src="<?= htmlspecialchars($student['id_picture']) ?>" alt="ID Picture">
    <?php endif; ?>
    <div class="student-info">
        <p><strong>ID:</strong> <?= htmlspecialchars($student['student_id']) ?></p>
        <p><strong>Last Name:</strong> <?= htmlspecialchars($student['last_name']) ?></p>
        <p><strong>First Name:</strong> <?= htmlspecialchars($student['first_name']) ?></p>
        <p><strong>Middle Name:</strong> <?= htmlspecialchars($student['middle_name']) ?></p>
        <p><strong>Suffix:</strong> <?= htmlspecialchars($student['suffix']) ?></p>
        <p><strong>Region:</strong> <?= htmlspecialchars($student['region_name']) ?></p>
        <p><strong>Province:</strong> <?= htmlspecialchars($student['province_name']) ?></p>
        <p><strong>Municipality:</strong> <?= htmlspecialchars($student['municipality_name']) ?></p>
        <p><strong>Barangay:</strong> <?= htmlspecialchars($student['barangay_name']) ?></p>
        <p><strong>Street Address:</strong> <?= htmlspecialchars($student['street_address']) ?></p>
        <p><strong>Zip Code:</strong> <?= htmlspecialchars($student['zip_code']) ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($student['date_of_birth']) ?></p>
        <p><strong>Place of Birth:</strong> <?= htmlspecialchars($student['place_of_birth']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($student['gender']) ?></p>
        <p><strong>Nationality:</strong> <?= htmlspecialchars($student['nationality_name']) ?></p>
        <p><strong>Religion:</strong> <?= htmlspecialchars($student['religion_name']) ?></p>
        <p><strong>Previous Grade:</strong> <?= htmlspecialchars($student['previous_grade_level']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($student['contact']) ?></p>
        <p><strong>School Last Attended:</strong> <?= htmlspecialchars($student['school_last_attended']) ?></p>
        <p><strong>School Address:</strong> <?= htmlspecialchars($student['school_address']) ?></p>

        <h3>Application Information</h3>
        <p><strong>School Year:</strong> <?= htmlspecialchars($student['school_year']) ?></p>
        <p><strong>Type of Student:</strong> <?= htmlspecialchars($student['type_of_student']) ?></p>
        <p><strong>Grade Applying For:</strong> <?= htmlspecialchars($student['grade_applying_for']) ?></p>
        <p><strong>Academic Track:</strong> <?= htmlspecialchars($student['academic_track']) ?></p>
        <p><strong>Academic Semester:</strong> <?= htmlspecialchars($student['academic_semester']) ?></p>
        <p><strong>Appointment Date:</strong> <?= htmlspecialchars($student['appointment_date']) ?></p>
        <p><strong>Appointment Time:</strong> <?= htmlspecialchars($student['appointment_time']) ?></p>
        <p><strong>Enrollment Status:</strong> <?= htmlspecialchars($student['enrollment_status']) ?></p>

        <h3>Documents</h3>
        <p><strong>Birth Certificate:</strong> <a href="<?= htmlspecialchars($student['birth_certificate']) ?>" target="_blank">View</a></p>
        <p><strong>Report Card:</strong> <a href="<?= htmlspecialchars($student['report_card']) ?>" target="_blank">View</a></p>
    </div>

    <br><br>
    <a href="admin-application-for-review.php">Back to Applications</a>
</body>
</html>