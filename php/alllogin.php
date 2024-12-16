<?php
// alllogin.php

// Enable error reporting for development (Disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Secure session cookie parameters (Ensure HTTPS is used)
ini_set('session.cookie_secure', '1'); // Set to '1' if using HTTPS
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');

// Start the session
session_start();

// Include the database connection script
require_once 'db_connect.php';

// Initialize a variable for error messages
$error = "";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize username and password from the POST data
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate input
    if (!empty($username) && !empty($password)) {
        // Prepare a statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
        if ($stmt === false) {
            // Log the error and show a generic error message
            error_log("Prepare failed: " . htmlspecialchars($conn->error));
            $error = "An unexpected error occurred. Please try again later.";
        } else {
            // Bind parameters and execute the statement
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                // Get the result
                $result = $stmt->get_result();
                if ($result->num_rows === 1) {
                    // Fetch user data
                    $user = $result->fetch_assoc();

                    // Verify the password by direct comparison (INSECURE)
                    if ($password === $user['password']) {
                        // Password is correct

                        // Regenerate session ID to prevent session fixation attacks
                        session_regenerate_id(true);

                        // Set session variables
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role'] = strtolower($user['role']); // Normalize role to lowercase

                        // Redirect based on role
                        switch ($_SESSION['role']) {
                            case 'admin':
                                header("Location: admindashboard.php");
                                exit();
                            case 'student':
                            case 'professional':
                                header("Location: studenthomepage.php");
                                exit();
                            default:
                                // Unrecognized role
                                $error = "Your account has an unrecognized role. Please contact support.";
                                break;
                        }
                    } else {
                        // Invalid password
                        $error = "Invalid username or password.";
                    }
                } else {
                    // Username not found
                    $error = "Invalid username or password.";
                }
            } else {
                // Execution failed
                error_log("Execute failed: " . htmlspecialchars($stmt->error));
                $error = "An unexpected error occurred. Please try again later.";
            }
            $stmt->close();
        }
    } else {
        // Missing username or password
        $error = "Please enter both username and password.";
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('../images/cat.jpg'); /* Ensure the path is correct */
            background-size: cover;
            background-position: center;
        }
        
        /* Login Form Styles */
        .login-form {
            background: rgba(64, 64, 64, 0.15);
            border: 3px solid rgba(255, 255, 255, 0.3);
            padding: 30px;
            border-radius: 16px;
            backdrop-filter: blur(25px);
            text-align: center;
            color: Black;
            width: 400px;
            box-shadow: 0px 0px 20px 10px rgba(0, 0, 0, 0.15);
        }
        
        .login-title {
            font-size: 40px;
            margin-bottom: 40px;
        }
        
        .input-box {
            margin: 20px 0;
            position: relative;
        }
        .input-box input {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            padding: 12px 12px 12px 45px;
            border-radius: 99px;
            outline: 3px solid transparent;
            transition: 0.3s;
            font-size: 17px;
            color: white;
            font-weight: 600;
        }
        .input-box input::placeholder {
            color: rgba(255, 255, 255, 0.8);
            font-size: 17px;
            font-weight: 500;
        }
        .input-box input:focus {
            outline: 3px solid rgba(255, 255, 255, 0.3);
        }
        .input-box input::-ms-reveal {
            filter: invert(100%);
        }
        
        .input-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .remember-forgot-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            font-size: 15px;
        }
        
        .remember-forgot-box label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .remember-forgot-box input[type="checkbox"] {
            accent-color: white;
            cursor: pointer;
        }
        
        .remember-forgot-box a {
            color: black;
            text-decoration: none;
        }
        .remember-forgot-box a:hover {
            text-decoration: underline;
        }
        
        .login-btn {
            width: 100%;
            padding: 10px 0;
            background: #2F9CF4;
            border: none;
            border-radius: 99px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-btn:hover {
            background: #0B87EC;
        }
        
        .register {
            margin-top: 15px;
            font-size: 15px;
        }
        .register a {
            color: Black;
            text-decoration: none;
            font-weight: 500;
        }
        .register a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <form action="alllogin.php" method="POST" class="login-form">
        <h1 class="login-title">Login</h1>

        <?php if(!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="input-box">
            <i class='bx bxs-user'></i>
            <input type="text" placeholder="Username" name="username" required>
        </div>
        <div class="input-box">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" placeholder="Password" name="password" required>
        </div>
        
        <div class="remember-forgot-box">
            <label for="remember">
                <input type="checkbox" id="remember" name="remember">
                Remember me
            </label>
            <!-- Optional: Add Forgot Password Link -->
            <!-- <a href="forgot_password.php">Forgot Password?</a> -->
        </div>
        <button class="login-btn" type="submit">Login</button>

        <p class="register">
            Don't have an account?
            <a href="allsignup.php">Register</a>
        </p>
    </form>
</body>
</html>
