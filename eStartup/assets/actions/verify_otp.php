<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "../settings/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userOTP = implode('', $_POST['OTP']);
    $expectedOTP = $_SESSION['OTP'] ?? '';
    $signingIn = $_SESSION['signing_in'] ?? '';
    $registering = $_SESSION['registering'] ?? '';
    $forgot_password = $_SESSION['forgot_password'];

    // Convert OTPs to strings for comparison
    $userOTP = (string) $userOTP;
    $expectedOTP = (string) $expectedOTP;

    // Check OTP expiration first
    $currentTime = time();
    $OTP_time_created = $_SESSION['OTP_timestamp'] ?? 0;
    $overdueOTP = $currentTime - $OTP_time_created;

    if ($overdueOTP > 120) {

        header("Location: ../views/verify_otp.php?msg=" . urlencode("OTP expired. Please try again."));
        exit();
    }

    // Verify OTP match first before any redirects
    if ($userOTP !== $expectedOTP) {

        header("Location: ../views/verify_otp.php?msg=" . urlencode("Incorrect OTP. Please try again."));
        exit();
    } else if ($userOTP === $expectedOTP && (isset($_SESSION['registering']))) {

        unset($_SESSION['registering']);
        $message = "registering";
        header("Location: ../actions/register.php?msg=" . urlencode($message));
    } else if ($userOTP === $expectedOTP && (isset($_SESSION['signing_in']))) {

        unset($_SESSION['signing_in']);
        $message = "signing_in";
        header("Location: ../actions/login.php?msg=" . urlencode($message));
    } else if ($userOTP === $expectedOTP && (isset($forgot_password))) {

        header("Location: ../actions/forgot_password.php?msg=" . urlencode("Reset Password"));
    } else {

        header("Location: ../views/verify_otp.php?msg=" . urlencode("Restart the login process."));
    }
}
