<?php
include 'header.php';
redir();
$course_details = $obj->singleData($_GET['cid'], 'cid', 'tbl_lc_course', "");
extract($course_details);

//DISPLAY THE CURRENT LECTURE
$lec_no = $_GET['lno'];
$data = $obj->singleData($lec_no, 'lno', 'tbl_lc_lecture', 'ORDER BY lno DESC limit 1');            
extract($data); 
$current_lno = $lno;
?>

<script type="text/javascript">
var nowdate = getNewDate();
$(function(){
	$("#video_player").jPlayer({
		/* イベントハンドラ */
		ready: function(){
			var movie = document.getElementById("movie_file").value;
			/* 再生するメディアの定義 */
			$(this).jPlayer("setMedia", {
				//m4v : "./media/video/sample_iPod.mp4?date~" + nowdate
				m4v: "./media/video/" + movie + "?date~" + nowdate
			});
		},
		/* プレロード（デフォルトは'metadata'、プレロードする場合は'auto'） */
		preload: "metadata",
		/* 音量（デフォルトは0.8、指定可能な値の範囲は0～1） */
		volume: 0.5,
		/* ミュートの有無（デフォルトはfalse）*/
		muted: false,
		/* 背景色（デフォルトは#000000） */
		backgroundColor: "#000000",
		/* エラーアラート表示の有無（デフォルトはfalse） */
		errorAlerts:false,
		/* 警告アラート表示の有無（デフォルトはfalse） */
		warningAlerts:false,
		/* 最後まで再生された時 */
		ended: function (event) {
			/* また再生する */
			$(this).jPlayer("stop");
			if($(".insert_pdf li").length <= 0){
				pdfReload(1);
				viewpdf = 1;
			} else if($(".insert_pdf li").length > 0) {
				
			//配列の取得
			arrval = lectureCreateArr();
			sp_arrval = arrval[0].split("-");
			
			if(sp_arrval[1] != 0){
				
				pdfReload(1);
				viewpdf = 1;
				
			}
			
		}
	},
	error:function(event){
	//console.log(event.jPlayer.error);
	//console.log(event.jPlayer.error.type);
},
/* Jplayer.swfのパス */
swfPath: "./js/jplayer/",
/* ソリューションの優先度（デフォルトは\"html, flash\") */
solution: 'html, flash',
/* フォーマット（デフォルトはmp3、カンマ区切りで複数指定可、優先度は左が高）*/
/* 指定可能なフォーマットは、mp3, m4a, m4v, oga, ogv, wav, webma, webmv */
/* 音声ならmp3 or m4a、動画ならm4v */
supplied: "m4v",
size: {
	width: "340px",
	height: "191px",
	cssClass: "jp-video-360p"
}
});
});

</script>
<script type="text/javascript">
$(function() {
	var pdf = document.getElementById("pdf_file").value;
	$(".media").attr("src", './media/pdf/' + pdf + '#page=1');	
});
</script>
<style type="text/css">
div.media{
	float: right;
}
div.media iframe{
	height: 306px;
}
.insert_pdf li{
	margin-bottom: 7px !important;
}
</style>

<div id="contents" class="clearfix column1">
	<div class="inner">
		<section class="stWrapper">
			<div class="stTtl table">
				<!--<h2 class="title cell">ビジネス現場の英語表現 Vol.1 マーケティング </h2>-->
				<h2 class="title cell"><?php echo $title ?></h2>
				<div class="cell">
					<dl class="lecture_num clearfix">
						<dt>レクチャー数<span><?= $obj->count_lecture('tbl_lc_lecture', 'where cid = ' . $cid); ?></span></dt>
						<dd class="items">
							<p>未購入<span><?= $obj->count_lecture('tbl_lc_lecture', 'where cid = ' . $cid) - $obj->count_free('tbl_lc_lecture', 'where cid = ' . $cid . ' AND price = 0 '); ?></span></p>
							<p>購入済<span>0</span></p>
							<p>Free<span><?= $obj->count_free('tbl_lc_lecture', 'where cid = ' . $cid . ' AND price = 0 '); ?></span></p>
						</dd>
					</dl>
				</div><!-- /[div.cell] -->
			</div><!-- /[div.stTtl] -->

			<?php
            
			?>
			<div class="table">
				<div class="cell stLeft">
					<p class="ttl"><?php echo $lname ?></p>


					<!-- mashup2 //-->
					<div id="lc_mashup_setting" class="clCnt cell " style="display: block;">
						<input type="hidden" name="movie_file" id="movie_file" value="<?php echo $ldata1 ?>">
						<input type="hidden" name="pdf_file" id="pdf_file" value="<?php echo $ldata2 ?>">

						<section class="upload">
							
							<div class="clearfix">
								
								<!-- ここから -->
								<div id="jp_container_1" class="jp-video jp-video-360p" style="width: 340px; float: left;">
									<div class="jp-type-single">
										<div id="video_player" class="jp-jplayer"></div>
										<div class="jp-gui">
											<div class="jp-video-play" style="height: 191px; margin-top: -191px;">
												<a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
											</div>
											<div class="jp-interface">
												<div class="jp-progress">
													<div class="jp-seek-bar">
														<div class="jp-play-bar"></div>
													</div>
												</div>
												<div class="jp-current-time"></div>
												<div class="jp-duration"></div>
												<div class="jp-controls-holder" style="width: 340px;">
													<ul class="jp-controls">
														<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
														<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
														<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
														<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
														<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
														<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
													</ul>
													<div class="jp-volume-bar">
														<div class="jp-volume-bar-value"></div>
													</div>
													<ul class="jp-toggles" style="margin-top: 10px;">
														<li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="full screen">full screen</a></li>
														<li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="restore screen">restore screen</a></li>
														<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
														<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
													</ul>
												</div>
												<div class="jp-title">
													<ul>
														<li>&nbsp;</li>
													</ul>
												</div>
											</div>
										</div>
										<div class="jp-no-solution">
											<span>Update Required</span>
											To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
										</div>
									</div>
								</div>
								<!-- / ここまで -->
								
								<!--ここから-->
								<iframe class="media" src="" style="width:340px; height:306px;"></iframe>
								<!--ここまで-->
							</div>
							
							<div class="error_area">
								<p>現在の時間より未来の内容が登録済みです</p>
							</div>
							
							<div class="editplaytime">
								<ul class="insert_pdf clearfix">
									
									<?php $countlec = $obj->count_lecture('tbl_mu_thubnail', 'where lno = '.$lec_no); ?>
									<?php 
									$ctr = 1;
									foreach ($obj->select_data_where('tbl_mu_thubnail',"WHERE lno = $lec_no", "") as $thumbnails) {
										extract($thumbnails);
										$pid = $thumbnails['pid'];
										$ptime = $thumbnails['ptime'];
										$ppage = $thumbnails['ppage'];
										echo '<li name="'.$pid.'-'.$ptime.'-'.$ppage.'" class="className_'.$ctr.'"><a href="javascript:controlMedia('.$ptime.'), pdfReload('.$pid.');"><img src="./img/thumbnails/' . $folder_name . '/thumbnail_' . $ctr . '.jpg" width="59" /></a></li>';
										$ctr++;						
									}
									unset($thumbnails);
									?>

								</ul>
							</div>
							
						</section>
					</div><!-- /[div.clCnt] -->
					<!-- mashup2 //-->

					<?php 
					if(isset($_POST['save_note']) && !empty($_POST['notes'])){
						$notes = $_POST['notes'];
						
									//GET PRG_TIME FROM THE LECTURE TABLE
						$data = $obj->singleData($lec_no, 'lno', 'tbl_lc_lecture', 'ORDER BY lno DESC limit 1');    
						extract($data); 
						$prg_time = $data['prg_time'];

									//INSERT DATA INTO UT LECTURE TABLE
						$form_data = array("uid" => $uid, "cid" => $cid, "lno" => $lno, "memo" => $notes, "prg_time" => $prg_time);
						$obj->insert("tbl_ut_lecture", $form_data);	
						echo "NOTE SAVED.";							
					}
					?>

					<div class="notes">
						<p><span class="fa fa-pencil-square-o"></span>NOTES</p>
						<form action="" method="post">
							<p><textarea name="notes" id="notes" cols="50" rows="10"></textarea></p>
							<p class="btn btn_black"><input type="submit" name="save_note" value="保存する"></p>
						</form>
					</div><!-- /[div.notes] -->
				</div><!-- /[div.cell] -->
				<div class="cell stList">
					
					<?php 
                    //FOR PAGINATION
					$page = isset($_GET['page']) ? $_GET['page'] : 1;                    
					$records_per_page = 7;
					$from_record_num = ($records_per_page * $page) - $records_per_page;
					$page_dom = "st_mashup.php?lno=" . $_GET['lno'] . "&&";
					$total_rows = $obj->count_lecture('tbl_lc_lecture', 'where cid = ' . $cid); ;
					$range = $obj->count_lecture('tbl_lc_lecture', 'where cid = ' . $cid); ;
					$obj->paging($page_dom, $records_per_page, $total_rows, $range);
					
					$table_1 = array('tbl_lc_lecture', 'tbl_lc_course');
					$column_name_1 = array('tbl_lc_lecture.ltype', 'tbl_lc_lecture.lname', 'tbl_lc_lecture.prg_time', 'tbl_lc_lecture.price', 'tbl_lc_lecture.free', 'tbl_lc_lecture.intro_data', 'tbl_lc_lecture.lno');
					$table_column_1 = array('tbl_lc_course.cid', 'tbl_lc_lecture.cid');
					foreach ($obj->select_w_join($table_1, $table_column_1, ' INNER JOIN ', 'tbl_lc_lecture.cid', $cid, " ", $column_name_1, "  LIMIT {$from_record_num} , {$records_per_page}") as $loop_1) :
						extract($loop_1);                	
					?>

					<div class="list">
						
						<?php 

		                //IF LECTURE FREE OR PAID
						$paid = $obj->count_lecture('tbl_ut_lecture', 'WHERE cid = '.$cid.' AND lno = '.$lno.' AND uid = '.$img_user['uid'].' AND (con = "S" OR con = "P")');	 
						if($price == 0 OR ($price != 0 AND $paid)){
						   			//DETERMINE WHAT LECTURE TYPE TO OPEN SPECIFIC PAGE
							if($ltype == 'mu'){                                
								$pages = "st_mashup.php";
							} elseif ($ltype == 'pdf') {                                
								$pages = "st_pdf.php";
							}elseif ($ltype == 'm') {                                 
								$pages = "st_movie.php";
							} elseif ($ltype == 'p') {                                
								$pages = "st_ppt.php";
							}
							elseif ($ltype == 'v') {                                
								$pages = "st_music.php";
							} 
						}
						else{
							$pages = "st_non_purchased.php";
						}
						
	                           	//DETERMINES THE CURRENT PAGE
						if($current_lno == $lno){
							echo '<a href="'.$pages.'?lno='.$lno.'&&cid='.$cid.'" class="active">'; 
						}
						else{
							echo '<a href="'.$pages.'?lno='.$lno.'&&cid='.$cid.'">';
						}	                            
						?>
						
						<ul>
							<li class="table active">
								<div class="cell list_inner">                                    	
									<p class="listTtl"><?=$lname?></p>
									<ul>
										<li class="icon">

											<?php 
	                                                    //ICON
											if($ltype == 'mu'){
												echo '<span class="fa fa-film fa-3x"></span>';    
												$type = "動画";                                                    
											} elseif ($ltype == 'pdf') {
	                                                        echo '<span class="fa fa-file-pdf-o fa-3x"></span>'; # code...  
	                                                        $type = "PDF"; 
	                                                    } elseif ($ltype == 'p') {
	                                                        echo '<span class="fa fa-file-pdf-o fa-3x"></span>'; # code...  
	                                                        $type = "Power Point";                                                           
	                                                    }elseif ($ltype == 'm') {                                                    	
	                                                        echo '<span class="fa fa-video-camera fa-3x"></span>'; # code...    
	                                                        $type = "動画";                                                                                                     
	                                                    } elseif ($ltype == 'v') {
	                                                        echo '<span class="fa fa-film fa-3x"></span>'; # code... 
	                                                        $type = "動画";                                                           
	                                                    }
	                                                    ?>
	                                                </li>
	                                                <?php 
	                                                //FREE OR NOT	                                           
	                                                if($price == 0){
	                                                    echo '<li class="txt01">無料</li>';
	                                                }elseif($price != 0 AND $paid){
	                                                	echo '<li class="txt01 yellow">購入済</li>';
	                                                }else{
	                                                    echo '<li class="txt01 yellow">Not購入済</li>';
	                                                }
	                                                ?>
	                                                <li class="txt02">形式
	                                                	<?php 
	                                                    //DESCRIPTION
	                                                	echo '<span>' . $type. '</span>';
	                                                	?>
	                                                </li>
	                                                <?php 
	                                             	//PLAYTIME FOR MASHUP/MOVIE
	                                                if($ltype == 'mu'){
	                                                	echo '<li class="txt03">時間<span>'.$prg_time.'</li>';
	                                                } elseif ($ltype == 'pdf') {
	                                                    echo ''; # code...
	                                                }elseif ($ltype == 'm') {
	                                                	echo '<li class="txt03">時間<span>'.$prg_time.'</li>';
	                                                } elseif ($ltype == 'ppt') {
	                                                	echo '';
	                                                }
	                                                ?>

	                                            </ul>                                        
	                                        </div>
	                                    </li>
	                                </ul>
	                            </a>                        
	                        </div><!-- /[div.list] -->
	                        
	                        <?php 
	                        endforeach;                    
	                        unset($loop_1);
	                        ?>

	                    </div><!-- /[div.cell] -->
	                </div><!-- /[div.table] -->
	                
	                <?php
				    //GET THE NEXT LECTURE
	                $arr_nextlec = $obj->nextData($lec_no, 'lno', $cid, 'cid', 'tbl_lc_lecture', 'ORDER BY lno limit 1');

				   	//NOT LAST RECORD
	                if($arr_nextlec != false){
	                	extract($arr_nextlec);				 		

				 		//IF LECTURE FREE OR PAID
	                	if($price == 0 OR ($price != 0 AND $paid)){
				   			//DETERMINE WHAT LECTURE TYPE TO OPEN SPECIFIC PAGE
	                		if($ltype == 'mu'){
	                			$next_page = "st_mashup.php";
	                		} elseif ($ltype == 'pdf') {
	                			$next_page = "st_pdf.php";
	                		}elseif ($ltype == 'm') { 
	                			$next_page = "st_movie.php";
	                		} elseif ($ltype == 'p') {
	                			$next_page = "st_ppt.php";
	                		} elseif ($ltype == 'v') {
	                			$next_page = "st_music.php"; 
	                		}

	                		echo "NEXT LECTURE: FREE";
	                	}
				   		//NOT FREE
	                	else{
	                		$next_page = "st_non_purchased.php";
	                		echo "NEXT LECTURE: NOT FREE";
	                	}

	                	echo '<p class="btn btn_red"><a href="' .$next_page.'?lno='.$lno.'&&cid='.$cid.'" class="nextBtn">次のレクチャーへ進む</a></p>';
	                }
	                else{
						//IF LAST RECORD
	                	echo "Last Record!";
	                }			    
	                ?>	

	            </section>
	        </div><!-- /.inner -->
	    </div><!-- /#contents -->
	    <script type="text/javascript">
	    var movie = document.getElementById("movie_file").value;
	    
	    var timer = setInterval(function(){
	    	
		//再生時間の取得
		var playtime = $(".jp-current-time").html();
		var arrMovTime = movPlayTime(playtime);
		var endtime = $(".jp-duration").html();
		var arrMovEndTime = movPlayTime(endtime);
		
		//配列の取得
		arrval = lectureCreateArr();
		
		//変数の宣言
		var sp_arrval = "";
		var sp_arrval_arrid = [];
		var sp_arrval_arrmt = [];
		var sp_arrval_arrpd = [];
		
		if(arrval.length == 1){
			
			sp_arrval = arrval[0].split("-");
			
			if(viewpdf != sp_arrval[2] && arrMovTime[2] >= sp_arrval[1]){
				
				viewpdf = sp_arrval[2];
				pdfReload(sp_arrval[2]);
				
			}
			
		} else if(arrval.length > 1) {
			
			for(i=0; i<arrval.length; i++){
				
				sp_arrval = arrval[i].split("-");
				sp_arrval_arrid.push(sp_arrval[0]);
				sp_arrval_arrmt.push(sp_arrval[1]);
				sp_arrval_arrpd.push(sp_arrval[2]);
				
			}
			
			for(j=0; j<sp_arrval_arrmt.length; j++){
				
				k = j + 1;
				
				if(sp_arrval_arrmt[k]){
					
					if(viewpdf != sp_arrval_arrpd[j] && arrMovTime[2] >= sp_arrval_arrmt[j] && arrMovTime[2] < sp_arrval_arrmt[k]){
						
						viewpdf = sp_arrval_arrpd[j];
						pdfReload(sp_arrval_arrpd[j]);
						
					}
					
				} else {
					
					if(viewpdf != sp_arrval_arrpd[j] && arrMovTime[2] >= sp_arrval_arrmt[j] && arrMovTime[2] <= arrMovEndTime[2]){
						
						viewpdf = sp_arrval_arrpd[j];
						pdfReload(sp_arrval_arrpd[j]);
						
					}
					
				}
				
			}
			
			//console.log(sparr_arrval);
			
		}
		
	}, 1000);

	// タイマーのクリア
	//clearInterval(timer);
	
	//動画を停止した時にPDF1ページ目を表示させる
	(function ($) {
		$(".jp-stop").click(function () {
			
			if($(".insert_pdf li").length <= 0){
				pdfReload(1);
				viewpdf = 1;
			} else if($(".insert_pdf li").length > 0) {
				
				//配列の取得
				arrval = lectureCreateArr();
				sp_arrval = arrval[0].split("-");
				
				if(sp_arrval[1] != 0){
					
					pdfReload(1);
					viewpdf = 1;
					
				}
				
			}
			
		});
	}(jQuery));

	</script>
	<?php include 'footer.php'; ?>