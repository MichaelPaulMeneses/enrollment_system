<?php
require 'db_connection.php'; // Include database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

header("Content-Type: application/json");

// Decode JSON data from request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['student_id'], $data['admin_user_id'], $data['status_remarks'])) {
    $student_id = intval($data['student_id']);
    $admin_user_id = intval($data['admin_user_id']);
    $status_remarks = htmlspecialchars(trim($data['status_remarks']));

    // Fetch student details for email
    $sql = "SELECT email, last_name, gender FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $userEmail = filter_var($row['email'], FILTER_SANITIZE_EMAIL);
        $surname = htmlspecialchars($row['last_name']);
        $gender = trim($row['gender']);

        $enrollment_status = 'Application Declined';
        // Update enrollment status to 'Declined'
        $updateSql = "UPDATE students 
                        SET enrollment_status = ?, 
                            status_remarks = ?, 
                            status_updated_by = ?, 
                            status_updated_at = NOW() 
                        WHERE student_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssii", $enrollment_status, $status_remarks, $admin_user_id, $student_id);
        if ($updateStmt->execute()) {
            
            // Determine salutation
            $salutation = ($gender == 'Male') ? 'Mr.' : (($gender == 'Female') ? 'Ms.' : '');

            $mail = new PHPMailer(true);
            try {
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sasatoru510@gmail.com';
                $mail->Password = 'iuddupmiyesabbvi'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Sender and Recipient
                $mail->setFrom('sasatoru510@gmail.com', 'SJBPS Admin');
                $mail->addAddress($userEmail);

                // Email Content
                $mail->isHTML(true);
                $mail->Subject = "Enrollment Declined - Status: $enrollment_status";                
                $mail->Body    = "<p>Dear $salutation $surname,</p>
                                    <p>We regret to inform you that your enrollment application has been declined.</p>
                                    <p><strong>Reason:</strong> $status_remarks</p>
                                    <p>For other instructions, visit or contact our Admissions Office:</p>
                                    <p><a href='mailto:registrar.sjbps@gmail.com'>registrar.sjbps@gmail.com</a> | (02) 8296 5896 | 0920 122 5764</p>";

                if ($mail->send()) {
                    echo json_encode(["success" => true, "message" => "Enrollment declined and email sent successfully."]);
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to send email."]);
                }
            } catch (Exception $e) {
                echo json_encode(["success" => false, "message" => "Mailer Error: {$mail->ErrorInfo}"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update student status."]);
        }
        $updateStmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Student not found."]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Missing required data."]);
}

$conn->close();
?>
