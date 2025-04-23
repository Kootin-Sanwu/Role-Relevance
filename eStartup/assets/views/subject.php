<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/job_roles.css">
  <title>Job Roles Form</title>
</head>
<body>
<div class="job-card">
    <h1>Organisation's Details</h1>
    <p>Enter the details of available job roles in your organization.</p>
    <form action="../actions/subject.php" method="POST" class="job-form">
      <div class="job-card-inputs">
        <label for="org-name">Organization Name</label>
        <input type="text" id="org-name" name="organization_name" required>

        <label for="org-description">Describe the organization's Current Priority</label>
        <label>(Optional – Strongly Encouraged)</label>
        <textarea id="org-description" name="organization_description" rows="4" required></textarea>

      </div>
      <button type="submit">Submit</button>
    </form>
  </div>
  <script src="../js/alerts.js" defer></script>
</body>
</html>
