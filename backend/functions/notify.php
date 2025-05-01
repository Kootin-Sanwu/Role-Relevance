
<?php

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../vendor/autoload.php';

// Function to notify admin
function notifyAdmin($adminEmail, $organizationEmail, $approvalLink, $declineLink)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kobekootinsanwu@gmail.com';
        $mail->Password   = 'jmvi iiki ugus zqnm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('kobekootinsanwu@gmail.com', 'Role Evaluation');
        $mail->addAddress($adminEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New User Registration Request';
        $mail->Body    = "A new user has requested registration. Email: <b>$organizationEmail</b><br><br>
                          <a href='$approvalLink'>Approve Registration</a> | 
                          <a href='$declineLink'>Decline Registration</a>";
        $mail->AltBody = "A new user has requested registration. Email: $organizationEmail. 
                          Approve: $approvalLink | Decline: $declineLink";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}