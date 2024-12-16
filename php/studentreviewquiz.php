<?php
// studentreviewquiz.php

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

// Verify that the attempt belongs to the logged-in student
$verify_sql = "SELECT ua.score, ua.quiz_id, q.quiz_title, q.category, ua.start_time, ua.end_time
               FROM user_quiz_attempts ua
               JOIN quizzes q ON ua.quiz_id = q.quiz_id
               WHERE ua.attempt_id = ? AND ua.user_id = ?";
$stmt = $conn->prepare($verify_sql);
$stmt->bind_param("ii", $attempt_id, $student_id);
$stmt->execute();
$verify_result = $stmt->get_result();

if ($verify_result->num_rows === 0) {
    die("Quiz attempt not found or you do not have permission to view it.");
}

$attempt = $verify_result->fetch_assoc();

// Fetch total number of questions
$total_questions_sql = "SELECT COUNT(*) as total FROM questions WHERE quiz_id = ?";
$stmt = $conn->prepare($total_questions_sql);
$stmt->bind_param("i", $attempt['quiz_id']);
$stmt->execute();
$total_result = $stmt->get_result();
$total_questions_row = $total_result->fetch_assoc();
$total_questions = isset($total_questions_row['total']) ? intval($total_questions_row['total']) : 0;

// Calculate percentage
if ($total_questions > 0) {
    $percentage = ($attempt['score'] / $total_questions) * 100;
} else {
    $percentage = 0;
}

// Fetch all user answers for this attempt
$user_answers_sql = "SELECT ua.question_id, ua.chosen_option_id, q.question_text, q.correct_option_id
                    FROM user_answers ua
                    JOIN questions q ON ua.question_id = q.question_id
                    WHERE ua.attempt_id = ?";
$stmt = $conn->prepare($user_answers_sql);
$stmt->bind_param("i", $attempt_id);
$stmt->execute();
$user_answers_result = $stmt->get_result();

$user_answers = [];
while ($row = $user_answers_result->fetch_assoc()) {
    $user_answers[$row['question_id']] = [
        'chosen_option_id' => $row['chosen_option_id'],
        'question_text' => $row['question_text'],
        'correct_option_id' => $row['correct_option_id']
    ];
}

// Fetch all questions and options for the quiz
$questions_sql = "
    SELECT 
        q.question_id, 
        q.question_text, 
        o.option_id, 
        o.option_text
    FROM questions q
    JOIN options o ON q.question_id = o.question_id
    WHERE q.quiz_id = ?
    ORDER BY q.question_id ASC, o.option_id ASC
";
$stmt = $conn->prepare($questions_sql);
$stmt->bind_param("i", $attempt['quiz_id']);
$stmt->execute();
$questions_result = $stmt->get_result();

$questions = [];
while ($row = $questions_result->fetch_assoc()) {
    $qid = $row['question_id'];
    if (!isset($questions[$qid])) {
        $questions[$qid] = [
            'question_id' => $qid,
            'question_text' => $row['question_text'],
            'options' => []
        ];
    }
    $questions[$qid]['options'][] = [
        'option_id' => $row['option_id'],
        'option_text' => $row['option_text']
    ];
}

$conn->close();

// Convert questions to a zero-indexed array for display
$questions = array_values($questions);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Quiz - <?php echo htmlspecialchars($attempt['quiz_title']); ?></title>
    <link rel="stylesheet" href="../css/studentheader.css">
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="../css/font-awesome.css">
    <style>
        /* Import fonts */
        @import url('https://fonts.googleapis.com/css2?family=Oleo+Script:wght@400;700&display=swap');

        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .review-container {
            width: 90%;
            max-width: 1000px;
            background: #1e1e1e;
            border-radius: 10px;
            padding: 20px;
            margin: 50px auto;
            text-align: center;
        }

        .review-header {
            margin-bottom: 20px;
        }

        .review-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .review-header p {
            font-size: 1.2rem;
            margin: 5px 0;
        }

        .question-review {
            background-color: #2e2e2e;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: left;
        }

        .question-review h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .options {
            list-style-type: none;
            padding: 0;
        }

        .options li {
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 5px;
            background-color: #3e3e3e;
            transition: background-color 0.3s;
        }

        .options li.correct {
            background-color: #28a745;
        }

        .options li.incorrect {
            background-color: #dc3545;
        }

        .options li.student-answer {
            border: 2px solid #ffc107;
        }

        .buttons {
            margin-top: 30px;
        }

        .buttons a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1e1e1e;
            color: #f4f4f4;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
            margin: 5px;
        }

        .buttons a:hover {
            background-color: #333333;
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {
            .review-container {
                padding: 10px;
            }

            .review-header h1 {
                font-size: 2rem;
            }

            .review-header p {
                font-size: 1rem;
            }

            .question-review h2 {
                font-size: 1.2rem;
            }

            .buttons a {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>

<body>
    <?php include 'studentheader.php'; ?>

    <div class="review-container">
        <div class="review-header">
            <h1>Review Quiz: <?php echo htmlspecialchars($attempt['quiz_title']); ?></h1>
            <p>Category: <?php echo htmlspecialchars($attempt['category']); ?></p>
            <p>Attempted on: <?php echo htmlspecialchars($attempt['start_time']); ?> - <?php echo htmlspecialchars($attempt['end_time']); ?></p>
            <p>Total Score: <?php echo htmlspecialchars(number_format($attempt['score'], 2)); ?> / <?php echo htmlspecialchars($total_questions); ?> (<?php echo htmlspecialchars(number_format($percentage, 2)); ?>%)</p>
        </div>

        <?php foreach ($questions as $question):
            $qid = $question['question_id'];
            $chosen_option_id = isset($user_answers[$qid]['chosen_option_id']) ? $user_answers[$qid]['chosen_option_id'] : null;
            $correct_option_id = $user_answers[$qid]['correct_option_id'];
        ?>
            <div class="question-review">
                <h2>Q: <?php echo htmlspecialchars($question['question_text']); ?></h2>
                <ul class="options">
                    <?php foreach ($question['options'] as $option):
                        $is_correct = $option['option_id'] === $correct_option_id;
                        $is_student_answer = $option['option_id'] === $chosen_option_id;
                    ?>
                        <li
                            class="<?php
                                    if ($is_correct) echo 'correct';
                                    if ($is_student_answer && !$is_correct) echo 'incorrect';
                                    if ($is_student_answer) echo ' student-answer';
                                    ?>">
                            <?php echo htmlspecialchars($option['option_text']); ?>
                            <?php
                            if ($is_correct) echo ' <strong>(Correct Answer)</strong>';
                            if ($is_student_answer) {
                                if ($is_correct) {
                                    echo ' <strong>(Your Answer - Correct)</strong>';
                                } else {
                                    echo ' <strong>(Your Answer - Incorrect)</strong>';
                                }
                            }
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

        <div class="buttons">
            <a href="studentdashboard.php">Back to Dashboard</a>
            <a href="studentquizquestion.php?quiz_id=<?php echo urlencode($attempt['quiz_id']); ?>">Try Again</a>
        </div>
    </div>

    <?php include 'studentfooter.php'?>
    <script src="../js/studentHeader.js"></script>
</body>

</html>