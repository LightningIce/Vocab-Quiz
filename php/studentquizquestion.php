<?php
// studentquizquestion.php

session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'student' && $_SESSION['role'] !== 'professional')) {
    header("Location: alllogin.php");
    exit();
}

$student_id = $_SESSION['user_id'];

if (!isset($_GET['quiz_id'])) {
    die("Quiz ID not specified.");
}

$quiz_id = intval($_GET['quiz_id']);

$quiz_sql = "SELECT quiz_title, description, category FROM quizzes WHERE quiz_id = ?";
$stmt = $conn->prepare($quiz_sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz_result = $stmt->get_result();

if ($quiz_result->num_rows === 0) {
    die("Quiz not found.");
}

$quiz = $quiz_result->fetch_assoc();

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
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="../css/font-awesome.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oleo+Script:wght@400;700&display=swap');

        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #f4f4f4;
            margin: 0;
            padding: 0;
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
            justify-content: flex-end;
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
            margin-left: 10px;
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
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <?php include 'studentheader.php'; ?>

    <div class="quiz-container">
        <div class="quiz-header">
            <span class="progress" id="progress">Question 1 of <?php echo count($questions); ?></span>
        </div>
        <div class="question">
            <h2 id="question">Question text</h2>
            <div class="options" id="options">
            </div>
        </div>
        <div class="navigation-buttons">
            <button class="nav-button" id="nextBtn">Next</button>
        </div>
        <button class="submit-button" id="submitBtn" style="display: none;">Submit Quiz</button>
        <img src="../images/questions.png" alt="question" class="question-img">
    </div>

    <?php include 'studentfooter.php'; ?>

    <script src="../js/studentHeader.js"></script>
    <script>
        const questions = <?php echo $questions_json; ?>;

        const questionElement = document.getElementById('question');
        const optionsContainer = document.getElementById('options');
        const nextButton = document.getElementById('nextBtn');
        const progressElement = document.getElementById('progress');
        const submitButton = document.getElementById('submitBtn');

        let currentQuestionIndex = 0;
        let score = 0;

        questions.forEach(question => {
            question.selectedAnswer = null;
        });

        function startQuiz() {
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

                if (currentQuestion.selectedAnswer === option.option_id) {
                    button.classList.add('selected');
                }

                button.addEventListener('click', () => selectAnswer(button, currentQuestion));
                optionsContainer.appendChild(button);
            });

            nextButton.style.display = currentQuestionIndex < questions.length - 1 ? 'inline-block' : 'none';
            submitButton.style.display = currentQuestionIndex === questions.length - 1 ? 'inline-block' : 'none';

            progressElement.textContent = `Question ${currentQuestionIndex + 1} of ${questions.length}`;
        }

        function selectAnswer(button, question) {
            const selectedButtons = optionsContainer.querySelectorAll('.selected');
            selectedButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
            question.selectedAnswer = button.dataset.optionId;
        }

        function goNext() {
            const currentQuestion = questions[currentQuestionIndex];
            const selectedOptionId = currentQuestion.selectedAnswer;

            if (!selectedOptionId) {
                alert('Please select an answer before proceeding.');
                return;
            }

            const isCorrect = currentQuestion.options.find(option => option.option_id == selectedOptionId).is_correct;

            fetch('save_user_answer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    attempt_id: sessionStorage.getItem('attempt_id'),
                    question_id: currentQuestion.question_id,
                    chosen_option_id: selectedOptionId,
                    is_correct: isCorrect ? 1 : 0
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (isCorrect) {
                            score++;
                        }
                        currentQuestionIndex++;
                        showQuestion();
                    } else {
                        alert('Failed to save your answer. Please try again.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('There was an error saving your answer. Please try again.');
                });
        }

        function submitQuiz() {
            const currentQuestion = questions[currentQuestionIndex];
            const selectedOptionId = currentQuestion.selectedAnswer;

            if (!selectedOptionId) {
                alert('Please select an answer before submitting.');
                return;
            }

            const isCorrect = currentQuestion.options.find(option => option.option_id == selectedOptionId).is_correct;

            fetch('save_user_answer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    attempt_id: sessionStorage.getItem('attempt_id'),
                    question_id: currentQuestion.question_id,
                    chosen_option_id: selectedOptionId,
                    is_correct: isCorrect ? 1 : 0
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (isCorrect) {
                            score++;
                        }
                        fetch('submit_quiz_attempt.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                attempt_id: sessionStorage.getItem('attempt_id'),
                                score: score
                            }),
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.href = `studentquizresult.php?attempt_id=${sessionStorage.getItem('attempt_id')}`;
                                } else {
                                    alert('There was an error submitting your quiz. Please try again.');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                alert('There was an error submitting your quiz. Please try again.');
                            });
                    } else {
                        alert('Failed to save your answer. Please try again.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('There was an error saving your answer. Please try again.');
                });
        }

        nextButton.addEventListener('click', goNext);
        submitButton.addEventListener('click', submitQuiz);

        startQuiz();
    </script>
</body>

</html>
