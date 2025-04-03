<?php
session_start();
header('Content-Type: application/json'); // Ensure JSON response

if (isset($_SESSION['OrganizationID'])) {
    echo json_encode(["Organizationid" => $_SESSION['OrganizationID']]);
} else {
    echo json_encode(["error" => "No organization ID found"]);
}
?>
