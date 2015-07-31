<script type="text/javascript">
$(function() {
    $( 'a[rel*=leanModal]').leanModal({
        top: 80,                     // モーダルウィンドウの縦位置を指定
        overlay : 0.7,               // 背面の透明度 
        // closeButton: ".closeBtn"  // 閉じるボタンのCSS classを指定
    });
}); 
</script>

<!-- html //-->
<div id="lc_html" class="clCnt cell ">

	<div class="clNo">
		<p class="no">レクチャーNo<span>4</span></p>
		<p><label form="clLc">レクチャー名<input type="text" name="lcName" value=""></label></p>
	</div><!-- /[div.clNo] -->

	<section class="upload">
		<h3>HTMLコードのアップロード</h3>
		<p>HTMLコードの入力</p>
		<textarea name="uploadHtml" cols="50" rows="25" class="uploadHtml"></textarea>
	</section>
</div><!-- /[div.clCnt] -->
<!-- html //-->