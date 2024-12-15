// studentheader.js

function toggleProfileMenu() {
    const profileDropdown = document.querySelector('.profile-dropdown');
    profileDropdown.classList.toggle('active');

    // Close dropdown when clicking outside
    document.addEventListener('click', function closeMenu(e) {
        if (!e.target.closest('.profile')) {
            profileDropdown.classList.remove('active');
            document.removeEventListener('click', closeMenu);
        }
    });
}
