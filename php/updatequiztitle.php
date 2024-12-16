<?php
// Database connection
$host = 'localhost';
$db   = 'vocabquiz';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed.");
}

$quiz_id = isset($_POST['quiz_id']) ? (int)$_POST['quiz_id'] : 0;
$new_title = isset($_POST['new_title']) ? trim($_POST['new_title']) : '';

if ($quiz_id <= 0 || $new_title === '') {
    // Invalid input, redirect back with an error or show a message
    header('Location: htmlphp.php?error=invalid_input');
    exit;
}

// Update the quiz title
$sql = "UPDATE quizzes SET quiz_title = ? WHERE quiz_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    header('Location: htmlphp.php?error=sql_error');
    exit;
}

$stmt->bind_param("si", $new_title, $quiz_id);
if ($stmt->execute()) {
    // Redirect back to the quiz review page
    header('Location: htmlphp.php?success=title_updated');
} else {
    header('Location: htmlphp.php?error=update_failed');
}

$stmt->close();
$conn->close();
