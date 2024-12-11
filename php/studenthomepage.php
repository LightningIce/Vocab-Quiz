<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
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
    margin-right: 5px; /* Add spacing between the logo and text */
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

.hero {
    text-align: center;
    padding: 5rem 2rem;
    background: #424949;
}

@font-face {
    font-family: 'Oleo Script';
    src: url('fonts/OleoScript-Regular.ttf') format('truetype');
}

.hero h1 {
    font-family: 'Oleo script', cursive;
    color:#f4f4f4 ;
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.hero p {
    font-family: 'Oleo script', cursive;
    font-size: 1.2rem;
    margin-bottom: 2rem;
}

.hero-buttons {
    display: flex; /* Enable flexbox */
    flex-direction: column; /* Stack items vertically */
    align-items: center; /* Center buttons horizontally */
    gap: 10px; /* Add spacing between buttons */
}

.btn {
    text-decoration: none;
    padding: 20px 30px;
    background: #000;
    color: #fff;
    border-radius: 5px;
    margin: 0 10px;
    transition: background 0.3s;
}

.hero-buttons .btn.secondary {
    background: #7f8c8d;
    color: #333;
}

.hero-buttons .btn:hover {
    opacity: 0.9;
}

section {
    padding: 2rem 2rem;
}

.about {
    display: flex; /* Enable Flexbox */
    align-items: center; /* Vertically center the items */
    justify-content: center; /* Center items horizontally */
    gap: 20px; /* Space between the image and text */
}

section .free-img {
    max-width: 40%; /* Adjust image size */
    height: 50%; /* Maintain aspect ratio */
    margin-right: 13px;
}

.about p {
    font-family: 'Oleo script', cursive;
    font-size: 3rem;
    margin-bottom: 2rem;
}

section .lesson-img {
    max-width: 40%; /* Adjust image size */
    height: 50%; /* Maintain aspect ratio */
    margin-right: 13px;
}

.lesson {
    display: flex; /* Enable Flexbox */
    align-items: center; /* Vertically center the items */
    justify-content: center; /* Center items horizontally */
    gap: 20px; /* Space between the image and text */
}

.lesson p {
    font-family: 'Oleo script', cursive;
    font-size: 3rem;
    margin-bottom: 2rem;
}

section .modes-img {
    max-width: 25%; /* Adjust logo size */
    height: 35%; /* Maintain aspect ratio */
    margin-right: 13px;
}

.modes {
    display: flex; /* Enable Flexbox */
    align-items: center; /* Vertically center the items */
    justify-content: center; /* Center items horizontally */
    gap: 20px; /* Space between the image and text */
}

.modes p {
    font-family: 'Oleo script', cursive;
    font-size: 3rem;
    margin-bottom: 2rem;
}

.contact {
    display: flex; /* Enable Flexbox */
    align-items: center; /* Vertically center the items */
    justify-content: center; /* Center items horizontally */
    gap: 20px; /* Space between the image and text */
}

.contact h2 p {
    display: flex; /* Enable flexbox */
    flex-direction: column; /* Stack items vertically */
    align-items: center; /* Center buttons horizontally */
    gap: 10px; /* Add spacing between buttons */
}

ul {
    list-style: disc inside;
}

footer {
    text-align: center;
    background: #000;
    color: #fff;
    padding: 1rem;
    margin-top: 2rem;
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
    <img src="profile2.png" alt="Profile" class="profile-img">
    </header>
    <main>
        <section class="hero">
            <h1>Vocabulary:</h1>
            <h1>The Key to Clear Communication!</h1>
            <div class="hero-buttons">
                <a href="#tests" class="btn">Start Now!</a>
                <a href="#register" class="btn secondary">I already have an account</a>
            </div>
        </section>
        <section class="about" id="about" >
            <img src="freeAccess.png" alt="freeAccess" class="free-img">
            <p>Free access, without subscription!</p>
        </section>
        <section class="lesson">
                <p>Short, bite-sized lessons!</p>
                <img src="lesson.png" alt="lesson" class="lesson-img">
        </section>
        <section class="modes">
        <img src="modes.png" alt="modes" class="modes-img">
        <p>Variety of learning modes!</p>
        </section>
        <section class="contact" id="contact">
            <h2>Contact Us</h2>
            <p>Email: vocabQuiz@mail.apu.edu.my</p>
            <p>Phone: +123 456 7890</p>
        </section>
        <script>
            
        </script>
    </main>
    <footer>
        <p>&copy; 2024 VocabQuiz. All rights reserved.</p>
    </footer>
</body>
</html>
