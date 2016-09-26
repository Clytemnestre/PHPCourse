<h3>Add New Picture</h3>

<?php
require_once 'db.php';

$target_dir = "uploads/";
$max_file_size = 5 * 1024 * 1024; // 5000000
// Only authenticated users are allowed to post a new article
if (!isset($_SESSION['user'])) {
    echo '<h1>Access forbidden</h1>';
    echo '<p>Only logged in users are allowed to post.</p>';
    echo '<a href="index.php">Click to continue</a>';
    exit;
}

function getForm($description = '') {
    $form = <<< ENDTAG
    <form method="POST" enctype="multipart/form-data">
    Picture: <input type="file" name="picFile"><br><br>
    description: <textarea  rows="20" cols="50" name="description" value="$description"></textarea><br><br>
    <input type ="submit" value="Add picture"> 
</form>  
ENDTAG;
    return $form;
}

if (!isset($_POST['description'])) {
    echo getForm();
} else {
    $description = $_POST['description'];
    $errorList = array();
    if (strlen($description) < 4) {
        array_push($errorList, "Description of the picture must be at least 4 characters long");
    }
    if (!isset($_FILES['picFile'])) {
        array_push($errorList, "You must select a picture for upload");
    } else {
        $fileUpload = $_FILES['picFile'];
        $check = getimagesize($fileUpload["tmp_name"]);
        if (!$check) {
            array_push($errorList, "File upload was not an image file.");
        } elseif (!in_array($check['mime'], array('image/png', 'image/gif', 'image/bmp', 'image/jpeg'))) {
            array_push($errorList, "Error: Only accepting valie png,gif,bmp,jpg files.");
        } elseif ($fileUpload['size'] > $max_file_size) {
            array_push($errorList, "Error: File to big, maximuma accepted is $max_file_size bytes");
        }
    }
    if ($errorList) {
        //submission failed
        echo "<h5>Problems  found in your submission</h5>\n";
        echo "</ul>\n";
        foreach ($errorList as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul><br><br><br><hr>";
        echo getForm($description);
    } else {
        $file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $fileUpload['name']);
        $file_extension = explode('/', $check['mime'])[1];
        $target_file = $target_dir . date("Ymd-His-") . $file_name . '.' . $file_extension;
        if (move_uploaded_file($fileUpload["tmp_name"], $target_file)) {
            echo "The file " . basename($fileUpload["name"]) . " has been uploaded.";
        } else {
            die("Fatal error: There was an server-side error handling the upload of your file.");
        }
        $sql = sprintf("INSERT INTO pictures VALUES (NULL, '%s', '%s', '%s')",
                mysqli_escape_string($conn, $_SESSION['user']['ID']),
                mysqli_escape_string($conn, $target_file),
                mysqli_escape_string($conn, $description));
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "Error executing query [$sql] : " . mysqli_error($conn);
        } else {
            echo "The picutre was posted succesfully<br><br>\n";
            echo "<a href=\"index.php\">Go to home page</a>";
        }
    }
}

