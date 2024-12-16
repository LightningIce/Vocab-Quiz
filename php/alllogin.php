<?php
session_start();
include 'db_connect.php';

// Initialize a variable for error messages
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve username and password from the POST data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Prepare a statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                // Fetch user data
                $user = $result->fetch_assoc();

                // Verify the password
                // Assuming passwords in DB are hashed using password_hash()
                if (password_verify($password, $user['password'])) {
                    // Password is correct
                    // Set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect to a dashboard page or wherever you want
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Error executing query.";
        }
        $stmt->close();
    } else {
        $error = "Please enter username and password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap');

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
            background-image: url(cat.jpg);
            background-size: cover;
            background-position: center;
        }
        
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
            margin: 20px 0;
            font-size: 15px;
        }
        
        .remember-forgot-box label {
            display: flex;
            gap: 8px;
            cursor: pointer;
        }
        .remember-forgot-box input {
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
    <form action="login.php" method="POST" class="login-form">
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
                <input type="checkbox" id="remember">
                Remember me
            </label>
            <a href="Forgotpassword.html">Forgot Password?</a>
        </div>
        <button class="login-btn" type="submit">Login</button>

        <p class="register">
            Don't have an account?
            <a href="Register.html">Register</a>
        </p>
    </form>
</body>
</html>
