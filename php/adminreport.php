<?php
// adminreport.php

session_start();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: alllogin.php");
    exit();
}

require_once 'db_connect.php';

$admin_user_id = $_SESSION['user_id'];

// Get the quiz_id from the URL
$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;

if ($quiz_id === 0) {
    die("Invalid Quiz ID.");
}

// Fetch statistics for the quiz

// 1. Get the total number of attempts
$attempts_stmt = $conn->prepare("SELECT COUNT(*) FROM user_quiz_attempts WHERE quiz_id = ?");
if ($attempts_stmt === false) {
    error_log("Prepare failed: " . htmlspecialchars($conn->error));
    die("An unexpected error occurred. Please try again later.");
}
$attempts_stmt->bind_param("i", $quiz_id);
if (!$attempts_stmt->execute()) {
    error_log("Execute failed: " . htmlspecialchars($attempts_stmt->error));
    die("An unexpected error occurred. Please try again later.");
}
$attempts_result = $attempts_stmt->get_result();
$total_attempts = $attempts_result->fetch_row()[0];
$attempts_stmt->close();

// 2. Get the average score
$average_score_stmt = $conn->prepare("SELECT AVG(score) FROM user_quiz_attempts WHERE quiz_id = ?");
if ($average_score_stmt === false) {
    error_log("Prepare failed: " . htmlspecialchars($conn->error));
    die("An unexpected error occurred. Please try again later.");
}
$average_score_stmt->bind_param("i", $quiz_id);
if (!$average_score_stmt->execute()) {
    error_log("Execute failed: " . htmlspecialchars($average_score_stmt->error));
    die("An unexpected error occurred. Please try again later.");
}
$average_score_result = $average_score_stmt->get_result();
$average_score = $average_score_result->fetch_row()[0];
$average_score_stmt->close();

// Handle NULL average_score by setting it to 0 if necessary
if ($average_score === null) {
    $average_score = 0;
}

// 3. Get the breakdown for each question
$questions_stmt = $conn->prepare("
    SELECT 
        q.question_id, 
        q.question_text, 
        COUNT(ua.chosen_option_id) AS total_answers,
        COUNT(CASE WHEN ua.chosen_option_id = q.correct_option_id THEN 1 END) AS correct_answers
    FROM 
        questions q
    LEFT JOIN 
        user_answers ua ON q.question_id = ua.question_id
    WHERE 
        q.quiz_id = ?
    GROUP BY 
        q.question_id, q.question_text
");
if ($questions_stmt === false) {
    error_log("Prepare failed: " . htmlspecialchars($conn->error));
    die("An unexpected error occurred. Please try again later.");
}
$questions_stmt->bind_param("i", $quiz_id);
if (!$questions_stmt->execute()) {
    error_log("Execute failed: " . htmlspecialchars($questions_stmt->error));
    die("An unexpected error occurred. Please try again later.");
}
$questions_result = $questions_stmt->get_result();
$questions_stmt->close();

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quiz Report - VocabQuiz Admin</title>
    <link rel="stylesheet" href="../css/adminheader.css">
    <style>
        /* Basic styling for the report page */
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 80%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h2,
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .statistics {
            margin-bottom: 30px;
        }

        .statistics p {
            font-size: 1.2em;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #333;
        }

        th {
            background-color: #333;
        }

        .btn {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 95%;
            }

            th,
            td {
                padding: 8px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include 'adminheader.php'; ?>

    <main>
        <div class="container">
            <h2>Quiz Report</h2>

            <div class="statistics">
                <h3>Quiz Statistics</h3>
                <p><strong>Total Attempts:</strong> <?php echo htmlspecialchars($total_attempts); ?></p>
                <p><strong>Average Score:</strong> <?php echo htmlspecialchars(number_format($average_score, 2)); ?>%</p>
            </div>

            <div class="questions-breakdown">
                <h3>Questions Breakdown</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Total Answers</th>
                            <th>Correct Answers</th>
                            <th>Incorrect Answers</th>
                            <th>Accuracy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($questions_result->num_rows > 0): ?>
                            <?php while ($row = $questions_result->fetch_assoc()): ?>
                                <?php
                                    $incorrect_answers = $row['total_answers'] - $row['correct_answers'];
                                    $accuracy = $row['total_answers'] > 0 ? ($row['correct_answers'] / $row['total_answers']) * 100 : 0;
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['question_text'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['total_answers'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['correct_answers'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($incorrect_answers, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($accuracy, 2), ENT_QUOTES, 'UTF-8'); ?>%</td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No questions found for this quiz.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <a href="admindashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </main>

    <?php include 'adminfooter.php'; ?>
</body>

</html>
