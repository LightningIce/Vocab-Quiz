<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Review Page</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            /* Dark background */
            color: #f4f4f4;
            /* Light text for contrast */
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1c1c1c;
            /* Dark gray header */
            color: #f4f4f4;
            padding: 10px 20px;
            border-bottom: 2px solid #333;
        }

        .menu-icon {
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            font-size: 1.2rem;
            font-weight: bold;
            color: #1e90ff;
            /* Blue color for the logo */
        }

        .user-icon {
            width: 32px;
            height: 32px;
            background-color: #1e90ff;
            /* Blue background for user icon */
            color: #ffffff;
            /* White text for contrast */
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 50%;
            /* Circular icon */
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        /* Banner for Quiz Title */
        .quiz-banner {
            background-color: #1e90ff;
            /* Blue banner background */
            color: #ffffff;
            /* White text for contrast */
            text-align: center;
            padding: 15px 10px;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Review Quiz Heading */
        .review-quiz {
            color: #1e90ff;
            /* Blue text */
            font-size: 1.4rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Total Questions */
        .total-questions {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: left;
        }

        /* Main Content */
        .content {
            padding: 20px;
        }

        .question-box {
            background-color: #1c1c1c;
            /* Dark gray question box */
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            color: #f4f4f4;
            position: relative;

        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .question-box {
            background-color: #1c1c1c;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
            transition: all 0.3s ease;
        }

        .cross-icon {
            width: 24px;
            height: 24px;
            background-color: #ff4d4d;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            left: 10px;
            display: none;
            /* Hidden by default */
        }

        .question-header {
            margin-left: 0px;
            /* Space for cross icon */
            transition: all 0.3s ease;
        }

        .options {
            list-style-type: none;
            padding: 0;
            margin: 15px 0 0;
            margin-left: 40px;
            /* Space for cross icon */
            transition: all 0.3s ease;
        }

        .audio-controls {
            display: flex;
            align-items: center;
            margin-left: 40px;
            /* Space for cross icon */
            transition: all 0.3s ease;
        }

        .sound-icon {
            width: 32px;
            height: 32px;
            background-color: #1e90ff;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            cursor: pointer;
        }

        .switch-icon {
            width: 32px;
            height: 32px;
            background-color: #1e90ff;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            cursor: pointer;
            margin-right: 10px;
            display: none;
            /* Hidden by default */
        }

        .speed-options {
            display: flex;
            gap: 5px;
        }

        .speed-btn {
            background-color: #333;
            color: #f4f4f4;
            border: 1px solid #444;
            padding: 5px 10px;
            font-size: 0.8rem;
            border-radius: 3px;
            cursor: pointer;
        }

        .speed-btn.selected {
            background-color: #1e90ff;
            color: #fff;
        }

        .question-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .audio-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .sound-icon {
            width: 24px;
            height: 24px;
            background-color: #1e90ff;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .switch-icon {
            width: 24px;
            height: 24px;
            background-color: #1e90ff;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
            cursor: pointer;
            margin-right: 10px;
            /* Space to the right of switch icon */
            display: none;
            /* Hidden by default */
        }

        .speed-options {
            display: flex;
            gap: 5px;
        }

        .speed-btn {
            background-color: #333;
            color: #f4f4f4;
            border: 1px solid #444;
            padding: 5px 10px;
            font-size: 0.8rem;
            border-radius: 3px;
            cursor: pointer;
        }

        .speed-btn.selected {
            background-color: #1e90ff;
            color: #fff;
        }

        .option-disabled {
            pointer-events: none;
            opacity: 1;
            /* No dimming effect */
        }


        .options {
            list-style-type: none;
            padding: 0;
            margin: 15px 0 0;
        }

        .options li {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .option-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #333;
            color: #f4f4f4;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 10px;
        }

        .option-icon.correct {
            background-color: #1e90ff;
        }

        .edit-btn {
            background-color: #1e90ff;
            color: #f4f4f4;
            border: none;
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            bottom: 15px;
            right: 15px;
        }

        .edit-btn:hover {
            background-color: #187bcd;
        }

        .done-btn {
            background-color: #28a745;
            color: #f4f4f4;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto 0;
        }

        .option-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #333;
            color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 10px;
        }

        .option-icon.selected {
            background-color: #1e90ff;
        }

        .option-disabled {
            pointer-events: none;
            opacity: 1;
            /* Ensures no dimming of question box */
        }

        .edit-btn {
            background-color: #1e90ff;
            color: #f4f4f4;
            border: none;
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            bottom: 15px;
            right: 15px;
            transition: all 0.3s ease;
        }

        .done-btn {
            background-color: #28a745;
            color: #f4f4f4;
            border: none;
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            bottom: 15px;
            right: 15px;
            transition: all 0.3s ease;
        }

        .content {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .question-box {
            background-color: #1c1c1c;
            border-radius: 5px;
            padding: 15px;
            position: relative;
            transition: all 0.3s ease;
        }

        .cross-icon {
            width: 24px;
            height: 24px;
            background-color: #ff4d4d;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            left: 10px;
            display: none;
        }

        .question-content {
            margin-left: 0;
            transition: all 0.3s ease;
        }

        .question-content.edit-mode {
            margin-left: 40px;
        }

        .question-header {
            text-align: left;
        }

        .audio-controls {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .switch-icon {
            width: 32px;
            height: 32px;
            background-color: #1e90ff;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            margin-right: 10px;
            display: none;
            /* Hidden by default */
        }

        .sound-icon {
            width: 32px;
            height: 32px;
            background-color: #1e90ff;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            cursor: pointer;
        }

        .speed-options {
            display: flex;
            gap: 5px;
        }

        .speed-btn {
            background-color: #333;
            color: #f4f4f4;
            border: 1px solid #444;
            padding: 5px 10px;
            font-size: 0.8rem;
            border-radius: 3px;
            cursor: pointer;
        }

        .speed-btn.selected {
            background-color: #1e90ff;
            color: #fff;
        }

        .edit-btn,
        .done-btn {
            position: absolute;
            bottom: 15px;
            right: 15px;
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 5px;
            border: none;
            color: #f4f4f4;
            cursor: pointer;
        }

        .edit-btn {
            background-color: #1e90ff;
        }

        .done-btn {
            background-color: #28a745;

        }

        .admin-done-btn {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 15px 30px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <header>
        <div class="menu-icon">
            ☰
            <span class="logo">Vocab Quiz</span>
        </div>
        <div class="user-icon">U</div>
    </header>

    <div class="quiz-banner">Business Terms Beginner Level Quiz 1</div>

    <div class="review-quiz">Review Quiz</div>

    <div class="total-questions">Total Questions: 2</div>

    <div class="content">
        <!-- Question 1 -->
        <div class="content">
            <div class="question-box">
                <div class="cross-icon" style="display: none;" onclick="removeQuestionBox(this)">✖</div>
                <div class="question-content">
                    <div class="question-header">
                        <h3>1. What is the synonym of "sad"?</h3>
                    </div>

                    <div class="audio-controls">
                        <div class="switch-icon" style="display: none;">🔄</div>
                        <div class="sound-icon">🔊</div>
                        <div class="speed-options">
                            <button class="speed-btn" onclick="selectSpeed(this)">0.5x</button>
                            <button class="speed-btn selected" onclick="selectSpeed(this)">1x</button>
                            <button class="speed-btn" onclick="selectSpeed(this)">1.5x</button>
                        </div>
                    </div>

                    <ul class="options option-disabled">
                        <li onclick="selectOption(this)"><span class="option-icon selected">A</span> Depressed</li>
                        <li onclick="selectOption(this)"><span class="option-icon">B</span> Angry</li>
                        <li onclick="selectOption(this)"><span class="option-icon">C</span> Crazy</li>
                        <li onclick="selectOption(this)"><span class="option-icon">D</span> Anxious</li>
                    </ul>
                </div>

                <button class="done-btn" style="display: none;" onclick="toggleEdit(this)">Done</button>
                <button class="edit-btn" onclick="toggleEdit(this)">Edit</button>
            </div>
        </div>

        <!-- Global Done Button for Admin -->
        <button class="admin-done-btn" onclick="submitAll()">Done</button>




        <!-- Question 2 -->
        <div class="content">
            <div class="question-box">
                <div class="cross-icon" style="display: none;" onclick="removeQuestionBox(this)">✖</div>
                <div class="question-content">
                    <div class="question-header">
                        <h3>1. What is the synonym of "sad"?</h3>
                    </div>

                    <div class="audio-controls">
                        <div class="switch-icon" style="display: none;">🔄</div>
                        <div class="sound-icon">🔊</div>
                        <div class="speed-options">
                            <button class="speed-btn" onclick="selectSpeed(this)">0.5x</button>
                            <button class="speed-btn selected" onclick="selectSpeed(this)">1x</button>
                            <button class="speed-btn" onclick="selectSpeed(this)">1.5x</button>
                        </div>
                    </div>

                    <ul class="options option-disabled">
                        <li onclick="selectOption(this)"><span class="option-icon selected">A</span> Depressed</li>
                        <li onclick="selectOption(this)"><span class="option-icon">B</span> Angry</li>
                        <li onclick="selectOption(this)"><span class="option-icon">C</span> Crazy</li>
                        <li onclick="selectOption(this)"><span class="option-icon">D</span> Anxious</li>
                    </ul>
                </div>

                <button class="done-btn" style="display: none;" onclick="toggleEdit(this)">Done</button>
                <button class="edit-btn" onclick="toggleEdit(this)">Edit</button>
            </div>
        </div>

        <!-- Global Done Button for Admin -->
        <button class="admin-done-btn" onclick="submitAll()">Done</button>



        <script>
            
            function toggleEdit(button) {
                const questionBox = button.closest('.question-box');
                const editButton = questionBox.querySelector('.edit-btn');
                const doneButton = questionBox.querySelector('.done-btn');
                const crossIcon = questionBox.querySelector('.cross-icon');
                const switchIcon = questionBox.querySelector('.switch-icon');
                const questionContent = questionBox.querySelector('.question-content');


                if (button.classList.contains('edit-btn')) {
                    editButton.style.display = 'none';
                    doneButton.style.display = 'block';
                    crossIcon.style.display = 'flex';
                    switchIcon.style.display = 'flex';
                    questionContent.classList.add('edit-mode');
                } else {
                    editButton.style.display = 'block';
                    doneButton.style.display = 'none';
                    crossIcon.style.display = 'none';
                    switchIcon.style.display = 'none';
                    questionContent.classList.remove('edit-mode');
                }
            }

            function selectOption(option) {
                const questionBox = option.closest('.question-box');
                const allOptions = questionBox.querySelectorAll('.option-icon');

                if (option.closest('.options').classList.contains('option-disabled')) return;

                allOptions.forEach(opt => opt.classList.remove('selected'));
                option.querySelector('.option-icon').classList.add('selected');
            }

            function removeQuestionBox(crossIcon) {
                const questionBox = crossIcon.closest('.question-box');
                const content = document.querySelector('.content');
                const questionBoxes = content.querySelectorAll('.question-box');

                if (questionBoxes.length > 1) {
                    questionBox.remove();
                } else {
                    alert('At least one question box must remain.');
                }
            }

            function selectOption(option) {
                const questionBox = option.closest('.question-box');
                const allOptions = questionBox.querySelectorAll('.option-icon');

                if (option.closest('.options').classList.contains('option-disabled')) return;

                allOptions.forEach(opt => opt.classList.remove('selected'));
                option.querySelector('.option-icon').classList.add('selected');
            }

            function selectSpeed(button) {
                const questionBox = button.closest('.question-box');
                const allSpeedButtons = questionBox.querySelectorAll('.speed-btn');

                if (button.classList.contains('locked')) return;

                allSpeedButtons.forEach(speedBtn => speedBtn.classList.remove('selected'));
                button.classList.add('selected');
            }
        </script>

</body>

</html>
