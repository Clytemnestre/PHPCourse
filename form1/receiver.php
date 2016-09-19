<?php

if ((isset($_['name'])) || (isset($_['age']))) {
    echo "Error: name and age must be provided";
} else {
    $name = $_GET['name'];
    $age = $_GET['age'];

    if (count($name) == 0) {
        echo "name must not be empty";
    }

    if (count($age) == 0) {
        echo "age must not be empty";
    }
    echo "Hello $name! you are $age years old.";
}
?>

