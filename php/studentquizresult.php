<?php
// studentquizresult.php

session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student' && $_SESSION['role'] !== 'professional') {
    header("Location: alllogin.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Check if attempt_id is set
if (!isset($_GET['attempt_id'])) {
    die("Attempt ID not specified.");
}

$attempt_id = intval($_GET['attempt_id']);

// Fetch quiz attempt details
$attempt_sql = "SELECT ua.score, q.quiz_title, q.category, ua.start_time, ua.end_time
                FROM user_quiz_attempts ua
                JOIN quizzes q ON ua.quiz_id = q.quiz_id
                WHERE ua.attempt_id = ? AND ua.user_id = ?";
$stmt = $conn->prepare($attempt_sql);
$stmt->bind_param("ii", $attempt_id, $student_id);
$stmt->execute();
$attempt_result = $stmt->get_result();

if ($attempt_result->num_rows === 0) {
    die("Quiz attempt not found.");
}

$attempt = $attempt_result->fetch_assoc();

// Determine pass/fail (e.g., 60% is passing)
$total_questions_sql = "SELECT COUNT(*) as total FROM questions WHERE quiz_id = (SELECT quiz_id FROM user_quiz_attempts WHERE attempt_id = ?)";
$stmt = $conn->prepare($total_questions_sql);
$stmt->bind_param("i", $attempt_id);
$stmt->execute();
$total_result = $stmt->get_result();
$total_questions = $total_result->fetch_assoc()['total'];

$percentage = $total_questions > 0 ? ($attempt['score'] / $total_questions) * 100 : 0;
$passing_score = 60; // Percentage required to pass
$passed = $percentage >= $passing_score;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result - <?php echo htmlspecialchars($attempt['quiz_title']); ?></title>
    <link rel="stylesheet" href="../css/studentheader.css">
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="../css/font-awesome.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .result-container {
            background-color: #1e1e1e;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            width: 80%;
            max-width: 500px;
        }

        .result-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .result-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .result-container .score {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .result-container .status {
            font-size: 1.5rem;
            color: <?php echo $passed ? '#28a745' : '#dc3545'; ?>;
            margin-bottom: 30px;
        }

        .result-container a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1e1e1e;
            color: #f4f4f4;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
            margin: 5px;
        }

        .result-container a:hover {
            background-color: #333333;
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {
            .result-container {
                padding: 20px;
            }

            .result-container h1 {
                font-size: 2rem;
            }

            .result-container .score {
                font-size: 1.5rem;
            }

            .result-container .status {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'studentheader.php'; ?>

    <div class="result-container">
        <h1>Quiz Results</h1>
        <p>You have completed the quiz.</p>
        <div class="score">Score: <?php echo htmlspecialchars($attempt['score']); ?> / <?php echo htmlspecialchars($total_questions); ?> (<?php echo number_format($percentage, 2); ?>%)</div>
        <div class="status"><?php echo $passed ? 'Congratulations! You passed the quiz.' : 'You did not pass the quiz. Try again!'; ?></div>
        <a href="studentdashboard.php">Back to Dashboard</a>
        <a href="studentreviewquiz.php?attempt_id=<?php echo urlencode($attempt_id); ?>">Review Quiz</a>
    </div>
    <script src="../js/studentHeader.js"></script>
</body>

</html>
