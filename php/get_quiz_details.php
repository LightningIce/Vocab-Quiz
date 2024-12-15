<?php
// get_quiz_details.php

session_start();

// Set header for JSON response
header('Content-Type: application/json');

require_once 'db_connect.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$student_id = $_SESSION['student_id'];

// Get the attempt_id from GET parameters
if (!isset($_GET['attempt_id'])) {
    echo json_encode(['success' => false, 'message' => 'No attempt ID provided.']);
    exit();
}

$attempt_id = intval($_GET['attempt_id']);

// Fetch quiz attempt details
// Replaced 'date_taken' with 'start_time'
$sql = "SELECT 
            q.quiz_title, 
            q.description,
            q.category,
            ua.score,
            ua.start_time
        FROM user_quiz_attempts ua
        JOIN quizzes q ON ua.quiz_id = q.quiz_id
        WHERE ua.attempt_id = ? AND ua.user_id = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . htmlspecialchars($conn->error)]);
    exit();
}
$stmt->bind_param("ii", $attempt_id, $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $quiz = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'quiz' => [
            'title' => $quiz['quiz_title'],
            'description' => $quiz['description'],
            'category' => ucfirst($quiz['category']),
            'score' => $quiz['score'],
            'start_time' => $quiz['start_time']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Quiz attempt not found.']);
}

$stmt->close();
$conn->close();
?>
