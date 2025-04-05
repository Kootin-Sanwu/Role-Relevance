<?php

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../../vendor/autoload.php';

function sendPredictionRequest($endpoint, $organizationID) {
    if (!$organizationID) {
        die("Error: OrganizationID is not set.");
    }

    // Define JSON data payload (Modify as per API needs)
    $data = json_encode([
        "message" => "test request"
    ]);

    $ch = curl_init("http://13.53.41.87:5000/$endpoint");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  // Send JSON data
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "OrganizationID: $organizationID"
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Debugging output
    echo "<pre>";
    echo "Endpoint: $endpoint\n";
    echo "HTTP Status Code: $http_code\n";
    echo "Response: $response\n";
    echo "cURL Error: $curl_error\n";
    echo "</pre>";
}
