<?php 

if(!isset($_GET['lno'])){ header('location:index.php');} else {
	include 'header.php';
	require_once('getid3/getid3.php');
	$getID3 = new getID3;
	$_SESSION['lecture_no'] = $_GET["lno"];
	foreach ($obj->select_data_where('tbl_lc_course 
		left join tbl_ut_user on tbl_lc_course.uid = tbl_ut_user.uid 
		left join tbl_lc_lecture on tbl_lc_course.cid = tbl_lc_lecture.cid', 'where tbl_lc_lecture.lno = '.$_GET["lno"] , '')as $value) {
		//test($value);
		if($uid != $value['uid']){
			echo "<p class='error_mess'>You don't have permission to access data</p>";
		} else {

			if(isset($_POST['submit_edit_pdf'])){
				$lecture_name_pdf = $_POST['lecture_name_pdf'];
				$lecture_intro_pdf = $_POST['lecture_intro_pdf'];
				$lecture_file_pdf = $_FILES["lecture_file_pdf"]["name"];
				$lecture_price_pdf = $_POST['lecture_price_pdf'];
				$pdf_file = $_POST['pdf_file'];

				$lecture_name_pdf_error = $form->walang_laman($lecture_name_pdf, "Lecture name");
				$lecture_intro_pdf_error = $form->walang_laman($lecture_intro_pdf, "Lecture Intro");
				$lecture_price_pdf_error = $form->walang_laman($lecture_price_pdf, "Price");

				$error = $lecture_price_pdf_error.$lecture_intro_pdf_error.$lecture_name_pdf_error;

				if(empty($error)){

					if(!isset($lecture_file_pdf)){
						$data = array('lname' => $lecture_name_pdf, 'intro_data' => $lecture_intro_pdf, 'price' => $lecture_price_pdf);
					} else {
						$temporary = explode(".", $lecture_file_pdf);
						$file_extension = end($temporary);
						$file_name = md5(rand(1, 9999999999)) . '.' . $file_extension;    
						$sourcePath = $_FILES['lecture_file_pdf']['tmp_name']; 
						$targetPath = "media/pdf/". $file_name;
						move_uploaded_file($sourcePath, $targetPath);
						chmod($targetPath, 0777);
						unlink('media/pdf/'.$pdf_file);
						$data = array('lname' => $lecture_name_pdf, 'intro_data' => $lecture_intro_pdf, 'price' => $lecture_price_pdf,'ldata1' => $file_name);
						$obj->update('tbl_lc_lecture', $data, ' WHERE lno = '.$_GET['lno']);
						echo "<script>window.location.replace('create_course.php');</script>";
						//header('location: create_course.php');
					}
				}else{ echo "error";}
			} elseif ($_POST['submit_edit_ppt']){
				$lecture_name_ppt = $_POST['lecture_name_ppt'];
				$lecture_intro_ppt = $_POST['lecture_intro_ppt'];
				$lecture_file_ppt = $_FILES["lecture_file_ppt"]["name"];
				$lecture_price_ppt = $_POST['lecture_price_ppt'];
				$ppt_file = $_POST['ppt_file'];

				$lecture_name_ppt_error = $form->walang_laman($lecture_name_ppt, "Lecture name");
				$lecture_intro_ppt_error = $form->walang_laman($lecture_intro_ppt, "Lecture Intro");
				$lecture_price_ppt_error = $form->walang_laman($lecture_price_ppt, "Price");

				$error = $lecture_price_ppt_error.$lecture_intro_ppt_error.$lecture_name_ppt_error;
				if(empty($error)){

					if(!isset($lecture_file_ppt)){
						$data = array('lname' => $lecture_name_ppt, 'intro_data' => $lecture_intro_ppt, 'price' => $lecture_price_ppt);
					} else {
						$temporary = explode(".", $lecture_file_ppt);
						$file_extension = end($temporary);
						$file_name = md5(rand(1, 9999999999)) . '.' . $file_extension;    
						$sourcePath = $_FILES['lecture_file_ppt']['tmp_name']; 
						$targetPath = "media/ppt/". $file_name;
						move_uploaded_file($sourcePath, $targetPath);
						chmod($targetPath, 0777);
						unlink('media/ppt/'.$ppt_file);
						$data = array('lname' => $lecture_name_ppt, 'intro_data' => $lecture_intro_ppt, 'price' => $lecture_price_ppt,'ldata1' => $file_name);
						//test($data);
						$obj->update('tbl_lc_lecture', $data, ' WHERE lno = '.$_GET['lno']);
						//header('location: create_course.php');
						echo "<script>window.location.replace('create_course.php');</script>";
					}
				}else{ echo "error";}
			} elseif(isset($_POST['submit_edit_vid'])){
				$lecture_name_vid = $_POST['lecture_name_vid'];
				$lecture_intro_vid = $_POST['lecture_intro_vid'];
				$lecture_file_vid = $_FILES["lecture_file_vid"]["name"];
				$lecture_price_vid = $_POST['lecture_price_vid'];
				$vid_file = $_POST['vid_file'];

				$lecture_name_vid_error = $form->walang_laman($lecture_name_vid, "Lecture name");
				$lecture_intro_vid_error = $form->walang_laman($lecture_intro_vid, "Lecture Intro");
				$lecture_price_vid_error = $form->walang_laman($lecture_price_vid, "Price");

				$error = $lecture_price_vid_error.$lecture_intro_vid_error.$lecture_name_vid_error;
				if(empty($error)){

					if(!isset($lecture_file_vid)){
						$data = array('lname' => $lecture_name_vid, 'intro_data' => $lecture_intro_vid, 'price' => $lecture_price_vid);
					} else {
						$temporary = explode(".", $lecture_file_vid);
						$file_extension = end($temporary);
						$file_name = md5(rand(1, 9999999999)) . '.' . $file_extension;    
						$sourcePath = $_FILES['lecture_file_vid']['tmp_name']; 
						$targetPath = "media/video/". $file_name;
						move_uploaded_file($sourcePath, $targetPath);
						chmod($targetPath, 0777);
						unlink('media/video/'.$vid_file);
						$data = array('lname' => $lecture_name_vid, 'intro_data' => $lecture_intro_vid, 'price' => $lecture_price_vid,'ldata1' => $file_name);
						//test($data);
						$obj->update('tbl_lc_lecture', $data, ' WHERE lno = '.$_GET['lno']);
						//header('location: create_course.php');
						echo "<script>window.location.replace('create_course.php');</script>";
					}
				}else{ echo "error";}
			} elseif(isset($_POST['submit_edit_au'])){
				$lecture_name_au = $_POST['lecture_name_au'];
				$lecture_intro_au = $_POST['lecture_intro_au'];
				$lecture_file_au = $_FILES["lecture_file_au"]["name"];
				$lecture_price_au = $_POST['lecture_price_au'];
				$au_file = $_POST['au_file'];

				$lecture_name_au_error = $form->walang_laman($lecture_name_au, "Lecture name");
				$lecture_intro_au_error = $form->walang_laman($lecture_intro_au, "Lecture Intro");
				$lecture_price_au_error = $form->walang_laman($lecture_price_au, "Price");

				$error = $lecture_price_au_error.$lecture_intro_au_error.$lecture_name_au_error;
				if(empty($error)){

					if(!isset($lecture_file_au)){
						$data = array('lname' => $lecture_name_au, 'intro_data' => $lecture_intro_au, 'price' => $lecture_price_au);
					} else {
						$temporary = explode(".", $lecture_file_au);
						$file_extension = end($temporary);
						$file_name = md5(rand(1, 9999999999)) . '.' . $file_extension;    
						$sourcePath = $_FILES['lecture_file_au']['tmp_name']; 
						$targetPath = "media/audio/". $file_name;
						move_uploaded_file($sourcePath, $targetPath);
						chmod($targetPath, 0777);
						unlink('media/audio/'.$au_file);
						$data = array('lname' => $lecture_name_au, 'intro_data' => $lecture_intro_au, 'price' => $lecture_price_au,'ldata1' => $file_name);
						$obj->update('tbl_lc_lecture', $data, ' WHERE lno = '.$_GET['lno']);
						echo "<script>window.location.replace('create_course.php');</script>";
					}
				}else{ echo "error";}
			} elseif(isset($_POST['submit_edit_mu'])){
				$lecture_name_mu = $_POST['lecture_name_mu'];
				$lecture_intro_mu = $_POST['lecture_intro_mu'];
				$lecture_price_mu = $_POST['lecture_price_mu'];
				$lecture_mu_ldata1 = $_POST['lecture_mu_ldata1'];
				$lecture_mu_ldata2 = $_POST['lecture_mu_ldata2'];
				
				$lecture_vid_mu = $_FILES['lecture_vid_mu']['name'];
				$lecture_pdf_mu = $_FILES['lecture_pdf_mu']['name'];
				
				$lecture_mu_f_name = $_POST['lecture_mu_f_name'];
				$data = array('lname' => $lecture_name_mu, 'intro_data' => $lecture_intro_mu, 'price' => $lecture_price_mu);
				if(empty($lecture_vid_mu) AND empty($lecture_pdf_mu)){
					
					$obj->update('tbl_lc_lecture', $data, ' WHERE lno = '.$_GET['lno']);
					echo "<script>window.location.replace('create_course.php');</script>";
				}
				elseif(!empty($lecture_vid_mu) AND !empty($lecture_pdf_mu)){

					//echo " parehas may laman.";
					$temporary = explode(".", $_FILES["lecture_vid_mu"]["name"]);
					$file_extension = end($temporary);
        			$lecture_movie = md5(rand(1, 9999999999)) . '.' . $file_extension; //RANDOM FILENAME
					$_SESSION['lecture_vid_mu'] = $lecture_movie; 

					$sourcePath = $_FILES['lecture_vid_mu']['tmp_name']; // Storing source path of the file in a variable                
					$targetPath = "media/video/" . $lecture_movie;        
	                move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file
	                chmod($targetPath, 0777);
	                
	                //PDF
	                $temporary_pdf = explode(".", $_FILES["lecture_pdf_mu"]["name"]);
        			$file_extension_pdf = end($temporary_pdf);
			        $lecture_pdf = md5(rand(1, 9999999999)) . '.' . $file_extension_pdf; //RANDOM FILENAME
					$_SESSION['lecture_pdf_mu'] = $lecture_pdf;

	                $sourcePath2 = $_FILES['lecture_pdf_mu']['tmp_name']; // Storing source path of the file in a variable
	                $targetPath2 = "media/pdf/" . $lecture_pdf;
	                move_uploaded_file($sourcePath2, $targetPath2); // Moving Uploaded file
	                chmod($targetPath2, 0777);                              

	                //GET TOTAL NUMBER OF PAGES - PDF
	                $file = $targetPath2;
	                $page_num = exec("pdfinfo $file | grep Pages: | awk '{print $2}'");
	                $_SESSION['page_num'] = $page_num;

	                //RANDOM NAME FOR FOLDER
	                $thumb_random_foldername = time().''.rand(1, 10000);
	                $_SESSION['thumb_foldername'] = $thumb_random_foldername;
	                $thumb_path = 'img/thumbnails/' . $thumb_random_foldername;                
	                mkdir($thumb_path, true);
	                chmod($thumb_path, 0777);

	                for ($i=0; $i < $page_num; $i++) {
                    //$targetPath2 IS WHERE THE UPLOADED PDF FILE LOCATED
                    //$thumb_path IS WHERE THE THUMBNAIL IMAGES WILL BE LOCATED
	                $obj->makethumbnail($i, $targetPath2, $thumb_path);
	                }
	                $obj->update('tbl_lc_lecture', $data, ' WHERE lno = '.$_GET['lno']);


					foreach ($obj->select_data_where('tbl_lc_lecture', 'WHERE lno = '.$_GET['lno'], '') as $mu_cid) {
						$_SESSION['course_id'] = $mu_cid['cid'];
						$_SESSION['mu_lno'] = $mu_cid['lno'];
						
					}

	                echo "<script>window.location.replace('edit_mu.php');</script>";

	            }
				//$x = "/home/raymund/Johnny Depp full movies  - Pubic Enemies !-yOJ1CgQNmao.mp4";
				//$file = $getID3->analyze($x);
				//echo("Duration: ".$file['playtime_string']." / Dimensions: ".$file['video']['resolution_x']." wide by ".$file['video']['resolution_y']." tall"." / Filesize: ".$file['filesize']." bytes<br />");

	        }
	        if($value['ltype'] == "mu"){ ?>
	        <div id="contents" class="clearfix column1">
	        	<div class="inner">
	        		<form action="" method="post" enctype="multipart/form-data">
	        			<div class="tblA">
	        				<dl>
	        					<dt>レクチャー名</dt>
	        					<dd>
	        						<input type="text" name="lecture_name_mu" value="<?=$value['lname']?>" placeholder=""  class="size1" required>
	        					</dd>
	        					<dt>Intro data</dt>
	        					<dd>
	        						<textarea name="lecture_intro_mu" required style="width: 370px; height: 150px;"><?=$value['intro_data']?> </textarea>

	        					</dd>
	        					<h3>【STEP1】ファイルをアップロード</h3>
	        					<h4>動画</h4>
	        					<dt>アップロード　ファイル名表示</dt>
	        					<dd>
	        						<input type="hidden" name="lecture_mu_ldata1" value="<?=$value['ldata1']?>">
	        						<input type="file" name="lecture_vid_mu" accept="video/*" id="lcMovie">
	        					</dd>

	        					<dt>アップロード可能なファイル形式</dt>
	        					<dd>mp4, mov, flv ファイルサイズ<span>1.0</span>GB以下</dd>

	        					<h4>スライド</h4>
	        					<dt>アップロード　ファイル名表示</dt>
	        					<dd>
	        						<input name="lecture_pdf_mu" accept="application/pdf" type="file">
	        						<input type="hidden" name="lecture_mu_f_name" value="<?=$value['folder_name']?>">
	        					</dd>
	        					<dt>Price</dt>
	        					<dd>
	        						<input type="hidden" name="lecture_mu_ldata2" value="<?=$value['ldata2']?>">
	        						<input name="lecture_price_mu" type="text" value="<?=$value['price']?>">
	        					</dd>
	        				</dl>
	        			</div>
	        			<div class="btns">
	        				<p class="btn_red"><input name="submit_edit_mu" class="w240 fs18 h45" value="更新する" type="submit"></p>
	        			</div>
	        		</form>
	        	</div>
	        </div>
	        <?php } elseif ($value['ltype'] == "pdf") { ?>
	        <div id="contents" class="clearfix column1">
	        	<div class="inner">
	        		<form action="" method="post" enctype="multipart/form-data">
	        			<div class="tblA">
	        				<dl>
	        					<dt>レクチャー名</dt>
	        					<dd>
	        						<input type="text" name="lecture_name_pdf" value="<?=$value['lname']?>" placeholder=""  class="size1" required>
	        					</dd>
	        					<dt>Intro data</dt>
	        					<dd>
	        						<textarea name="lecture_intro_pdf" required style="width: 370px; height: 150px;"><?=$value['intro_data']?> </textarea>
	        					</dd>
	        					<h3>PDFファイルをアップロード</h3>
	        					<dt>アップロード　ファイル名表示</dt>
	        					<dd>
	        						<input type="hidden" value="<?=$value['ldata1']?>" name="pdf_file">
	        						<input name="lecture_file_pdf" accept="application/pdf" type="file">
	        					</dd>
	        					<dd>アップロード可能なファイル形式 pdf ファイルサイズ<span>1.0</span>GB以下</dd>
	        					<dt>Price</dt>
	        					<dd>
	        						<input name="lecture_price_pdf" type="number" value="<?=$value['price']?>" required>
	        					</dd>
	        				</dl>
	        			</div>
	        			<div class="btns">
	        				<p class="btn_red"><input name="submit_edit_pdf" class="w240 fs18 h45" value="更新する" type="submit"></p>
	        			</div>
	        		</form>
	        	</div>
	        </div>
	        <?php } elseif ($value['ltype'] == "p") { ?>
	        <div id="contents" class="clearfix column1">
	        	<div class="inner">
	        		<form action="" method="post" enctype="multipart/form-data">
	        			<div class="tblA">
	        				<dl>
	        					<dt>レクチャー名</dt>
	        					<dd>
	        						<input type="text" name="lecture_name_ppt" value="<?=$value['lname']?>" placeholder=""  class="size1" required>
	        					</dd>
	        					<dt>Intro data</dt>
	        					<dd>
	        						<textarea name="lecture_intro_ppt" required style="width: 370px; height: 150px;"><?=$value['intro_data']?> </textarea>
	        					</dd>
	        					<h3>PPTファイルをアップロード</h3>
	        					<dt>アップロード　ファイル名表示</dt>
	        					<dd>
	        						<input type="hidden" value="<?=$value['ldata1']?>" name="ppt_file">
	        						<input name="lecture_file_ppt" accept=".ppt" type="file">
	        					</dd>
	        					<dd>アップロード可能なファイル形式 ppt ファイルサイズ<span>1.0</span>GB以下</dd>
	        					<dt>Price</dt>
	        					<dd>
	        						<input name="lecture_price_ppt" type="number" value="<?=$value['price']?>" required>
	        					</dd>

	        				</dl>
	        			</div>
	        			<div class="btns">
	        				<p class="btn_red"><input name="submit_edit_ppt" class="w240 fs18 h45" value="更新する" type="submit"></p>
	        			</div>
	        		</form>
	        	</div>
	        </div>
	        <?php } elseif ($value['ltype'] == "m") { ?>
	        <div id="contents" class="clearfix column1">
	        	<div class="inner">
	        		<form action="" method="post" enctype="multipart/form-data">
	        			<div class="tblA">
	        				<dl>
	        					<dt>レクチャー名</dt>
	        					<dd>
	        						<input type="text" name="lecture_name_vid" value="<?=$value['lname']?>" placeholder=""  class="size1" required>
	        					</dd>
	        					<dt>Intro data</dt>
	        					<dd>
	        						<textarea name="lecture_intro_vid" required style="width: 370px; height: 150px;"><?=$value['intro_data']?> </textarea>
	        					</dd>
	        					<h3>音声ファイルをアップロード</h3>
	        					<dt>アップロード　ファイル名表示</dt>
	        					<dd>
	        						<input type="hidden" value="<?=$value['ldata1']?>" name="vid_file">
	        						<input name="lecture_file_vid" accept="video/*" type="file">
	        					</dd>
	        					<dd>アップロード可能なファイル形式   mp3, wav  ファイルサイズ<span>1.0</span>GB以下</dd>
	        					<dt>Price</dt>
	        					<dd>
	        						<input name="lecture_price_vid" type="number" value="<?=$value['price']?>" required>
	        					</dd>
	        				</dl>
	        			</div>
	        			<div class="btns">
	        				<p class="btn_red"><input name="submit_edit_vid" class="w240 fs18 h45" value="更新する" type="submit"></p>
	        			</div>
	        		</form>
	        	</div>
	        </div>
	        <?php }elseif ($value['ltype'] == "v") { ?>
	        <div id="contents" class="clearfix column1">
	        	<div class="inner">
	        		<form action="" method="post" enctype="multipart/form-data">
	        			<div class="tblA">
	        				<dl>
	        					<dt>レクチャー名</dt>
	        					<dd>
	        						<input type="text" name="lecture_name_au" value="<?=$value['lname']?>" placeholder=""  class="size1" required>
	        					</dd>
	        					<dt>Intro data</dt>
	        					<dd>
	        						<textarea name="lecture_intro_au" required style="width: 370px; height: 150px;"><?=$value['intro_data']?> </textarea>
	        					</dd>
	        					<h3>動画ファイルをアップロード</h3>
	        					<dt>アップロード　ファイル名表示</dt>
	        					<dd>
	        						<input type="hidden" value="<?=$value['ldata1']?>" name="au_file">
	        						<input name="lecture_file_au" accept="audio/*" type="file">
	        					</dd>
	        					<dd>アップロード可能なファイル形式  mp4, mov, flv ファイルサイズ<span>1.0</span>GB以下</dd>
	        					<dt>Price</dt>
	        					<dd>
	        						<input name="lecture_price_au" type="number" value="<?=$value['price']?>" required>
	        					</dd>
	        				</dl>
	        			</div>
	        			<div class="btns">
	        				<p class="btn_red"><input name="submit_edit_au" class="w240 fs18 h45" value="更新する" type="submit"></p>
	        			</div>
	        		</form>
	        	</div>
	        </div>
	        <?php } ?> 

	        <?php } } include 'footer.php'; }?>