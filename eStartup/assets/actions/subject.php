<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the organization name and description from the form
    $organizationName = isset($_POST['organization_name']) ? $_POST['organization_name'] : '';
    $organizationDescription = isset($_POST['organization_description']) ? $_POST['organization_description'] : '';

    // Store these values in the session
    $_SESSION['organization_name'] = $organizationName;
    $_SESSION['organization_description'] = $organizationDescription;

    // For debugging purposes (optional)
    echo "Organization Name: " . $_SESSION['organization_name'] . "<br>";
    echo "Organization Description: " . $_SESSION['organization_description'] . "<br>";

    // Redirect or send a success response after storing the session data
    header('Location: ../views/details.php');  // Redirect to a success page (you can create this page)
    exit();
}
?>
