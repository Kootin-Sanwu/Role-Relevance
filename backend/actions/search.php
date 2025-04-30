<?php
require '../settings/connection.php'; // Include database connection

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    
    try {
        $stmt = $pdo->prepare("SELECT DISTINCT role_title FROM temp_job_roles WHERE role_title LIKE :search");
        $stmt->execute(['search' => "%$query%"]);
        
        $jobTitles = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only role_title column

        if (empty($jobTitles)) {
            echo json_encode(["error" => "No matching roles found"]);
        } else {
            echo json_encode($jobTitles);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
}
?>
