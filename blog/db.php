<?php
session_start();

$db_name = 'blogs';
$db_user = 'root';
$db_pass = '';

$conn = mysqli_connect('localhost', $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Error connecting to database: " . mysqli_error($conn));
}

