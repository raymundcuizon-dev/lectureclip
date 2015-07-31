<?php
session_start();
function __autoload($class) {
    include_once '../lib/' . $class . '.php';
}
$form = new validator_admin();
$obj = new obj();
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>レクチャークリップ</title>
        <link rel="stylesheet" href="css/foundation.css" />
        
        <link rel="shortcut icon" href="../img/common/favicon.ico">
        <link rel="stylesheet" href="ico/foundation-icons.css" />
        <link rel="stylesheet" href="css/ic.css" />
        <link rel="stylesheet" href="css/jquery.dataTables.css"/>
        <script src="js/vendor/modernizr.js"></script>
    </head>
    <body onload=display_ct();>