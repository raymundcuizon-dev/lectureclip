<?php
//start session in all pages
if (session_status() == PHP_SESSION_NONE) { session_start(); } //PHP >= 5.4.0
//if(session_id() == '') { session_start(); } //uncomment this line if PHP < 5.4.0 and comment out line above


$PayPalMode    = 'sandbox'; // sandbox or live
$PayPalApiUsername   = 'fatimaatog-facilitator_api1.gmail.com'; //PayPal API Username
$PayPalApiPassword   = 'AC2L89FAPNWTRZ3U'; //Paypal API password
$PayPalApiSignature  = 'Ah4G0EhJDssVtdzNSrPBVmMjl0EBAy.p8qQbN80zdmRRsi06M7nDyVCJ'; //Paypal API Signature
$PayPalCurrencyCode 	= 'USD'; //Paypal Currency Code
$PayPalReturnURL 		= 'http://'.$_SERVER['SERVER_NAME'].'/lectureclip/complete2.php';//Point to process.php page
$PayPalCancelURL 		= 'http://'.$_SERVER['SERVER_NAME'].'/lectureclip/cancel_url.php'; //Cancel URL if user clicks cancel
?>