function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({ 
        behavior: 'smooth' 
    });
}

document.addEventListener("DOMContentLoaded", function () {
    var buttons = document.querySelectorAll(".metric-button");

    // Function to reset button styles
    function resetStyles() {
        buttons.forEach(button => {
            button.style.backgroundColor = '#ff8c00';
            button.style.transform = 'scale(1)';
        });
    }

    // Function to apply hover effect
    function applyHover(button) {
        button.style.transform = 'scale(1.03)';
        button.style.backgroundColor = '#3d84ff';
        // button.style.boxShadow = '0px 6px 20px rgba(21, 21, 21, 0.8)';
    }

    // Function to handle button press effect
    function applyPressedEffect(button) {
        button.style.transform = 'scale(0.975)';
        button.style.boxShadow = 'none';
    }

    // Function to remove button press effect
    function removePressedEffect(button) {
        button.style.transform = 'scale(1)';
        button.style.boxShadow = '0px 4px 15px rgba(37, 37, 37, 0.5)';
    }

    // Button animation effect on page load
    buttons.forEach((button, index) => {
        button.style.opacity = "0";
        button.style.transform = "translateY(20px)";
        button.style.transition = "opacity 0.8s ease-out, transform 0.3s ease-out";

        // Staggered animation effect
        setTimeout(() => {
            button.style.opacity = "1";
            button.style.transform = "translateY(0)";
        }, 700 + index * 300);

        // Hover effect
        button.addEventListener('mouseover', function () {
            applyHover(this);
        });

        button.addEventListener('mouseout', function () {
            resetStyles();
        });

        // Button press effect
        button.addEventListener('mousedown', function () {
            applyPressedEffect(this);
        });

        button.addEventListener('mouseup', function () {
            removePressedEffect(this);
        });
    });
});
