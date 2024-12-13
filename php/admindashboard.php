<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vocab Quiz Dashboard for Admin">
    <title>Vocab Quiz Dashboard</title>
    <link rel="stylesheet" href="../css/adminheader.css">
    <link rel="stylesheet" href="../css/font-awesome.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            color: #f4f4f4;
        }

        .admin-dashboard-banner {
            background-color: rgb(57, 57, 57);
            color: rgb(255, 255, 255);
            text-align: center;
            padding: 15px 10px;
            font-size: 1.5rem;
            font-weight: bold;
            width: 100%;
        }

        .admin-dashboard-container {
            box-sizing: border-box;
            padding: 20px;
        }

        .admin-level {
            box-sizing: border-box;
            margin-bottom: 40px;
        }

        .admin-dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .admin-dashboard-title {
            color: #ffffff;
            align-items: center;
            font-size: 36px;
            font-weight: 600;
            display: flex;
        }

        .admin-dashboard-see-more a {
            color: #1e90ff;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        .admin-dashboard-see-more a:hover {
            color: #63a4ff;
        }

        .admin-quiz-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }

        .quiz-button {
            background-color: #1e1e1e;
            color: #f4f4f4;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            width: 200px;
            text-align: center;
            white-space: normal;
            word-wrap: break-word;
            height: auto;
        }

        .quiz-button:hover,
        .quiz-button:focus {
            background-color: #333333;
            color: #ffffff;
            outline: none;
            transform: translateY(-5px);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
            position: relative;
            color: #000;
        }

        .close-button {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            background: none;
            border: none;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            outline: none;
        }

        @media (max-width: 600px) {
            .quiz-button {
                width: 100%;
            }

            .admin-dashboard-container {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <?php include 'adminheader.php'; ?>
    <main>
        <div class="admin-dashboard-banner">Admin Dashboard</div>
        <div class="admin-dashboard-container">
            <!-- Easy Category -->
            <div class="admin-level">
                <div class="admin-dashboard-header">
                    <div class="admin-dashboard-title">Easy</div>
                    <div class="admin-dashboard-see-more"><a href="admindashboardeasy.php">See More</a></div>
                </div>
                <div class="admin-quiz-container" data-category="Easy"></div>
            </div>
            <!-- Hard Category -->
            <div class="admin-level">
                <div class="admin-dashboard-header">
                    <div class="admin-dashboard-title">Hard</div>
                    <div class="admin-dashboard-see-more"><a href="admindashboardhard.php">See More</a></div>
                </div>
                <div class="admin-quiz-container" data-category="Hard"></div>
            </div>
            <!-- Business Category -->
            <div class="admin-level">
                <div class="admin-dashboard-header">
                    <div class="admin-dashboard-title">Business</div>
                    <div class="admin-dashboard-see-more"><a href="admindashboardbusiness.php">See More</a></div>
                </div>
                <div class="admin-quiz-container" data-category="Business"></div>
            </div>
        </div>
    </main>
    <?php include 'adminfooter.php'; ?>

    <!-- Modal for Quiz Details -->
    <div id="quiz-modal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="modal-title">
        <div class="modal-content">
            <button class="close-button" aria-label="Close Modal">&times;</button>
            <h2 id="modal-title">Quiz Title</h2>
            <p id="modal-description">Quiz Description</p>
        </div>
    </div>

    <!-- JavaScript Embedded in the Same File -->
    <script>
        // JavaScript to Render 5 Title-Only Quiz Buttons per Category

        // Initial Quiz Data with Categories
        const quizzes = [
            {
                id: 0,
                title: "Daily Check-in",
                category: "Easy"
            },
            {
                id: 1,
                title: "Gratitude Lesson - SEL (Inspired by Kenyecta Smith)",
                category: "Easy"
            },
            {
                id: 2,
                title: "Math: 6th Grade (with new question types)",
                category: "Hard"
            },
            {
                id: 3,
                title: "Science : 3rd Grade (with new question types)",
                category: "Hard"
            },
            {
                id: 4,
                title: "Math: 3rd Grade (with new question types)",
                category: "Business"
            },
            {
                id: 5,
                title: "Business Fundamentals",
                category: "Business"
            },
            {
                id: 6,
                title: "Advanced Vocabulary in Business",
                category: "Business"
            },
            {
                id: 7,
                title: "Financial Literacy Basics",
                category: "Hard"
            },
            {
                id: 8,
                title: "Introduction to Economics",
                category: "Easy"
            },
            {
                id: 9,
                title: "Strategic Management Concepts",
                category: "Business"
            }
            // Add more quizzes as needed
        ];

        function createQuizButton(quiz) {
            const button = document.createElement('button');
            button.classList.add('quiz-button');
            button.setAttribute('aria-label', `Quiz: ${quiz.title}`);
            button.textContent = quiz.title;

            button.addEventListener('click', () => {
                openModal(quiz);
            });

            return button;
        }

        function renderQuizzes() {
            const quizContainers = document.querySelectorAll('.admin-quiz-container');

            quizContainers.forEach(container => {
                const category = container.getAttribute('data-category');

                // Filter quizzes by category
                const quizzesInCategory = quizzes.filter(quiz => quiz.category === category);

                // Select the first 5 quizzes or all if fewer than 5
                const quizzesToRender = quizzesInCategory.slice(0, 7);

                // Clear existing content in the container
                container.innerHTML = '';

                // Create and append buttons for each quiz
                quizzesToRender.forEach(quiz => {
                    const quizButton = createQuizButton(quiz);
                    container.appendChild(quizButton);
                });

                // Optional: Display a message if there are no quizzes
                if (quizzesToRender.length === 0) {
                    const noQuizzesMsg = document.createElement('p');
                    noQuizzesMsg.textContent = 'No quizzes available in this category.';
                    noQuizzesMsg.style.color = '#ccc';
                    container.appendChild(noQuizzesMsg);
                }
            });
        }

        // Function to open modal with quiz details
        function openModal(quiz) {
            const modal = document.getElementById('quiz-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalDescription = document.getElementById('modal-description');

            modalTitle.textContent = quiz.title;
            // Add more details as needed
            modalDescription.textContent = `This is the "${quiz.title}" quiz. More details can be added here.`;

            modal.style.display = 'block';
            modal.setAttribute('aria-hidden', 'false');

            // Set focus to the modal for accessibility
            modal.querySelector('.close-button').focus();
        }

        // Function to close modal
        function closeModal() {
            const modal = document.getElementById('quiz-modal');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');

            // Return focus to the body or a specific element
            document.body.focus();
        }

        // Event listener for closing the modal
        const closeButton = document.querySelector('.close-button');
        if (closeButton) {
            closeButton.addEventListener('click', closeModal);
        }

        // Close modal when clicking outside the modal content
        window.addEventListener('click', (event) => {
            const modal = document.getElementById('quiz-modal');
            if (event.target == modal) {
                closeModal();
            }
        });

        // Close modal with 'Escape' key for accessibility
        window.addEventListener('keydown', (event) => {
            const modal = document.getElementById('quiz-modal');
            if (event.key === 'Escape' && modal.style.display === 'block') {
                closeModal();
            }
        });

        // Initialize rendering when DOM is fully loaded
        document.addEventListener('DOMContentLoaded', renderQuizzes);
    </script>
</body>

</html>
