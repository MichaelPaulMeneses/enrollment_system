<?php
require 'db_connection.php'; // Include DB connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($data['student_id'], $data['admin_user_id'])) {
        echo json_encode(["success" => false, "message" => "Missing required parameters."]);
        exit;
    }

    $student_id = intval($data['student_id']);
    $admin_user_id = intval($data['admin_user_id']);

    // Step 1: Fetch student details for email
    $fetchSql = "SELECT email, last_name, gender, type_of_student, appointment_date, appointment_time FROM students WHERE student_id = ?";
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
    $type_of_student = htmlspecialchars($row['type_of_student']);
    $appointment_date = htmlspecialchars($row['appointment_date']);
    $appointment_time = htmlspecialchars($row['appointment_time']);
    $fetchStmt->close();

    // Step 2: Update Enrollment Status
    $enrollment_status = 'For Payment'; 
    

    $updateSql = "UPDATE students SET enrollment_status = ?, status_updated_by = ?, status_updated_at = NOW() WHERE student_id = ?";
    
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sii", $enrollment_status, $admin_user_id, $student_id);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Database error while updating enrollment status."]);
        exit;
    }
    $stmt->close();

    // Step 3: Send Email Notification
    $salutation = ($gender == 'Male') ? 'Mr.' : (($gender == 'Female') ? 'Ms.' : '');
    
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
        $message = "<p>Dear $salutation $surname,</p>
                <p>Congratulations! You have successfully passed the interview at Saint John the Baptist Parochial School.</p>";

        $message .= "<p>You may now proceed with the payment process at the cashier to finalize your registration.</p>";

        $message .= "<p>For further assistance or inquiries, please contact our Admissions Office:</p>
                <p><a href='mailto:registrar.sjbps@gmail.com'>registrar.sjbps@gmail.com</a> | (02) 8296 5896 | 0920 122 5764</p>";

        $mail->Body = $message;

        if ($mail->send()) {
            echo json_encode(["success" => true, "message" => "Enrollment approved and email sent successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to send email."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Mailer Error: " . $mail->ErrorInfo]);
    }

} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>
