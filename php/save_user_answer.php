<?php
// save_user_answer.php

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
    !isset($data['question_id']) ||
    !isset($data['chosen_option_id']) ||
    !isset($data['is_correct'])
) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit();
}

$attempt_id = intval($data['attempt_id']);
$question_id = intval($data['question_id']);
$chosen_option_id = intval($data['chosen_option_id']);
$is_correct = intval($data['is_correct']);

$insert_sql = "INSERT INTO user_answers (attempt_id, question_id, chosen_option_id, correct)
               VALUES (?, ?, ?, ?)
               ON DUPLICATE KEY UPDATE chosen_option_id = VALUES(chosen_option_id), correct = VALUES(correct), answered_at = NOW()";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("iiii", $attempt_id, $question_id, $chosen_option_id, $is_correct);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Answer saved successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save answer.']);
}

$stmt->close();
$conn->close();
?>
