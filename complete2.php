<?php 
include 'header.php';
include_once("config.php");
include_once("paypal.class.php");
redir();

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"]))
{
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	
	$token = $_GET["token"];
	$payer_id = $_GET["PayerID"];

	$item_list = $_SESSION['ItemList'];

	$ItemTotalPrice 	= $_SESSION['ItemTotalPrice']; //(Item Price x Quantity = Total) Get total amount of product; 
	$TotalTaxAmount 	= $_SESSION['TotalTaxAmount'] ;  //Sum of tax for all items in this order. 
	$HandalingCost 		= $_SESSION['HandalingCost'];  //Handling cost for this order.
	$InsuranceCost 		= $_SESSION['InsuranceCost'];  //shipping insurance cost for this order.
	$ShippinDiscount 	= $_SESSION['ShippinDiscount']; //Shipping discount for this order. Specify this as negative number.
	$ShippinCost 		= $_SESSION['ShippinCost']; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
	$GrandTotal 		= $_SESSION['GrandTotal'];

	$stringConnect = '';
	$ctr = 0;
	foreach ($item_list as $value) 
	{
		$stringConnect = $stringConnect . '&L_PAYMENTREQUEST_0_NAME' . $ctr . '='.urlencode($value['name']).
		'&L_PAYMENTREQUEST_0_NUMBER' . $ctr . '='.urlencode($value['number']).
		'&L_PAYMENTREQUEST_0_DESC' . $ctr . '='.urlencode($value['desc']).
		'&L_PAYMENTREQUEST_0_AMT' . $ctr . '='.urlencode($value['amt']).
		'&L_PAYMENTREQUEST_0_QTY' . $ctr . '='. urlencode($value['qty']);

		$ctr++;
	}
	

	$padata = 	'&TOKEN='.urlencode($token).
	'&PAYERID='.urlencode($payer_id).
	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
	$stringConnect.
				//set item info here, otherwise we won't see product details later
				/*'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
				'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
				'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
				'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
				'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).

				/* 
				//Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
				'&L_PAYMENTREQUEST_0_NAME1='.urlencode($ItemName2).
				'&L_PAYMENTREQUEST_0_NUMBER1='.urlencode($ItemNumber2).
				'&L_PAYMENTREQUEST_0_DESC1=Description text'.
				'&L_PAYMENTREQUEST_0_AMT1='.urlencode($ItemPrice2).
				'&L_PAYMENTREQUEST_0_QTY1='. urlencode($ItemQty2).
				*/

				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
				'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
				'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
				'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);

	//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
				$paypal= new MyPayPal();
				$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

	//Check if everything went ok..
				if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
				{

		//echo '<h2>Success</h2>';
		//echo 'Your Transaction ID : '.urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);

		/*
		//Sometimes Payment are kept pending even when transaction is complete. 
		//hence we need to notify user about it and ask him manually approve the transiction
		*/
		
		if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
		{
			//echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
		}
		elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
		{
			echo '<div style="color:red">Transaction Complete, but payment is still pending! '.
			'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
		}

		// we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
		// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
		$padata = '&TOKEN='.urlencode($token);
		$paypal = new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
		{
			/////////PAYMENT SUCCESS/////////////////
			//if(isset($_POST['confirm']))
			//{

			// $x = $obj->count_lecture('tbl_otwk_cart', "where p_target = 'C' AND cno =  {$add_cart}");
			// $y = $obj->count_lecture('tbl_otwk_cart', "where p_target = 'L' AND cno =  {$add_cart}");

			$add_cart = $_SESSION['add_cart'];
			foreach ($obj->select_data_where('tbl_otwk_cart', 'WHERE cno = ' . $add_cart) as $value) {
				if ($value['p_target'] == 'C') {
					$data = array('cid' => $value['cid'], 'stat' => '0', 'uid' => $uid, 'con' => 'P');
					$obj->insert('tbl_ut_course', $data);
					foreach ($obj->select_data_where('tbl_lc_lecture', 'WHERE cid = ' . $value['cid']) as $c) {
						
						if ($c['prg_time'] != NULL) {
							$data_c = array('uid' => $uid, 'cid' => $c['cid'], 'lno' => $c['lno'], 'con' => 'P', 'stat' => '0', 'prg_time' => $c['prg_time']);
							$obj->insert('tbl_ut_lecture', $data_c);
						} else {
							$data_c = array('uid' => $uid, 'cid' => $c['cid'], 'lno' => $c['lno'], 'con' => 'P', 'stat' => '0');
							$obj->insert('tbl_ut_lecture', $data_c);
						}
					}
					$tp_data = array('tbl_lc_lecture.cid as cid', 'tbl_lc_lecture.lno as lno', 'tbl_lc_course.catid as catid');
					$tp_table = 'tbl_lc_lecture INNER JOIN tbl_lc_course on tbl_lc_lecture.cid = tbl_lc_course.cid';
					$tp_where = 'WHERE tbl_lc_lecture.cid = ' . $value['cid'];
					foreach ($obj->select_w_join_2($tp_data, $tp_table, $tp_where) as $tp_chart) {
						$a = $obj->count_lecture('tbl_top_chart', 'WHERE tlid = ' . $tp_chart['lno']);
						if ($a >= 1) {
							foreach ($obj->select_data_where('tbl_top_chart', 'WHERE tlid = ' . $tp_chart['lno']) as $top) {
								$cplus = $top['tccnt'] + 1;
								$lplus = $top['tcnt'] + 1;
								$tp_data_update = array('tccnt' => $cplus, 'tcnt' => $lplus);
								$obj->update('tbl_top_chart', $tp_data_update, 'WHERE tlid = ' . $tp_chart['lno']);
							}
						} else {
							$tp_data = array('tcatid' => $tp_chart['catid'],'tcid' => $tp_chart['cid'], 'tlid' => $tp_chart['lno'], 'tccnt' => 1, 'tcnt' => 1, 'tyear' => date('Y'), 'tmonth' => date('m'));
							$obj->insert('tbl_top_chart', $tp_data);
						}
					}
					$data_ot_cart_cou = array('ordno' => $value['owid'], 'cno' => $value['cno'], 'tid' => $value['tip'], 'p_target' => $value['p_target'], 'cid' => $value['cid']);
					$obj->insert('tbl_ot_cart', $data_ot_cart_cou);
				} elseif ($value['p_target'] == 'L') {

					$tp_data1 = array('tbl_lc_lecture.cid as cid', 'tbl_lc_lecture.lno as lno', 'tbl_lc_course.catid as catid');
					$tp_table1 = 'tbl_lc_lecture INNER JOIN tbl_lc_course on tbl_lc_lecture.cid = tbl_lc_course.cid';
					$tp_where1 = 'WHERE tbl_lc_lecture.lno = ' . $value['lno'];

					foreach ($obj->select_w_join_2($tp_data1, $tp_table1, $tp_where1) as $b) {

						$bcount1 = $obj->count_lecture('tbl_top_chart', 'WHERE tlid = ' . $b['lno']);
						if($bcount1 >= 1){
							foreach ($obj->select_data_where('tbl_top_chart', 'WHERE tlid = ' . $b['lno']) as $top) {
								$cplus = $top['tccnt'] + 1;
								$lplus = $top['tcnt'] + 1;
								$tp_data_update_lec = array('tcnt' => $lplus, 'tccnt' => $cplus);
								$obj->update('tbl_top_chart', $tp_data_update_lec, 'WHERE tlid = ' . $b['lno']);
							}
						} else {
							$bcount = $obj->count_lecture('tbl_top_chart', 'WHERE tcid = ' . $b['cid']);
							if ($bcount >= 1) {
								foreach ($obj->select_data_where('tbl_top_chart', 'WHERE tcid = ' . $b['cid']) as $top) {
									$cplus = $top['tccnt'] + 1;
									$lplus = $top['tcnt'] + 1;
									$tp_data_update_lec = array('tcnt' => $lplus, 'tccnt' => $cplus);
									$obj->update('tbl_top_chart', $tp_data_update_lec, 'WHERE tcid = ' . $b['cid']);
								}
							} else {
								$tp_data_lec = array('tcatid' => $b['catid'],'tcid' => $b['cid'],'tlid' => $b['lno'], 'tcnt' => 1, 'tccnt' => 1, 'tyear' => date('Y'), 'tmonth' => date('m'));
								$obj->insert('tbl_top_chart', $tp_data_lec);
							}
						}
						$data = array('cid' => $b['cid'], 'stat' => '0', 'uid' => $uid, 'con' => 'S');
						$obj->insert('tbl_ut_course', $data);

						foreach ($obj->select_data_where('tbl_lc_lecture', 'WHERE lno = ' . $b['lno']) as $c) {
							if ($c['prg_time'] != NULL) {
								$data_c = array('uid' => $uid, 'cid' => $c['cid'], 'lno' => $c['lno'], 'con' => 'S', 'stat' => '0', 'prg_time' => $c['prg_time']);
								$obj->insert('tbl_ut_lecture', $data_c);
							} else {
								$data_c = array('uid' => $uid, 'cid' => $c['cid'], 'lno' => $c['lno'], 'con' => 'S', 'stat' => '0');
								$obj->insert('tbl_ut_lecture', $data_c);
							}
						}
					}
					$data_ot_cart_cou = array('ordno' => $value['owid'], 'cno' => $value['cno'], 'tid' => $value['tip'], 'p_target' => $value['p_target'], 'lno' => $value['lno']);
					$obj->insert('tbl_ot_cart', $data_ot_cart_cou);
				}


				$where = "where owid =  " . $value['owid'];
				$obj->delete('tbl_otwk_cart', $where);
				unset($_SESSION['add_cart']);
			}
				//}
                //Send mail function here
			// echo "<script>window.location.replace('complete.php');</script>";
			//}

			///////////////SHOW COURSES/ LECTURES PURCHASED/////////////////		
			// if(isset($_POST['take_1'])){
			// 	//echo "<script>alert('asdasd');</script>";
			// 	unset($_SESSION['complete']);
			// 	echo "<script>window.location.replace('course_detail.php?cid=".$_POST['target_1']."');</script>";
			// } elseif(isset($_POST['take_2'])){
			// 	//echo "<script>alert('asdasd');</script>";
			// 	unset($_SESSION['complete']);
			// 	echo "<script>window.location.replace('st_non_purchased.php?lno=".$_POST['target_2']."&&cid=".$_POST['target_1']."');</script>";
			// }

			if(!isset($_SESSION['complete'])){ 
				$obj->emptysession("index.php");
			} else { 
				
				?>
				<div id="contents" class="clearfix">
					<div class="inner">
						<div id="cartBox" class="clearfix">
							<ul>
								<li class="box">カートの中身</li>
								<li class="box">支払い設定</li>
								<li class="box">購入内容の確認</li>
								<li class="boxEnd active">購入完了</li>
							</ul>
						</div><!-- /[div#cartBox] -->
						<form method="post">
							<section class="cartInner">
								<h2 class="title">購入完了</h2>
								<p class="txt">ご購入ありがとうございました。<br>以下の「受講」ボタンより講座の受講へお進みください。</p>
								<!--block 開始 -->
								<?php 
								foreach ($obj->select_data_where('tbl_ot_cart', 'WHERE cno = '.$_SESSION['complete'].' GROUP BY tid') as $ot_cart_info) {
									extract($ot_cart_info); ?>
									<div class="block">				
										<dl class="lecturer clearfix">
											<dt>講師名</dt>
											<dd>
												<?php 
												foreach($obj->select_data_where('tbl_ut_user', 'WHERE uid = '.$ot_cart_info['tid']) as $ot_cart_tip) {
													extract($ot_cart_tip);
													echo ucfirst($ot_cart_tip['name1']).' '.ucfirst($ot_cart_tip['name2']);
												}
												unset($ot_cart_tip);
												?>
											</dd>
										</dl>
										<table>
											<thead>
												<tr>
													<th width="20px"></th>
													<th class="img">コース名</th>
													<th width="660px" class="courseName"></th>
													<th class="point"></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$data = array('tbl_lc_course.cid','tbl_lc_course.intro_data as c_intro_data','tbl_lc_course.title', 'tbl_lc_course.course_img','tbl_lc_course.price as c_price', 
													'tbl_lc_lecture.ltype', 'tbl_lc_lecture.lname','tbl_lc_lecture.lno', 'tbl_lc_lecture.intro_data as l_intro_data', 'tbl_lc_lecture.price as l_price', 'tbl_lc_lecture.prg_time',
													'tbl_ot_cart.p_target', 'tbl_ot_cart.ordno');
												$table = 'tbl_ot_cart
												LEFT JOIN tbl_lc_course ON tbl_ot_cart.cid = tbl_lc_course.cid
												LEFT JOIN tbl_lc_lecture ON tbl_ot_cart.lno = tbl_lc_lecture.lno
												LEFT JOIN tbl_ut_user ON tbl_ot_cart.tid = tbl_ut_user.uid';
												$where = 'WHERE cno =  '.$_SESSION['complete'].' AND tid = '.$ot_cart_info['tid'];
												foreach ($obj->select_w_join_2($data, $table, $where) as $details) { ?>
												<tr>
													<td class="no"></td>
													<td class="img">
														<?php if($details['p_target'] == 'C') {
															echo '<img src="img/other/'.$details['course_img'].'" width="224" height="126" alt=""> </td>';
														} else {
															if($details['ltype'] == 'mu'){
																echo '<span class="fa fa-film fa-7x"></span><span class="time">'.$details['prg_time'].'</span>';
															} elseif($details['ltype'] == 'pdf'){
																echo '<span class="fa fa-file-pdf-o fa-7x"></span>';
															} elseif($details['ltype'] == 'm'){
																echo '';
															} elseif($details['ltype'] == 'p'){
																echo '';
															} elseif($details['ltype'] == 'v'){
																echo '<span class="fa fa-video-camera fa-7x"></span>';
															}
														}
														?> </td>
														<td class="courseName">
															<dl class="txt">
																<?php 
																if($details['p_target'] == 'C'){ ?>
																<dt class="ttl"><?=$details['title']?></dt>
																<dd class="register">登録レクチャー数(<span><?=$obj->count_lecture('tbl_lc_lecture', 'where cid = '.$details['cid']);?></span>)</dd>
																<dd><?=$details['c_intro_data']?></dd>
																<?php	} else { ?>
																<dt class="ttl"><span class="fs16"><?=$details['lname']?></dt>
																<dd><?=$details['l_intro_data']?></dd>
																<?php	} ?>
															</dl>
														</td>
														<?php if($details['p_target'] == 'C'){ ?>

														<td class="btn btn_black">
															<a href="course_detail.php?cid=<?=$details['cid']?>">受講する</a>
														</td>
														<?php }  else { ?>
														<td class="btn btn_black">
															<a href="st_non_purchased.php?lno=<?=$details['lno']."&&cid=".$details['cid']?>">受講する</a>
														</td>
														<?php } ?>
													</tr>
													<?php   } ?>
												</tbody>
											</table>
										</div><!-- /[div.block] -->
										<?php  } unset($ot_cart_info); ?>
										<br>
										<br>
										<!--<p class="topBack btn btn_red"><input style="width:350px; height: 45px" type="submit" name="complete" value="TOPページに戻る"></p>-->
										<center><p class="topBack btn btn_red"><a href="mypage.php" class="fs18 w390 h45">TOPページに戻る</a></p></center>

									</section>
								</form>
							</div><!-- /.inner -->
						</div><!-- /#contents -->
						<?php }

			/////////////////////////END SUCCESS PAYMENT/////////////////////


		//ERROR DoExpressCheckoutPayment
					} else  {
						echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
						echo '<pre>';
						print_r($httpParsedResponseAr);
						echo '</pre>';

					}

	//ERROR DoExpressCheckoutPayment
				}else{
					echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
					echo '<pre>';
					print_r($httpParsedResponseAr);
					echo '</pre>';
				}
			}

//ERROR TOKEN
			else{
				$obj->emptysession("index.php","There are no items in this cart.");
			} 
			include 'footer.php';
			?>