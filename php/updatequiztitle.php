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
    header('Location: adminquizedit.php?error=invalid_input');
    exit;
}

$sql = "UPDATE quizzes SET quiz_title = ? WHERE quiz_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    header('Location: adminquizedit.php?error=sql_error');
    exit;
}

$stmt->bind_param("si", $new_title, $quiz_id);
if ($stmt->execute()) {
    header('Location: adminquizedit.php?quiz_id=' . $quiz_id . '&success=title_updated');
} else {
    header('Location: adminquizedit.php?quiz_id=' . $quiz_id . '&error=update_failed');
}

$stmt->close();
$conn->close();
