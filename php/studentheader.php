<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<header>
    <nav>
        <div class="logo">
            <img src="../images/icon.png" alt="VocabQuiz Logo">
            VocabQuiz
        </div>
        <ul class="nav-links">
            <li><a href="studenthomepage.php">Home</a></li>
            <li><a href="studentdashboard.php">Quizzes</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="profile">
            <div class="profile-toggle" onclick="toggleProfileMenu()">
                <i class="fas fa-user" aria-hidden="false"></i>
            </div>
            <div class="profile-dropdown">
                <a href="#profile">My Profile</a>
                <a href="studentquizhistory.php">Quiz History</a>
                <a href="#settings">Settings</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
</header>