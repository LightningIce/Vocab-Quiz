<?php
$host = 'localhost';
$db   = 'vocabquiz';
$user = 'root';
$pass = ''; 

// Create a new mysqli connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}