<h3>Article View</h3>

<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    die('No article to view');
}

$sql = sprintf("SELECT * FROM articles, users WHERE authorID=users.ID and articles.ID= '%s'",
        mysqli_escape_string($conn, $_GET['id']));
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error executing query [$sql] : " . mysqli_error($conn));
}
$row = mysqli_fetch_assoc($result);
if (!$row) {
    die("No article was found");
}
$title = htmlspecialchars($row['title']);
$body = htmlspecialchars($row['body']);
$author = $row['name'];
$pubDate = $row['pubDate'];
$imagePath = $row['imagePath'];

echo "<h3>$title</h3>";
echo "<p><i>Posted by</i> $author at $pubDate</p><br><br><br>";
echo sprintf('<img src="%s">',  $imagePath);
echo "<p>$body</p>";


