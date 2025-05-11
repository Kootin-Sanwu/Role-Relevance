<?php
session_start();
$organizationID = $_COOKIE['organization_id'] ?? null;

if (!$organizationID) {
    echo "Unauthorized access.";
    exit;
}

$backend_url = getenv("BACKEND_URL") ?: "http://backend";
$apiUrl = "$backend_url/actions/get_org_info.php?organization_id=" . urlencode($organizationID);
$response = @file_get_contents($apiUrl);

$roles = [];
if ($response !== false) {
    $roles = json_decode($response, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/modal_details.css" />
  <title>Add Role Description</title>
</head>
<body>
  <div class="job-card">
    <h1>Role Description</h1>
    <p>Add a brief description for one of your organization’s roles.</p>

    <form action="http://localhost:8080/actions/details.php" method="POST" class="job-form">
      <input type="hidden" name="adding" value="Role Description Submission" />

      <div class="job-card-inputs">
        <div class="job-entry">
          <label for="role-name">Select Role</label>
          <select id="role-name" name="role_name" required>
            <option value="">-- Choose a role --</option>
            <?php foreach ($roles as $role): ?>
              <option value="<?= htmlspecialchars($role['RoleName']) ?>" data-description="<?= htmlspecialchars($role['RoleDescription']) ?>">
                <?= htmlspecialchars($role['RoleName']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="job-entry">
          <label for="role-description">Description</label>
          <textarea id="role-description" name="role_description" rows="4" required></textarea>
        </div>
      </div>

      <button type="submit" class="btn-icon btn-submit">
        <i class="bi bi-check-lg"></i>
      </button>
    </form>
  </div>

  <script src="../javascript/alerts.js" defer></script>
  <script src="../javascript/search.js" defer></script>
  <script src="../javascript/role_desc.js" defer></script>
</body>
</html>
