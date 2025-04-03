<?php
session_start(); // Start the session
include "../settings/connection.php"; // Include the database connection

// Include the database connection file
include_once "../settings/connection.php";

// Include the OTP generation function
include_once "../functions/send_OTP.php";

// Include the approval link generation function
include_once "../functions/link.php";

// Include the approval notification function
include_once "../functions/approval.php";

// Include the approval notification function
include_once "../functions/notify.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['Action'])) {

    if ($_POST['Action'] === 'approve') {
        
        $organizationName = trim($_POST['organization_name']);
        $jobTitles = isset($_POST['job_title']) ? (array)$_POST['job_title'] : [];

        $organizationEmail = $_POST['organization_email'];
        $hashedPassword = $_POST['hashed_password'];

        // Validate input fields
        if (empty($_POST['organization_name']) || empty($_POST['job_title']) || empty($_POST['organization_email']) || empty($_POST['hashed_password'])) {
            header("Location: ../admin/approval.php?msg=" . urlencode("Please fill in all required fields."));
        }

        // Check if organization already exists
        $stmt = $pdo->prepare("SELECT OrganizationID FROM Organizations WHERE Email = ?");
        $stmt->execute([$organizationEmail]);
        $row = $stmt->fetch();

        if ($row) {
            // Fetch existing OrganizationID
            $organizationID = $row['OrganizationID'];
        } else {
            // Insert new organization
            $stmt = $pdo->prepare("INSERT INTO Organizations (Name, Email, Password) VALUES (?, ?, ?)");
        
            if (!$stmt->execute([$organizationName, $organizationEmail, $hashedPassword])) {
                header("Location: ../dist/metrics.html");
                exit();
            }
        
            $organizationID = $pdo->lastInsertId(); // Get the newly inserted OrganizationID
        }

        // Insert job roles with descriptions
        $stmt = $pdo->prepare("INSERT INTO JobRoles (OrganizationID, RoleName, RoleDescription) VALUES (?, ?, ?)");
        $desc_stmt = $pdo->prepare("SELECT combined_description FROM temp_job_roles WHERE role_title = ?");

        foreach ($jobTitles as $jobTitle) {
            $jobTitle = trim($jobTitle);

            if (!empty($jobTitle)) {
                // Fetch the combined description
                $desc_stmt->execute([$jobTitle]);
                $desc_row = $desc_stmt->fetch();
                $roleDescription = $desc_row ? $desc_row['combined_description'] : "Description not available";
            
                // Insert into JobRoles table
                $stmt->execute([$organizationID, $jobTitle, $roleDescription]);
            } else {
                echo ("Not inserting empty roles into the database");
            }
        }

        // Send approval email
        if (sendApprovalEmail($organizationEmail)) {
            header("Location: ../admin/approval.php?msg=" . urlencode("User approved and registered successfully. Notification sent."));
            exit();
        } else {
            header("Location: ../admin/approval.php?msg=" . urlencode("User approved and registered successfully. Email notification failed."));
            exit();
        }
    } else if ($_POST['Action'] === 'decline') {

        $organizationEmail = $data['organization_email'];
        header("Location: ../../index.php?msg=User registration declined for email: $organizationEmail");
        exit();
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve user email from session
    if (!isset($_SESSION['organization_email'])) {
        die("Organization not logged in");
    }
    
    $organizationEmail = $_SESSION['organization_email'];

    $hashedPassword = $_SESSION['hashed_password'];
    
    // Store organization name in session
    $_SESSION['organization_name'] = $_POST['organization_name'];
    $organizationName = $_SESSION['organization_name'];

    // Store job roles in session
    $_SESSION['job_title'] = $_POST['job_title'];
    $jobTitles = $_SESSION['job_title'];

    // Generate OTP
    $OTP = rand(100000, 999999);
    $_SESSION['OTP'] = $OTP;
    $_SESSION['OTP_timestamp'] = time();

    // Send OTP email
    sendOTP($organizationEmail, $OTP);

    header("Location: ../views/verify_otp.php");
}
