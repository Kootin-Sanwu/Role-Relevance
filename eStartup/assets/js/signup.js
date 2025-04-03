document.addEventListener('DOMContentLoaded', function () {
    const signUpForm = document.querySelector('.sign-up-container form');

    signUpForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission

        // Fetch input values
        const email = signUpForm.querySelector('input[name="organization_email"]').value.trim();
        const password = signUpForm.querySelector('input[name="password"]').value.trim();
        const confirmPassword = signUpForm.querySelector('input[name="confirm_password"]').value.trim();

        // Basic validation
        if (email === '' || password === '' || confirmPassword === '') {
            Swal.fire("Error", "All fields are required.", "error");
            return;
        }

        // Password match validation
        if (password !== confirmPassword) {
            Swal.fire("Error", "Passwords do not match.", "error");
            return;
        }

        // If all validations pass, you can submit the form
        signUpForm.submit();
    });
});

