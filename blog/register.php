<?php
// heredoc

function getForm($name = '', $email = '') {

    $f = <<< HEREDOC
        <form method="post">
            Name: <input type="text" name="name" value="$name"><br>
            email <input type="text" name="email" value="$email"><br>
            password <input type="password" name="password"><br>
            confirm password <input type="password" name="passwordCheck"><br><br>
            <input type="submit"><br>
        </form>
HEREDOC;

    return $f;
}

require_once 'db.php';

if (!isset($_POST['name'])) {
    echo getForm();
} else {
    // acquire data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordCheck = $_POST['passwordCheck'];
    // list in which to put th errors if any should be found. 
    $errorList = array();

    if (strlen($name) < 4) {
        array_push($errorList, "Your nam has to be at least 4 characters long.");
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
        array_push($errorList, "Please enter a valid email address");
    } else {
        $sql = sprintf("SELECT * FROM users WHERE email='%s'", mysqli_escape_string($conn, $email));
        $result = mysqli_query($conn, $sql);
        if (!$result){
            die ("error executing query [$sql]".mysqli_error($conn));
        }
        if (mysqli_num_rows($result) != 0){
            array_push($errorList, "This email address is already in use");
        }
    }

    if (!preg_match('/[A-Z]/', $password)) {
        array_push($errorList, "Your password must contain at least one capitalized letter");
    }

    if (!preg_match('/[0-9]/', $password)) {
        array_push($errorList, "Your password must contain at least one number");
    }
    
    if (!preg_match('/[\'\";:?.><,`~!@#$%^&*()+=*-]/', $password)) {
        array_push($errorList, "Your password must contain at least one special character");
    }
    
    if ($password != $passwordCheck){
        array_push($errorList, "the two passwords you entered are not identical");
    }
    
    if ($errorList) {
        // STATE 3: submission failed        
        echo "<ul>\n";
        foreach ($errorList as $error) {
            echo "<li>" . htmlspecialchars($error);
        }
        echo "</ul>\n\n";
        echo getForm($name, $email);
    } else {
        // STATE 2: submission successful
        $sql = sprintf("INSERT INTO users VALUES (NULL, '%s', '%s', '%s')", mysqli_escape_string($conn, $name), mysqli_escape_string($conn, $email), mysqli_escape_string($conn, $password));
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Error executing query [ $sql ] : " . mysqli_error($conn));
        }
        echo "<h1>Registration successful</h1>\n";
        echo '<a href="login.php">Click to login</a>';
    }
}