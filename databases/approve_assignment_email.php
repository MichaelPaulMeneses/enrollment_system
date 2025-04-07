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
    // Assign values
    $student_id = intval($data['student_id']);
    $section_id = intval($data['section_id']);
    $admin_user_id = intval($data['admin_user_id']);

    // Step 1: Fetch student details for email
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

    // Step 2: Fetch curriculum_id for the student
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

    // Only keep academic_track and academic_semester if Grade 11 or 12
    if (!in_array($gradeApplyingFor, [14, 15])) {
        $academicTrack = null;
        $academicSemester = null;
    }

    // Step 3: Fetch subjects for the student's grade level and curriculum
    $fetchSubjectsSql = "SELECT subject_code, subject_name FROM subjects 
                        WHERE curriculum_id = ? AND grade_level_id = ? AND academic_track = ? AND academic_semester = ?";
    $fetchSubjectsStmt = $conn->prepare($fetchSubjectsSql);
    $fetchSubjectsStmt->bind_param("iisi", $curriculum_id, $gradeApplyingFor, $academicTrack, $academicSemester);
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

    // Step 6: Prepare email content with subjects (optional)
    $subjectList = "";
    foreach ($subjects as $subject) {
        $subjectList .= $subject['subject_code'] . " - " . $subject['subject_name'] . "\n";
    }

    // Define salutation based on gender
    if ($gender == 'Male') {
        $salutation = 'Mr.';
    } elseif ($gender == 'Female') {
        $salutation = 'Ms.';
    } else {
        $salutation = 'Mr./Ms.';  // Default, for other gender or unspecified
    }


    // Example of sending email notification using PHPMailer (Step 4 optional)
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

        $mail->Subject = "Enrollment Approved - Status: $enrollment_status";

        // Email body
        $message = "<p>Dear $salutation $surname,</p>";

        $message .= "<p>Congratulations! We are pleased to inform you that you have successfully passed the interview at Saint John the Baptist Parochial School and your enrollment has been approved.</p>";

        $message .= "<p>You are now officially enrolled in $gradeName and your following subjects are:</p>";
        $message .= "<ul>";
        foreach ($subjects as $subject) {
            $message .= "<li><strong>{$subject['subject_code']}</strong> - {$subject['subject_name']}</li>";
        }
        $message .= "</ul>";

        $message .= "<p>For further assistance or inquiries, please contact our Admissions Office:</p>
        <p><a href='mailto:registrar.sjbps@gmail.com'>registrar.sjbps@gmail.com</a> | (02) 8296 5896 | 0920 122 5764</p>";

        $mail->Body = $message;
        $mail->CharSet = 'UTF-8';
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
