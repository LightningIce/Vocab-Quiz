<?php
// submit_quiz_attempt.php

session_start();
header('Content-Type: application/json');

require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'student' && $_SESSION['role'] !== 'professional')) {
    header("Location: alllogin.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

if (
    !$data ||
    !isset($data['attempt_id']) ||
    !isset($data['score'])
) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit();
}

$attempt_id = intval($data['attempt_id']);
$score = floatval($data['score']);

$update_sql = "UPDATE user_quiz_attempts
               SET end_time = NOW(), score = ?, completed = 1
               WHERE attempt_id = ? AND user_id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("dii", $score, $attempt_id, $student_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Quiz submitted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit quiz.']);
}

$stmt->close();
$conn->close();
?>
