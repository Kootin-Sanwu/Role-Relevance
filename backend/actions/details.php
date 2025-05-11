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

$frontend_url = getenv("FRONTEND_URL") ?: "http://localhost:3000";
$backend_url = getenv("BACKEND_URL") ?: "http://localhost:8080";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['Action'])) {

    if ($_POST['Action'] === 'approve') {
        
        $organizationName = trim($_POST['organization_name']);
        $jobTitles = isset($_POST['job_title']) ? (array)$_POST['job_title'] : [];

        $organizationEmail = $_POST['organization_email'];
        $hashedPassword = $_POST['hashed_password'];
        $organizationDescription = $_POST['organization_description'];

        $organizationEmail = trim($organizationEmail); // Sanitize input
        $organizationDescription = trim($organizationDescription); // Sanitize input

        $_SESSION['organization_email'] = $organizationEmail; // Store email in session
        $_SESSION['organization_name'] = $organizationName; // Store organization name in session
        $_SESSION['hashed_password'] = $hashedPassword; // Store hashed password in session

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
                header("Location: $frontend_url/views/metrics.php");
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
            header("Location: $frontend_url/views/approval.php?msg=" . urlencode("User approved and registered successfully. Notification sent."));
            exit();
        } else {
            header("Location: $frontend_url/views/approval.php?msg=" . urlencode("User approved and registered successfully. Email notification failed."));
            exit();
        }

        
        if (headers_sent($file, $line)) {
            echo "Headers already sent in $file on line $line";
            exit();
        }
    } else if ($_POST['Action'] === 'decline') {

        $organizationEmail = $data['organization_email'];
        header("Location: $frontend_url/index.php?msg=User registration declined for email: $organizationEmail");
        exit();
    }

} else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adding"]) && $_POST["adding"] === "Role Title Submission") {
    
    // session_start();
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
                    alert('Role \"$roleTitle\" already exists!');
                    window.parent.postMessage({ type: 'roleExists' }, '*');
                  </script>
                ";
                exit();
            }

            // If we get here, the role doesn't exist, so insert it
            $add_role_desc_stmt = $pdo->prepare("SELECT combined_description FROM temp_job_roles WHERE role_title = ?");            
            $roleTitle = trim($roleTitle);
            
            if (!empty($roleTitle)) {

                $add_org_desc_stmt = $pdo->prepare("SELECT Description FROM Organizations WHERE Email = ?");
                $add_org_desc_stmt->execute([$_SESSION['organization_email']]);
                $org_decs_row = $add_org_desc_stmt->fetch();
                $organizationDescription = $org_decs_row ? $org_decs_row['Description'] : "Description not available";
                
                // Fetch the combined description from the temp_job_roles table
                $add_role_desc_stmt->execute([$roleTitle]);
                $role_desc_row = $add_role_desc_stmt->fetch();
                $roleDescription = $role_desc_row ? $role_desc_row['combined_description'] : "Description not available";
                
                $organizationName = $_SESSION['organization_name'];

                // Combine the organization description with the job role description
                $combinedDescription = $organizationDescription . " " . $roleDescription . " " . $organizationName ." " . $roleTitle;

            } else {
                echo ("Not combining empty roles into the database");
            }
            
            $stmt = $pdo->prepare("
                INSERT INTO JobRoles (OrganizationID, RoleName, RoleDescription)
                VALUES (:organizationID, :roleTitle, :roleDescription)
            ");
            $stmt->bindParam(':organizationID', $organizationID, PDO::PARAM_INT);
            $stmt->bindParam(':roleTitle', $roleTitle, PDO::PARAM_STR);
            $stmt->bindParam(':roleDescription', $combinedDescription, PDO::PARAM_STR); // Same as roleTitle

            if ($stmt->execute()) {
                echo "
                  <script>
                    alert('Role \"$roleTitle\" added successfully!');
                    window.parent.postMessage({ type: 'roleAdded' }, '*');
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
                        window.parent.postMessage({ type: 'roleDescriptionAdded' }, '*');
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

                echo "
                  <script>
                    alert('Role \"$roleName\" removed successfully!');
                    window.parent.postMessage({ type: 'roleRemoved' }, '*');
                  </script>
                ";
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

    header("Location: $frontend_url/views/verify_otp.php");

}
?>
