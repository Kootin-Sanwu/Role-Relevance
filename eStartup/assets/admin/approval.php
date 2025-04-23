<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../settings/connection.php";

// Validate token and action
if (isset($_GET['action']) && isset($_GET['token'])) {

    $action = $_GET['action'];
    $token = $_GET['token'];

    // Decode the token
    $data = json_decode(base64_decode($token), true);

    if (!$data || !isset($data['organization_email'])) {

        $error = "Invalid or expired token.";
    } else {

        $organizationEmail = htmlspecialchars($data['organization_email']);
        $hashedPassword = isset($data['hashed_password']) ? htmlspecialchars($data['hashed_password']) : '';
        $organizationName = isset($data['organization_name']) ? htmlspecialchars($data['organization_name']) : '';
        $jobTitles = isset($data['job_title']) ? (is_array($data['job_title']) ? $data['job_title'] : [$data['job_title']]) : [];
        $organizationDescription = isset($data['organization_description']) ? htmlspecialchars($data['organization_description']) : '';
    }
}
else {

    $error = "Done";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/verify_otp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Admin Approval</title>
</head>

<body>
    <div class="otp-card">
        <h1>Admin Approval</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php else: ?>
            <p><strong>Email:</strong> <?php echo $organizationEmail; ?></p>

            <p><strong>Action:</strong> Approve Registration</p>
            <form action="../actions/details.php" method="POST">

                <input type="hidden" name="organization_email" value="<?php 
                                                                            echo htmlspecialchars($organizationEmail);
                                                                        ?>">
                <input type="hidden" name="hashed_password" value="<?php 
                                                                        echo htmlspecialchars($hashedPassword); 
                                                                    ?>">
                <input type="hidden" name="organization_name" value="<?php 
                                                                            echo htmlspecialchars($organizationName); 
                                                                        ?>">
                <input type="hidden" name="organization_description" value="<?php 
                                                                            echo htmlspecialchars($organizationDescription); 
                                                                        ?>">                                                                        

                <?php foreach ($jobTitles as $job) : ?>
                    <input type="hidden" name="job_title[]" value="<?php echo htmlspecialchars($job); ?>">
                <?php endforeach; ?>

                <input type="hidden" name="Action" value="approve">
                <button type="submit">Approve</button>
            </form>

            <p><strong>Action:</strong> Decline Registration</p>
            <form action="../actions/approval.php" method="POST">
                <input type="hidden" name="organization_email" value="<?php echo htmlspecialchars($organizationEmail); ?>">
                <input type="hidden" name="Action" value="decline">
                <button type="submit">Decline</button>
            </form>

        <?php endif; ?>
    </div>
    
    <script src="../js/alerts.js" defer></script>

</body>

</html>