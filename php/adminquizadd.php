<?php
// adminquizadd.php

session_start();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: alllogin.php");
    exit();
}

require_once 'db_connect.php';

$admin_user_id = $_SESSION['user_id'];

// Initialize variables
$quizName = $description = $difficulty = '';
$errors = [];
$success = '';

// Handle Quiz Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createQuiz'])) {
    // Retrieve and sanitize form inputs
    $quizName = trim($_POST['quizName']);
    $description = trim($_POST['description']);
    $difficulty = trim($_POST['difficulty']);

    // Basic validation
    if (empty($quizName) || empty($description) || empty($difficulty)) {
        $errors[] = "All fields are required to create a quiz.";
    }

    // Validate difficulty value
    $valid_difficulties = ['easy', 'hard', 'business'];
    if (!in_array(strtolower($difficulty), $valid_difficulties)) {
        $errors[] = "Invalid difficulty selected.";
    }

    if (empty($errors)) {
        // Prepare and execute the INSERT statement for quizzes
        $stmt = $conn->prepare("INSERT INTO quizzes (quiz_title, description, category, created_by) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            error_log("Prepare failed (createQuiz): " . htmlspecialchars($conn->error));
            $errors[] = "An unexpected error occurred. Please try again later.";
        } else {
            $stmt->bind_param("sssi", $quizName, $description, $difficulty, $admin_user_id);
            if ($stmt->execute()) {
                $quizId = $conn->insert_id;
                $stmt->close();

                // Redirect with success message
                header("Location: adminquizadd.php?quiz_id=$quizId&success=Quiz+created+successfully!+You+can+now+add+questions+to+it.");
                exit;
            } else {
                error_log("Execute failed (createQuiz): " . htmlspecialchars($stmt->error));
                $errors[] = "Failed to create quiz. Please try again.";
                $stmt->close();
            }
        }
    }
}

// Handle Question Addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addQuestion'])) {
    $quizId = isset($_POST['quizId']) ? intval($_POST['quizId']) : 0;
    $questionText = trim($_POST['question']);
    $optionA = trim($_POST['optionA']);
    $optionB = trim($_POST['optionB']);
    $optionC = trim($_POST['optionC']);
    $optionD = trim($_POST['optionD']);
    $correctAnswer = $_POST['correctAnswer'];

    // Basic validation
    if ($quizId === 0 || empty($questionText) || empty($optionA) || empty($optionB) || empty($optionC) || empty($optionD) || empty($correctAnswer)) {
        $errors[] = "All fields are required to add a question.";
    }

    // Validate correctAnswer value
    if (!in_array($correctAnswer, ['A', 'B', 'C', 'D'])) {
        $errors[] = "Invalid correct answer selection.";
    }

    if (empty($errors)) {
        // Insert Question into `questions` Table
        $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
        if ($stmt === false) {
            error_log("Prepare failed (addQuestion - insert question): " . htmlspecialchars($conn->error));
            $errors[] = "An unexpected error occurred. Please try again later.";
        } else {
            $stmt->bind_param("is", $quizId, $questionText);
            if ($stmt->execute()) {
                $questionId = $conn->insert_id;
                $stmt->close();

                // Insert Options into `options` Table
                $options = ['A' => $optionA, 'B' => $optionB, 'C' => $optionC, 'D' => $optionD];
                $optionIds = [];

                foreach ($options as $key => $optionText) {
                    $stmt = $conn->prepare("INSERT INTO options (question_id, option_text) VALUES (?, ?)");
                    if ($stmt === false) {
                        error_log("Prepare failed (addQuestion - insert option $key): " . htmlspecialchars($conn->error));
                        $errors[] = "An unexpected error occurred while adding options. Please try again.";
                        break;
                    }

                    $stmt->bind_param("is", $questionId, $optionText);
                    if ($stmt->execute()) {
                        $optionIds[$key] = $conn->insert_id;
                        $stmt->close();
                    } else {
                        error_log("Execute failed (addQuestion - insert option $key): " . htmlspecialchars($stmt->error));
                        $errors[] = "Failed to add option '$key'. Please try again.";
                        $stmt->close();
                        break;
                    }
                }

                // If no errors during option insertion, update the correct_option_id
                if (empty($errors)) {
                    $correctOptionId = $optionIds[$correctAnswer];
                    $stmt = $conn->prepare("UPDATE questions SET correct_option_id = ? WHERE question_id = ?");
                    if ($stmt === false) {
                        error_log("Prepare failed (addQuestion - update correct_option_id): " . htmlspecialchars($conn->error));
                        $errors[] = "An unexpected error occurred. Please try again later.";
                    } else {
                        $stmt->bind_param("ii", $correctOptionId, $questionId);
                        if ($stmt->execute()) {
                            $stmt->close();

                            // Redirect with success message
                            header("Location: adminquizadd.php?quiz_id=$quizId&success=Question+added+successfully!");
                            exit;
                        } else {
                            error_log("Execute failed (addQuestion - update correct_option_id): " . htmlspecialchars($stmt->error));
                            $errors[] = "Failed to set the correct answer. Please try again.";
                            $stmt->close();
                        }
                    }
                }
            } else {
                error_log("Execute failed (addQuestion - insert question): " . htmlspecialchars($stmt->error));
                $errors[] = "Failed to add question. Please try again.";
                $stmt->close();
            }
        }
    }
}

// Handle Done Action
if (isset($_GET['done']) && isset($_GET['quiz_id'])) {
    // Redirect with success message
    header("Location: admindashboard.php?success=You+have+finished+adding+questions!");
    exit;
}

// Fetch quizzes for selection (optional, depending on your UI needs)
$quizzes = [];
if (isset($_GET['quiz_id'])) {
    $currentQuizId = intval($_GET['quiz_id']);
} else {
    $currentQuizId = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quiz or Questions - VocabQuiz Admin</title>
    <!-- Link to external CSS files -->
    <link rel="stylesheet" href="../css/adminheader.css">
    <style>
        /* Background Div Styling */
        .background {
            background-color: #121212; /* Dark background */
            min-height: calc(100vh - 80px - 60px); /* Adjust based on header and footer heights */
            padding-top: 20px;
            padding-bottom: 20px;
        }

        /* Container Styling */
        .admin-container {
            width: 60%;
            max-width: 800px;
            margin: 0 auto; /* Center horizontally */
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            color: #f4f4f4;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: none;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .done-btn {
            background-color: #007bff;
        }
        .done-btn:hover {
            background-color: #0056b3;
        }
        .error-message, .success-message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .error-message {
            background-color: #f44336;
            color: white;
        }
        .success-message {
            background-color: #4CAF50;
            color: white;
        }
        @media screen and (max-width: 768px) {
            .admin-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <?php include 'adminheader.php'; ?>
    <div class="background">
        <div class="admin-container">
            <?php
            // Display Success Message
            if (!empty($_GET['success'])) {
                echo "<div class='success-message'>" . htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8') . "</div>";
            }

            // Display Error Messages
            if (!empty($errors)) {
                echo "<div class='error-message'>";
                foreach ($errors as $error) {
                    echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "<br>";
                }
                echo "</div>";
            }

            if (!isset($_GET['quiz_id'])):
            ?>
            <!-- Quiz Creation Form -->
            <h2>Create New Quiz</h2>
            <form action="adminquizadd.php" method="POST">
                <input type="hidden" name="createQuiz" value="1">
                <label for="quizName">Quiz Name</label>
                <input type="text" id="quizName" name="quizName" placeholder="Enter quiz name" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter quiz description" required></textarea>

                <label for="difficulty">Difficulty</label>
                <select id="difficulty" name="difficulty" required>
                    <option value="">--Select Difficulty--</option>
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
                <input type="hidden" name="quizId" value="<?php echo htmlspecialchars($_GET['quiz_id'], ENT_QUOTES, 'UTF-8'); ?>">

                <label for="question">Question</label>
                <textarea id="question" name="question" placeholder="Enter question" required></textarea>

                <label for="optionA">Option A</label>
                <input type="text" id="optionA" name="optionA" placeholder="Enter option A" required>

                <label for="optionB">Option B</label>
                <input type="text" id="optionB" name="optionB" placeholder="Enter option B" required>

                <label for="optionC">Option C</label>
                <input type="text" id="optionC" name="optionC" placeholder="Enter option C" required>

                <label for="optionD">Option D</label>
                <input type="text" id="optionD" name="optionD" placeholder="Enter option D" required>

                <label for="correctAnswer">Correct Answer</label>
                <select id="correctAnswer" name="correctAnswer" required>
                    <option value="">--Select Correct Answer--</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>

                <button type="submit">Add Question</button>
            </form>
            <form action="adminquizadd.php" method="GET">
                <input type="hidden" name="done" value="1">
                <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($_GET['quiz_id'], ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="done-btn">Done</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'adminfooter.php'; ?>

    <!-- Include the adminheader.js script -->
    <script src="../js/adminheader.js"></script>
</body>
</html>
