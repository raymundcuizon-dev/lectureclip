<?php
include 'header.php';
redir();
//test($_SESSION);
$movie_filename = 'media/video/'.$_SESSION['lecture_vid_mu'];
$pdf = 'media/pdf/'.$_SESSION['lecture_pdf_mu'];
$thumb_foldername = $_SESSION['thumb_foldername'];
$pcn = $_SESSION['page_num'];
$cid = $_SESSION['course_id'];
$lno = $_SESSION['mu_lno'];
?>

<script type="text/javascript">

var nowdate = getNewDate();

$(function () {
	$("#video_player").jPlayer({
		/* イベントハンドラ */
		ready: function () {
			/* 再生するメディアの定義 */
			$(this).jPlayer("setMedia", {
				m4v: "<?=$movie_filename;?>"

                            //m4v: "./media/video/sample.mp4?date~" + nowdate
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
		errorAlerts: false,
		/* 警告アラート表示の有無（デフォルトはfalse） */
		warningAlerts: false,
		/* 最後まで再生された時 */
		ended: function (event) {
			/* また再生する */
			$(this).jPlayer("stop");
			if ($(".insert_pdf li").length <= 0) {
				pdfReload(1);
				viewpdf = 1;
			} else if ($(".insert_pdf li").length > 0) {

                    //配列の取得
                    arrval = lectureCreateArr();
                    sp_arrval = arrval[0].split("-");

                    if (sp_arrval[1] != 0) {

                    	pdfReload(1);
                    	viewpdf = 1;

                    }

                }
            },
            error: function (event) {
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
	cssClass: "jp-video-360p"
}
});
});

</script>


<script type="text/javascript">
    $(document).ready(function () {
        $("#filename_data").html(sessionStorage.filename_data);

        //$(".media").attr("src", "./media/pdf/" + sessionStorage.pdf_filename)       
        $(".media").attr("src", "<?=$pdf;?>#page=0")
    });


</script>
<!--<script type="text/javascript">
$(document).ready(function () {
	$(".media").attr("src", "<?=$pdf;?>#page=0")
});
</script> -->
<style type="text/css">
.small_thumb{
	width: 100%;
	height: auto;
}
</style>
<div id="contents" class="clearfix column1">

		<div class="inner">
			<div id="jp_container_1" class="jp-video jp-video-360p" style="width: 480px; float: left;">
				<div class="jp-type-single">
					<div id="video_player" class="jp-jplayer"></div>
					<div class="jp-gui">
						<div class="jp-video-play">
							<a href="javascript:;" class="jp-video-play-icon"  tabindex="1">play</a>
						</div>
						<div class="jp-interface">
							<div class="jp-progress">
								<div class="jp-seek-bar">
									<div class="jp-play-bar"></div>
								</div>
							</div>
							<div class="jp-current-time"></div>
							<div class="jp-duration"></div>
							<div class="jp-controls-holder">
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
								<ul class="jp-toggles">
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
			<iframe class="media" src="" style="width:500px; height:364px;"></iframe>
			<div class="error_area">
				<p>現在の時間より未来の内容が登録済みです</p>
			</div> <br><br>
			<div class="editplaytime">
				<ul class="insert_pdf clearfix">
					<li name="1-0-1" class="className_1"><a href="javascript:controlMedia('0'), pdfReload('1');"><img src="./img/thumbnails/<?php echo $thumb_foldername ?>/thumbnail_1.jpg" width="59"></a><span><a href="javascript:removeList('.className_1', 1);">削除</a></span></li>
				</ul>
			</div>
			<ul class="pdf_thums clearfix">
				<?php
				for ($i = 1; $i <= $pcn; $i++) {
					if ($i == 1) {
						echo '<li><a href="javascript:createMashup('.$i.','.$thumb_foldername.');"><span class="thummask_' . $i . '" style="display: block;"><img src="./img/other/thum_mask.png"></span><img src="./img/thumbnails/' . $thumb_foldername . '/thumbnail_' . $i . '.jpg"></a></li>';
					} else {
						echo '<li><a href="javascript:createMashup('.$i.','.$thumb_foldername.');"><span class="thummask_' . $i . '"><img src="./img/other/thum_mask.png"></span><img src="./img/thumbnails/' . $thumb_foldername . '/thumbnail_' . $i . '.jpg"></a></li>';
					}
				}
				?>           
			</ul>
			<input type="hidden" id="listval" name="listval" value="1">
			<!-- <div class="btns">
				<p class="btn_red"><input name="submit_edit_au" class="w240 fs18 h45" value="更新する" type="submit"></p>
			</div> -->

			<form action="" class="clearfix" id="clLc">

				<p class="btn_red"><input type="submit" onClick="return editMashup(<?=$cid;?>, <?=$lno;?>);" class="w240 fs18 h45" name="edit_mashup" value="更新する"></p>
				<!-- <input type="submit" onclick="return uploadFile();" class="fs18 w370 h45" name="btn02" value="作成"></p> -->
				<!--<div class="btns">
					<p class="btn_red"><input name="submit_edit_au" class="w240 fs18 h45" value="更新する" type="submit"></p>
				</div> -->
               <!-- <p class="float_l btn btn_black"><input type="button" name="btn01" class="fs18 w370 h45" value="作成済のレクチャー一覧" onClick="location.href = 'cl_lecture_list.php'"></p>-->
              	<!-- <p id="lcb_next" class="float_r btn btn_red" style="display: block;">
              	<input type="button" name="btn03" class="fs18 w370 h45" value="次へ" onClick="javascript:loadContents(1);">
              	</p> -->
                <!-- <p id="lcb_create" class="float_r btn btn_red" style="display: none;"> 
                <a id="modaltrigger" rel="leanModal" href="#m_createLecture" ></a> -->
                <!--<input type="button" name="btn03" class="fs18 w370 h45" value="次へ" onClick="javascript:loadContents(1);"> -->
                <!-- <input type="submit" onclick="return uploadFile();" class="fs18 w370 h45" name="btn02" value="作成">  -->
                 
            </form>
		</div>
</div>
<script type="text/javascript">

function editMashup(c_id, l_no){
	console.log("CID NUMMBER: " + c_id);
	console.log("LCTURE NUMMBER: " + l_no);
	
	var cid = c_id;
	var lno = l_no;
	var thumb_arr = lectureCreateArr();	
	//console.log("THUMBNAIL ARRAY: " + thumb_arr);

	$.ajax({
        url : "/lectureclip/lib/api.php",
        type: "POST",
        data :{
				api_function: 'updateThumbnail',
				thumbnail_data: thumb_arr,
				cid: cid,
				lno: lno			
			},

		beforeSend: function(xhr){
			$('#preloader').show();  // #info must be defined somehwere
		},                 

        success: function(data){ 
            //console.log("DATA: " + data);           
            $(location).attr('href','create_course.php');               
        },

        //PRELOADER
		complete: function(xhr, textStatus){
			$('#preloader').hide();  // #info must be defined somehwere
		},

        error: function (){
            //console.log("ERROR");
        }
    });	

	return false;
}

var timer = setInterval(function () {
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

        if (arrval.length == 1) {

        	sp_arrval = arrval[0].split("-");

        	if (viewpdf != sp_arrval[2] && arrMovTime[2] >= sp_arrval[1]) {

        		viewpdf = sp_arrval[2];
        		pdfReload(sp_arrval[2]);

        	}

        } else if (arrval.length > 1) {

        	for (i = 0; i < arrval.length; i++) {

        		sp_arrval = arrval[i].split("-");
        		sp_arrval_arrid.push(sp_arrval[0]);
        		sp_arrval_arrmt.push(sp_arrval[1]);
        		sp_arrval_arrpd.push(sp_arrval[2]);

        	}

        	for (j = 0; j < sp_arrval_arrmt.length; j++) {

        		k = j + 1;

        		if (sp_arrval_arrmt[k]) {

        			if (viewpdf != sp_arrval_arrpd[j] && arrMovTime[2] >= sp_arrval_arrmt[j] && arrMovTime[2] < sp_arrval_arrmt[k]) {

        				viewpdf = sp_arrval_arrpd[j];
        				pdfReload(sp_arrval_arrpd[j]);

        			}

        		} else {

        			if (viewpdf != sp_arrval_arrpd[j] && arrMovTime[2] >= sp_arrval_arrmt[j] && arrMovTime[2] <= arrMovEndTime[2]) {

        				viewpdf = sp_arrval_arrpd[j];
        				pdfReload(sp_arrval_arrpd[j]);

        			}
        		}
        	}
        }

    }, 1000);
(function ($) {
	$(".jp-stop").click(function () {

		if ($(".insert_pdf li").length <= 0) {
			pdfReload(1);
			viewpdf = 1;
		} else if ($(".insert_pdf li").length > 0) {

                //配列の取得
                arrval = lectureCreateArr();

                //console.log("ARRVAL: " + arrval);
                sp_arrval = arrval[0].split("-");

                if (sp_arrval[1] != 0) {

                	pdfReload(1);
                	viewpdf = 1;

                }

            }

        });
}(jQuery));

</script>

<?php include 'footer.php';?>