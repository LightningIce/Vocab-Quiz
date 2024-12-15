<?php
// studentquizquestion.php

session_start();
require_once 'db_connect.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id']) || $_SESSION['role'] !== 'student') {
    header("Location: alllogin.php"); // Redirect to login page if not logged in
    exit();
}

$student_id = $_SESSION['student_id'];

// Check if quiz_id is set
if (!isset($_GET['quiz_id'])) {
    die("Quiz ID not specified.");
}

$quiz_id = intval($_GET['quiz_id']);

// Fetch quiz details
$quiz_sql = "SELECT quiz_title, description, category FROM quizzes WHERE quiz_id = ?";
$stmt = $conn->prepare($quiz_sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz_result = $stmt->get_result();

if ($quiz_result->num_rows === 0) {
    die("Quiz not found.");
}

$quiz = $quiz_result->fetch_assoc();

// Fetch questions and options with computed is_correct
$questions_sql = "
    SELECT 
        q.question_id, 
        q.question_text, 
        q.correct_option_id, 
        o.option_id, 
        o.option_text,
        CASE 
            WHEN o.option_id = q.correct_option_id THEN 1 
            ELSE 0 
        END AS is_correct
    FROM questions q
    JOIN options o ON q.question_id = o.question_id
    WHERE q.quiz_id = ?
    ORDER BY q.question_id ASC, o.option_id ASC
";
$stmt = $conn->prepare($questions_sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$questions_result = $stmt->get_result();

$questions = [];
while ($row = $questions_result->fetch_assoc()) {
    $qid = $row['question_id'];
    if (!isset($questions[$qid])) {
        $questions[$qid] = [
            'question_id' => $qid,
            'question_text' => $row['question_text'],
            'correct_option_id' => $row['correct_option_id'],
            'options' => []
        ];
    }
    $questions[$qid]['options'][] = [
        'option_id' => $row['option_id'],
        'option_text' => $row['option_text'],
        'is_correct' => $row['is_correct']
    ];
}

$conn->close();

// Convert questions to a zero-indexed array for JavaScript
$questions = array_values($questions);
$questions_json = json_encode($questions, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz - <?php echo htmlspecialchars($quiz['quiz_title']); ?></title>
    <link rel="stylesheet" href="../css/studentheader.css">
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

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #000;
            color: #f4f4f4;
            padding: 1rem 2rem;
        }

        header .logo-img {
            width: 35px;
            height: 35px;
            margin-right: 10px;
        }

        header .name {
            font-size: 2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
        }

        .quiz-container {
            width: 90%;
            max-width: 800px;
            background: #1e1e1e;
            border-radius: 10px;
            padding: 20px;
            margin: 50px auto;
            text-align: center;
        }

        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        /* Removed styles related to .score */

        .question {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .option {
            padding: 10px;
            border: none;
            background-color: #333333;
            color: #f4f4f4;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            text-align: left;
        }

        .option:hover,
        .option.selected {
            background-color: #555555;
            transform: translateY(-2px);
        }

        .navigation-buttons {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
        }

        .nav-button {
            padding: 10px 20px;
            border: none;
            background-color: #1e1e1e;
            color: #f4f4f4;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-left: 10px; /* Space between buttons */
        }

        .nav-button:hover {
            background-color: #333333;
            transform: translateY(-2px);
        }

        .submit-button {
            padding: 10px 20px;
            border: none;
            background-color: #28a745;
            color: #ffffff;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 20px;
        }

        .submit-button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .question-img {
            width: 100%;
            max-width: 250px;
            height: auto;
            display: block;
            margin: 20px auto 0;
        }

        @media (max-width: 600px) {
            .quiz-container {
                padding: 10px;
            }

            .nav-button,
            .submit-button {
                width: 100%;
                margin: 5px 0;
            }

            .navigation-buttons {
                justify-content: center; /* Center buttons on small screens */
            }
        }
    </style>
</head>

<body>
    <?php include 'studentheader.php'; ?>

    <div class="quiz-container">
        <div class="quiz-header">
            <span class="progress" id="progress">Question 1 of <?php echo count($questions); ?></span>
            <!-- Removed the score display -->
            <!-- <span class="score" id="score">Score: 0</span> -->
        </div>
        <div class="question">
            <h2 id="question">Question text</h2>
            <div class="options" id="options">
                <!-- Options will be dynamically inserted here -->
            </div>
        </div>
        <div class="navigation-buttons">
            <!-- Removed Back Button -->
            <button class="nav-button" id="nextBtn">Next</button>
        </div>
        <button class="submit-button" id="submitBtn" style="display: none;">Submit Quiz</button>
        <img src="../images/questions.png" alt="question" class="question-img">
    </div>

    <script>
        // Fetch questions from PHP
        const questions = <?php echo $questions_json; ?>;

        const questionElement = document.getElementById('question');
        const optionsContainer = document.getElementById('options');
        const nextButton = document.getElementById('nextBtn');
        const progressElement = document.getElementById('progress');
        // Removed the scoreElement as it's no longer needed
        // const scoreElement = document.getElementById('score');
        const submitButton = document.getElementById('submitBtn');

        let currentQuestionIndex = 0;
        let score = 0;

        // Initialize selected answers
        questions.forEach(question => {
            question.selectedAnswer = null;
        });

        // Function to start a quiz attempt
        function startQuiz() {
            // Create a new quiz attempt in the database
            fetch('start_quiz_attempt.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    student_id: <?php echo $student_id; ?>,
                    quiz_id: <?php echo $quiz_id; ?>,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store the attempt_id in session storage for later use
                        sessionStorage.setItem('attempt_id', data.attempt_id);
                        showQuestion();
                    } else {
                        alert('Failed to start quiz. Please try again.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('There was an error starting the quiz. Please try again.');
                });
        }

        function showQuestion() {
            const currentQuestion = questions[currentQuestionIndex];
            questionElement.textContent = `Q${currentQuestionIndex + 1}: ${currentQuestion.question_text}`;
            optionsContainer.innerHTML = '';

            currentQuestion.options.forEach(option => {
                const button = document.createElement('button');
                button.classList.add('option');
                button.textContent = option.option_text;
                button.dataset.optionId = option.option_id;
                button.dataset.isCorrect = option.is_correct;

                // Highlight if previously selected
                if (currentQuestion.selectedAnswer === option.option_id) {
                    button.classList.add('selected');
                }

                button.addEventListener('click', () => selectAnswer(button, currentQuestion));
                optionsContainer.appendChild(button);
            });

            // Update navigation buttons
            // Removed Back Button logic
            nextButton.style.display = currentQuestionIndex < questions.length - 1 ? 'inline-block' : 'none';
            submitButton.style.display = currentQuestionIndex === questions.length - 1 ? 'inline-block' : 'none';

            // Update progress
            progressElement.textContent = `Question ${currentQuestionIndex + 1} of ${questions.length}`;
            // Removed score display update
            // scoreElement.textContent = `Score: ${score}`;
        }

        function selectAnswer(button, question) {
            const previouslySelectedButton = optionsContainer.querySelector('.selected');
            const attempt_id = sessionStorage.getItem('attempt_id');

            if (!attempt_id) {
                alert('Quiz attempt not found. Please refresh the page.');
                return;
            }

            if (previouslySelectedButton) {
                // If previously selected was correct, decrease score
                if (previouslySelectedButton.dataset.isCorrect === '1') {
                    score--;
                }
                previouslySelectedButton.classList.remove('selected');

                // Remove previous answer from user_answers table
                fetch('remove_user_answer.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        attempt_id: attempt_id,
                        question_id: question.question_id
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            console.error('Failed to remove previous answer:', data.message);
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            }

            // Select the new answer
            button.classList.add('selected');
            question.selectedAnswer = button.dataset.optionId;

            // If the new answer is correct, increase score
            if (button.dataset.isCorrect === '1') {
                score++;
            }

            // Update the score display (removed)
            // scoreElement.textContent = `Score: ${score}`;

            // Save the answer to the database
            fetch('save_user_answer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    attempt_id: attempt_id,
                    question_id: question.question_id,
                    chosen_option_id: button.dataset.optionId,
                    is_correct: button.dataset.isCorrect === '1' ? 1 : 0
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Failed to save answer:', data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }

        function goNext() {
            if (currentQuestionIndex < questions.length - 1) {
                currentQuestionIndex++;
                showQuestion();
            }
        }

        function submitQuiz() {
            const attempt_id = sessionStorage.getItem('attempt_id');

            if (!attempt_id) {
                alert('Quiz attempt not found. Please refresh the page.');
                return;
            }

            // Update the end_time and calculate the score in the database
            fetch('submit_quiz_attempt.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    attempt_id: attempt_id,
                    score: score
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to the results page
                        window.location.href = `studentquizresult.php?attempt_id=${attempt_id}`;
                    } else {
                        alert('There was an error submitting your quiz. Please try again.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('There was an error submitting your quiz. Please try again.');
                });
        }

        nextButton.addEventListener('click', goNext);
        submitButton.addEventListener('click', submitQuiz);

        // Start the quiz
        startQuiz();
    </script>
</body>

</html>
