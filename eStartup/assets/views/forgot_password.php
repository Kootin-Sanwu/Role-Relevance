<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/forgot_password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Forgot Password</title>
</head>

<body>
    <div class="email-card">
        <h1>FORGOT PASSWORD</h1>
        <p>Enter your email address to receive a password reset link</p>

        <!-- Email Input Form -->
        <form action="../actions/forgot_password.php" method="POST" class="email-form">
            <div class="email-card-inputs">
                <input type="email" placeholder="Email" name="organization_email" required>
            </div>

            <!-- Hidden Input Field -->
            <input type="hidden" name="forgot_password" value="forgot_password">

            <button type="submit">VERIFY</button>
        </form>
    </div>

    <script src="../js/alerts.js" defer></script>
</body>

</html>