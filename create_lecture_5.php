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

<!-- music //-->
<div id="lc_music" class="clCnt cell ">

	<div class="clNo">
		<p class="no">レクチャーNo<span>4</span></p>
		<p><label form="clLc">レクチャー名<input type="text" name="lcName" id="lcName" value=""></label></p><br>
		<p><label form="clLc">Intro data<input type="text" name="intro_data" id="intro_data" value=""></label></p>
	</div><!-- /[div.clNo] -->

	<section class="upload">
		<h3>音声ファイルをアップロード</h3>
		<p><a href="#">アップロード　ファイル名表示</a></p>
		<div class="fileBox clearfix">
                    <input type="file" name="lc_music_1" id="filetoUpload"> <div id="preloader"> </div>
			<!-- <a rel="leanModal" href="#m_uploadFileMusic"><p class="fileBtn">音声ファイルのアップロード<input type="button" name="lc_music_1" value="音声ファイルのアップロード"></p></a> -->
		</div><!-- /[div.fileBox] -->
		<dl>
			<dt>アップロード可能なファイル形式</dt>
			<dd>mp3, wav</dd>
			<dd>ファイルサイズ<span>1.0</span>GB以下</dd>
		</dl>
	</section>
</div><!-- /[div.clCnt] -->
<!-- music //-->
