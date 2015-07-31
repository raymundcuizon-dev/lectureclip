<?php include 'header.php'; redir();
//var_dump($_SESSION);
?>
<script type="text/javascript">
$(document).ready(function () {
});
</script>
<div id="contents" class="clearfix column1">
    <div class="inner">
        <section class="clWrapper">
            <div class="clTtl table">
                <h2 class="title cell">作成済のレクチャー一覧</h2>
                <div class="cell align_r">
                    <dl class="lecture_num">
                        <dt>レクチャー数</dt>
                        <dd>7</dd>
                    </dl>
                </div><!-- /[div.cell] -->
            </div><!-- /[div.stTtl] -->
            <?php 
            if(isset($_POST['delete'])){
                $del_id = $_POST['del_lec'];
                $data = array('status' => '1');
                $where = "where lno =  ".$del_id;
                $obj->update('tbl_lc_lecture', $data, $where);
                echo "<script>window.location.replace('cl_lecture_list.php');</script>";
            }
            ?>
            <form action="" method="post">
                <table>
                    <tbody>
                        <?php
                        foreach ($obj->list_course($_SESSION['cid']) as $list) {
                            extract($list);
                            ?>
                            <tr>
                                <td class="icon">
                                    <?php
                                    if ($list['ltype'] == 'mu') {
                                        echo '<span class="fa fa-film fa-6x"></span>';
                                    } elseif ($list['ltype'] == 'm') {
                                        echo '<span class="fa fa-video-camera fa-6x"></span>';
                                    } elseif ($list['ltype'] == 'pdf') {
                                        echo '<span class="fa fa-file-pdf-o fa-6x"></span>';
                                    } elseif ($list['ltype'] == 'p') {
                                        echo '<span class="fa fa-file-pdf-o fa-6x"></span>';
                                    }
                                    ?>
                                </td>
                                <td class="content">
                                    <?php if ($list['ltype'] == 'mu') { ?>
                                    <p class="ttl"><span class="fs16"><?= $list['lname']; ?></span></p>
                                    <p class="time">時間<span><?= $list['prg_time']; ?></span></p>
                                    <?php } elseif ($ltype == 'm') { ?>
                                    <p class="ttl"><span class="fs16"><?= $list['lname']; ?></span></p>
                                    <p class="time">時間<span><?= $list['prg_time']; ?></span></p>
                                    <?php } elseif ($ltype == 'pdf') { ?>
                                    <p class="ttl"><span class="fs16"><?= $list['lname']; ?></span></p>
                                    <?php } elseif ($ltype == 'p') { ?>
                                    <p class="ttl"><span class="fs16"><?= $list['lname']; ?></span></p>  
                                    <?php } ?>
                                </td>
                                <td class="price"><span><?= $list['price']; ?></span>円</td>
                                <td class="clearfix">
                                    <p class="btn_yellow float_l"><a href="cl_upload.html">編集</a></p>
                                    <input type="hidden" name="del_lec" value="<?=$list['lno'];?>" />
                                    <p class="btn btn_black float_l"><input type="submit" name="delete" value="削除" onClick="return confirm('Are you sure you want to delete your account?');"></p>
                                </td>
                            </tr>
                            <?php }   ?>
                        </tbody>
                    </table>
                </form>
                <div class="btnBox">
                    <p class="btn btn_black b01"><a href="cl_upload.php" class="fs18 w200 h45">レクチャー追加</a></p>
                    <p class="btn btn_red b03"><a href="course_info.php" class="fs18 w200 h45">コース登録</a></p>
                </div><!-- /[div.btnBox] -->
            </section>
        </div><!-- /.inner -->
    </div><!-- /#contents -->
<?php include 'footer.php'; ?>