// Get the select element and the text area
const roleSelect = document.getElementById('role-name');
const descriptionTextArea = document.getElementById('role-description');

// Add event listener for when the user selects a role
roleSelect.addEventListener('change', function() {
  // Get the selected option
  const selectedOption = roleSelect.selectedOptions[0];

  // Get the description from the selected option's data attribute
  const roleDescription = selectedOption.getAttribute('data-description');

  // Set the description in the textarea
  descriptionTextArea.value = roleDescription;
});
