<?php
// delete_account.php

session_start();

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id'])) {
    header("Location: alllogin.php");
    exit();
}

require_once 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Initialize variables
$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form input
    $password = $_POST['password'];

    // Basic validation
    if (empty($password)) {
        $errors[] = "Password is required to delete your account.";
    }

    if (empty($errors)) {
        // Fetch the current password from the database
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($stored_password);
            if ($stmt->fetch()) {
                // Verify the password (plain text comparison)
                if ($password === $stored_password) {
                    $stmt->close();

                    // Delete the user account
                    $delete_stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
                    if ($delete_stmt) {
                        $delete_stmt->bind_param("i", $user_id);
                        if ($delete_stmt->execute()) {
                            $delete_stmt->close();

                            // Destroy the session
                            session_unset();
                            session_destroy();

                            // Redirect to goodbye page
                            header("Location: goodbye.php");
                            exit();
                        } else {
                            $errors[] = "Failed to delete your account. Please try again.";
                            $delete_stmt->close();
                        }
                    } else {
                        $errors[] = "Database error: Unable to prepare delete statement.";
                    }
                } else {
                    $errors[] = "Incorrect password.";
                    $stmt->close();
                }
            } else {
                $errors[] = "User not found.";
                $stmt->close();
            }
        } else {
            $errors[] = "Database error: Unable to prepare statement.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account - VocabQuiz</title>
    <style>
        /* CSS Reset and Base Styles */
        :root {
            --primary-black: #121212;
            --secondary-black: #1e1e1e;
            --text-white: #f4f4f4;
            --accent-color: #e0e0e0;
            --hover-color: #ffffff;
            --danger-color: #ff4444;
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

        /* Delete Account Container */
        .delete-container {
            background-color: var(--secondary-black);
            border-radius: 12px;
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
            padding-bottom: 50px;
        }

        .delete-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .delete-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 2rem;
            object-fit: cover;
        }

        .delete-info h1 {
            color: var(--text-white);
            margin-bottom: 0.5rem;
        }

        .delete-info p {
            color: var(--accent-color);
        }

        .delete-form {
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
            border-color: var(--danger-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: var(--primary-black);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            align-self: flex-start;
        }

        .btn-danger:hover {
            background-color: #ff6666;
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
            .delete-header {
                flex-direction: column;
                text-align: center;
            }

            .delete-avatar {
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

            .delete-container {
                padding: 1rem;
            }
        }
    </style>
    <link rel="stylesheet" href="../css/student.css">
</head>

<body>
    <?php include 'studentheader.php'; ?>

    <main>
        <div class="delete-container">
            <?php if (!empty($errors)) : ?>
                <div class="error-message">
                    <?php foreach ($errors as $error) {
                        echo htmlspecialchars($error) . "<br>";
                    } ?>
                </div>
            <?php endif; ?>

            <div class="delete-header">
                <!-- Profile Avatar (You can modify the src to fetch from the database if available) -->
                <img src="Profile_Icon.png" alt="Profile Picture" class="delete-avatar">
                <div class="delete-info">
                    <h1>Delete Account</h1>
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                </div>
            </div>

            <form class="delete-form" method="POST" action="delete_account.php">
                <div class="form-group">
                    <label for="password">Enter Your Password to Confirm</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-danger">Delete Account</button>
            </form>
        </div>
    </main>

    <?php include 'studentfooter.php'; ?>

    <script src="../js/studentHeader.js"></script>
</body>

</html>