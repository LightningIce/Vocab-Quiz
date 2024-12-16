// delete_question.php
<?php
$host = 'localhost';
$db   = 'vocabquiz';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed.");
}

$question_id = isset($_GET['question_id']) ? (int)$_GET['question_id'] : 0;

// Find quiz_id for this question
$sql = "SELECT quiz_id FROM questions WHERE question_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $question_id);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();
$stmt->close();

if (!$question) {
    header('Location: adminquizedit.php?error=question_not_found');
    exit;
}

$quiz_id = (int)$question['quiz_id'];

// Count questions in this quiz
$count_sql = "SELECT COUNT(*) as total FROM questions WHERE quiz_id = ?";
$stmt = $conn->prepare($count_sql);
$stmt->bind_param('i', $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row['total'] <= 1) {
    header('Location: adminquizedit.php?error=cannot_delete_last_question');
    exit;
}

$delete_sql = "DELETE FROM questions WHERE question_id = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param('i', $question_id);
$stmt->execute();
$stmt->close();

$conn->close();

header('Location: adminquizedit.php?quiz_id=' . $quiz_id . '&success=question_deleted');
exit;
