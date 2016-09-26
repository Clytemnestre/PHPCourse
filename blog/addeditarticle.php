<?php

//heredoc
function getArticleForm($title = '', $body = '') {

    $f = <<< HEREDOC
        <form method="post" enctype="multipart/form-data">
            Title: <input type="text" name="title" value="$title"><br>
            Article: <textarea rows="10" cols="50" value="$body" name="body"></textarea><br>
            <input type="file" name="fileToUpload">
            <input type="submit"><br>
        </form>
HEREDOC;

    return $f;
}

require_once 'db.php';

if (!isset($_SESSION['user'])) {
    echo 'you are not logged in';
    echo '<a href="index.php">click to continue</a>';
} else {
    echo getArticleForm();

    if (isset($_POST['title'])) {
        $erroList = array();

        $title = $_POST['title'];
        $body = $_POST['body'];
        $authorID = $_SESSION['user']['ID'];
        $pudDate = date("Y-m-d");
        //////////////////////////
        //////////////////////////
        //////IMAGES UPLOAD///////
        // directory in which the images will be put
        $target_dir = "uploads/";
        // get file uploaded
        $fileUpload = $_FILES['fileToUpload'];
        // the max file size
        $max_file_size = 5 * 1024 * 1024; // 5000000
        // check that the uploaded file is existing
        $check = getimagesize($fileUpload["tmp_name"]);

        if (!$check) {
            array_push($errorList, "Error: File upload was not an image file.");
        }
        switch ($check['mime']) {
            case 'image/png':
            case 'image/gif':
            case 'image/bmp':
            case 'image/jpeg':
                break;
            default:
                array_push($errorList, "Error: Only accepting valie png,gif,bmp,jpg files.");
        }

        if ($fileUpload['size'] > $max_file_size) {
            array_push($errorList, "Error: File to big, maximuma accepted is $max_file_size bytes");
        }

        $file_extension = explode('/', $check['mime'])[1];
        $target_file = $target_dir . md5($fileUpload["name"] . time()) . '.' . $file_extension;

        if (move_uploaded_file($fileUpload["tmp_name"], $target_file)) {
            echo "The file " . basename($fileUpload["name"]) . " has been uploaded.";
        } else {
            array_push($errorList, "there was an error trying to upload your file");;
        }

        if (strlen($title) < 4) {
            array_push($errorList, "the title of your article must at least be 4 characters long");
        }

        if (strlen($body) < 50) {
            array_push($errorList, "the title of your article must at least be 50 characters long");
        }



        if ($erroList) {
            echo "<h5>Problems found in your submission</h5>\n";
            echo "<ul>\n";
            foreach ($errorList as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul>\n";
            echo getArticleForm($title, $body);
        } else {
            $sql = sprintf("INSERT INTO articles VALUES (NULL, '%s', '%s', '%s', '%s', '%s')", mysqli_escape_string($conn, $authorID), mysqli_escape_string($conn, $pudDate), mysqli_escape_string($conn, $title), mysqli_escape_string($conn, $body), mysqli_escape_string($conn, $target_file));

            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Error executing query [ $sql ] : " . mysqli_error($conn));
            }

            echo 'Your submission was successfull';
        }
    }
}



