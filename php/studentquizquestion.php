<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Assignment</title>
    <style >
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
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
    width: 35px; /* Adjust logo size */
    height: 35px; /* Maintain aspect ratio */
    margin-right: 2px; /* Add spacing between the logo and text */
}

header .name {
    font-size: 2.0rem;
    font-weight: bold;
}

.profile-img {
    width: 50px; /* Adjust logo size */
    height: 50px; /* Maintain aspect ratio */
    margin-right: 5px; /* Add spacing between the logo and text */
}

.quiz-container {
    width: 90%;
    max-width: 600px;
    background: #fff;
    border-radius: 10px;
    padding: 70px;
    margin: 1px auto; /* Center container horizontally */
    text-align: center; /* Align content inside the container */
}

.quiz-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.progress {
    font-family: Arial, Bold;
    font-weight: bold;
    font-size: 1.5rem;
}

.points {
    font-family: Arial, Bold;
    font-weight: bold;
    font-size: 1.5rem;
}

.question {
    font-family: "Oleo Script", cursive;
    text-align: center;
    margin-bottom: 20px;
}

.question h2 {
    font-size: 1.5rem;
    color: #333;
}

.options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.option {
    padding: 10px;
    border: none;
    background-color: #e0e0e0;
    color: #333;
    font-family: Oleo Script, Bold;
    font-size: 2rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.option:hover {
    background-color: #000; /* Highlight color, you can choose your own */
    color: white; /* Change text color to contrast with the background */
}

.lowerBtn {
    display: flex;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}


.nextBtn {
    padding: 10px;
    border: none;
    background-color: #e0e0e0;
    color: #333;
    align-items: center;
    font-family: Oleo Script, Bold;
    font-size: 2rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 20px;
    width: 50;
    text-align: center;
    right: 20px;
}

.backBtn {
    padding: 10px;
    border: none;
    background-color: #e0e0e0;
    color: #333;
    align-items: center;
    font-family: Oleo Script, Bold;
    font-size: 2rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 20px;
    width: 50;
    text-align: center;
}

.nextBtn:hover {
    background-color: #000; /* Highlight color, you can choose your own */
    color: white;
}

.backBtn:hover {
    background-color: #000; /* Highlight color, you can choose your own */
    color: white;
}

.hint {
    grid-column: span 2;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #e0e0e0;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
}

.hint img {
    width: 25px;
    height: 25px;
}

.question-img {
    width: 100%; /* Let the image fill the container's width */
    max-width: 250px; /* Set a maximum size for the image */
    height: auto; /* Maintain aspect ratio */
    display: block;
    margin: 1px auto 0; /* Center the image with vertical spacing */
}

.option.selected {
    background-color: #000; /* Highlight color, you can choose your own */
    color: white; /* Change text color to contrast with the background */
}

</style>
</head>
    <style>
        /*import fonts*/
        @import url('https://fonts.googleapis.com/css2?family=Oleo+Script:wght@400;700&display=swap');
    </style>
<body>
    <header>
    <div class="name">
    <img src="logo3.png" alt="Logo" class="logo-img">    
    Vocab Quiz</div>
    <img src="profile2.png" alt="profile" class="profile-img">
    </header>
    <div class="quiz-container">
        <div class="quiz-header">
            <span class="progress" id="progress"></span>
            <span class="points" id="progress"></span>
        </div>
        <div class="question">
            <h2 id="question">Question here</h2>
        <div class="options" id="options">
            <button class="option">Ans 1</button>
            <button class="option">Ans 2</button>
            <button class="option">Ans 3</button>
            <button class="option">Ans 4</button>
        </div>
    </div>
    <div class="lowerBtn">
        <button class="backBtn" id="backBtn">Back</button>
        <button class = "nextBtn" id="nextBtn">Next</button>
    </div>
    <img src="questions.png" alt="question" class="question-img">
,<script>
    const questions = [
        {
            question: "What is a synonym of happy ?",
            options: [
                {text: "Joy", correct: true},
                {text: "Sad", correct: false} ,
                {text: "Depressed", correct: false},
                {text: "Angry", correct: false},
            ]
        },
        {
            question: "What is the capital of France?",
            options: [
                {text: "Paris", correct: false},
                {text: "Berlin", correct: true} ,
                {text: "Marlin", correct: false},
                {text: "Rome", correct: false},
            ]
        },
        {
            question: "What is 1 + 1?",
            options: [
                {text: "2", correct: false},
                {text: "35", correct: true} ,
                {text: "1", correct: false},
                {text: "idk", correct: false},
            ]
        },
        {
            question: "Spell BMW.",
            options: [
                {text: "Deywha", correct: true},
                {text: "BMW", correct: false} ,
                {text: "a car", correct: false},
                {text: "huh?", correct: false},
            ]
        },
        {
            question: "How deep is a 6 foot hole?",
            options: [
                {text: "6 foot", correct: false},
                {text: "0.001 miles", correct: false} ,
                {text: "88.001 yards", correct: false},
                {text: "it's about like 20 feet", correct: true},
            ]
        },
        {
            question: "What color is a carrot?",
            options: [
                {text: "green", correct: true},
                {text: "orange", correct: false} ,
                {text: "idk about carrots", correct: false},
                {text: "its a vegetable", correct: false},
            ]
        },
        {
            question: "What color is a carrot?",
            options: [
                {text: "green", correct: true},
                {text: "orange", correct: false} ,
                {text: "idk about carrots", correct: false},
                {text: "its a vegetable", correct: false},
            ]
        },
        {
            question: "What color is a carrot?",
            options: [
                {text: "green", correct: true},
                {text: "orange", correct: false} ,
                {text: "idk about carrots", correct: false},
                {text: "its a vegetable", correct: false},
            ]
        },
        {
            question: "What color is a carrot?",
            options: [
                {text: "green", correct: true},
                {text: "orange", correct: false} ,
                {text: "idk about carrots", correct: false},
                {text: "its a vegetable", correct: false},
            ]
        },{
            question: "What color is a carrot?",
            options: [
                {text: "green", correct: true},
                {text: "orange", correct: false} ,
                {text: "idk about carrots", correct: false},
                {text: "its a vegetable", correct: false},
            ]
        }
    ];

        const questionElement = document.getElementById('question');
        const optionsButton = document.getElementById('options');
        const nextButton = document.getElementById('nextBtn');
        const progressElement = document.getElementById('progress');
        const pointsElement = document.getElementById('points');
        const backButton = document.getElementById('backBtn');

    let currentQuestionIndex = 0;
    let score = 0;

    // Add a "selectedAnswer" property to each question to track the user's answer
    questions.forEach(question => {
    question.selectedAnswer = null; // Initialize selectedAnswer to null
    });

    function startQuiz() {
    currentQuestionIndex = 0;
    score = 0;
    // Reset the "selectedAnswer" for all questions
    questions.forEach(question => (question.selectedAnswer = null));
    showQuestion();
}

function showQuestion() {
    let currentQuestion = questions[currentQuestionIndex];
    questionElement.innerHTML = (currentQuestionIndex + 1) + ". " + currentQuestion.question; // Set question text
    optionsButton.innerHTML = ""; // Clear previous options

    // Create buttons for each option
    currentQuestion.options.forEach(option => {
        const button = document.createElement("button");
        button.innerHTML = option.text;
        button.classList.add("option");
        if (currentQuestion.selectedAnswer === option.text) {
            button.classList.add("selected");
        }
        button.addEventListener("click", () => selectAnswer(option)); // Attach event listener
        optionsButton.appendChild(button);
    });

    backButton.style.display = currentQuestionIndex > 0 ? "block" : "none";// Show Back button only if not the first question
    // Update progress and score
    progressElement.innerHTML = `Question ${currentQuestionIndex + 1} of ${questions.length}`;
    pointsElement.innerHTML =  `Score: ${score}`;
}

function selectAnswer(option, button) {
    const currentQuestion = questions[currentQuestionIndex];

    // If the user changes their answer, adjust the score
    if (currentQuestion.selectedAnswer !== null) {
        // Remove score for the previous correct answer
        const previousAnswer = currentQuestion.options.find(opt => opt.text === currentQuestion.selectedAnswer);
        if (previousAnswer && previousAnswer.correct) {
            score--;
        }
    }
    // Set the new selected answer
    currentQuestion.selectedAnswer = option.text;

    // Add score if the new answer is correct
    if (option.correct) {
        score++;
    }
    // Update UI to show the selected button
    const allOptions = optionsContainer.querySelectorAll(".option");
    allOptions.forEach(button => btn.classList.remove("selected")); // Remove "selected" class from all buttons
    button.classList.add("selected"); // Highlight the newly selected button
    // Ensure the Next button is visible
    nextButton.style.display = "block";

    // Update the score display
    pointsElement.innerText = `Score: ${score}`;
}

function goBack() {
    // Move to the previous question
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        showQuestion(); // Show the previous question
    }
}


    nextButton.addEventListener("click", () => {
        currentQuestionIndex++; // Move to the next question
        if (currentQuestionIndex < questions.length) {
            showQuestion(); // Show the next question
        } else {
            showResults(); // Show results if no more questions
        }
    });

    backButton.addEventListener("click", () => {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--; // Move to the previous question
        showQuestion(); // Show the previous question
    }
});

    function showResults(isPassed) {
        const resultPage = "Final result.php";  // Replace with the actual result page name
        const finalScore = score * 10; 
        window.location.href = `${resultPage}?score=${finalScore}&passed=${isPassed}`;
    }

    startQuiz();
</script>
</body>
</html> 