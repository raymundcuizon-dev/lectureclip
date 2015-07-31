<?php

$userMail = $_REQUEST['userMail'];
if (preg_match("/[^a-z0-9]/", $userMail)) {
    print "<span style=\"color:red;\">Username contains illegal charaters.</span>";
    exit;
}
mysql_connect("localhost", "root", "root");
mysql_select_db("db_lectureclip");
$data = mysql_query("SELECT * FROM tbl_ut_pass where email='$userMail'");
if (mysql_num_rows($data) > 0) {
    print "<span style=\"color:red;\">Username already exists :(</span>";
} else {
    print "<span style=\"color:green;\">Username is available :)</span>";
}
?>