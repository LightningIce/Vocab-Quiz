<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'student' && $_SESSION['role'] !== 'professional')) {
    header("Location: alllogin.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VocabQuiz - Enhance Your Language Skills</title>
    <style>
        :root {
            --primary-black: #121212;
            --secondary-black: #1e1e1e;
            --text-white: #f4f4f4;
            --accent-color: #e0e0e0;
            --hover-color: #ffffff;
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
        }

        /* Navigation Styles */
        header {
            background-color: var(--secondary-black);
            padding: 1rem 5%;
            box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .nav-links a {
            color: var(--text-white);
            text-decoration: none;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--accent-color);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

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
            background-color: var(--accent-color);
            color: var(--primary-black);
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: var(--hover-color);
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
            background-color: var(--accent-color);
            color: var(--primary-black);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .quiz-stats button:hover {
            background-color: var(--hover-color);
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
    </style>
</head>

<body>
    <?php include 'studentheader.php'; ?>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Master Your Vocabulary</h1>
                <p>Personalized quizzes to enhance your language skills</p>
                <a href="#start-quiz" class="cta-button">Start Quiz</a>
            </div>
            <div class="hero-image">
                <img src="../images/icon.png" alt="Black Cat Mascot" class="cat-image">
            </div>
        </section>

        <section class="quiz-overview">
            <div class="quiz-card">
                <div class="quiz-icon">📚</div>
                <h2>Recent Quiz</h2>
                <p>Advanced Vocabulary</p>
                <div class="quiz-stats">
                    <span>Score: 85%</span>
                    <button onclick="viewQuizDetails()">View Details</button>
                </div>
            </div>

            <div class="quiz-card">
                <div class="quiz-icon">🎓</div>
                <h2>Academic English</h2>
                <p>Comprehensive Vocabulary Test</p>
                <div class="quiz-stats">
                    <span>Status: Available</span>
                    <button onclick="startQuiz()">Begin Quiz</button>
                </div>
            </div>

            <div class="quiz-card">
                <div class="quiz-icon">💼</div>
                <h2>Business Terminology</h2>
                <p>Professional Language Skills</p>
                <div class="quiz-stats">
                    <span>Status: In Progress</span>
                    <button onclick="resumeQuiz()">Continue</button>
                </div>
            </div>
        </section>
    </main>

    <?php include 'studentfooter.php' ?>

    <script src="../js/studentHeader.js"></script>
    <script>
        // Toggle profile dropdown menu
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

        // View quiz details
        function viewQuizDetails() {
            alert('Detailed quiz statistics will be displayed here.');
        }

        // Start a new quiz
        function startQuiz() {
            alert('Preparing your vocabulary quiz...');
        }

        // Resume an in-progress quiz
        function resumeQuiz() {
            alert('Continuing your previous quiz session.');
        }

        // Optional: Add smooth scroll to sections
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>