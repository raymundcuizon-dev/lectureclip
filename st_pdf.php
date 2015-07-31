<?php
include 'header.php';
redir();
if(!isset($_GET['cid'])){
	echo "<script>window.location.replace('index.php');</script>";
}



$course_details = $obj->singleData($_GET['cid'], 'cid', 'tbl_lc_course', "");
extract($course_details);

$lec_no = $_GET['lno'];
$lecture_details = $obj->singleData($lec_no, 'lno', 'tbl_lc_lecture', 'ORDER BY lno DESC limit 1');     
extract($lecture_details);
$current_lno = $lno; 

?>

<script type="text/javascript">
$(function() {
	var pdf = document.getElementById("pdf_file").value;
	$(".media").attr("src", './media/pdf/' + pdf + '#page=0');	
});
</script>

<div id="contents" class="clearfix column1">
	<div class="inner">
		<section class="stWrapper">
			<div class="stTtl table">
				<h2 class="title cell"><?php echo $title; //レクチャー編集 update= 変更を保存?></h2>
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
       
			?>

			<div class="table">
				<div class="cell stLeft">
					<p class="ttl"><?php echo $lname ?></p>


					<!--ここから--> 
					<!--<a class="media" href="./media/pdf/2f97e1383d8ff3e89773ecbd67e97dff.pdf#page=2">&nbsp;</a> --> 
					<iframe class="media" src="" style="width:720px; height:405px;"></iframe>
					<input type="hidden" name="pdf_file" id="pdf_file" value="<?php echo $ldata1 ?>">
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
					$page_dom = "st_pdf.php?lno=" . $_GET['lno'] . "&&";
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
	}
//NOT FREE
	else{
		$next_page = "st_non_purchased.php";				   			
	}

	echo '<p class="btn btn_red"><a href="' .$next_page.'?lno='.$lno.'&&cid='.$cid.'" class="nextBtn">次のレクチャーへ進む</a></p>';
}
else{
//IF LAST RECORD
	echo "Last Record!";
}			    
?>

<!-- <p class="btn btn_red"><a href="#" class="nextBtn">次のレクチャーへ進む</a></p> -->				
</section>

</div><!-- /.inner -->

</div><!-- /#contents -->
<?php include 'footer.php'; ?>