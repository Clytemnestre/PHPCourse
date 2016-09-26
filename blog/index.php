<?php

function getArticleForm($title = '', $body = '', $dueDate='', $authorName='') {

    $f = <<< HEREDOC
        <form method="post">
            <input type="text" name="title" value="$title"><br>
            <input type="text" name="title" value="$authorName"><br>
            <input type="text" name="title" value="$dueDate"><br>
            <textarea rows="10" cols="50" value="$body" name="body"></textarea><br>
        </form>
HEREDOC;

    return $f;
}

require_once 'db.php';

if (isset($_SESSION['user'])) {
    echo "Welcome " . $_SESSION['user']['name'] . "!";
    echo "<a href='addeditarticle.php'>post article   </a><a href='logout.php'>logout</a>";
} else {
    echo "You are not logged in<br>";
    echo "<a href='login.php'>login   </a><a href='register.php'>register</a>";
}

$sql = "SELECT * FROM articles ORDER BY pubDate DESC LIMIT 5";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error executing query [ $sql ] : " . mysqli_error($conn));
}

$dataRows = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($dataRows as $row) {
    $title = $row['title'];
    $authorID = $row['authorID'];
    $dueDate = $row['pubDate'];
    $body = $row['body'];
    // get the author name by their IDs
    $sql = sprintf("SELECT name FROM users WHERE ID='%s'", mysqli_escape_string($conn, $authorID));
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error executing query [ $sql ] : " . mysqli_error($conn));
    } 
    
    $authorName = mysqli_fetch_all($result, MYSQLI_ASSOC);;
    echo getArticleForm($title, $body, $dueDate, $authorName);
    
}