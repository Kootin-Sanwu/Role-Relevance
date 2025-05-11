<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Include the database connection file
include_once "../settings/connection.php";

// Include the OTP generation function
include_once "../functions/send_OTP.php";

// Include the predict function
include_once "../functions/predict.php"; 

$frontend_url = getenv("FRONTEND_URL") ?: "http://localhost:3000";
$backend_url = getenv("BACKEND_URL") ?: "http://localhost:8080";
$mlapi_url = getenv("ML_API_URL") ?: "http://localhost:5000";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['signing_in'] = "signing_in";
    $organizationEmail = trim($_POST['organization_email']);
    $errors = [];

    if (empty($_POST['organization_email'])) {

        $errors[] = "Email is required.";
    } else {

        $organizationEmail = filter_var($organizationEmail, FILTER_SANITIZE_EMAIL);

        if (!filter_var($organizationEmail, FILTER_VALIDATE_EMAIL)) {

            $errors[] = "Invalid email format.";
        }
    }

    if (empty($_POST['password'])) {

        $errors[] = "Password is required.";
    }

    if (empty($errors)) {

        $password = $_POST['password'];

        try {

            $stmt = $pdo->prepare("SELECT OrganizationID, Password, Name FROM Organizations WHERE Email = :Email");
            $stmt->bindParam(":Email", $organizationEmail, PDO::PARAM_STR);
            $stmt->execute();
    
            if ($stmt->rowCount() === 1) {
                $row = $stmt->fetch();
    
                if (password_verify($password, $row['Password'])) {
                    $_SESSION['OrganizationID'] = $row['OrganizationID'];
                    $_SESSION['organization_email'] = $organizationEmail;
                    $_SESSION['organization_name'] = $row['Name'];

                    setcookie("organization_name", $_SESSION['organization_name'], time() + 3600, "/", "localhost", false, true);
                    setcookie("organization_id", $_SESSION['OrganizationID'], time() + 3600, "/", "localhost", false, true);
                    setcookie("organization_email", $_SESSION['organization_email'], time() + 3600, "/", "localhost", false, true);

                    $organizationID = $_SESSION["OrganizationID"];

                    if (!$organizationID) {
                        die("Error: OrganizationID is not set.");
                    }

                    // Set up headers
                    $options = [
                        "http" => [
                            "header" => "Organization-ID: $organizationID\r\n",
                            "method" => "GET"
                        ]
                    ];

                    // Generate OTP
                    $OTP = rand(100000, 999999);
                    $_SESSION['OTP'] = $OTP;
                    $_SESSION['OTP_timestamp'] = time();

                    // Send OTP email
                    sendOTP($organizationEmail, $OTP);

                    // After login, call the Flask API
                    $headers = [
                        'Content-Type: application/json',
                        'Organizationid: ' . $organizationID  
                    ];

                    // $ch = curl_init('http://127.0.0.1:5000/get_scores_1');
                    $ch = curl_init("$mlapi_url/get_scores_1");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([]));

                    $response = curl_exec($ch);
                    curl_close($ch);

                    // echo $organizationID;
                    // echo $response;

                    // Redirect to OTP verification
                    $message = "Successfully signed in. Kindly check your email for the OTP.";
                    header("Location: $frontend_url/views/verify_otp.php?msg=" . urlencode($message));
                    exit();
                } else {

                    header("Location: $frontend_url/views/login.php?msg=" . urlencode('Invalid Email or Password.'));
                    exit();
                }
            } else {

                header("Location: $frontend_url/views/login.php?msg=" . urlencode('Invalid email or password.'));
                exit();
            }
        } catch (PDOException $e) {

            header("Location: $frontend_url/views/login.php?msg=Database error: " . $e->getMessage());
            exit();
        }
    } else {

        header("Location: $frontend_url/views/login.php?msg=" . urlencode(implode(" ", $errors)));
        exit();
    }
} else if (isset($_GET['msg'])) {

    $message = $_GET['msg'];

    if ($message === "signing_in") {

        $organizationName = urlencode($_SESSION['organization_name']);
        header("Location: $frontend_url/views/metrics.php?organization_name=$organizationName");
    } else {

        echo "The message is: " . htmlspecialchars($message);
    }
}
 else {
    header("Location: $frontend_url/views/login.php?msg=" . urlencode('Invalid request method.'));
    exit();
};
