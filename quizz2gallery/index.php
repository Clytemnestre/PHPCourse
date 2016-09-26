<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once 'db.php';
        if (isset($_SESSION['user'])) {
            echo "Welcome " . $_SESSION['user']['email'] . "!";
            echo '<a href="addpic.php">add a picture</a> or <a href="logout.php">logout</a>';


            $sql = sprintf("SELECT ID, description, picturePath FROM pictures WHERE ownerID = '%s'", mysqli_escape_string($conn, $_SESSION['user']['ID']));
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                echo "Error executing query [ $sql ] : " . mysqli_error($conn);
                exit;
            }
            $dataRows = mysqli_fetch_all($result, MYSQLI_ASSOC);

            echo "<table border='1'>";
            echo "<th>#</th><th>Description</th><th>Image</th>";
            foreach ($dataRows as $row) {
                $ID = $row['ID'];
                $description = ($row['description']);
                $picturePath = $row['picturePath'];

                echo "<li><tr><td>$ID</td><td>$description</td><td><a href='$picturePath'><img src='$picturePath' alt='$description' width='150'></a></td></tr>";
            }
            echo "</table>";
        } else {
            echo "You are not logged in.";
            echo '<a href="login.php">Login</a> or <a href="register.php">Register</a>.';
        }
        ?>
    </body>
</html>
