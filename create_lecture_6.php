<script type="text/javascript">
$(function() {
    $( 'a[rel*=leanModal]').leanModal({
        top: 80,                     // モーダルウィンドウの縦位置を指定
        overlay : 0.7,               // 背面の透明度 
        // closeButton: ".closeBtn"  // 閉じるボタンのCSS classを指定
    });
    $('#preloader').hide();
}); 
</script>

<!-- movie //-->
<div id="lc_movie" class="clCnt cell ">

	<div class="clNo">
		<p class="no">レクチャーNo<span>5</span></p>
		<p><label form="clLc">レクチャー名<input type="text" name="lcName" id="lcName" value=""></label></p><br>
		<p><label form="clLc">Intro data<input type="text" name="intro_data" id="intro_data" value=""></label></p>
	</div><!-- /[div.clNo] -->

	<section class="upload">
		<h3>動画ファイルをアップロード</h3>
		<p><a href="#">アップロード　ファイル名表示</a></p>
		<div class="fileBox clearfix">
                    <input type="file" name="lc_movie_1B" id="filetoUpload"> <div id="preloader"> </div>
			<!-- <a rel="leanModal" href="#m_uploadFileMovieB"><p class="fileBtn">動画ファイルのアップロード<input type="button" name="lc_movie_1B" value="動画ファイルのアップロード"></p></a>-->
			<span><strong>動画の再生時間</strong>&nbsp;<input type="text" name="lc_movietime_1B" size="5">&nbsp;分</span>
		</div><!-- /[div.fileBox] -->
		<dl>
			<dt>アップロード可能なファイル形式</dt>
			<dd>mp4, mov, flv</dd>
			<dd>ファイルサイズ<span>1.0</span>GB以下</dd>
		</dl>
	</section>
</div><!-- /[div.clCnt] -->
<!-- movie //-->