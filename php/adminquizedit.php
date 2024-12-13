<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Quiz Review - Full-Width Header</title>
    <style>
        /* CSS: Black and Blue Theme with Full-Width Header */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            /* Black background */
            color: #fff;
            /* White text */
        }

        .gray-layer {
            background-color: #333;
            /* Gray background layer */
            padding: 30px 0;
            min-height: 100vh;
            /* Ensure it covers the entire viewport */
        }

        .header {
            width: 100%;
            /* Full-width header */
            background-color: #222;
            /* Dark gray header */
            color: #2196F3;
            /* Blue for header text */
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #2196F3;
            /* Blue underline */
            position: fixed;
            /* Keep the header fixed at the top */
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .header h1 {
            margin: 1;
            font-size: 20px;
            text-align: center;
            flex: 1;
        }


        .container {
            max-width: 800px;
            margin: 80px auto 0;
            /* Add top margin to avoid overlap with fixed header */
            padding: 20px;
            background-color: #222;
            /* Dark gray content box */
            border-radius: 10px;
            /* Rounded corners for the content box */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            /* Subtle shadow for elevation */
        }

        .quiz-info {
            background-color: #444;
            /* Lighter gray for quiz info */
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
            /* Soft white shadow */
        }

        .quiz-info h2 {
            text-align: center;
            color: #2196F3;
            /* Blue title */
            margin: 0 0 10px;
        }

        .quiz-info p {
            text-align: center;
            margin: 0;
        }

        .questions-container {
            margin-top: 20px;

        }

        .question-item {
            background-color: #333;
            /* Background color of the question card */
            margin-bottom: 25px;
            padding: 45px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
            /* Soft white shadow */
            color: #fff;
            position: relative;
            /* Relative to position the edit button inside */
        }

        .question-item p {
            margin: 0 0 10px;
            font-weight: bold;
        }

        /* Remove default list styles */
        .question-item ul {
            list-style-type: none;
            padding: 0;
        }

        .question-item li {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .question-item li::before {
            content: '●';
            /* Circle before each option */
            color: #555;
            /* Default gray color for circles */
            font-size: 18px;
            margin-right: 10px;
        }

        .question-item li.preselected::before {
            color: #2196F3;
            /* Blue color for preselected circle */
        }

        .question-item li.disabled::before {
            color: #555;
            /* Default gray for disabled circles */
        }

        .question-item li .option-box {
            background-color: #fff;
            /* White background for option box */
            color: #333;
            /* Dark text for option */
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #555;
            /* Border around the option box */
            width: 100%;
            /* Full width for the option box */
            display: inline-block;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .question-item li.preselected .option-box {
            border-color: #2196F3;
            /* Blue border for preselected option */
        }

        .question-item li.disabled .option-box {
            background-color: #f0f0f0;
            /* Light gray background for disabled option */
            color: #999;
            /* Gray text for disabled option */
            pointer-events: none;
            /* Disable user interaction */
            cursor: not-allowed;
            /* Not-allowed cursor */
        }


        .edit-btn {
            background: #2196F3;
            /* Blue button */
            color: #fff;
            /* White text */
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            font-weight: bold;
            position: absolute;
            /* Position it relative to the question card */
            bottom: 15px;
            /* Distance from bottom of the card */
            right: 15px;
            /* Distance from left of the card */
        }

        .edit-btn:hover {
            background: #42a5f5;
            /* Lighter blue hover */
        }

        .done-btn {
            display: block;
            width: 100%;
            background-color: #2196F3;
            /* Blue button */
            color: #fff;
            /* White text */
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: bold;
        }

        .done-btn:hover {
            background: #42a5f5;
            /* Lighter blue hover */
        }

        /* Add More Question Button */
        .add-question-btn {
            display: block;
            width: 100%;
            background-color: #4caf50;
            /* Green button */
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 20px;
        }

        .add-question-btn:hover {
            background-color: #45a049;
            /* Darker green on hover */
        }

        /* New Question Form */
        .new-question-form {
            background-color: #444;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .new-question-form input[type="text"] {
            width: 97%;
            padding: 10px;
            margin-top: 0px 0;
            border-radius: 5px;
            border: 1px solid #555;
            background-color: #fff;
            color: #333;
        }

        .new-question-form .option-input {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .new-question-form .option-input input[type="text"] {
            flex: 1;
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #555;
            background-color: #fff;
            color: #333;
        }

        .new-question-form .option-input input[type="radio"] {
            cursor: pointer;
        }

        .new-question-form .done-question-btn {
            display: block;
            width: 100%;
            background-color: #2196F3;
            /* Blue button */
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 20px;
        }

        .new-question-form .done-question-btn:hover {
            background-color: #42a5f5;
        }

        /* Cross Icon to Remove the Question Form */
        .remove-question-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 18px;
            color: #ff0000;
            /* Red cross icon */
            background: none;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .remove-question-btn:hover {
            color: #ff5555;
            /* Lighter red on hover */
        }
    </style>
</head>

<body>

    <!-- Gray Background Layer -->
    <div class="gray-layer">
        <!-- Full-Width Header -->
        <header class="header">
            <!-- Back Button -->
            <!-- Logo -->
            <h1>Quiz Review</h1>
        </header>

        <!-- Content Container -->
        <div class="container">
            <!-- Quiz Info Section -->
            <div class="quiz-info">
                <h2>Business Terms Beginner Level Quiz 1</h2>
                <p>Total questions (1)</p>
            </div>

            <!-- Single Question -->
            <div class="questions-container" id="questions-container">
                <div class="question-item" id="question-1">
                    <p><strong>1.</strong> What is the synonym of sad?</p>
                    <ul>
                        <li class="disabled">
                            <span class="option-box">Angry</span>
                        </li>
                        <li class="preselected">
                            <span class="option-box">Depressed</span>
                        </li>
                        <li class="disabled">
                            <span class="option-box">Crazy</span>
                        </li>
                        <li class="disabled">
                            <span class="option-box">Anxious</span>
                        </li>
                    </ul>


                    <button class="edit-btn" onclick="toggleEditDone(this)">Edit</button>

                </div>
            </div>
            <!-- Add this button inside the container, below the existing questions -->
            <!-- Add the "Add More Question" button -->
            <button class="add-question-btn" id="add-question-btn">Add More Question</button>


            <!-- Done Button -->
            <button class="done-btn">Done</button>

            <!-- Footer -->

        </div>
    </div>

    <script>
        // JavaScript: Back Button Functionality


        // JavaScript: Done Button Functionality
        const doneButton = document.querySelector('.done-btn');
        doneButton.addEventListener('click', () => {
            alert('Quiz review is completed!');
        });
        let questionCount = 1; // Keeps track of the number of questions

        // Function to add a new question form
        // Function to add a new question form
        document.getElementById('add-question-btn').addEventListener('click', () => {
            questionCount++;

            // Create a new form for adding a question
            const newQuestionForm = document.createElement('div');
            newQuestionForm.classList.add('new-question-form');
            newQuestionForm.id = `new-question-form-${questionCount}`;
            newQuestionForm.innerHTML = `
        <button class="remove-question-btn" onclick="removeQuestionForm('${newQuestionForm.id}')">❌</button>
        
        <label for="question-title-${questionCount}"><strong>Question Title</strong></label>
        <input type="text" id="question-title-${questionCount}" placeholder="Enter your question title here" required>

        <div class="option-input">
            <input type="radio" name="correct-option-${questionCount}" value="1">
            <input type="text" id="option-1-${questionCount}" placeholder="Option 1" required>
        </div>

        <div class="option-input">
            <input type="radio" name="correct-option-${questionCount}" value="2">
            <input type="text" id="option-2-${questionCount}" placeholder="Option 2" required>
        </div>

        <div class="option-input">
            <input type="radio" name="correct-option-${questionCount}" value="3">
            <input type="text" id="option-3-${questionCount}" placeholder="Option 3" required>
        </div>

        <div class="option-input">
            <input type="radio" name="correct-option-${questionCount}" value="4">
            <input type="text" id="option-4-${questionCount}" placeholder="Option 4" required>
        </div>

        <button class="done-question-btn" onclick="addNewQuestion('${newQuestionForm.id}')">Done</button>
    `;

            // Add new form to the questions container
            document.getElementById('questions-container').appendChild(newQuestionForm);
        });

        // Function to remove the question form
        function removeQuestionForm(formId) {
            const formElement = document.getElementById(formId);
            if (formElement) {
                formElement.remove();
            }
        }

        // Function to update the numbering of all questions
        // Function to toggle between Edit and Done
        // Function to update the total number of questions shown
        function updateTotalQuestions() {
            const totalQuestionsCount = document.querySelectorAll('.question-item').length; // Count the question items
            const totalQuestionsElement = document.querySelector('.quiz-info p'); // The paragraph showing total questions
            totalQuestionsElement.textContent = `Total questions (${totalQuestionsCount})`; // Update the text
        }

        // Function to toggle between Edit and Done
        function toggleEditDone(button) {
            const parentQuestion = button.closest('.question-item'); // Get the parent question item
            const isEdit = button.textContent === 'Edit'; // Check if it's currently in Edit mode

            if (isEdit) {
                button.textContent = 'Done'; // Change button to Done
                enableEditing(parentQuestion); // Enable editing for question and options
            } else {
                button.textContent = 'Edit'; // Change button back to Edit
                disableEditing(parentQuestion); // Save changes and disable editing
            }
        }

        // Enable editing (make question and options editable)
        function enableEditing(questionCard) {
            const questionTextElement = questionCard.querySelector('p'); // The <p> containing the question
            const questionNumber = questionTextElement.querySelector('strong').textContent; // Keep the numbering intact
            const currentQuestionText = questionTextElement.textContent.replace(questionNumber, '').trim(); // Extract the question text

            // Replace question text with an input field, keeping numbering fixed
            questionTextElement.innerHTML = `
        <strong>${questionNumber}</strong> 
        <input type="text" value="${currentQuestionText}" class="edit-title-input" />
    `;

            // Add a cross icon for removing the question during edit mode
            if (!questionCard.querySelector('.edit-remove-btn')) {
                const removeBtn = document.createElement('button');
                removeBtn.textContent = '❌';
                removeBtn.classList.add('edit-remove-btn');
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '20px';
                removeBtn.style.right = '20px';
                removeBtn.style.color = '#ff0000';
                removeBtn.style.background = 'none';
                removeBtn.style.border = 'none';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.fontSize = '18px';
                removeBtn.style.fontWeight = 'bold';

                removeBtn.addEventListener('click', () => removeEditedQuestion(questionCard));
                questionCard.appendChild(removeBtn);
            }

            // Make the options editable
            const options = questionCard.querySelectorAll('.option-box'); // Options
            options.forEach(option => {
                const currentOption = option.textContent.trim();
                option.innerHTML = `<input type="text" value="${currentOption}" class="edit-option-input" />`;
            });
        }

        // Disable editing (save changes to question text and options)
        function disableEditing(questionCard) {
            const questionTextElement = questionCard.querySelector('p'); // The <p> containing the question
            const questionNumber = questionTextElement.querySelector('strong').textContent; // Keep the numbering intact
            const questionInput = questionTextElement.querySelector('.edit-title-input'); // The input field for the question title

            // Save the updated question text
            if (questionInput) {
                const newQuestionText = questionInput.value.trim();
                questionTextElement.innerHTML = `
            <strong>${questionNumber}</strong> ${newQuestionText}
        `;
            }

            // Save the updated options
            const optionInputs = questionCard.querySelectorAll('.edit-option-input'); // Option inputs
            optionInputs.forEach(input => {
                const newOption = input.value.trim();
                input.parentElement.textContent = newOption;
            });

            // Remove the edit-specific cross icon
            const editRemoveBtn = questionCard.querySelector('.edit-remove-btn');
            if (editRemoveBtn) {
                editRemoveBtn.remove();
            }
        }

        // Remove question during Edit mode with confirmation
        function removeEditedQuestion(questionCard) {
            const questionItems = document.querySelectorAll('.question-item'); // Get all question cards

            if (questionItems.length > 1) {
                const confirmed = confirm("Are you sure you want to delete this question?"); // Ask for confirmation
                if (confirmed) {
                    questionCard.remove();
                    updateQuestionNumbers(); // Update the numbering after removal
                    updateTotalQuestions(); // Update the total questions count
                }
            } else {
                alert('At least one question must remain in the quiz.');
            }
        }

        // Function to update the numbering of all questions
        function updateQuestionNumbers() {
            const questionItems = document.querySelectorAll('.question-item'); // Get all question cards
            questionItems.forEach((question, index) => {
                const questionNumberElement = question.querySelector('p strong'); // The <strong> element for numbering
                questionNumberElement.textContent = `${index + 1}.`; // Update numbering (1, 2, 3, ...)
            });
            updateTotalQuestions(); // Always update total questions after numbering
        }

        // Function to add a new question
        function addNewQuestion(formId) {
            const formElement = document.getElementById(formId);
            const questionTitle = formElement.querySelector(`#question-title-${formId.split('-').pop()}`).value;

            // Get all option inputs
            const options = [
                formElement.querySelector(`#option-1-${formId.split('-').pop()}`).value,
                formElement.querySelector(`#option-2-${formId.split('-').pop()}`).value,
                formElement.querySelector(`#option-3-${formId.split('-').pop()}`).value,
                formElement.querySelector(`#option-4-${formId.split('-').pop()}`).value
            ];

            // Get the correct option
            const correctOption = formElement.querySelector(`input[name="correct-option-${formId.split('-').pop()}"]:checked`);

            // Validation: Check for empty fields
            if (!questionTitle.trim()) {
                alert("Please enter the question title.");
                return;
            }

            for (let i = 0; i < options.length; i++) {
                if (!options[i].trim()) {
                    alert(`Option ${i + 1} cannot be empty.`);
                    return;
                }
            }

            if (!correctOption) {
                alert("Please select the correct answer.");
                return;
            }

            // Create a new question card
            const newQuestion = document.createElement('div');
            newQuestion.classList.add('question-item');
            newQuestion.innerHTML = `
        <p><strong></strong> ${questionTitle}</p>
        <ul>
            ${options.map((option, index) => `
                <li class="${correctOption.value == index + 1 ? 'preselected' : 'disabled'}">
                    <span class="option-box">${option}</span>
                </li>
            `).join('')}
        </ul>
        <button class="edit-btn" onclick="toggleEditDone(this)">Edit</button>
    `;

            // Add new question to the questions container
            document.getElementById('questions-container').appendChild(newQuestion);

            // Remove the form after adding the question
            formElement.remove();

            // Update the question numbers and total questions
            updateQuestionNumbers();
        }
    </script>
</body>

</html>
