<?php
require_once __DIR__ . '/../settings/connection.php';
header('Content-Type: application/json');

$organizationID = $_GET['organization_id'] ?? null;

if (!$organizationID) {
    echo json_encode(["error" => "Missing organization ID"]);
    exit;
}

$stmt = $pdo->prepare("SELECT JobRoleID, RoleName, RoleDescription FROM JobRoles WHERE OrganizationID = :orgID");
$stmt->execute([':orgID' => $organizationID]);
$roles = $stmt->fetchAll();

// echo json_encode(["Organizationid" => $_GET['organization_id']]);
echo json_encode($roles);
?>
