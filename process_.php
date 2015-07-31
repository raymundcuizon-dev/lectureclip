<?php
session_start();
include_once("config.php");
include_once("paypal.class.php");
function __autoload($class) {
	include_once 'lib/' . $class . '.php';
}
$obj = new obj();
$paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';
if($_POST)
{	
	$cno = $_SESSION['add_cart'];
	$col = array('tbl_lc_course.cid', 'tbl_lc_course.intro_data as c_intro_data', 'tbl_lc_course.title', 'tbl_lc_course.course_img', 'tbl_lc_course.price as c_price', 'tbl_lc_lecture.ltype', 'tbl_lc_lecture.lname', 'tbl_lc_lecture.intro_data as l_intro_data', 'tbl_lc_lecture.price as l_price', 'tbl_lc_lecture.prg_time', 'tbl_otwk_cart.p_target');
	$table = 'tbl_otwk_cart	LEFT JOIN tbl_lc_course ON tbl_otwk_cart.cid = tbl_lc_course.cid LEFT JOIN tbl_lc_lecture ON tbl_otwk_cart.lno = tbl_lc_lecture.lno LEFT JOIN tbl_ut_user ON tbl_otwk_cart.tip = tbl_ut_user.uid';
	$where = 'WHERE cno =  '.$cno;	
	$data = $obj->select_w_join_2($col, $table, $where);
	extract($data);
	$TotalTaxAmount 	= 0;  //Sum of tax for all items in this order. 
	$HandalingCost 		= 0;  //Handling cost for this order.
	$InsuranceCost 		= 0;  //shipping insurance cost for this order.
	$ShippinDiscount 	= 0; //Shipping discount for this order. Specify this as negative number.
	$ShippinCost 		= 0; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
	$item_total = 0;
	$subtotal = 0;
	$stringConnect = "";
	$ctr = 0;
	$item_list = array();
	foreach ($data as $value)
	{
		if($value['p_target'] == 'L'){
			$stringConnect = $stringConnect . '&L_PAYMENTREQUEST_0_NAME' . $ctr . '='.urlencode($value['lname']).
			'&L_PAYMENTREQUEST_0_NUMBER' . $ctr . '='.urlencode($value['lno']).
			'&L_PAYMENTREQUEST_0_DESC' . $ctr . '='.urlencode($value['lname']).
			'&L_PAYMENTREQUEST_0_AMT' . $ctr . '='.urlencode($value['l_price']).
			'&L_PAYMENTREQUEST_0_QTY' . $ctr . '='. urlencode(1);
			$subtotal = ((float)$value['l_price'] * 1);
			$item_total = ($item_total + $subtotal);
			$ctr++;
			$temp_item_array = array();
			$temp_item_array['name'] = $value['lname'];
			$temp_item_array['number'] = $value['lno'];
			$temp_item_array['desc'] = $value['lname'];
			$temp_item_array['amt'] = $value['l_price'];
			$temp_item_array['qty'] = 1;
			array_push($item_list, $temp_item_array);
		} else {
			$stringConnect = $stringConnect . '&L_PAYMENTREQUEST_0_NAME' . $ctr . '='.urlencode($value['title']).
			'&L_PAYMENTREQUEST_0_NUMBER' . $ctr . '='.urlencode($value['cid']).
			'&L_PAYMENTREQUEST_0_DESC' . $ctr . '='.urlencode($value['title']).
			'&L_PAYMENTREQUEST_0_AMT' . $ctr . '='.urlencode($value['c_price']).
			'&L_PAYMENTREQUEST_0_QTY' . $ctr . '='. urlencode(1);
			$subtotal = ((float)$value['c_price'] * 1);
			$item_total = ($item_total + $subtotal);
			$ctr++;
			$temp_item_array = array();
			$temp_item_array['name'] = $value['title'];
			$temp_item_array['number'] = $value['cid'];
			$temp_item_array['desc'] = $value['title'];
			$temp_item_array['amt'] = $value['c_price'];
			$temp_item_array['qty'] = 1;
			array_push($item_list, $temp_item_array);
		}
	}

	
	$GrandTotal = ($item_total + $TotalTaxAmount + $HandalingCost + $InsuranceCost + $ShippinCost + $ShippinDiscount);
	$padata = 	'&METHOD=SetExpressCheckout'.
	'&RETURNURL='.urlencode($PayPalReturnURL ).
	'&CANCELURL='.urlencode($PayPalCancelURL).
	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
	$stringConnect.'&NOSHIPPING=1'.'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($item_total).
	'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
	'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
	'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
	'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
	'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
	'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
	'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
	'&LOCALECODE=GB'.'&CARTBORDERCOLOR=FFFFFF'.'&ALLOWNOTE=1';
	$_SESSION['ItemList'] = $item_list;
	$_SESSION['ItemTotalPrice'] 	=  $item_total; //(Item Price x Quantity = Total) Get total amount of product; 
	$_SESSION['TotalTaxAmount'] 	=  $TotalTaxAmount;  //Sum of tax for all items in this order. 
	$_SESSION['HandalingCost'] 		=  $HandalingCost;  //Handling cost for this order.
	$_SESSION['InsuranceCost'] 		=  $InsuranceCost;  //shipping insurance cost for this order.
	$_SESSION['ShippinDiscount'] 	=  $ShippinDiscount; //Shipping discount for this order. Specify this as negative number.
	$_SESSION['ShippinCost'] 		=   $ShippinCost; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
	$_SESSION['GrandTotal'] 		=  $GrandTotal;
	$paypal= new MyPayPal();		
	$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
	{
		$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	}else{
		echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo '<pre>';
		print_r($httpParsedResponseAr);
		echo '</pre>';
	}
}
?>
