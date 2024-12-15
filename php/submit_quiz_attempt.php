<?php
// submit_quiz_attempt.php

session_start();
header('Content-Type: application/json');

require_once 'db_connect.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$student_id = $_SESSION['student_id'];

// Get the POST data
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

// Update the quiz attempt with end_time and score
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
