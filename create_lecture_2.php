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

<!-- pdf //-->
<div id="lc_pdf" class="clCnt cell ">
	<form>
		<div class="clNo">
			<p class="no">レクチャーNo<span>2</span></p>
			<p><label form="clLc">レクチャー名<input type="text" name="lcName" id="lcName" value=""></label></p> <br>
			<p><label form="clLc">Intro data<input type="text" name="intro_data" id="intro_data" value=""></label></p>
		</div><!-- /[div.clNo] -->

		<section class="upload">
			<h3>PDFファイルをアップロード</h3>
			<p><a href="#">アップロード　ファイル名表示</a></p>
			<div class="fileBox clearfix">
	            <input type="file" name="lc_pdf_1B" id="filetoUpload"> <div id="preloader"> </div>
				<!-- <a rel="leanModal" href="#m_uploadFilePdfB"><p class="fileBtn">PDFファイルのアップロード<input type="button" name="lc_pdf_1B" value="PDFファイルのアップロード"></p></a> -->
			</div><!-- /[div.fileBox] -->
			<dl>
				<dt>アップロード可能なファイル形式</dt>
				<dd>pdf</dd>
				<dd>ファイルサイズ<span>1.0</span>GB以下</dd>
			</dl>
		</section>
	</form>	
</div><!-- /[div.clCnt] -->
<!-- pdf //-->