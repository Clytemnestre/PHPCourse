<?php

require_once 'db.php';

function getRegisterForm($email = "") {
    return <<< ENDTAG
<h1>Register user</h1>
<form method="post">
    Email: <input type="text" name="email" value="$email"><br>
    Password: <input type="password" name="pass1"><br>
    Password (repeated) <input type="password" name="pass2"><br>
    <input type="submit" value="Register">
</form>
ENDTAG;
}

if (!isset($_POST['email'])) {
    // STATE 1: First show
    echo getRegisterForm();
} else {
    $email = $_POST['email'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $errorList = array();

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
        array_push($errorList, "Email does not look like a valid email");
    } else {
        $sql = sprintf("SELECT ID FROM users WHERE email='%s'", mysqli_escape_string($conn, $email));
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Error executing query [ $sql ] : " . mysqli_error($conn));
        }
        if (mysqli_num_rows($result) != 0) {
            array_push($errorList, "Email already registered");
        }
    }
    if (!preg_match('/[0-9;\'".,<>`~|!@#$%^&*()_+=-]/', $pass1) || (!preg_match('/[a-z]/', $pass1)) || (!preg_match('/[A-Z]/', $pass1)) || (strlen($pass1) < 8)) {
        array_push($errorList, "Password must be at least 8 characters " .
                "long, contain at least one upper case, one lower case, " .
                " one digit or special character");
    } else if ($pass1 != $pass2) {
        array_push($errorList, "Passwords don't match");
    }
    if ($errorList) {      
        echo "<ul>\n";
        foreach ($errorList as $error) {
            echo "<li>" . htmlspecialchars($error);
        }
        echo "</ul>\n\n";
        echo getRegisterForm($email);
    } else {
        $sql = sprintf("INSERT INTO users VALUES (NULL, '%s', '%s')", mysqli_escape_string($conn, $email), mysqli_escape_string($conn, $pass1));
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Error executing query [ $sql ] : " . mysqli_error($conn));
        }
        echo "<h1>Registration successful</h1>\n";
        echo '<a href="login.php">Click to login</a>';
    }
}

