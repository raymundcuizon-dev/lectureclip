<script type="text/javascript">
$(function () {
    $('a[rel*=leanModal]').leanModal({
            top: 80, // モーダルウィンドウの縦位置を指定
            overlay: 0.7, // 背面の透明度 
            // closeButton: ".closeBtn"  // 閉じるボタンのCSS classを指定
        });
    $('#preloader').hide();
});
</script>

<!-- mashup1 //-->
<div id="lc_mashup" class="clCnt cell ">
    <!--FIX --> <div  style="color:red;" id="lnameerr"></div>
    <div class="clNo">
        <?php // var_dump($_SESSION) ?>
        <p class="no">レクチャーNo<span>1</span></p>
        <p><label form="clLc">レクチャー名<input type="text" id="lcName" name="lcName"></label></p><br>
        <p><label form="clLc">Intro data <input type="text" id="intro_data" name="intro_data"></label></p>
    </div><!-- /[div.clNo] -->

    <section class="upload">
        <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
            <div id="preloader"> </div>
            <h3>【STEP1】ファイルをアップロード</h3>
            <h4>動画</h4>
            <p><a href="#">アップロード　ファイル名表示</a></p>
            <div class="fileBox clearfix">                
                <input type="file" name="lcMovie" id="lcMovie"/>
                <span><strong>動画の再生時間</strong>&nbsp;<input type="text" id="lcMovietime" name="lcMovietime" size="5">&nbsp;分</span>                    
                <div id="message"></div>              
                
                <!--<a rel="leanModal" href="#m_uploadFileMovieA"><p class="fileBtn">動画ファイルのアップロード<input type="button" name="lc_movie_1A" value="動画ファイルのアップロード"></p></a> -->
                
            </div><!-- /[div.fileBox] -->
            <dl>
                <dt>アップロード可能なファイル形式</dt>
                <dd>mp4, mov, flv</dd>
                <dd>ファイルサイズ<span>1.0</span>GB以下</dd>
            </dl>
            <br><br>
            <h4>スライド</h4>
            <p><a href="#">アップロード　ファイル名表示</a></p>
            <div class="fileBox clearfix">
                <input type="file" name="lcPdf" id="lcPdf"/>
                <!--<a rel="leanModal" href="#m_uploadFilePdfA"><p class="fileBtn">PDFファイルのアップロード<input type="button" name="lc_pdf_1A" value="PDFファイルのアップロード"></p></a> -->
            </div><!-- /[div.fileBox] -->
            <dl>
                <dt>アップロード可能なファイル形式</dt>
                <dd>pdf</dd>
                <dd>ファイルサイズ<span>1.0</span>GB以下</dd>
            </dl>
        </form> 
    </section>
</div><!-- /[div.clCnt] -->
<!-- mashup1 //-->

