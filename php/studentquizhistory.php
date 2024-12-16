<?php
// studentquizhistory.php
require_once 'db_connect.php';

session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'student' && $_SESSION['role'] !== 'professional')) {
    header("Location: alllogin.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch quiz attempts by the student
// Using 'ua.start_time AS date_taken' to maintain the original reference
$sql = "SELECT 
            ua.attempt_id, 
            q.quiz_title, 
            ua.score, 
            ua.start_time AS date_taken, 
            q.category 
        FROM user_quiz_attempts ua
        JOIN quizzes q ON ua.quiz_id = q.quiz_id
        WHERE ua.user_id = ?
        ORDER BY ua.start_time DESC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$quizAttempts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $quizAttempts[] = [
            'attempt_id' => $row['attempt_id'],
            'quiz_title' => $row['quiz_title'],
            'score' => $row['score'],
            'date_taken' => $row['date_taken'],
            'category' => ucfirst($row['category'])
        ];
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz History - VocabQuiz</title>
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="../css/font-awesome.css">
    <!-- Font Awesome CDN (Optional: Ensure it's correctly linked) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-dNmOOzjVqEgf9slrKRnZd2LthD77HvxjP8l0xj7mvGm6Q7HUGKZgxFQNV2plLifg54l9jY1LXo2v0xRCrK/4pg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Additional Styles Specific to Quiz History Page */
        .quiz-history-container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: var(--secondary-black);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .quiz-history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .quiz-history-header h2 {
            color: var(--text-white);
            font-size: 2rem;
        }

        .quiz-history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .quiz-history-table th,
        .quiz-history-table td {
            padding: 12px 15px;
            text-align: left;
        }

        .quiz-history-table th {
            background-color: var(--primary-black);
            color: var(--text-white);
        }

        .quiz-history-table tr:nth-child(even) {
            background-color: var(--secondary-black);
        }

        .quiz-history-table tr:hover {
            background-color: var(--accent-color);
            color: var(--primary-black);
        }

        .review-button {
            background-color: var(--accent-color);
            color: var(--primary-black);
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
            font-size: 14px;
        }

        .review-button:hover {
            background-color: var(--hover-color);
            transform: translateY(-2px);
        }

        .no-attempts {
            text-align: center;
            color: #ccc;
            font-size: 1.2rem;
            padding: 20px 0;
        }

        @media (max-width: 768px) {
            .quiz-history-table th,
            .quiz-history-table td {
                padding: 8px 10px;
            }

            .review-button {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <?php include 'studentheader.php'; ?>

    <div class="quiz-history-container">
        <div class="quiz-history-header">
            <h2>Your Quiz History</h2>
        </div>

        <?php if (!empty($quizAttempts)): ?>
            <table class="quiz-history-table">
                <thead>
                    <tr>
                        <th>Quiz Title</th>
                        <th>Category</th>
                        <th>Score</th>
                        <th>Date Taken</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizAttempts as $attempt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($attempt['quiz_title'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($attempt['category'] ?? 'N/A'); ?></td>
                            <td>
                                <?php 
                                    if (is_null($attempt['score'])) {
                                        echo 'In Progress';
                                    } else {
                                        echo htmlspecialchars($attempt['score']);
                                    }
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo htmlspecialchars(date("F j, Y, g:i a", strtotime($attempt['date_taken'] ?? 'N/A')));
                                ?>
                            </td>
                            <td>
                                <?php if ($attempt['score'] !== null): ?>
                                    <a href="studentreviewquiz.php?attempt_id=<?php echo urlencode($attempt['attempt_id']); ?>">
                                        <button class="review-button"><i class="fas fa-eye"></i> Review</button>
                                    </a>
                                <?php else: ?>
                                    <!-- If the quiz is still in progress or not completed -->
                                    <button class="review-button" disabled>
                                        <i class="fas fa-spinner fa-spin"></i> In Progress
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-attempts">
                <p>You haven't taken any quizzes yet. <a href="studentdashboard.php#quizzes">Start a quiz now!</a></p>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'studentfooter.php'; ?>
    <script src="../js/studentHeader.js"></script>
</body>

</html>
