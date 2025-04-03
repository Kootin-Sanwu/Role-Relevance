
<?php

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../../vendor/autoload.php';

function sendApprovalEmail($recipientEmail) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username   = 'kobekootinsanwu@gmail.com';
        $mail->Password   = 'jmvi iiki ugus zqnm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('kobekootinsanwu@gmail.com', 'Role Evaluation');
        $mail->addAddress($recipientEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Registration Approved';
        $mail->Body = "Dear User,<br><br>Your registration has been approved successfully.<br><br>Welcome to our platform!<br><br>Best Regards,<br>Admin Team";
        $mail->AltBody = "Dear User,\n\nYour registration has been approved successfully.\n\nWelcome to our platform!\n\nBest Regards,\nAdmin Team";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}