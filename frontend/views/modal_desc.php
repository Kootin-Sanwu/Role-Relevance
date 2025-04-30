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

    <form action="../../backend/actions/details.php" method="POST" class="job-form">
      <input type="hidden" name="adding" value="Role Description Submission" />

      <div class="job-card-inputs">
        <!-- Role Name (dropdown for existing roles) -->
        <div class="job-entry">
          <label for="role-name">Select Role</label>
          <select id="role-name" name="role_name" required>
            <option value="">-- Choose a role --</option>
            <?php
              session_start();
              require_once "../../backend/settings/connection.php"; // Adjust path to your DB connection
              $organizationID = $_SESSION["OrganizationID"];

              $stmt = $pdo->prepare("SELECT RoleName FROM JobRoles WHERE OrganizationID = :orgID");
              $stmt->bindParam(':orgID', $organizationID, PDO::PARAM_INT);
              $stmt->execute();
              $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

              foreach ($roles as $role) {
                echo "<option value=\"" . htmlspecialchars($role['RoleName']) . "\">" . htmlspecialchars($role['RoleName']) . "</option>";
              }
            ?>
          </select>
        </div>

        <!-- Description Textarea -->
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

  <script src="../javascript/job_roles.js" defer></script>
  <script src="../javascript/alerts.js" defer></script>
  <script src="../javascript/search.js" defer></script>
</body>
</html>
