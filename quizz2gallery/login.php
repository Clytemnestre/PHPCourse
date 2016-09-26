<?php

require_once 'db.php';

function getLoginForm() {
    return <<< ENDTAG
<h1>Login</h1>
<form method="post">
    Email: <input type="text" name="email"><br>
    Password: <input type="password" name="pass"><br>
    <input type="submit" value="Login">
</form>
ENDTAG;
}

if (!isset($_POST['email'])) {
    echo getLoginForm();
} else {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $sql = sprintf("SELECT * FROM users WHERE email='%s'", mysqli_escape_string($conn, $email));
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error executing query [ $sql ] : " . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) == 0) {
        echo "<p>Login failed</p>";
        echo getLoginForm();        
    } else {
        $row = mysqli_fetch_assoc($result);
        // password MUST be compared in PHP because SQL is case-insenstive
        if ($row['password'] == $pass) {
            // LOGIN successful
            unset($row['password']);
            $_SESSION['user'] = $row;
            echo "<h1>Login successful.</h1>";
            echo '<a href="index.php">Click to continue</a>';
        } else {
            echo "<p>Login failed</p>";
            echo getLoginForm();
        }
    }
}
