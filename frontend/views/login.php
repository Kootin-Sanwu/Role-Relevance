<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login/Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="icon" href="https://www.google.com/favicon.ico" type="image/x-icon">
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <!-- <form action="../../backend/actions/register.php" method="POST"> -->
            <form action="http://localhost:8080/actions/register.php" method="POST">
                <h1>Create Account</h1>
                <div class="infield">
                    <input type="email" placeholder="Organization Email" name="organization_email" />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="password" />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Confirm Password" name="confirm_password" />
                    <label></label>
                </div>
                <button type="submit">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <!-- <form action="../../backend/actions/login.php" method="POST"> -->
            <form action="http://localhost:8080/actions/login.php" method="POST">
                <h1>Sign in</h1>
                <div class="infield">
                    <input type="email" placeholder="Organization Email" name="organization_email" required/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="password" required/>
                    <label></label>
                </div>
                <a href="../views/forgot_password.php" class="forgot">Forgot your password?</a>
                <button>Sign In</button>
            </form>    
        </div>
        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome!</h1>
                    <br>
                    <p>To keep connected with us please login with your info</p>
                    <br><br>
                    <button>Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <br>
                    <p>Enter your organization's details to start your journey</p>
                    <br><br>
                    <button>Sign Up</button>
                </div>
            </div>
            <button id="overlayBtn"></button>
        </div>
    </div>

    <script src="../javascript/signup.js"></script>
    <script src="../javascript/login.js"></script>
    <script src="../javascript/alerts.js" defer></script>
</body>

</html>