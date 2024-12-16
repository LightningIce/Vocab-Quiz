<?php
// Database Connection
$host = 'localhost';
$db   = 'vocabquiz';
$user = 'root';
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Quiz Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createQuiz'])) {
    $quizName = $_POST['quizName'];
    $description = $_POST['description'];
    $difficulty = $_POST['difficulty'];

    if (!$quizName || !$description || !$difficulty) {
        die("<script>alert('All fields are required to create a quiz.'); window.location.href = 'adminquizadd.php';</script>");
    }

    // Insert Quiz into `quizzes` Table
    $stmt = $conn->prepare("INSERT INTO quizzes (quiz_title, description, category, created_by) VALUES (?, ?, ?, 1)");
    $stmt->bind_param("sss", $quizName, $description, $difficulty);
    $stmt->execute();

    $quizId = $conn->insert_id;
    $stmt->close();

    echo "<script>alert('Quiz created successfully! You can now add questions to it.'); window.location.href = 'adminquizadd.php?quiz_id=$quizId';</script>";
    exit;
}

// Handle Question Addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addQuestion'])) {
    $quizId = $_POST['quizId'];
    $questionText = $_POST['question'];
    $optionA = $_POST['optionA'];
    $optionB = $_POST['optionB'];
    $optionC = $_POST['optionC'];
    $optionD = $_POST['optionD'];
    $correctAnswer = $_POST['correctAnswer'];

    if (!$quizId || !$questionText || !$optionA || !$optionB || !$optionC || !$optionD || !$correctAnswer) {
        die("<script>alert('All fields are required to add a question.'); window.location.href = 'adminquizadd.php?quiz_id=$quizId';</script>");
    }

    // Insert Question into `questions` Table
    $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
    $stmt->bind_param("is", $quizId, $questionText);
    $stmt->execute();
    $questionId = $conn->insert_id;

    // Insert Options into `options` Table
    $options = ['A' => $optionA, 'B' => $optionB, 'C' => $optionC, 'D' => $optionD];
    $optionIds = [];

    foreach ($options as $key => $optionText) {
        $stmt = $conn->prepare("INSERT INTO options (question_id, option_text) VALUES (?, ?)");
        $stmt->bind_param("is", $questionId, $optionText);
        $stmt->execute();
        $optionIds[$key] = $conn->insert_id;
    }

    // Update Correct Option in `questions` Table
    $correctOptionId = $optionIds[$correctAnswer];
    $stmt = $conn->prepare("UPDATE questions SET correct_option_id = ? WHERE question_id = ?");
    $stmt->bind_param("ii", $correctOptionId, $questionId);
    $stmt->execute();

    $stmt->close();

    echo "<script>alert('Question added successfully!'); window.location.href = 'adminquizadd.php?quiz_id=$quizId';</script>";
    exit;
}

// Handle Done Action
if (isset($_GET['done']) && isset($_GET['quiz_id'])) {
    echo "<script>alert('You have finished adding questions!'); window.location.href = 'dashboard.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quiz or Questions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            margin: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .done-btn {
            background-color: #007bff;
            margin-top: 20px;
        }
        .done-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!isset($_GET['quiz_id'])): ?>
        <!-- Quiz Creation Form -->
        <h2>Create New Quiz</h2>
        <form action="adminquizadd.php" method="POST">
            <input type="hidden" name="createQuiz" value="1">
            <label>Quiz Name</label>
            <input type="text" name="quizName" placeholder="Enter quiz name" required>

            <label>Description</label>
            <textarea name="description" placeholder="Enter quiz description" required></textarea>

            <label>Difficulty</label>
            <select name="difficulty" required>
                <option value="easy">Easy</option>
                <option value="hard">Hard</option>
                <option value="business">Business</option>
            </select>

            <button type="submit">Create Quiz</button>
        </form>
        <?php else: ?>
        <!-- Question Addition Form -->
        <h2>Add Question to Quiz</h2>
        <form action="adminquizadd.php" method="POST">
            <input type="hidden" name="addQuestion" value="1">
            <input type="hidden" name="quizId" value="<?php echo $_GET['quiz_id']; ?>">

            <label>Question</label>
            <textarea name="question" placeholder="Enter question" required></textarea>

            <label>Option A</label>
            <input type="text" name="optionA" placeholder="Enter option A" required>

            <label>Option B</label>
            <input type="text" name="optionB" placeholder="Enter option B" required>

            <label>Option C</label>
            <input type="text" name="optionC" placeholder="Enter option C" required>

            <label>Option D</label>
            <input type="text" name="optionD" placeholder="Enter option D" required>

            <label>Correct Answer</label>
            <select name="correctAnswer" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>

            <button type="submit">Add Question</button>
        </form>
        <form action="adminquizadd.php" method="GET">
            <input type="hidden" name="done" value="1">
            <input type="hidden" name="quiz_id" value="<?php echo $_GET['quiz_id']; ?>">
            <button type="submit" class="done-btn">Done</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
