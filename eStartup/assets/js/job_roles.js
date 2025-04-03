document.getElementById('add-job').addEventListener('click', function() {
    let container = document.querySelector('.job-card-inputs');
    let card = document.querySelector('.job-card');
    let nextIndex = container.querySelectorAll('.job-entry').length + 1;

    // Limit to 10 job roles
    if (nextIndex > 10) {
        alert('You can only add up to 10 job roles.');
        return;
    }

    // Get the current card height
    const initialHeight = card.offsetHeight;

    let jobEntry = document.createElement('div');
    jobEntry.classList.add('job-entry');

    const minusIconSvg = `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="8" y1="12" x2="16" y2="12"></line>
        </svg>
    `;

    jobEntry.innerHTML = `
        <label for="job-title-${nextIndex}">Role Title</label>
        <input type="text" id="job-title-${nextIndex}" name="job_title[]" required>
        <button type="button" class="remove-button">
            ${minusIconSvg}
        </button>
    `;

    container.appendChild(jobEntry);

    // Set new height after adding the element
    const newHeight = card.scrollHeight;
    card.style.height = `${initialHeight}px`;
    
    // Trigger reflow so the change is recognized
    card.offsetHeight;
    
    // Animate to the new height (the CSS transition on .job-card handles this)
    card.style.height = `${newHeight}px`;

    // Add listener for the remove button on the new job entry
    jobEntry.querySelector('.remove-button').addEventListener('click', function() {
        // Get the current height before removal
        const initialHeight = card.offsetHeight;

        // Add the removing class to trigger the slideUp animation (Reverse effect)
        // jobEntry.classList.add('removing');

        // Animate the card height
        card.style.height = `${initialHeight - jobEntry.offsetHeight - 8}px`;

        // Wait for the animation to finish (400ms) before removing the element
        setTimeout(() => {
            container.removeChild(jobEntry);
        }, 100);

        // Ensure the job card expands when new entries are added
        setTimeout(() => {
            // card.style.height = 'auto';
        }, 200);
    });
});

