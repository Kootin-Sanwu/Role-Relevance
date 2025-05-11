<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Include the OTP generation function
include_once "../functions/send_OTP.php";

$frontend_url = getenv("FRONTEND_URL") ?: "http://localhost:3000";
$backend_url = getenv("BACKEND_URL") ?: "http://localhost:8080";

if (isset($_SESSION['organization_email'])) {

    $organizationEmail = $_SESSION['organization_email'];
    $errors = [];

    // Generate OTP
    $OTP = rand(100000, 999999);

    $_SESSION['OTP'] = $OTP;
    $_SESSION['organization_email'] = $organizationEmail;
    $_SESSION['OTP_timestamp'] = time();

    // Send OTP email
    sendOTP($organizationEmail, $OTP);

    // Redirect to OTP verification page
    header("Location: $frontend_url/views/verify_otp.php?msg=" . urlencode("OTP has been resent."));
    exit();
}
