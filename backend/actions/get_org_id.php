<?php
header("Access-Control-Allow-Origin: http://13.60.64.199:3000");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");

session_start();
header('Content-Type: application/json'); // Ensure JSON response

if (isset($_SESSION['OrganizationID'])) {
    echo json_encode(["Organizationid" => $_SESSION['OrganizationID']]);
} else {
    echo json_encode(["error" => "No organization ID found"]);
}
?>
