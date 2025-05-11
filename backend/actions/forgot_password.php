<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Include the database connection file
include_once "../settings/connection.php";

// Include the OTP generation function
include_once "../functions/send_OTP.php";

$frontend_url = getenv("FRONTEND_URL") ?: "http://13.60.64.199:3000";
$backend_url = getenv("BACKEND_URL") ?: "http://13.60.64.199:8080";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['organization_email'])) {

    $organizationEmail = filter_var($_POST['organization_email'], FILTER_SANITIZE_EMAIL);
    $_SESSION['organization_email'] = $organizationEmail;

    if (isset($_POST['Forgot_Password'])) {
        $forgotPassword = $_POST['forgot_password'];
        $_SESSION['forgot_password'] = $forgotPassword;
    } else if (
        isset($_POST['new_password']) && isset($_POST['confirm_new_password']) &&
        !empty($_POST['new_password']) && !empty($_POST['confirm_new_password'])
    ) {
        $newPassword = trim($_POST['new_password']);
        $confirmNewPassword = trim($_POST['confirm_new_password']);

        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($passwordRegex, $newPassword)) {
            header("Location: $frontend_url/views/reset_password.php?msg=Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
            exit();
        }

        if ($newPassword !== $confirmNewPassword) {
            header("Location: $frontend_url/views/reset_password.php?msg=Passwords do not match.");
            exit();
        }

        $hashedPassword = password_hash($confirmNewPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE Organizations SET Password = ? WHERE Email = ?");
        $stmt->execute([$hashedPassword, $organizationEmail]);

        if ($stmt->rowCount() > 0) {
            header("Location: $frontend_url/views/login.php?msg=Password updated successfully.");
            exit();
        } else {
            header("Location: $frontend_url/views/reset_password.php?msg=Error updating password.");
            exit();
        }
    }

    $errors = [];

    try {
        if ($_SESSION['forgot_password'] === "forgot_password") {

            $stmt = $pdo->prepare("SELECT Email FROM Organizations WHERE Email = ?");
            $stmt->execute([$organizationEmail]);

            // Email exists, proceed to generate OTP
            if ($stmt->rowCount() > 0) {

                // Generate OTP
                $OTP = rand(100000, 999999);

                // Store OTP and email in session for verification later
                $_SESSION['OTP'] = $OTP;
                $_SESSION['organization_email'] = $organizationEmail;
                $_SESSION['OTP_timestamp'] = time();

                // Send OTP email
                sendOTP($organizationEmail, $OTP);
                header("Location: $frontend_url/views/verify_otp.php?msg=" . urlencode($forgotPassword));
                exit();
            } else {

                header("Location: $frontend_url/views/forgot_password.php?msg=Email not found.");
                exit();
            }
        } else {

            echo "No password reset request received.";
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else if (isset($_GET['msg']) && $_GET['msg'] === 'Reset Password') {

    header("Location: $frontend_url/views/reset_password.php");
    exit();
} else {

    echo "No request method received. No Reset Password message received";
};
