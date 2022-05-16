<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // echo "I am here";
    $name = $_POST['fullname'];
    $phone = $_POST['phone'];
    $package = $_POST['package'];

    echo $package;
}

?>