<?php

// Include PHPMailer
require '../vendor/autoload.php';

// Function to generate admin approval link
function generateApprovalLink($organizationEmail, $hashedPassword, $organizationName, $jobTitles, $organizationDescription)
{
    
    $frontend_url = getenv("FRONTEND_URL") ?: "http://localhost:3000";

    // Ensure job_titles is always an array
    $jobTitles = is_array($jobTitles) ? array_values($jobTitles) : [$jobTitles];

    // Encode data
    $tokenData = [
        "organization_email" => $organizationEmail,
        "hashed_password" => $hashedPassword,
        "organization_name" => $organizationName,
        "job_title" => $jobTitles,
        "organization_description" => $organizationDescription
    ];

    $token = base64_encode(json_encode($tokenData));
    return "$frontend_url/views/approval.php?action=approve&token=$token";
}

// Function to generate admin decline link
function generateDeclineLink($organizationEmail)
{
    
    $frontend_url = getenv("FRONTEND_URL") ?: "http://localhost:3000";

    $token = base64_encode(json_encode(["organizaiton_email" => $organizationEmail]));
    return "$frontend_url/views/approval.php?action=decline&token=$token";
}