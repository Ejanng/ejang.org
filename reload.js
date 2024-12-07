setInterval(function () {
    if (document.visibilityState === "visible") {
        // Check if any form field is focused or has data
        const formInputs = document.querySelectorAll("input, textarea");
        let isFormActive = false;

        formInputs.forEach(input => {
            if (document.activeElement === input || input.value.trim() !== "") {
                isFormActive = true; // User is interacting with the form
            }
        });

        // Only reload if no form is being interacted with
        if (!isFormActive) {
            localStorage.setItem("scrollPosition", window.scrollY); // Save scroll position
            location.reload();
        }
    }
}, 300000);

// Restore scroll position after reload
window.onload = function () {
    const scrollPosition = localStorage.getItem("scrollPosition");
    if (scrollPosition) {
        window.scrollTo(0, parseInt(scrollPosition, 10)); // Scroll to saved position
        localStorage.removeItem("scrollPosition"); // Optional: Clean up storage
    }
};