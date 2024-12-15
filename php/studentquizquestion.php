<?php
// studentquizquestion.php

session_start();
require_once 'db_connect.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: alllogin.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Check if quiz_id is set
if (!isset($_GET['quiz_id'])) {
    die("Quiz ID not specified.");
}

$quiz_id = intval($_GET['quiz_id']);

// Fetch quiz details
$quiz_sql = "SELECT quiz_title, quiz_description, quiz_category FROM quiz WHERE quiz_id = ?";
$stmt = $conn->prepare($quiz_sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz_result = $stmt->get_result();

if ($quiz_result->num_rows === 0) {
    die("Quiz not found.");
}

$quiz = $quiz_result->fetch_assoc();

// Fetch questions and answers
$questions_sql = "SELECT q.question_id, q.question_text, q.question_type, a.answer_id, a.answer_text, a.is_correct
                FROM question q
                JOIN answer a ON q.question_id = a.question_id
                WHERE q.quiz_id = ?
                ORDER BY q.question_id ASC, a.answer_id ASC";
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
            'question_type' => $row['question_type'],
            'options' => []
        ];
    }
    $questions[$qid]['options'][] = [
        'answer_id' => $row['answer_id'],
        'answer_text' => $row['answer_text'],
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

        .progress {
            font-size: 1.2rem;
        }

        .score {
            font-size: 1.2rem;
        }

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
            justify-content: space-between;
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
        }
    </style>
</head>

<body>
    <?php include 'studentheader.php'; ?>

    <div class="quiz-container">
        <div class="quiz-header">
            <span class="progress" id="progress">Question 1 of <?php echo count($questions); ?></span>
            <span class="score" id="score">Score: 0</span>
        </div>
        <div class="question">
            <h2 id="question">Question text</h2>
            <div class="options" id="options">
                <!-- Options will be dynamically inserted here -->
            </div>
        </div>
        <div class="navigation-buttons">
            <button class="nav-button" id="backBtn" style="display: none;">Back</button>
            <button class="nav-button" id="nextBtn">Next</button>
        </div>
        <button class="submit-button" id="submitBtn" style="display: none;">Submit Quiz</button>
    </div>

    <script>
        // Fetch questions from PHP
        const questions = <?php echo $questions_json; ?>;

        const questionElement = document.getElementById('question');
        const optionsContainer = document.getElementById('options');
        const nextButton = document.getElementById('nextBtn');
        const backButton = document.getElementById('backBtn');
        const progressElement = document.getElementById('progress');
        const scoreElement = document.getElementById('score');
        const submitButton = document.getElementById('submitBtn');

        let currentQuestionIndex = 0;
        let score = 0;

        // Initialize selected answers
        questions.forEach(question => {
            question.selectedAnswer = null;
        });

        function showQuestion() {
            const currentQuestion = questions[currentQuestionIndex];
            questionElement.textContent = `Q${currentQuestionIndex + 1}: ${currentQuestion.question_text}`;
            optionsContainer.innerHTML = '';

            currentQuestion.options.forEach(option => {
                const button = document.createElement('button');
                button.classList.add('option');
                button.textContent = option.answer_text;
                button.dataset.correct = option.is_correct;

                // Highlight if previously selected
                if (currentQuestion.selectedAnswer === option.answer_id) {
                    button.classList.add('selected');
                }

                button.addEventListener('click', () => selectAnswer(button, currentQuestion));
                optionsContainer.appendChild(button);
            });

            // Update navigation buttons
            backButton.style.display = currentQuestionIndex > 0 ? 'inline-block' : 'none';
            nextButton.style.display = currentQuestionIndex < questions.length - 1 ? 'inline-block' : 'none';
            submitButton.style.display = currentQuestionIndex === questions.length - 1 ? 'inline-block' : 'none';

            // Update progress and score
            progressElement.textContent = `Question ${currentQuestionIndex + 1} of ${questions.length}`;
            scoreElement.textContent = `Score: ${score}`;
        }

        function selectAnswer(button, question) {
            const previouslySelectedButton = optionsContainer.querySelector('.selected');
            if (previouslySelectedButton) {
                // If previously selected was correct, decrease score
                if (previouslySelectedButton.dataset.correct === 'true') {
                    score--;
                }
                previouslySelectedButton.classList.remove('selected');
            }

            // Select the new answer
            button.classList.add('selected');
            question.selectedAnswer = button.dataset.correct === 'true' ? button.textContent : null;

            // If the new answer is correct, increase score
            if (button.dataset.correct === 'true') {
                score++;
            }

            // Update the score display
            scoreElement.textContent = `Score: ${score}`;
        }

        function goNext() {
            if (currentQuestionIndex < questions.length - 1) {
                currentQuestionIndex++;
                showQuestion();
            }
        }

        function goBack() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                showQuestion();
            }
        }

        function submitQuiz() {
            // Prepare data to send
            const results = questions.map(q => ({
                question_id: q.question_id,
                selected_answer: q.selectedAnswer
            }));

            // Send results via AJAX to save in the database
            fetch('submit_quiz.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    student_id: <?php echo $student_id; ?>,
                    quiz_id: <?php echo $quiz_id; ?>,
                    results: results,
                    score: score
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to the results page
                    window.location.href = `studentquizresult.php?quiz_id=<?php echo $quiz_id; ?>&score=${score}`;
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
        backButton.addEventListener('click', goBack);
        submitButton.addEventListener('click', submitQuiz);

        // Initialize the quiz
        showQuestion();
    </script>
</body>

</html>
