<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<header class="admin-header">
    <div class="nav-wrap">
        <!-- Logo Section -->
        <div id="header-logo">
            <div class="header-logo-cat">
                <a href="admindashboard.php"><img src="../images/icon.png" alt="Logo"></a>
            </div>
            <div class="header-logo-name">
                <a href="admindashboard.php">Vocab Quiz</a>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav id="header-navigation">
            <ul>
                <li class="nav-home"><a href="admindashboard.php"><span>Home</span></a></li>
                <li class="nav-easy"><a href="admindashboardeasy.php"><span>Easy</span></a></li>
                <li class="nav-hard"><a href="admindashboardhard.php"><span>Hard</span></a></li>
                <li class="nav-business"><a href="admindashboardbusiness.php"><span>Business</span></a></li>
            </ul>
        </nav>

        <!-- Create Quiz Button -->
        <div id="header-create-quiz">
            <div class="header-create-button">
                <a href="adminquizadd.php"><span>Create Quiz</span></a>
            </div>
        </div>

        <!-- Hamburger Menu and Dropdown -->
        <div class="admin-dropdown-container">
            <button class="admin-dropdown-button" aria-label="Toggle navigation" aria-expanded="false">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
            <div class="admin-dropdown">
                <ul class="admin-dropdown-items">
                    <li>
                        <a href="adminviewprofile.php">
                            <span class="admin-dropdown-profile-icon"><i class="fas fa-user" aria-hidden="true"></i></span>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <span><i class="fas fa-sign-out-alt" aria-hidden="true"></i></span>
                            <span>Log Out</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
