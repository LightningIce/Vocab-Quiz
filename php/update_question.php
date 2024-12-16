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

$question_id = isset($_POST['question_id']) ? (int)$_POST['question_id'] : 0;
$question_text = isset($_POST['question_text']) ? trim($_POST['question_text']) : '';
$options = isset($_POST['options']) ? $_POST['options'] : [];
$correct_index = isset($_POST['correct_option']) ? (int)$_POST['correct_option'] : -1;

// Validate input
if ($question_id <= 0 || $question_text === '' || empty($options) || $correct_index < 0 || $correct_index >= count($options)) {
    header('Location: adminquizedit.php?error=invalid_input');
    exit;
}

// Update question text
$update_question_sql = "UPDATE questions SET question_text = ? WHERE question_id = ?";
$stmt = $conn->prepare($update_question_sql);
$stmt->bind_param('si', $question_text, $question_id);
$stmt->execute();
$stmt->close();

// Get the quiz_id associated with the question
$quiz_id_sql = "SELECT quiz_id FROM questions WHERE question_id = ?";
$stmt = $conn->prepare($quiz_id_sql);
$stmt->bind_param('i', $question_id);
$stmt->execute();
$result = $stmt->get_result();
$quiz_row = $result->fetch_assoc();
$stmt->close();

if ($quiz_row) {
    $quiz_id = $quiz_row['quiz_id'];
} else {
    // Handle the case where quiz_id is not found
    header('Location: adminquizedit.php?error=quiz_not_found');
    exit;
}

// Get existing option_ids for this question
$get_option_sql = "SELECT option_id FROM options WHERE question_id = ? ORDER BY option_id ASC";
$stmt = $conn->prepare($get_option_sql);
$stmt->bind_param('i', $question_id);
$stmt->execute();
$result = $stmt->get_result();
$option_ids = [];
while ($row = $result->fetch_assoc()) {
    $option_ids[] = $row['option_id'];
}
$stmt->close();

// Update each option text. Assume the number of existing options matches `count($options)`
for ($i = 0; $i < count($options); $i++) {
    if (!isset($option_ids[$i])) break; // Safety check
    $opt_text = trim($options[$i]);
    $update_option_sql = "UPDATE options SET option_text = ? WHERE option_id = ?";
    $stmt = $conn->prepare($update_option_sql);
    $stmt->bind_param('si', $opt_text, $option_ids[$i]);
    $stmt->execute();
    $stmt->close();
}

$correct_option_id = $option_ids[$correct_index];
$update_correct_sql = "UPDATE questions SET correct_option_id = ? WHERE question_id = ?";
$stmt = $conn->prepare($update_correct_sql);
$stmt->bind_param('ii', $correct_option_id, $question_id);
$stmt->execute();
$stmt->close();

$conn->close();

header('Location: adminquizedit.php?quiz_id=' . $quiz_id . '&success=question_updated');
exit;
