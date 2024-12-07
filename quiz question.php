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

.hearts {
    display: flex;
    gap: 2px;
}

.hearts span {
    font-size: 3rem;
    color: red;
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
    background-color: #d4d4d4;
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

</style>
</head>
    <style>
        /*import fonts*/
        @import url('https://fonts.googleapis.com/css2?family=Oleo+Script:wght@400;700&display=swap');
    </style>
<body>
    <header>
    <div class="name">
    <img src="images/logo3.png" alt="Logo" class="logo-img">    
    Vocab Quiz</div>
    <img src="images/profile2.png" alt="profile" class="profile-img">
    </header>
    <div class="quiz-container">
        <div class="quiz-header">
            <span class="progress">1 of 10</span>
            <div class="hearts">
                <span>❤️</span>
                <span>❤️</span>
                <span>❤️</span>
            </div>
            <span class="points">100 points</span>
        </div>

        <div class="question">
            <h2>What is a synonym of happy?</h2>
        </div>

        <div class="options">
        <button class="option">Joy</button>
            <button class="option">Sad</button>
            <button class="option">Depressed</button>
            <button class="option">Angry</button>
            <button class="hint">
            </button>
        </div>
    </div>
    <img src="images/questions.png" alt="question" class="question-img">
</body>
</html> 