<?php
session_start();
header('Content-Type: application/json');

require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'student' && $_SESSION['role'] !== 'professional')) {
    header("Location: alllogin.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['quiz_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit();
}

$quiz_id = intval($data['quiz_id']);

$insert_sql = "INSERT INTO user_quiz_attempts (user_id, quiz_id, start_time) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("ii", $student_id, $quiz_id);

if ($stmt->execute()) {
    $attempt_id = $stmt->insert_id;
    echo json_encode(['success' => true, 'attempt_id' => $attempt_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to start quiz attempt.']);
}

$stmt->close();
$conn->close();
?>
