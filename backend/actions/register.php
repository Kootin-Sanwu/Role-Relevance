<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];

    // Retrieve form inputs
    $organizationEmail = trim($_POST['organization_email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $_SESSION['organization_email'] = $organizationEmail;
    $_SESSION['password'] = $password;
    $_SESSION['confirm_password'] = $confirmPassword;

    // Validate required fields
    if (empty($organizationEmail) || empty($password) || empty($confirmPassword)) {

        // header("Location: ../../frontend/views/login.php?msg=" . urlencode("All fields must be filled"));
        // exit();

        header("Location: $frontend_url/views/login.php?msg=" . urlencode("All fields must be filled"));
        exit();
    }

    // Validate email format
    if (!filter_var($organizationEmail, FILTER_VALIDATE_EMAIL)) {

        header("Location: $frontend_url/views/login.php?msg=Please enter a valid email address.");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {

        header("Location: $frontend_url/views/login.php?msg=" . urlencode("Passwords do not match."));
        exit();
    }

    // Validate password strength
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordRegex, $password)) {

        header("Location: $frontend_url/views/login.php?msg=Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
        exit();
    }

    try {
        // Check for existing email in the database
        $stmt = $pdo->prepare("SELECT Email FROM Organizations WHERE Email = :Email");
        $stmt->bindParam(':Email', $organizationEmail);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            header("Location: $frontend_url/views/login.php?msg=" . urlencode("Email already exists. Please use a different email."));
            exit();
        }

        // Do not move this session. The placement is critical to the execution of this code
        $_SESSION['registering'] = "registering";

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $_SESSION['hashed_password'] = $hashedPassword;

        if (headers_sent($file, $line)) {
            echo "Headers already sent in $file on line $line";
            exit();
        }
        
        // header("Location: ../views/details.php");
        header("Location: $frontend_url/views/subject.php");
        exit();
    } catch (PDOException $e) {

        header("Location: $frontend_url/views/login.php?msg=Database error: " . $e->getMessage());
        exit();
    }
} else if (isset($_GET['msg'])) {

    $message = $_GET['msg'];

    if ($message === "registering") {

        $organizationEmail = $_SESSION['organization_email'];
        $hashedPassword = $_SESSION['hashed_password'];
        $organizationName = $_SESSION['organization_name'];
        $jobTitles = $_SESSION['job_title'];
        $organizationDescription = $_SESSION['organization_description'];

        try {
            // Send email to admin for approval
            $adminEmail = "kootin.nuamah@ashesi.edu.gh";
            $adminApprovalLink = generateApprovalLink($organizationEmail, $hashedPassword, $organizationName, $jobTitles, $organizationDescription);
            $adminDeclineLink = generateDeclineLink($organizationEmail);
            notifyAdmin($adminEmail, $organizationEmail, $adminApprovalLink, $adminDeclineLink);

            // Redirect with success message
            header("Location: $frontend_url/views/login.php?msg=" . urlencode("Your registration request has been sent for approval."));
            exit();
        } catch (PDOException $e) {
            // Redirect with database error message
            header("Location: $frontend_url/views/login.php?msg=" . urlencode("Database error: " . $e->getMessage()));
            exit();
        }
    } else {
        echo "The message is: " . htmlspecialchars($message);
    }
} else {
    echo "No message found. No requirement met.";
}
