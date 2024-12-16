<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - VocabQuiz</title>
    <style>
      /* CSS Reset and Base Styles */
:root {
    --primary-black: #121212;
    --secondary-black: #1e1e1e;
    --text-white: #f4f4f4;
    --accent-color: #e0e0e0;
    --hover-color: #ffffff;
    --orange-accent: #ff9800;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
    background-color: var(--primary-black);
    color: var(--text-white);
    line-height: 1.6;
    overflow-x: hidden;
}

/* Header Styles */
header {
    background-color: var(--secondary-black);
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
}

/* Navigation */
nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 5%;
    max-width: 1200px;
    margin: 0 auto;
}

.logo {
    display: flex;
    align-items: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--text-white);
}

.logo img {
    height: 40px;
    margin-right: 10px;
}

.nav-links {
    display: flex;
    list-style: none;
    gap: 2rem;
    align-items: center;
}

.nav-links a {
    color: var(--text-white);
    text-decoration: none;
    transition: color 0.3s ease;
    position: relative;
    padding: 10px 0;
}

.nav-links a::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: var(--accent-color);
    transition: width 0.3s ease;
}

.nav-links a:hover::after {
    width: 100%;
}

/* Profile Dropdown */
.profile {
    position: relative;
}

.profile-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    transition: transform 0.2s;
}

.profile-icon:hover {
    transform: scale(1.1);
}

.profile-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background-color: var(--secondary-black);
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(255, 255, 255, 0.1);
    min-width: 200px;
    padding: 10px;
    z-index: 1100;
}

.profile-dropdown.active {
    display: block;
}

.profile-dropdown a {
    display: block;
    color: var(--text-white);
    padding: 10px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.profile-dropdown a:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Main Content Spacing */
main {
    padding-top: 100px;
}

/* Hero Section */
.hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 4rem 5%;
    gap: 2rem;
}

.hero-content {
    flex: 1;
}

.hero-content h1 {
    font-size: 3rem;
    color: var(--text-white);
    margin-bottom: 1rem;
}

.hero-content p {
    font-size: 1.2rem;
    color: var(--accent-color);
    margin-bottom: 2rem;
}

.cta-button {
    display: inline-block;
    background-color: var(--orange-accent);
    color: var(--primary-black);
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.cta-button:hover {
    background-color: #e58900;
}

.hero-image {
    flex: 1;
    text-align: center;
}

.cat-image {
    max-width: 100%;
    max-height: 400px;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(255, 255, 255, 0.1);
}

/* Quiz Overview */
.quiz-overview {
    display: flex;
    justify-content: space-between;
    padding: 2rem 5%;
    gap: 2rem;
}

.quiz-card {
    background-color: var(--secondary-black);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    flex: 1;
    transition: transform 0.3s ease;
}

.quiz-card:hover {
    transform: translateY(-10px);
}

.quiz-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.quiz-card h2 {
    color: var(--text-white);
    margin-bottom: 1rem;
}

.quiz-stats {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    margin-top: 1rem;
}

.quiz-stats button {
    background-color: var(--orange-accent);
    color: var(--primary-black);
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.quiz-stats button:hover {
    background-color: #e58900;
}

/* Footer */
footer {
    background-color: var(--secondary-black);
    padding: 3rem 5%;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-content {
    display: flex;
    justify-content: space-between;
    gap: 2rem;
}

.footer-section {
    flex: 1;
}

.footer-section h3 {
    color: var(--text-white);
    margin-bottom: 1rem;
}

.footer-section ul {
    list-style: none;
}

.footer-section ul li a {
    color: var(--accent-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: var(--hover-color);
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-icon {
    color: var(--accent-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.social-icon:hover {
    color: var(--hover-color);
}

.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    color: var(--accent-color);
}

/* Hamburger Menu (Mobile) */
.hamburger {
    display: none;
    cursor: pointer;
    z-index: 1100;
}

.hamburger span {
    display: block;
    width: 25px;
    height: 3px;
    background-color: var(--text-white);
    margin: 5px 0;
    transition: 0.4s;
}

/* Responsive Design */
@media screen and (max-width: 1024px) {
    nav {
        padding: 1rem 3%;
    }

    .nav-links {
        gap: 1.5rem;
    }
}

@media screen and (max-width: 768px) {
    /* Mobile Navigation */
    .nav-links {
        display: none;
        position: fixed;
        top: 70px;
        left: 0;
        width: 100%;
        background-color: var(--secondary-black);
        flex-direction: column;
        align-items: center;
        padding: 2rem 0;
        height: calc(100vh - 70px);
        overflow-y: auto;
    }

    .nav-links.active {
        display: flex;
    }

    .nav-links li {
        margin: 10px 0;
        width: 100%;
        text-align: center;
    }

    .nav-links li a {
        display: block;
        padding: 15px;
    }

    /* Hamburger Menu */
    .hamburger {
        display: block;
    }

    .hamburger.active span:nth-child(1) {
        transform: rotate(-45deg) translate(-5px, 6px);
    }

    .hamburger.active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger.active span:nth-child(3) {
        transform: rotate(45deg) translate(-5px, -6px);
    }

    /* Responsive Hero and Quiz Sections */
    .hero, .quiz-overview {
        flex-direction: column;
        text-align: center;
    }

    .hero-content, .hero-image, .quiz-card {
        width: 100%;
        margin-bottom: 2rem;
    }

    .cat-image {
        max-height: 300px;
    }
}
     /* Reuse the existing CSS variables and base styles */
     :root {
        --primary-black: #121212;
        --secondary-black: #1e1e1e;
        --text-white: #f4f4f4;
        --accent-color: #e0e0e0;
        --hover-color: #ffffff;
        --orange-accent: #ff9800;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
        background-color: var(--primary-black);
        color: var(--text-white);
        line-height: 1.6;
        overflow-x: hidden;
    }



    main {
        padding-top: 100px;
        max-width: 800px;
        margin: 0 auto;
        padding-bottom: 50px;
    }

    .profile-container {
        background-color: var(--secondary-black);
        border-radius: 12px;
        padding: 2rem;
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-right: 2rem;
        object-fit: cover;
    }

    .profile-info h1 {
        color: var(--text-white);
        margin-bottom: 0.5rem;
    }

    .profile-info p {
        color: var(--accent-color);
    }

    .profile-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        color: var(--accent-color);
    }

    .form-group input {
        background-color: var(--primary-black);
        color: var(--text-white);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 6px;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--orange-accent);
    }

    .btn {
        background-color: var(--orange-accent);
        color: var(--primary-black);
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        align-self: flex-start;
    }

    .btn:hover {
        background-color: #e58900;
    }

    .profile-options {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .danger-zone a {
        color: #ff4444;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .danger-zone a:hover {
        color: #ff6666;
    }



    @media screen and (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-avatar {
            margin-right: 0;
            margin-bottom: 1rem;
        }
    }

@media screen and (max-width: 480px) {
    nav {
        padding: 1rem 2%;
    }

    .logo {
        font-size: 1.2rem;
    }

    .logo img {
        height: 30px;
        margin-right: 5px;
    }

    .hero-content h1 {
        font-size: 2rem;
    }

    .hero-content p {
        font-size: 1rem;
    }
}
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="icon.png" alt="VocabQuiz Logo">
                VocabQuiz
            </div>
            <ul class="nav-links">
                <li><a href="HomePage.html">Home</a></li>
                <li><a href="#quizzes">Quizzes</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li class="profile">
                    <div class="profile-toggle" onclick="toggleProfileMenu()">
                        <img src="Profile_Icon.png" alt="Profile" class="profile-icon">
                    </div>
                    <div class="profile-dropdown">
                        <a href="#profile">My Profile</a>
                        <a href="#history">Quiz History</a>
                        <a href="#settings">Settings</a>
                        <a href="#logout">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            <div class="profile-header">
                <img src="Profile_Icon.png" alt="Profile Picture" class="profile-avatar">
                <div class="profile-info">
                    <h1>John Doe</h1>
                    <p>Language Learner</p>
                </div>
            </div>

            <form class="profile-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="johndoe" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="john.doe@example.com" required>
                </div>

                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" name="current-password" required>
                </div>

                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new-password">
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="confirm-password">
                </div>

                <button type="submit" class="btn">Save Changes</button>
            </form>

            <div class="profile-options">
                <div class="danger-zone">
                    <a href="#delete-account">Delete Account</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About VocabQuiz</h3>
                <p>Empowering language learners through interactive and personalized vocabulary quizzes.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#quizzes">Quizzes</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Connect With Us</h3>
                <div class="social-links">
                    <a href="#" class="social-icon">Twitter</a>
                    <a href="#" class="social-icon">LinkedIn</a>
                    <a href="#" class="social-icon">Instagram</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 VocabQuiz. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Reuse profile menu toggle from homepage
        function toggleProfileMenu() {
            const profileDropdown = document.querySelector('.profile-dropdown');
            profileDropdown.classList.toggle('active');

            document.addEventListener('click', function closeMenu(e) {
                if (!e.target.closest('.profile')) {
                    profileDropdown.classList.remove('active');
                    document.removeEventListener('click', closeMenu);
                }
            });
        }

        // Basic form validation
        document.querySelector('.profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword !== confirmPassword) {
                alert('New passwords do not match');
                return;
            }

            // Add your save changes logic here
            alert('Profile updated successfully!');
        });
    </script>
        <script src="../js/studentHeader.js"></script>
</body>
</html>
