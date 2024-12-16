<?php
// goodbye.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goodbye - VocabQuiz</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
            background-color: #121212;
            color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .goodbye-container {
            background-color: #1e1e1e;
            padding: 2rem;
            border-radius: 12px;
        }

        a {
            color: #ff9800;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: #e58900;
        }
    </style>
</head>

<body>
    <div class="goodbye-container">
        <h1>Goodbye!</h1>
        <p>Your account has been successfully deleted.</p>
        <p>If you change your mind, feel free to <a href="alllogin.php">sign up</a> again.</p>
    </div>
</body>

</html>
