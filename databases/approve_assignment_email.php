<?php
require 'db_connection.php'; // Include DB connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

header("Content-Type: application/json");

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if required data is present
if (isset($data['student_id'], $data['section_id'], $data['admin_user_id'])) {
    $student_id = intval($data['student_id']);
    $section_id = intval($data['section_id']);
    $admin_user_id = intval($data['admin_user_id']);

    // Step 1: Fetch student details
    $fetchSql = "SELECT s.email, s.last_name, s.gender, g.grade_name, s.grade_applying_for, s.academic_track, s.academic_semester
                FROM students s
                JOIN grade_levels g ON s.grade_applying_for = g.grade_level_id
                WHERE s.student_id = ?";
    $fetchStmt = $conn->prepare($fetchSql);
    $fetchStmt->bind_param("i", $student_id);
    $fetchStmt->execute();
    $result = $fetchStmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        echo json_encode(["success" => false, "message" => "Student details not found for email notification."]);
        exit;
    }

    $userEmail = filter_var($row['email'], FILTER_SANITIZE_EMAIL);
    $surname = htmlspecialchars($row['last_name']);
    $gender = trim($row['gender']);
    $gradeName = htmlspecialchars($row['grade_name']);
    $gradeApplyingFor = intval($row['grade_applying_for']);
    $academicTrack = htmlspecialchars($row['academic_track']);
    $academicSemester = intval($row['academic_semester']);
    $fetchStmt->close();

    // Step 2: Fetch active curriculum
    $fetchCurrSql = "SELECT curriculum_id FROM curriculums WHERE curriculum_is_active = 1";
    $fetchCurrStmt = $conn->prepare($fetchCurrSql);
    $fetchCurrStmt->execute();
    $currResult = $fetchCurrStmt->get_result();

    if ($currRow = $currResult->fetch_assoc()) {
        $curriculum_id = intval($currRow['curriculum_id']);
        $fetchCurrStmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Active curriculum not found."]);
        exit;
    }

    // Step 3: Fetch subjects based on grade level
    if (in_array($gradeApplyingFor, [14, 15])) {
        // Grades 11 and 12 - use track and semester
        $fetchSubjectsSql = "SELECT s.subject_code, s.subject_name 
                            FROM subjects s
                            INNER JOIN curriculum_subjects cs ON s.subject_id = cs.subject_id
                            WHERE cs.curriculum_id = ? 
                            AND s.grade_level_id = ? 
                            AND s.academic_track = ? 
                            AND s.academic_semester = ?";
        $fetchSubjectsStmt = $conn->prepare($fetchSubjectsSql);
        $fetchSubjectsStmt->bind_param("iisi", $curriculum_id, $gradeApplyingFor, $academicTrack, $academicSemester);
    } else {
        // Grades 2–10 - no track or semester
        $fetchSubjectsSql = "SELECT s.subject_code, s.subject_name 
                            FROM subjects s
                            INNER JOIN curriculum_subjects cs ON s.subject_id = cs.subject_id
                            WHERE cs.curriculum_id = ? 
                            AND s.grade_level_id = ?";
        $fetchSubjectsStmt = $conn->prepare($fetchSubjectsSql);
        $fetchSubjectsStmt->bind_param("ii", $curriculum_id, $gradeApplyingFor);
    }

    $fetchSubjectsStmt->execute();
    $subjectsResult = $fetchSubjectsStmt->get_result();

    $subjects = [];
    while ($subject = $subjectsResult->fetch_assoc()) {
        $subjects[] = [
            'subject_code' => $subject['subject_code'],
            'subject_name' => $subject['subject_name']
        ];
    }
    $fetchSubjectsStmt->close();

    if (empty($subjects)) {
        echo json_encode(["success" => false, "message" => "No subjects found for the given curriculum and grade level."]);
        exit;
    }

    // Step 4: Insert into assigned_students
    $insertSql = "INSERT INTO assigned_students (student_id, section_id) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ii", $student_id, $section_id);

    if (!$insertStmt->execute()) {
        echo json_encode(["success" => false, "message" => "Error inserting assignment: " . $insertStmt->error]);
        exit;
    }
    $insertStmt->close();

    // Step 5: Update enrollment_status
    $enrollment_status = 'Fully Enrolled';
    $updateSql = "UPDATE students SET enrollment_status = ?, status_updated_by = ?, status_updated_at = NOW() WHERE student_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sii", $enrollment_status, $admin_user_id, $student_id);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Database error while updating enrollment status."]);
        exit;
    }
    $stmt->close();

    // Step 6: Prepare email
    $salutation = ($gender == 'Male') ? 'Mr.' : (($gender == 'Female') ? 'Ms.' : 'Mr./Ms.');
    $subjectList = "";
    foreach ($subjects as $subject) {
        $subjectList .= $subject['subject_code'] . " - " . $subject['subject_name'] . "\n";
    }

    $sectionNameSql = "
    SELECT s.section_name
    FROM sections s
    JOIN assigned_students a ON s.section_id = a.section_id
    WHERE a.student_id = ?
    ";
    $sectionNameStmt = $conn->prepare($sectionNameSql);
    $sectionNameStmt->bind_param("i", $student_id);
    $sectionNameStmt->execute();
    $sectionNameResult = $sectionNameStmt->get_result();

    if ($sectionRow = $sectionNameResult->fetch_assoc()) {
        $sectionName = htmlspecialchars($sectionRow['section_name']);
        $sectionNameStmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Section name not found for student."]);
        exit;
    }


    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sasatoru510@gmail.com';
        $mail->Password = 'iuddupmiyesabbvi'; // Store securely!
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('sasatoru510@gmail.com', 'SJBPS Admin');
        $mail->addAddress($userEmail);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = "Enrollment Approved - Status: $enrollment_status";

        $message = "<p>Dear $salutation $surname,</p>";
        $message .= "<p>Congratulations! We are pleased to inform you that you have successfully passed the interview at Saint John the Baptist Parochial School and your enrollment has been approved.</p>";
        $message .= "<p>You are now officially enrolled in $gradeName <strong>$sectionName</strong> and your following subjects are:</p><ul>";
        foreach ($subjects as $subject) {
            $message .= "<li><strong>{$subject['subject_code']}</strong> - {$subject['subject_name']}</li>";
        }
        $message .= "</ul>";
        $message .= "<p>For further assistance or inquiries, please contact our Admissions Office:</p>
            <p><a href='mailto:registrar.sjbps@gmail.com'>registrar.sjbps@gmail.com</a> | (02) 8296 5896 | 0920 122 5764</p>";

        $mail->Body = $message;
        $mail->send();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Email sending failed: " . $mail->ErrorInfo]);
        exit;
    }

    // Final response
    echo json_encode(["success" => true, "message" => "Student successfully assigned, enrolled, and notified."]);
} else {
    echo json_encode(["success" => false, "message" => "Incomplete request data."]);
}

$conn->close();
?>
