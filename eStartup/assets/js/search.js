document.addEventListener("DOMContentLoaded", function () {
  const jobForm = document.querySelector(".job-form");
  const suggestionsContainer = document.createElement("div");
  let isValidSelection = false; // Flag to track valid selections

  suggestionsContainer.id = "job-title-suggestions";
  document.body.appendChild(suggestionsContainer);

  function fetchJobSuggestions(inputElement) {
      const query = inputElement.value.trim();
      if (query.length < 2) {
          suggestionsContainer.innerHTML = "";
          return;
      }

      fetch(`../actions/search.php?q=${encodeURIComponent(query)}`)
          .then(response => response.json())
          .then(data => {
              suggestionsContainer.innerHTML = "";
              if (data.error) {
                  console.warn(data.error);
                  return;
              }

              data.forEach(job => {
                  const suggestionItem = document.createElement("div");
                  suggestionItem.classList.add("suggestion-item");
                  suggestionItem.textContent = job;

                  suggestionItem.addEventListener("mousedown", function (event) {
                      event.preventDefault(); // Prevent losing focus before click event
                      inputElement.value = job; // REPLACE input with selected value
                      isValidSelection = true; // Mark selection as valid
                      suggestionsContainer.innerHTML = ""; // Hide suggestions
                  });

                  suggestionsContainer.appendChild(suggestionItem);
              });

              const rect = inputElement.getBoundingClientRect();
              suggestionsContainer.style.position = "absolute";
              suggestionsContainer.style.left = `${rect.left}px`;
              suggestionsContainer.style.top = `${rect.bottom + window.scrollY}px`;
              suggestionsContainer.style.width = `${rect.width}px`;
          })
          .catch(error => console.error("Error fetching job titles:", error));
  }

  jobForm.addEventListener("input", function (event) {
      if (event.target.matches("input[name='job_title[]']")) {
          isValidSelection = false; // Reset flag on manual input
          fetchJobSuggestions(event.target);
      }
  });

  jobForm.addEventListener("blur", function (event) {
      if (event.target.matches("input[name='job_title[]']")) {
          setTimeout(() => {
              const enteredValue = event.target.value.trim();
              const options = [...suggestionsContainer.getElementsByClassName("suggestion-item")].map(item => item.textContent);

              if (enteredValue && !isValidSelection && !options.includes(enteredValue)) {
                  alert("⚠ Warning: This role does not exist in our database. Please check the spelling or select a valid role.");
                  event.target.value = "";
              }
          }, 200); // Delay to allow click event to register first
      }
  }, true);
});
