<?php
// remove_user_answer.php

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

if (!$data || !isset($data['attempt_id']) || !isset($data['question_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit();
}

$attempt_id = intval($data['attempt_id']);
$question_id = intval($data['question_id']);

// Delete the user's previous answer
$delete_sql = "DELETE FROM user_answers WHERE attempt_id = ? AND question_id = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("ii", $attempt_id, $question_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Previous answer removed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove previous answer.']);
}

$stmt->close();
$conn->close();
?>
