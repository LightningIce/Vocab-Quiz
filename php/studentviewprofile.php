<?php
// studentviewprofile.php

session_start();

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'student' && $_SESSION['role'] !== 'professional')) {
    header("Location: alllogin.php");
    exit();
}

require_once 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Initialize variables
$username = $email = $full_name = '';
$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($current_password)) {
        $errors[] = "Username, Email, and Current Password are required.";
    }

    // Fetch the current password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($stored_password);
        if (!$stmt->fetch()) {
            $errors[] = "User not found.";
        }
        $stmt->close();
    } else {
        $errors[] = "Database error: Unable to prepare statement.";
    }

    // Verify the current password (plain text comparison)
    if (!empty($current_password) && $current_password !== $stored_password) {
        $errors[] = "Current password is incorrect.";
    }

    // If no errors, proceed to update
    if (empty($errors)) {
        // Determine if the password needs to be updated
        $update_password = false;
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                $errors[] = "New passwords do not match.";
            } else {
                $update_password = true;
            }
        }

        // If still no errors, prepare the update statement
        if (empty($errors)) {
            if ($update_password) {
                // Update username, email, and password
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE user_id = ?");
                if ($stmt) {
                    $stmt->bind_param("sssi", $username, $email, $new_password, $user_id);
                } else {
                    $errors[] = "Database error: Unable to prepare update statement.";
                }
            } else {
                // Update only username and email
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
                if ($stmt) {
                    $stmt->bind_param("ssi", $username, $email, $user_id);
                } else {
                    $errors[] = "Database error: Unable to prepare update statement.";
                }
            }

            // Execute the update if the statement was prepared successfully
            if (isset($stmt) && empty($errors)) {
                if ($stmt->execute()) {
                    $success = "Profile updated successfully.";
                } else {
                    $errors[] = "Failed to update profile. Please try again.";
                }
                $stmt->close();
            }
        }
    }
}

// Fetch user data to display
$stmt = $conn->prepare("SELECT username, email, full_name FROM users WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_username, $db_email, $db_full_name);
    $stmt->fetch();
    $stmt->close();
} else {
    $errors[] = "Database error: Unable to prepare statement.";
}

// If form was not submitted or there were errors, populate variables with database values
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !empty($errors)) {
    $username = htmlspecialchars($db_username);
    $email = htmlspecialchars($db_email);
    $full_name = htmlspecialchars($db_full_name);
}

$conn->close();
?>
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

        /* Main Content Spacing */
        main {
            padding-top: 100px;
        }

        /* Profile Container */
        .profile-container {
            background-color: var(--secondary-black);
            border-radius: 12px;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 50px;
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

        /* Success and Error Messages */
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error-message {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Responsive Design */
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

            .profile-container {
                padding: 1rem;
            }
        }
    </style>
    <link rel="stylesheet" href="../css/student.css">
</head>

<body>
    <?php include 'studentheader.php'; ?>

    <main>
        <div class="profile-container">
            <!-- Display Success Message -->
            <?php if (!empty($success)) : ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <!-- Display Error Messages -->
            <?php if (!empty($errors)) : ?>
                <div class="error-message">
                    <?php foreach ($errors as $error) {
                        echo htmlspecialchars($error) . "<br>";
                    } ?>
                </div>
            <?php endif; ?>

            <div class="profile-header">
                <!-- Profile Avatar (You can modify the src to fetch from the database if available) -->
                <img src="../images/icon.png" alt="Profile Picture" class="profile-avatar">
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($full_name); ?></h1>
                    <p>Language Learner</p>
                </div>
            </div>

            <!-- Profile Form -->
            <form class="profile-form" method="POST" action="studentviewprofile.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" name="current-password" required>
                </div>

                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new-password" placeholder="Leave blank to keep current password">
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Leave blank to keep current password">
                </div>

                <button type="submit" class="btn">Save Changes</button>
            </form>

            <div class="profile-options">
                <div class="danger-zone">
                    <a href="delete_account.php" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'studentfooter.php'; ?>

    <script src="../js/studentHeader.js"></script>
</body>

</html>
