<?php
// alllogin.php

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
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }
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

                    // Redirect based on role
                    switch (strtolower($user['role'])) {
                        case 'lecturer':
                            header("Location: admindashboard.php");
                            break;
                        case 'student':
                        case 'professional':
                            header("Location: studenthomepage.php");
                            break;
                        default:
                            // If role is unrecognized, redirect to a default page or show an error
                            $error = "Unrecognized user role.";
                            break;
                    }
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
