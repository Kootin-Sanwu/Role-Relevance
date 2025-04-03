<?php

// Database connection parameters
$host = 'localhost';
$dbname = 'RoleEvaluation';
$username = 'root';
$password = '';

try {

    // Establish a connection to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Fetch results as associative arrays

} catch (PDOException $e) {

    // If connection fails, terminate script and display error message
    die("Database connection failed: " . $e->getMessage());
}
