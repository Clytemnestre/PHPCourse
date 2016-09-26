<?php
session_start();

$db_name = 'quizz2gallery';
$db_user = 'quizz2gallery';
$db_pass = 'eb6XDSddrs9qKq3h';

$conn = mysqli_connect('localhost', $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Error connecting to database: " . mysqli_error($conn));
}

