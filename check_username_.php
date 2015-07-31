<?php

function __autoload($class) {
    include 'lib/' . $class . '.php';
}
$obj = new obj;
if (isset($_POST["username"])) {
    $username = trim($_POST["username"]);
    $user = $obj->singleData($username, "email", "tbl_ut_pass");
    if ($user) {    
        echo'<img src="img/not-available.png" />';
        echo '<script>document.getElementById("user-reg").disabled = true;</script>';
    } else {
        echo'<img src="img/available.png" />';
        echo '<script>document.getElementById("user-reg").disabled = false;</script>';
    }
}

if (isset($_POST["CourseName"])) {
    $CourseName = trim($_POST["CourseName"]);
    $CourseName_valid = $obj->singleData($CourseName, "title", "tbl_lc_course");
    if ($CourseName_valid) {    
        echo '<img src="img/not-available.png" />';
        echo '<script>document.getElementById("myBtn").disabled = true;</script>';
    } else {
        echo '<img src="img/available.png" />';
        echo '<script>document.getElementById("myBtn").disabled = false;</script>';
    }
}

?>