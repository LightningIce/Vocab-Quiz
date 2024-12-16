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
$question_text = isset($_POST['question_text']) ? trim($_POST['question_text']) : '';
$options = isset($_POST['options']) ? $_POST['options'] : [];
$correct_index = isset($_POST['correct_option']) ? (int)$_POST['correct_option'] : 0;

// Validate input
// correct_option is 1-based index, so we must adjust for 0-based array indexing
if ($quiz_id <= 0 || $question_text === '' || empty($options) || $correct_index <= 0 || $correct_index > count($options)) {
    header('Location: htmlphp.php?error=invalid_input');
    exit;
}

// Insert new question
$insert_question_sql = "INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)";
$stmt = $conn->prepare($insert_question_sql);
$stmt->bind_param('is', $quiz_id, $question_text);
$stmt->execute();
$new_question_id = $stmt->insert_id;
$stmt->close();

// Insert options
$option_ids = [];
foreach ($options as $opt_text) {
    $opt_text = trim($opt_text);
    if ($opt_text === '') $opt_text = 'N/A'; // Fallback if empty
    $insert_option_sql = "INSERT INTO options (question_id, option_text) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_option_sql);
    $stmt->bind_param('is', $new_question_id, $opt_text);
    $stmt->execute();
    $option_ids[] = $stmt->insert_id;
    $stmt->close();
}

// Set the correct option_id
// correct_option is 1-based, so adjust index by -1
$correct_option_id = $option_ids[$correct_index - 1];
$update_correct_sql = "UPDATE questions SET correct_option_id = ? WHERE question_id = ?";
$stmt = $conn->prepare($update_correct_sql);
$stmt->bind_param('ii', $correct_option_id, $new_question_id);
$stmt->execute();
$stmt->close();

$conn->close();

// Redirect back to the main page
header('Location: adminquizedit.php?quiz_id=' . $quiz_id . '&success=question_added');
exit;
