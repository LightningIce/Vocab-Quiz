<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    width: 40px; /* Adjust logo size */
    height: 40px; /* Maintain aspect ratio */
    margin-right: 10px; /* Add spacing between the logo and text */
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

.easy-img {
    width: 100%; /* Let the image fill the container's width */
    max-width: 250px; /* Set a maximum size for the image */
    height: auto; /* Maintain aspect ratio */
    display: block;
    margin: 100px auto 0; /* Center the image with vertical spacing */
}

.Result {
    display: flex;
    flex-direction: column; /* Stack result texts */
    justify-content: center;
    align-items: center;
    height: auto; /* Remove 100vh for dynamic height */
    margin-top: 20px;
    font-family: "Oleo Script", cursive;
    font-size: 35px;
    color: #333;
}

.choices {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    padding: 20px;
}

.choice {
    padding: 10px;
    border: none;
    background-color: #e0e0e0;
    color: #333;
    font-family: Oleo Script, Bold;
    font-size: 2rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    max-width: 300px;
    min-width: 100px;
    width: 100%;
    justify-self: center;/*center the button in it's grid cell*/
}

.choice:hover {
    background-color: #d4d4d4;
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
    <img src="easy.png" alt="easy" class="easy-img">
    <div class="Result">
        <h1>Final Result:</h1>
        <h2>35/40</h2>
    </div>
    <div class="choices">
        <button class="choice">Review Question</button>  
        <button class="choice">Exit</button>
    </div>
</body>
</html>