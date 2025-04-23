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
        $organizationDescription = $_POST['organization_description'];

        // // Validate input fields
        // if (empty($_POST['organization_name']) || empty($_POST['job_title']) || empty($_POST['organization_email']) || empty($_POST['hashed_password']) || empty($_SESSION['organization_description'])) {
        //     header("Location: ../admin/approval.php?msg=" . urlencode("Please fill in all required fields."));
        // }

        echo ($_POST['organization_name']);
        echo ($_POST['job_title']);
        echo ($_POST['organization_email']);
        echo ($_POST['hashed_password']);
        echo ($_POST['organization_description']);

        // Check if organization already exists
        $stmt = $pdo->prepare("SELECT OrganizationID, Description FROM Organizations WHERE Email = ?");
        $stmt->execute([$organizationEmail]);
        $row = $stmt->fetch();

        if ($row) {
            // Fetch existing OrganizationID and Description
            $organizationID = $row['OrganizationID'];
            $newOrganizationDescription = $row['Description'];
        } else {
            // Insert new organization
            $stmt = $pdo->prepare("INSERT INTO Organizations (Name, Email, Password, Description) VALUES (?, ?, ?, ?)");

            if (!$stmt->execute([$organizationName, $organizationEmail, $hashedPassword, $organizationDescription])) {
                header("Location: ../dist/metrics.php");
                exit();
            }

            $organizationID = $pdo->lastInsertId(); // Get the newly inserted OrganizationID
            $organizationDescription = $organizationDescription; // Use the newly provided description
        }

        // Insert job roles with descriptions
        $stmt = $pdo->prepare("INSERT INTO JobRoles (OrganizationID, RoleName, RoleDescription) VALUES (?, ?, ?)");
        $desc_stmt = $pdo->prepare("SELECT combined_description FROM temp_job_roles WHERE role_title = ?");

        foreach ($jobTitles as $jobTitle) {
            $jobTitle = trim($jobTitle);
        
            if (!empty($jobTitle)) {
                // Fetch the combined description from the temp_job_roles table
                $desc_stmt->execute([$jobTitle]);
                $desc_row = $desc_stmt->fetch();
                $roleDescription = $desc_row ? $desc_row['combined_description'] : "Description not available";
            
                // Combine the organization description with the job role description
                $combinedDescription = $organizationDescription . " " . $roleDescription . " " . $organizationName;
            
                // Insert into JobRoles table, including the combined description
                $stmt->execute([$organizationID, $jobTitle, $combinedDescription]);
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

} else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adding"]) && $_POST["adding"] === "Role Title Submission") {
    $message = $_POST["adding"];
    echo "<h2>Message: $message</h2>";
    
    session_start();
    $organizationID = $_SESSION["OrganizationID"]; // Ensure this is correctly set during login
    
    // Sanitize input
    $roleTitle = trim($_POST["job_title"][0]); // Assumes one role for now
    
    if (!empty($roleTitle)) {
        try {
            // First check if the role already exists for this organization
            $checkStmt = $pdo->prepare("
                SELECT COUNT(*) FROM JobRoles 
                WHERE OrganizationID = :organizationID AND RoleName = :roleTitle
            ");
            $checkStmt->bindParam(':organizationID', $organizationID, PDO::PARAM_INT);
            $checkStmt->bindParam(':roleTitle', $roleTitle, PDO::PARAM_STR);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() > 0) {
                // Role already exists
                echo "
                <script>
                    alert('Role \"$roleTitle\" already exists for your organization!');
                    // Optional: You can either keep the modal open or close it
                    window.parent.location.reload(); // Reload parent and close modal
                </script>
                ";
                exit();
            }
            
            // If we get here, the role doesn't exist, so insert it
            $stmt = $pdo->prepare("
                INSERT INTO JobRoles (OrganizationID, RoleName, RoleDescription)
                VALUES (:organizationID, :roleTitle, :roleDescription)
            ");
            $stmt->bindParam(':organizationID', $organizationID, PDO::PARAM_INT);
            $stmt->bindParam(':roleTitle', $roleTitle, PDO::PARAM_STR);
            $stmt->bindParam(':roleDescription', $roleTitle, PDO::PARAM_STR); // Same as roleTitle
            
            if ($stmt->execute()) {
                echo "
                <script>
                    alert('Role \"$roleTitle\" added successfully!');
                    window.parent.location.reload(); // Reload parent and close modal
                </script>
                ";
                exit();
            } else {
                echo "<p>Failed to insert role.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Database error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Please enter a valid role title.</p>";
    }

} else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adding"]) && $_POST["adding"] === "Role Description Submission") {

    // Retrieve session data
    $organizationID = $_SESSION["OrganizationID"];  // Ensure this is set after login
    $roleName = trim($_POST["role_name"]);
    $roleDescription = trim($_POST["role_description"]);

    // Basic validation
    if (!empty($roleName) && !empty($roleDescription)) {
        try {
            // Check if the role exists in the organization's roles
            $stmt = $pdo->prepare("SELECT * FROM JobRoles WHERE OrganizationID = :orgID AND RoleName = :roleName");
            $stmt->bindParam(':orgID', $organizationID, PDO::PARAM_INT);
            $stmt->bindParam(':roleName', $roleName, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Role exists, update the description
                $stmt = $pdo->prepare("UPDATE JobRoles SET RoleDescription = :roleDescription WHERE OrganizationID = :orgID AND RoleName = :roleName");
                $stmt->bindParam(':roleDescription', $roleDescription, PDO::PARAM_STR);
                $stmt->bindParam(':orgID', $organizationID, PDO::PARAM_INT);
                $stmt->bindParam(':roleName', $roleName, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    echo "
                        <script>
                          alert('Description for \"$roleName\" added successfully!');
                          window.parent.location.reload(); // optional if you want a full refresh
                        </script>
                    ";
                } else {
                    echo "<p>Failed to update the description.</p>";
                }
            } else {
                echo "<p>Role not found in your organization.</p>";
            }

        } catch (PDOException $e) {
            echo "<p>Database error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Please select a role and enter a valid description.</p>";
    }

} else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["removing"]) && $_POST["removing"] === "Role Deletion Request") {
    $jobRoleID = $_POST["role_id"] ?? null;
    $organizationID = $_SESSION['OrganizationID'] ?? null;

    if ($jobRoleID && $organizationID) {
        try {
            // Ensure role belongs to organization
            $checkStmt = $pdo->prepare("SELECT JobRoleID FROM JobRoles WHERE JobRoleID = :roleID AND OrganizationID = :orgID");
            $checkStmt->execute([':roleID' => $jobRoleID, ':orgID' => $organizationID]);

            if ($checkStmt->rowCount() === 1) {
                $deleteStmt = $pdo->prepare("DELETE FROM JobRoles WHERE JobRoleID = :jobRoleID");
                $deleteStmt->execute([':jobRoleID' => $jobRoleID]);

                echo "<script>
                        alert('Role deleted successfully.');
                        window.parent.location.reload();
                      </script>";
            } else {
                echo "<p>Role not found or does not belong to your organization.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Invalid role selection.</p>";
    }

} else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve user email from session
    if (!isset($_SESSION['organization_email'])) {
        die("Organization not logged in");
    }
    
    $organizationEmail = $_SESSION['organization_email'];

    $hashedPassword = $_SESSION['hashed_password'];
    
    // Store organization name in session
    // $_SESSION['organization_name'] = $_POST['organization_name'];
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
?>
