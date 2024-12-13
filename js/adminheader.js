document.addEventListener('DOMContentLoaded', () => {
    const dropdownButton = document.querySelector('.admin-dropdown-button');
    const dropdownMenu = document.querySelector('.admin-dropdown');

    dropdownButton.addEventListener('click', () => {
        dropdownButton.classList.toggle('active'); // Toggle the icon
        dropdownMenu.classList.toggle('active');   // Toggle the dropdown menu
    });
});
