<?php
	include 'header.php';
	redir();
	$course_details = $obj->singleData($_GET['cid'], 'cid', 'tbl_lc_course', "");
	extract($course_details);

	$ppt_files = "Effective_presentation.ppt";
?>

<script type="text/javascript">
	$(function() {
		var ppt = document.getElementById("ppt_file").value;
		$(".media").attr("src", './media/ppt/' + ppt);			
	});
</script>

	<div id="contents" class="clearfix column1">
		<div class="inner">
			<section class="stWrapper">
				<div class="stTtl table">
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
                    //DISPLAY THE CURRENT LECTURE
				    $lec_no = $_GET['lno'];
				    $data = $obj->singleData($lec_no, 'lno', 'tbl_lc_lecture', 'ORDER BY lno DESC limit 1');            
				    extract($data); 
				    $current_lno = $lno;
                ?>
				<div class="table">
					<div class="cell stLeft">
						<p class="ttl"><?php echo $lname ?></p>

							<!--ここから-->
							<!--<a href="./media/ppt/sample.ppt">View My Presentation in PowerPoint</a>-->
						<!-- <iframe src="//www.slideshare.net/slideshow/embed_code/218950" width="425" height="355" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC; border-width:1px; margin-bottom:5px; max-width: 100%;" allowfullscreen> </iframe> <div style="margin-bottom:5px"> <strong> <a href="//www.slideshare.net/guesta599e2/google-presentation" title="Google Presentation" target="_blank">Google Presentation</a> </strong> from <strong><a href="//www.slideshare.net/guesta599e2" target="_blank">guesta599e2</a></strong> </div> -->
							<!-- <iframe src="http://docs.google.com/viewer?url=http://www2.ensc.sfu.ca/grad/theses/tips/Effective_presentation.ppt&embedded=true" width="600" height="600" style="border: none;"></iframe> -->
							<iframe src="http://docs.google.com/viewer?url=http://www2.ensc.sfu.ca/grad/theses/tips/<?php echo $ppt_files; ?>&embedded=true" width="600" height="600" style="border: none;"></iframe>
							 	<!--<iframe src="http://docs.google.com/viewer?url=http://192.168.1.2/lectureclip/media/pdf/6a953c4502d8f29f682aea40782301c7.pdf" width="600" height="600" style="border: none;"></iframe> -->
							<!--<iframe src="media/ppt/sample.ppt&&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>-->
							<!--<iframe class="media" src="" style="width:600px; height:600px;"></iframe>-->
							<input type="hidden" name="ppt_file" id="ppt_file" value="<?php echo $ppt_file ?>">
							<!--ここまで-->
						
						<?php 
							if(isset($_POST['save_note']) && !empty($_POST['notes'])){
								$notes = $_POST['notes'];

								//INSERT DATA INTO UT LECTURE TABLE
								$form_data = array("uid" => $uid, "cid" => $cid, "lno" => $lno, "memo" => $notes);
								$obj->insert("tbl_ut_lecture", $form_data);	
								echo "NOTE SAVED.";							
							}
						?>

						<div class="notes">
							<p><span class="fa fa-pencil-square-o"></span>NOTES</p>
							<form action="" method="post">
								<p><textarea name="notes" cols="50" rows="10"></textarea></p>
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
<?php include 'footer.php'; ?>