<?php
session_start(); // Start session

// If session is invalid, destroy it and force login
if (!isset($_SESSION['organization_id'])) {
    session_destroy();
    header("Location: ../views/login.php?error=not_logged_in"); // Redirect to login
    exit();
}

// Don't force redirect logged-in users unless they are on login.php
$requestedPage = basename($_SERVER['PHP_SELF']); // Get the current file name
if ($requestedPage === "login.php" && isset($_SESSION['organization_id'])) {
    header("Location: ../dist/metrics.html"); // Redirect only if they try to access login.php while logged in
    exit();
}

// Function to sanitize user inputs
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

// Function to hash passwords
function hashPassword($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function to verify passwords
function verifyPassword($password, $hashedPassword)
{
    return password_verify($password, $hashedPassword);
}
