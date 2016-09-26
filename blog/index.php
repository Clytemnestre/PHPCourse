<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once 'db.php';
        if (isset($_SESSION['user'])) {
            echo "Welcome " . $_SESSION['user']['name'] . "!";
            echo 'You may <a href="logout.php">Logout</a> or <a href="articleaddedit.php">Post an article</a>';
        } else {
            echo "You are not logged in.";
            echo '<a href="login.php">Login</a> or <a href="register.php">Register</a>.';
        }


        $sql = "SELECT articles.ID as articleID, name, pubDate,title, body FROM articles, "
                . " users where articles.authorID = users.ID ORDER BY pubDate desc LIMIT 5";

        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "Error executing query [ $sql ] : " . mysqli_error($conn);
            exit;
        }
        $dataRows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        echo "<ul>";
        foreach ($dataRows as $row) {
            $ID = $row['articleID'];
            $author = htmlspecialchars($row['name']);
            $pubDate = $row['pubDate'];
            $title = htmlspecialchars($row['title']);
            $body = htmlspecialchars($row['body']);

            echo "<li>$title $author $pubDate <a href=\"article.php?id=$ID\">View Article</a></li>";
        }
        ?>
    </ul>
</body>
</html>