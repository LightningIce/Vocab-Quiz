<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VocabQuiz - Contact Page</title>
    <link rel="stylesheet" href="../css/student.css">
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

      

        /* Main Content Spacing */
        main {
            background-color: #121212;
            padding-top: 120px; /* Increased top padding to prevent header overlap */
            padding-bottom: 60px; /* Added bottom padding for more breathing room */
            min-height: calc(100vh - 300px); /* Ensure main content takes up remaining viewport height */
        }

        /* Contact Section */
        .contact-section {
            max-width: 600px;
            margin: 0 auto;
            background-color: var(--secondary-black);
            padding: 2rem;
            border-radius: 12px;
        }

        .contact-section h1 {
            color: var(--text-white);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .contact-info {
            margin-bottom: 1.5rem;
        }

        .contact-info p {
            color: var(--accent-color);
            margin-bottom: 1rem;
            text-align: center;
        }

        .contact-button {
            display: block;
            width: 100%;
            background-color: var(--orange-accent);
            color: var(--primary-black);
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .contact-button:hover {
            background-color: #e58900;
        }
    </style>
</head>
<body>
<?php include 'studentheader.php'; ?>
    <main>
        <section class="contact-section">
            <h1>Contact Us</h1>
            <div class="contact-info">
                <p>Email: support@vocabquiz.com</p>
                <p>Phone: +1 (123) 456-7890</p>
                <p>Address: 123 Learning Street, Education City, 12345</p>
            </div>
            <a href="mailto:support@vocabquiz.com" class="contact-button">Send an Email</a>
        </section>
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
                    <li><a href="ContactUs.html">Contact</a></li>
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
        document.addEventListener('DOMContentLoaded', () => {
            const header = document.querySelector('header');
            let lastScrollTop = 0;

            // Header scroll behavior
            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Add scrolled class
                header.classList.toggle('scrolled', scrollTop > 100);
                
                // Scroll up/down detection
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    header.classList.add('scrolled');
                } else if (scrollTop < lastScrollTop) {
                    header.classList.remove('scrolled');
                }
                
                lastScrollTop = scrollTop;
            });

            // Mobile Navigation Toggle
            const hamburger = document.querySelector('.hamburger');
            const navLinks = document.querySelector('.nav-links');

            if (hamburger && navLinks) {
                hamburger.addEventListener('click', () => {
                    hamburger.classList.toggle('active');
                    navLinks.classList.toggle('active');
                });
            }

            // Close mobile menu when a link is clicked
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    navLinks.classList.remove('active');
                });
            });
        });

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
    </script>
</body>
</html>
