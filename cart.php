<?php include 'header.php'; redir();
$add_cart = $_SESSION['add_cart'];
if(isset($_POST['delete'])){
    $where = "where owid =  ".$_POST['del_cart'];
    $obj->delete('tbl_otwk_cart', $where);

}
if(!isset($_SESSION['add_cart'])){ 
    $obj->emptysession("index.php","There are no items in this cart.");
} else { 

 $add_cart_count = $obj->count_lecture('tbl_otwk_cart', 'where cno = '.$_SESSION['add_cart']);
 if($add_cart_count == 0){ $obj->emptysession("index.php","There are no items in this cart."); } else { ?>

 <div id="contents" class="clearfix">
    <div class="inner">
        <div id="cartBox" class="clearfix">
            <ul>
                <li class="box active">カートの中身</li>
                <li class="box">支払い設定</li>
                <li class="box">購入内容の確認</li>
                <li class="boxEnd">購入完了</li>
            </ul>
        </div><!-- /[div#cartBox] -->
        <section class="cartInner">
            <h2 class="title">カートの中身</h2>
            <?php 
            foreach ($obj->select_data_where('tbl_otwk_cart', 'WHERE cno = '.$add_cart.' GROUP BY tip') as $c_info) {
                extract($c_info); ?>
                <div class="block">             
                    <dl class="lecturer clearfix">
                        <dt>講師名</dt>
                        <dd>
                            <?php 
                            foreach($obj->select_data_where('tbl_ut_user', 'WHERE uid = '.$c_info['tip']) as $c_tip) {
                                extract($c_tip);
                                echo ucfirst($c_tip['name1']).' '.ucfirst($c_tip['name2']);
                            }
                            unset($c_tip);
                            ?>
                        </dd>
                    </dl>
                    <table>
                        <thead>
                            <tr>
                                <th class="img"></th>
                                <th width="450px" class="courseName">コース名</th>
                                <th class="tuitionFee">受講料（税込）</th>
                                <th class="point">獲得ポイント</th>
                                <th class="delete">削除</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $data = array('tbl_lc_course.cid','tbl_lc_course.intro_data as c_intro_data','tbl_lc_course.title', 'tbl_lc_course.course_img','tbl_lc_course.price as c_price', 'tbl_lc_lecture.ltype', 'tbl_lc_lecture.lname', 'tbl_lc_lecture.intro_data as l_intro_data', 'tbl_lc_lecture.price as l_price', 'tbl_lc_lecture.prg_time', 'tbl_otwk_cart.p_target', 'tbl_otwk_cart.owid');
                            $table = 'tbl_otwk_cart LEFT JOIN tbl_lc_course ON tbl_otwk_cart.cid = tbl_lc_course.cid LEFT JOIN tbl_lc_lecture ON tbl_otwk_cart.lno = tbl_lc_lecture.lno LEFT JOIN tbl_ut_user ON tbl_otwk_cart.tip = tbl_ut_user.uid';
                            $where = 'WHERE cno =  '.$add_cart.' AND tip = '.$c_info['tip'];
                            foreach ($obj->select_w_join_2($data, $table, $where) as $details) {
                                extract($details); ?>
                                <tr>
                                    <td class="img"><?php if($details['p_target'] == 'C') {
                                        echo '<img src="img/other/'.$details['course_img'].'" width="160" height="90" alt=""> </td>';
                                    } else {
                                        if($details['ltype'] == 'mu'){ echo '<span class="fa fa-film fa-6x"></span>'; } 
                                        elseif($details['ltype'] == 'pdf'){ echo '<span class="fa fa-file-pdf-o fa-6x"></span>'; } 
                                        elseif($details['ltype'] == 'm'){ echo ''; }
                                        elseif($details['ltype'] == 'p'){ echo ''; } 
                                        elseif($details['ltype'] == 'v'){ echo '<span class="fa fa-video-camera fa-6x"></span>'; }
                                    } ?>
                                </td>
                                <td class="courseName">
                                    <dl class="txt">
                                        <?php if($details['p_target'] == 'C') { ?>
                                        <dt class="ttl"><?=$details['title'];?></dt>
                                        <dt>登録レクチャー数 <?=$obj->count_lecture('tbl_lc_lecture', 'where cid = '.$details['cid']);?> </dt>
                                        <dd class="courseBox clearfix">
                                            <dl class="number">
                                                <dt><?=$details['c_intro_data'];?></dt>
                                                <?php } elseif($details['p_target'] == 'L'){ echo '<p class="ttl"><span class="fs16">'.$details['lname'].'<br>'.$details['l_intro_data'].'</p>'; } ?>
                                            </dl>
                                        </dd>
                                    </dl>
                                </td>
                                <td class="tuitionFee">
                                    <?php if($details['p_target'] == 'C'){ echo number_format($details['c_price']); } elseif($details['p_target'] == 'L'){ echo number_format($details['l_price']); } ?>円
                                </td>
                                <td>
                                    <?php if($details['p_target'] == 'C'){ 
                                        echo number_format($details['c_price']*.01,2); 
                                    } elseif($details['p_target'] == 'L') {
                                        echo number_format($details['l_price']*.01,2);
                                    }
                                    ?>
                                </td>
                                <td class="btn_black">
                                    <form action="" method="post">
                                        <input type="hidden" name="del_cart" value="<?=$details['owid']?>" />
                                        <input type="submit" name="delete" value="削除" onClick="return confirm('Are you sure you want to delete your account?');">
                                    </form>
                                </td>
                            </tr>
                            <?php    } ?>
                        </tbody>
                    </table>
                    <dl class="notices">
                        <dt>特記事項等</dt>
                        <dd>特記事項テキスト テキスト テキスト テキスト テキスト テキスト テキスト テキスト。テキスト テキスト テキスト テキスト テキスト テキスト テキスト。テキスト テキスト テキスト テキスト テキスト テキスト テキスト テキスト テキスト テキスト テキスト テキスト テキスト テキスト。</dd>
                    </dl>
                </div><!-- /[div.block] -->
                <?php } unset($c_info); ?>
                <ul class="btn">
                    <li class="btn_black" style="float:left"><a href="javascript: window.history.go(-1)" class="other fs18 w390 h50">その他のコースを探す</a></li>
                    <li class="btn_red"><a href="payment.php" class="other fs18 w390 h50">ご購入手続きに進む</a></li>
                </ul>
            </section>
        </div><!-- /.inner -->

    </div><!-- /#contents -->
    <?php } 
}  
include 'footer.php'; ?>