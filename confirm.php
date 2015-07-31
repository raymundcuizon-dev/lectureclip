<?php
include 'header.php';
redir();
$add_cart = $_SESSION['add_cart'];
if (!isset($_SESSION['add_cart'])) {
	echo "<div id='contents'>
	<h1>There are no items in this cart.</h1><br>
	<p class='btn_yellow'><a href='index.php' class='w390 fs18 h45'>Continue Shopping</a></p>
	</div>";
} else {
	$x = $obj->count_lecture('tbl_otwk_cart', "where p_target = 'C' AND cno =  {$add_cart}");
	$y = $obj->count_lecture('tbl_otwk_cart', "where p_target = 'L' AND cno =  {$add_cart}");
	?>
	<div id="contents" class="clearfix">
		<div class="inner">
			<?php
			// if (isset($_POST['confirm'])) {
			// 	$condition = 'P';
			// 	if (!isset($_POST['form_key']) || !$obj->validate()) {
			// 		$error = "<p class='error_mess'>Invalid submission!</p>";
			// 	} else {
			// 		foreach ($obj->select_data_where('tbl_otwk_cart', 'WHERE cno = ' . $add_cart) as $value) {
			// 			extract($value);
			// 			if ($value['p_target'] == 'C') {
			// 				$data = array('cid' => $value['cid'], 'stat' => '0', 'uid' => $uid, 'con' => 'P');
			// 				$obj->insert('tbl_ut_course', $data);
			// 				foreach ($obj->select_data_where('tbl_lc_lecture', 'WHERE cid = ' . $value['cid']) as $c) {
			// 					extract($c);
			// 					if ($c['prg_time'] != NULL) {
			// 						$data_c = array('uid' => $uid, 'cid' => $c['cid'], 'lno' => $c['lno'], 'con' => 'P', 'stat' => '0', 'prg_time' => $c['prg_time']);
			// 						$obj->insert('tbl_ut_lecture', $data_c);
			// 					} else {
			// 						$data_c = array('uid' => $uid, 'cid' => $c['cid'], 'lno' => $c['lno'], 'con' => 'P', 'stat' => '0');
			// 						$obj->insert('tbl_ut_lecture', $data_c);
			// 					}
			// 				}
			// 				unset($c);
			// 				$tp_data = array('tbl_lc_lecture.cid as cid', 'tbl_lc_lecture.lno as lno', 'tbl_lc_course.catid as catid');
			// 				$tp_table = 'tbl_lc_lecture INNER JOIN tbl_lc_course on tbl_lc_lecture.cid = tbl_lc_course.cid';
			// 				$tp_where = 'WHERE tbl_lc_lecture.cid = ' . $value['cid'];
			// 				foreach ($obj->select_w_join_2($tp_data, $tp_table, $tp_where) as $tp_chart) {
			// 					$a = $obj->count_lecture('tbl_top_chart', 'WHERE tlid = ' . $tp_chart['lno']);
			// 					if ($a >= 1) {
			// 						foreach ($obj->select_data_where('tbl_top_chart', 'WHERE tlid = ' . $tp_chart['lno']) as $top) {
			// 							$cplus = $top['tccnt'] + 1;
			// 							$lplus = $top['tcnt'] + 1;
			// 							$tp_data_update = array('tccnt' => $cplus, 'tcnt' => $lplus);
			// 							$obj->update('tbl_top_chart', $tp_data_update, 'WHERE tlid = ' . $tp_chart['lno']);
			// 						}
			// 					} else {
			// 						$tp_data = array('tcatid' => $tp_chart['catid'],'tcid' => $tp_chart['cid'], 'tlid' => $tp_chart['lno'], 'tccnt' => 1, 'tcnt' => 1, 'tyear' => date('Y'), 'tmonth' => date('m'));
			// 						$obj->insert('tbl_top_chart', $tp_data);
			// 					}
			// 				}
			// 				$data_ot_cart_cou = array('ordno' => $value['owid'], 'cno' => $value['cno'], 'tid' => $value['tip'], 'p_target' => $value['p_target'], 'cid' => $value['cid']);
			// 				$obj->insert('tbl_ot_cart', $data_ot_cart_cou);
			// 			} elseif ($value['p_target'] == 'L') {

			// 				$tp_data1 = array('tbl_lc_lecture.cid as cid', 'tbl_lc_lecture.lno as lno', 'tbl_lc_course.catid as catid');
			// 				$tp_table1 = 'tbl_lc_lecture INNER JOIN tbl_lc_course on tbl_lc_lecture.cid = tbl_lc_course.cid';
			// 				$tp_where1 = 'WHERE tbl_lc_lecture.lno = ' . $value['lno'];

			// 				foreach ($obj->select_w_join_2($tp_data1, $tp_table1, $tp_where1) as $b) {
			// 					//test($b);
			// 					$bcount1 = $obj->count_lecture('tbl_top_chart', 'WHERE tlid = ' . $b['lno']);
			// 					if($bcount1 >= 1){
			// 						foreach ($obj->select_data_where('tbl_top_chart', 'WHERE tlid = ' . $b['lno']) as $top) {
			// 							$cplus = $top['tccnt'] + 1;
			// 							$lplus = $top['tcnt'] + 1;
			// 							$tp_data_update_lec = array('tcnt' => $lplus, 'tccnt' => $cplus);
			// 							$obj->update('tbl_top_chart', $tp_data_update_lec, 'WHERE tlid = ' . $b['lno']);
			// 						}
			// 					} else {
			// 						$bcount = $obj->count_lecture('tbl_top_chart', 'WHERE tcid = ' . $b['cid']);
			// 						if ($bcount >= 1) {
			// 							foreach ($obj->select_data_where('tbl_top_chart', 'WHERE tcid = ' . $b['cid']) as $top) {
			// 								$cplus = $top['tccnt'] + 1;
			// 								$lplus = $top['tcnt'] + 1;
			// 								$tp_data_update_lec = array('tcnt' => $lplus, 'tccnt' => $cplus);
			// 								$obj->update('tbl_top_chart', $tp_data_update_lec, 'WHERE tcid = ' . $b['cid']);
			// 							}
			// 						} else {
			// 							$tp_data_lec = array('tcatid' => $b['catid'],'tcid' => $b['cid'],'tlid' => $b['lno'], 'tcnt' => 1, 'tccnt' => 1, 'tyear' => date('Y'), 'tmonth' => date('m'));
			// 							$obj->insert('tbl_top_chart', $tp_data_lec);
			// 						}
			// 					}
			// 					$data = array('cid' => $b['cid'], 'stat' => '0', 'uid' => $uid, 'con' => 'S');
			// 					$obj->insert('tbl_ut_course', $data);
			
			// 					foreach ($obj->select_data_where('tbl_lc_lecture', 'WHERE lno = ' . $b['lno']) as $c) {
			// 						if ($c['prg_time'] != NULL) {
			// 							$data_c = array('uid' => $uid, 'cid' => $c['cid'], 'lno' => $c['lno'], 'con' => 'S', 'stat' => '0', 'prg_time' => $c['prg_time']);
			// 							$obj->insert('tbl_ut_lecture', $data_c);
			// 						} else {
			// 							$data_c = array('uid' => $uid, 'cid' => $c['cid'], 'lno' => $c['lno'], 'con' => 'S', 'stat' => '0');
			// 							$obj->insert('tbl_ut_lecture', $data_c);
			// 						}
			// 					}
			// 				}
			// 			}
			// 			$where = "where owid =  " . $value['owid'];
			// 			$obj->delete('tbl_otwk_cart', $where);
			// 			unset($_SESSION['add_cart']);
			// 		}
			// 		unset($value);
			// 	}
   //              //Send mail function here
			// 	echo "<script>window.location.replace('complete2.php');</script>";
			// }
			?>
			<?php // (isset($error)) ? $error : ''; ?>
			<div id="cartBox" class="clearfix">
				<ul>
					<li class="box">カートの中身</li>
					<li class="box">支払い設定</li>
					<li class="box active">購入内容の確認</li>
					<li class="boxEnd">購入完了</li>
				</ul>
			</div>
			<section class="buyInner">
				<h2 class="title">購入内容の確認</h2>
				<p class="txt">以下の購入内容をご確認の上「ご購入確定」ボタンをクリックしてください。</p>
				<div class="info">
					<p class="ttl">ご購入者情報</p>
					<dl class="clearfix">
						<dt>お名前</dt>
						<dd><?= ucfirst($name1) . ' ' . ucfirst($name2) ?><span>様</span></dd>
					</dl>
				</div>
				<?php
				foreach ($obj->select_data_where('tbl_otwk_cart', 'WHERE cno = ' . $add_cart . ' GROUP BY tip') as $c_info) {
					extract($c_info);
					?>
					<div class="block">
						<dl class="lecturer clearfix">
							<dt>講師名: </dt>
							<dd>
								<?php
								foreach ($obj->select_data_where('tbl_ut_user', 'WHERE uid = ' . $c_info['tip']) as $c_tip) {
									extract($c_tip);
									echo ucfirst($c_tip['name1']) . ' ' . ucfirst($c_tip['name2']);
								}
								unset($c_tip);
								?>
							</dd>
							<table>
								<thead>
									<tr>
										<th></th>
										<th>コース名</th>
										<th class="courseName"></th>
										<th class="tuitionFee">受講料（税込）</th>
										<th class="point">獲得ポイント</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$data = array('tbl_lc_course.cid', 'tbl_lc_course.intro_data as c_intro_data', 'tbl_lc_course.title', 'tbl_lc_course.course_img', 'tbl_lc_course.price as c_price',
										'tbl_lc_lecture.ltype', 'tbl_lc_lecture.lname', 'tbl_lc_lecture.intro_data as l_intro_data', 'tbl_lc_lecture.price as l_price', 'tbl_lc_lecture.prg_time',
										'tbl_otwk_cart.p_target');
									$table = 'tbl_otwk_cart
									LEFT JOIN tbl_lc_course ON tbl_otwk_cart.cid = tbl_lc_course.cid
									LEFT JOIN tbl_lc_lecture ON tbl_otwk_cart.lno = tbl_lc_lecture.lno
									LEFT JOIN tbl_ut_user ON tbl_otwk_cart.tip = tbl_ut_user.uid';
									$where = 'WHERE cno =  ' . $add_cart . ' AND tip = ' . $c_info['tip'];
									foreach ($obj->select_w_join_2($data, $table, $where) as $details) {
										extract($details);
										?>
										<tr>
											<td class="no"></td>
											<td class="img"><?php
											if ($details['p_target'] == 'C') {
												echo '<img src="img/other/' . $details['course_img'] . '" width="160" height="90" alt=""> </td>';
											} else {
												if ($details['ltype'] == 'mu') {
													echo '<span class="fa fa-film fa-6x"></span>';
												} elseif ($details['ltype'] == 'pdf') {
													echo '<span class="fa fa-file-pdf-o fa-6x"></span>';
												} elseif ($details['ltype'] == 'm') {
													echo '';
												} elseif ($details['ltype'] == 'p') {
													echo '';
												} elseif ($details['ltype'] == 'v') {
													echo '<span class="fa fa-video-camera fa-6x"></span>';
												}
											}
											?>
										</td>
										<td class="courseName">
											<dl class="txt">
												<?php if ($details['p_target'] == 'C') { ?>
												<dt class="ttl"><?= $details['title']; ?></dt>
												<dt style='font-size: 13px;'>登録レクチャー数 <?= $obj->count_lecture('tbl_lc_lecture', 'where cid = ' . $details['cid']); ?> </dt>
												<dd class="courseBox clearfix">
													<dl class="number">
														<?php
													} elseif ($details['p_target'] == 'L') {
														echo '<dt class="ttl"><span class="fs16">' . $details['lname'] . '<br>' . $details['l_intro_data'] . '</dt>';
													}
													?>
												</dl>
											</dd>
										</dl>
									</td>
									<td>
										<span class="fs">
											<?php
											if ($details['p_target'] == 'C') {
												echo number_format($details['c_price']);
											} elseif ($details['p_target'] == 'L') {
												echo number_format($details['l_price']);
											}
											?>
										</span>円</td>
										<td>
											<?php
											if ($details['p_target'] == 'C') {
												echo number_format($details['c_price'] * .01, 2);
											} elseif ($details['p_target'] == 'L') {
												echo number_format($details['l_price'] * .01, 2);
											}
											?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table> 
						</dl>	
					</div>
					<?php } unset($c_info); ?>
					<div class="sum">
						<dl class="clearfix">
							<dt>今回の獲得ポイント</dt>
							<dd><span>
								<?php
								if (!empty($x)) {
									$course_price = $obj->sum('sum(price)', ' tbl_otwk_cart LEFT JOIN tbl_lc_course ON tbl_otwk_cart.cid =  tbl_lc_course.cid', ' WHERE cno = ' . $add_cart . ' ');
								}

								if (!empty($y)) {
									$lecture_price = $obj->sum('sum(price)', ' tbl_otwk_cart LEFT JOIN tbl_lc_lecture ON tbl_otwk_cart.lno =  tbl_lc_lecture.lno', ' WHERE cno = ' . $add_cart . ' ');
								}
								$tot = $course_price + $lecture_price;
								echo $tot * .01;
								?>
							</span>pt</dd>
							<dt>合計</dt>
							<dd><span><?php
							if (!empty($x)) {
								$course_price = $obj->sum('sum(price)', ' tbl_otwk_cart LEFT JOIN tbl_lc_course ON tbl_otwk_cart.cid =  tbl_lc_course.cid', ' WHERE cno = ' . $add_cart . ' ');
							}

							if (!empty($y)) {
								$lecture_price = $obj->sum('sum(price)', ' tbl_otwk_cart LEFT JOIN tbl_lc_lecture ON tbl_otwk_cart.lno =  tbl_lc_lecture.lno', ' WHERE cno = ' . $add_cart . ' ');
							}
							echo number_format($course_price + $lecture_price);
							?></span>円</dd>
						</dl>
					</div><!-- /[div.sum] -->
					<br>
					<form action="process.php" method="post">	
						<?php // $obj->outputKey(); ?>
						<p class="btn btn_red"><input style="width:350px; height: 45px" type="submit" name="confirm" value="ご購入確定"></p>
					</form>
				</section>
			</div><!-- /.inner -->
		</div><!-- /#contents -->
		<?php } ?>
		<?php
		include 'footer.php'; //redir();?>