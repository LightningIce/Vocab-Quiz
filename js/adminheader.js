document.addEventListener('DOMContentLoaded', () => {
    const dropdownButton = document.querySelector('.admin-dropdown-button');
    const dropdownMenu = document.querySelector('.admin-dropdown');

    dropdownButton.addEventListener('click', (event) => {
        event.stopPropagation(); // Prevent click from bubbling up
        dropdownButton.classList.toggle('active'); // Toggle the icon
        dropdownMenu.classList.toggle('active');   // Toggle the dropdown menu
    });

    // Close the dropdown when clicking outside of it
    document.addEventListener('click', (event) => {
        if (dropdownMenu.classList.contains('active') &&
            !dropdownMenu.contains(event.target) &&
            !dropdownButton.contains(event.target)) {
            dropdownMenu.classList.remove('active');
            dropdownButton.classList.remove('active');
        }
    });

    // Optional: Close the dropdown when pressing the Escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && dropdownMenu.classList.contains('active')) {
            dropdownMenu.classList.remove('active');
            dropdownButton.classList.remove('active');
        }
    });
});
