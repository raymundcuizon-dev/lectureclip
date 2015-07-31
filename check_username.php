<?php
session_start();
function __autoload($class) {
    include 'lib/' . $class . '.php';
}
$obj = new obj;
if (isset($_POST["username"])) {
    $username = trim($_POST["username"]);
    $user = $obj->singleData($username, "email", "tbl_ut_pass");
    //EMAIL DOES NOT EXISTS.
    if (!$user) {  
        $_SESSION['tempUsertype'] = 1;
        echo'<img src="img/available.png" />';
        echo '<script>document.getElementById("user-reg").disabled = false;</script>';         
    } else {
        //FB USER
        extract($user);
        if($usertype == 2){
            $_SESSION['tempuid'] = $uid;
            $_SESSION['tempUsertype'] = 3;
            echo'<img src="img/available.png" />';
            echo '<script>document.getElementById("user-reg").disabled = false;</script>';
        }
        //EMAIL EXISTS
        else{
            echo'<img src="img/not-available.png" />';
            echo '<script>document.getElementById("user-reg").disabled = true;</script>';
        }        
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