<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        session_start();
        if (!isset($_SESSION['counter'])){
            $_SESSION['counter'] = 0;
        }
        $_SESSION['counter']++;
        echo "counter value in this browsing session is :" . $_SESSION['counter'];
        ?>
    </body>
</html>
