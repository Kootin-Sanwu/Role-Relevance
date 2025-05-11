<?php

session_start(); // Start the session
include "../settings/connection.php"; // Include the database connection

$frontend_url = getenv("FRONTEND_URL") ?: "http://13.60.64.199:3000";
$backend_url = getenv("BACKEND_URL") ?: "http://13.60.64.199:8080";

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $frontend_url/views/job_roles.php");
    exit;
}

// Retrieve and sanitize form inputs
$organizationName = trim($_POST['organization_name'] ?? '');
$jobTitles = $_POST['job_title'] ?? [];

// Ensure organization name and at least one job title are provided
if (empty($organizationName) || empty($jobTitles)) {
    header("Location: $frontend_url/views/job_roles.php?msg=" . urlencode('Organization name and at least one job title are required.'));
}

// Check if the Organization ID exists in the session
if (!isset($_SESSION['OrganizationID'])) {
    header("Location: $frontend_url/views/job_roles.php?msg=" . urlencode('No organization found in session. Please log in again.'));
}

$organizationID = $_SESSION['OrganizationID'];

try {
    // Update the organization's name where the ID matches
    $stmt = $pdo->prepare("UPDATE Organizations SET Name = ? WHERE OrganizationID = ?");
    $stmt->execute([$organizationName, $organizationID]);

    // Prepare the INSERT statement for job roles, ensuring no duplicates
    $stmtJob = $pdo->prepare("INSERT IGNORE INTO JobRoles (OrganizationID, RoleName, CurrentScore) VALUES (?, ?, ?)");

    foreach ($jobTitles as $jobTitle) {
        $jobTitle = trim($jobTitle);
        if (!empty($jobTitle)) {
            $stmtJob->execute([$organizationID, $jobTitle, 0.00]);
        }
    }

    // Redirect with success message
    header("Location: $frontend_url/index.php?msg=" . urlencode('Job roles submitted successfully.'));
    exit();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
