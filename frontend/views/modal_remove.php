<?php
$organizationID = $_COOKIE['organization_id'] ?? null;

if (!$organizationID) {
    echo "<p>Unauthorized access. Please log in.</p>";
    exit;
}

$frontend_url = getenv("FRONTEND_URL") ?: "http://localhost:3000";
$backend_url = getenv("BACKEND_URL") ?: "http://backend";

$apiUrl = "$backend_url/actions/get_org_info.php?organization_id=" . urlencode($organizationID);
$response = file_get_contents($apiUrl);

if ($response === false) {
    echo "<p>Failed to connect to backend.</p>";
    exit;
}

$roles = json_decode($response, true);

if (!is_array($roles)) {
    echo "<p>Invalid data received.</p>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Remove Job Role</title>
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/modal_details.css" />
</head>
<body>
  <div class="job-card">
    <h1>Remove a Job Role</h1>
    <p>Select the role you wish to delete from the organization.</p>

    <?php if (count($roles) === 0): ?>
      <p><strong>No roles found.</strong></p>
    <?php else: ?>
    <form action="http://localhost:8080/actions/details.php" method="POST" class="job-form">
      <input type="hidden" name="removing" value="Role Deletion Request" />

      <div class="job-card-inputs">
        <label for="roleSelect">Select Role to Remove:</label>
        <p></p>
        <select name="role_id" id="roleSelect" required>
          <!-- <option value="">-- Choose a role --</option> -->
          <option value="" disabled selected hidden>-- Choose a role --</option>
          <?php foreach ($roles as $role): ?>
            <option value="<?= $role['JobRoleID'] ?>">
              <?= htmlspecialchars($role['RoleName']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
        <i class="bi bi-trash"></i>
      </button>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>
