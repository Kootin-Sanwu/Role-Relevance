<?php
$host = 'mysql-db';
$db   = 'RoleEvaluation';
$user = 'root';
$pass = 'K22.Kb16.Nk28.Ny27';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    $hostname = gethostname();
    echo "Host (container) name: " . $hostname . "<br>";
    echo "Trying to connect to MySQL host: " . $host . "<br>";
    echo "Database connection failed: " . $e->getMessage();
}







// $host = '127.0.0.1';
// $dbname = getenv("MYSQL_DATABASE") ?: "RoleEvaluation";
// $username = getenv("MYSQL_USER") ?: "root";
// $password = getenv("MYSQL_PASSWORD") ?: "K22.Kb16.Nk28.Ny27";

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//     echo "Database connection successful!";
// } catch (PDOException $e) {
//     $hostname = gethostname();
//     echo "Host (container) name: " . $hostname . "<br>";
//     echo "Trying to connect to MySQL host: " . $host . "<br>";
//     echo "Database connection failed: " . $e->getMessage();
// }