<?php
require_once 'db.php';
// heredoc
function getLoginForm($email = '') {

    $f = <<< HEREDOC
        <form method="post">
            email <input type="text" name="email"><br>
            password <input type="password" name="password"><br>
            <input type="submit"><br>
        </form>
HEREDOC;

    return $f;
}

if (!isset($_POST['email'])) {
    echo getLoginForm();
} else {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = sprintf("SELECT * FROM users WHERE email='%s'", mysqli_escape_string($conn, $email));
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("error executing query [$sql]" . mysqli_error($conn));
    }
    
    if (mysqli_num_rows($result) == 0){
        echo "<p>Login failed</p>";
        echo getLoginForm();
    } else {
        $row = mysqli_fetch_assoc($result);
        // password compared in php for capitalization of certain letters
        if ($row['password'] == $password){
            // LOGIN successful
            unset($row['password']);
            $_SESSION['user'] = $row;
            echo '<a href="index.php">login is goog, click to continue</a>';
        } else {
            echo "<p>Login failed</p>";
            echo getLoginForm($email);
        }
    }
}

