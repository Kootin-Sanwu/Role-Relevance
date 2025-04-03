<?php

// Include PHPMailer
require '../../../vendor/autoload.php';


// Function to generate admin approval link
function generateApprovalLink($organizationEmail, $hashedPassword, $organizationName, $jobTitles)
{
    // Ensure job_titles is always an array
    $jobTitles = is_array($jobTitles) ? array_values($jobTitles) : [$jobTitles];

    // Encode data
    $tokenData = [
        "organization_email" => $organizationEmail,
        "hashed_password" => $hashedPassword,
        "organization_name" => $organizationName,
        "job_title" => $jobTitles // Now always an array
    ];

    $token = base64_encode(json_encode($tokenData));
    return "http://localhost/Local-Job-Role-Relevance-3/eStartup/assets/admin/approval.php?action=approve&token=$token";
}

// Function to generate admin decline link
function generateDeclineLink($organizationEmail)
{
    $token = base64_encode(json_encode(["organizaiton_email" => $organizationEmail]));
    return "http://localhost/Local-Job-Role-Relevance-3/eStartup/assets/admin/approval.php?action=decline&token=$token";
}