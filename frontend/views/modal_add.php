<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/modal_details.css">
  <title>Job Roles Form</title>
</head>
<body>
  <div class="job-card">
    <h1>Job Roles Submission</h1>
    <p>Enter the name of one role in your organization.</p>
    <form action="../../backend/actions/details.php" method="POST" class="job-form">
        <input type="hidden" name="adding" value="Role Title Submission">
        <div class="job-card-inputs">

          <div class="job-entry">
            <label for="job-title-1">Role Title</label>
            <input type="text" id="job-title-1" name="job_title[]" list="job-titles" required>
            <datalist id="job-titles"></datalist>

            <button type="button" style="display:none;">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                   stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
            </button>
          </div>

        </div>

       <!-- Replace the submit button -->
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
