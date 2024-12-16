<?php
session_start();
include 'db_connect.php';

$error = "";
$success = "";

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $role = trim($_POST["role"]);

    // Validate inputs
    if (empty($username) || empty($password) || empty($confirm_password) || empty($full_name) || empty($email) || empty($role)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or Email already exists.";
        } else {
            // *No password hashing* (Not recommended in real scenarios)
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password, full_name, email, role) VALUES (?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("sssss", $username, $password, $full_name, $email, $role);

            if ($insert_stmt->execute()) {
                $success = "Registration successful. You can now <a href='alllogin.php'>login</a>.";
            } else {
                $error = "Error in registration. Please try again.";
            }

            $insert_stmt->close();
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

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
            background-image: url(../images/cat.jpg);
            background-size: cover;
            background-position: center;
        }
        
        .register-form {
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
        
        .register-title {
            font-size: 40px;
            margin-bottom: 40px;
        }
        
        .input-box {
            margin: 20px 0;
            position: relative;
        }
        .input-box input, .input-box select {
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
            appearance: none; /* For select styling */
        }
        .input-box input::placeholder {
            color: rgba(255, 255, 255, 0.8);
            font-size: 17px;
            font-weight: 500;
        }
        .input-box select {
            color: white;
        }
        .input-box input:focus, .input-box select:focus {
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

        .error {
            color: red;
            margin: 10px 0;
            font-weight: 600;
        }

        .success {
            color: green;
            margin: 10px 0;
            font-weight: 600;
        }
        
        .register-btn {
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
        .register-btn:hover {
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

        /* Custom arrow for the select dropdown */
        .input-box select {
            background: rgba(0, 0, 0, 0.1) url('data:image/svg+xml;utf8,<svg fill="white" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"></path></svg>') no-repeat right 15px center;
            background-size: 20px 20px;
        }
    </style>
</head>
<body>
    
    <form action="allsignup.php" method="POST" class="register-form">
        <h1 class="register-title">Register</h1>

        <?php if(!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if(!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="input-box">
            <i class='bx bxs-user'></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-box">
            <i class='bx bxs-user'></i>
            <input type="text" name="full_name" placeholder="Full Name" required>
        </div>
        <div class="input-box">
            <i class='bx bxs-envelope'></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-box">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-box">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        <div class="input-box">
            <i class='bx bxs-user'></i>
            <select name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="student">Student</option>
                <option value="professionals">Professionals</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button class="register-btn" type="submit">Register</button>

        <p class="register">
            Have an account?
            <a href="alllogin.php">Login</a>
        </p>
    </form>

</body>
</html>