<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - VocabQuiz</title>
    <link rel="stylesheet" href="ProfilePage.css">
    <style>
        /* Dark Theme Color Palette */
:root {
    --bg-primary: #121212;
    --bg-secondary: #1e1e1e;
    --text-primary: #e0e0e0;
    --text-secondary: #a0a0a0;
    --accent-color: #4CAF50;
    --accent-color-hover: #45a049;
    --border-color: #333333;
    --shadow-color: rgba(0, 0, 0, 0.3);
}

/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    color: var(--text-primary);
    background-color: var(--bg-primary);
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
/* Main Content Container */
main {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
    margin-top: 10rem; /* Increased top margin to create space below fixed header */
}

.profile-container {
    background-color: var(--bg-secondary);
    border-radius: 10px;
    box-shadow: 0 4px 6px var(--shadow-color);
    padding: 2rem;
    border: 1px solid var(--border-color);
}

.profile-header {
    text-align: center;
    margin-bottom: 2rem;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
    filter: brightness(0) invert(1);
}

.profile-info h1 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.profile-info p {
    color: var(--text-secondary);
}

/* Form Styles */
.profile-form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 5px;
    font-size: 1rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
}

.btn {
    background-color: var(--accent-color);
    color: var(--text-primary);
    border: none;
    padding: 0.75rem;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 1rem;
}

.btn:hover {
    background-color: var(--accent-color-hover);
}

.profile-options {
    margin-top: 2rem;
    text-align: center;
}

.danger-zone a {
    color: #ff6b6b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.danger-zone a:hover {
    color: #ff4757;
    text-decoration: underline;
}

/* About Page Specific Styles */
.about-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.about-content section {
    background-color: var(--bg-secondary);
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px var(--shadow-color);
    border: 1px solid var(--border-color);
}

.about-content h2 {
    color: var(--text-primary);
    margin-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
    padding-bottom: 0.5rem;
}

.about-content ul {
    list-style-type: disc;
    padding-left: 2rem;
    color: var(--text-secondary);
}

.team-members {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1.5rem;
}

.team-member {
    text-align: center;
    width: 200px;
}

.team-member img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
    filter: brightness(0) invert(1);
}

.team-member h3 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.team-member p {
    color: var(--text-secondary);
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
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

/* Optional: Scrollbar Styling for Dark Theme */
::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    background: var(--bg-secondary);
}

::-webkit-scrollbar-thumb {
    background-color: var(--border-color);
    border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
    background-color: var(--text-secondary);
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
                <h1>About VocabQuiz</h1>
            </div>

            <div class="about-content">
                <section class="mission">
                    <h2>Our Mission</h2>
                    <p>VocabQuiz is dedicated to transforming language learning through interactive, engaging, and personalized vocabulary experiences. We believe that learning a language should be fun, accessible, and tailored to individual needs.</p>
                </section>

                <section class="our-story">
                    <h2>Our Story</h2>
                    <p>Founded by a team of passionate language enthusiasts and tech innovators, VocabQuiz was born from the desire to make language learning more interactive and enjoyable. We recognized the challenges students and professionals face in expanding their vocabulary and communication skills.</p>
                </section>

                <section class="features">
                    <h2>What Makes Us Unique</h2>
                    <ul>
                        <li>Gamified Learning Experience</li>
                        <li>Personalized Vocabulary Quizzes</li>
                        <li>Adaptive Learning Paths</li>
                        <li>Comprehensive Business English Support</li>
                        <li>Mobile-Friendly Design</li>
                    </ul>
                </section>

                <section class="team">
                    <h2>Our Team</h2>
                    <div class="team-members">
                        <div class="team-member">
                            <img src="Profile_Icon.png" alt="Team Member">
                            <h3>Lee Ken Yang</h3>
                            <p>Lead Developer</p>
                        </div>
                        <div class="team-member">
                            <img src="Profile_Icon.png" alt="Team Member">
                            <h3>Liew Jun Wei Ivan</h3>
                            <p>UI/UX Designer</p>
                        </div>
                        <div class="team-member">
                            <img src="Profile_Icon.png" alt="Team Member">
                            <h3>Leong Kean Tshung</h3>
                            <p>Product Manager</p>
                        </div>
                        <div class="team-member">
                            <img src="Profile_Icon.png" alt="Team Member">
                            <h3>Kow Jing Joo</h3>
                            <p>Backend Engineer</p>
                        </div>
                        <div class="team-member">
                            <img src="Profile_Icon.png" alt="Team Member">
                            <h3>Howard Ngu Wen Hao</h3>
                            <p>Content Strategist</p>
                        </div>
                    </div>
                </section>
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
    </script>
</body>
</html>
