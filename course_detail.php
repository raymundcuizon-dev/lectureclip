    <?php 
    include 'header.php'; 
//redir();
//$_SESSION['add_cart'] = NULL;
    ?>
    <form name="form" action="" method="post">
        <?php 
        $catdetails = $obj->singleData($_GET['cid'], 'cid', 'tbl_lc_course', "");
        extract($catdetails);

        if (isset($_POST['add_cart_course'])) {
            if (!isset($_POST['form_key']) || !$obj->validate()) {
                $error = "<p class='error_mess'>Invalid submission!</p>";
            } else {

                //echo $cc;

                if($img_user['uid'] == $catdetails['uid']){
                 $error = "<p class='error_mess'>You can't buy this course.</p>";
             }  else {

                $bb = $obj->count_lecture('tbl_ut_course', 'WHERE cid = '.$cid.' AND uid = '.$img_user['uid'].' AND con = "P"');
                $cc = $obj->count_lecture('tbl_lc_lecture', 'where price != 0 AND cid = '.$cid);
                $dd = $obj->count_lecture('tbl_ut_course', 'where cid = '.$cid.' AND con = "S" AND uid ='.$img_user['uid']);

                if($bb >= 1 OR $cc == $dd ){
                    $error = "<p class='error_mess'>You already purchased this course.</p>";
                } else {
                    if ($_SESSION['add_cart'] == NULL ){
                        $seq = time().rand(1, 9999);
                        $_SESSION['add_cart'] = $seq;
                        $_SESSION['complete'] = $seq;
                        $data = array('cno' => $_SESSION['add_cart'], 'tip' => $uid, 'p_target' => 'C', 'cid' => $cid);
                        $obj->insert('tbl_otwk_cart', $data);
                        $success_add_cart = "<p class='success'>successfully added product to the cart</p>"; 
                    }   else {
                        $a = $obj->count_lecture('tbl_otwk_cart', 'WHERE cid = '.$cid.' AND cno = '.$_SESSION['add_cart']);
                        if($a >= 1){ 
                            $error = "<p class='error_mess'>This course is already in cart.</p>";
                        } else {
                            foreach ($obj->select_data_where('tbl_lc_lecture', 'WHERE cid = '.$cid) as $c){
                                extract($c);
                                $where = 'WHERE cno = '.$_SESSION['add_cart'].' AND lno = '.$c['lno'];
                                $obj->delete('tbl_otwk_cart', $where);
                            }
                            unset($c); 
                            $data = array('cno' => $_SESSION['add_cart'], 'tip' => $uid, 'p_target' => 'C', 'cid' => $cid);
                            $obj->insert('tbl_otwk_cart', $data);
                            $success_add_cart = "<p class='success'>successfully added product to the cart</p>";

                        }
                    }    
                } 

            }         

        }       
    }
    if(isset($_POST['add_to_wishlist'])){
        $get_course = $obj->count_lecture('tbl_ut_course', 'WHERE cid = '.$cid.' AND uid = '.$img_user['uid'].' AND con = "R"');        
        if ($get_course >= 1){ 
            $error = "<p class='error_mess'>Your Selected Course is already added to Wishlist.</p>";
        }
        else{

            if($img_user['uid'] == $catdetails['uid']){
             $error = "<p class='error_mess'>You can't buy this course.</p>";
         }  else {
            $arr_wl = array('cid' => $cid, 'uid' => $img_user['uid'], 'con' => 'R');
            $obj->insert('tbl_ut_course', $arr_wl);

            foreach ($obj->select_data_where('tbl_lc_lecture', 'WHERE cid = ' . $cid) as $c) { 
                $data_c = array('uid' => $img_user['uid'], 'cid' => $c['cid'], 'lno' => $c['lno'], 'con' => 'R');
                $obj->insert('tbl_ut_lecture', $data_c);
            }
            $success_add_cart =  "<p class='success'>Your Selected Course is added to Wishlist.</p>";
        }


    }        
}
?>
<div id="contents" class="clearfix">
    <div class="inner">
        <?= (isset($error)) ? $error : ''; ?>
        <?= (isset($success_add_cart)) ? $success_add_cart : ''; ?>

        <div id="mainContents" class="clearfix">
            <section id="basicInfo">
                <h2 class="title"><?=$title?></h2>

                <p class="introduction"><?=$intro_data?></p>
                <dl class="category clearfix">
                    <dt>カテゴリー</dt>
                    <dd>
                        <?php 
                        $column_name_1 = array('tbl_m_category.catname as t_category_name');
                        $table_1 = array('tbl_lc_course', 'tbl_m_category');
                        $table_column_1 = array('tbl_lc_course.catid', 'tbl_m_category.catid');
                        foreach ($obj->select_w_join($table_1, $table_column_1, ' INNER JOIN ', 'tbl_lc_course.catid', $catid, " ",  $column_name_1, "LIMIT 1") as $loop_1 ):
                            extract($loop_1);
                        echo $t_category_name;
                        endforeach;
                        unset($loop_1);
                        ?>
                    </dd>
                </dl>
                <?php 
                if(isset($_COOKIE['lc_login_id'])) { ?>
                <dl class="lecturer clearfix">
                    <?php
                    $column_name_2 = array('tbl_ut_user.profile_img','tbl_ut_user.name1','tbl_ut_user.name2', 'tbl_ut_user.profile');
                    $table_2 = array('tbl_lc_course', 'tbl_ut_user');
                    $table_column_2 = array('tbl_lc_course.uid', 'tbl_ut_user.uid');
                    foreach ($obj->select_w_join($table_2, $table_column_2, ' LEFT JOIN ','tbl_ut_user.uid', $uid, "AND tbl_lc_course.cid = $cid",  $column_name_2 , " ") as $loop_2 ):
                        extract($loop_2);
                    echo '<dt class="img"><img style="height: 70px; width: 70px" src="img/user/'.$profile_img.'"alt="円谷 ウクレレ 一郎"></dt>';
                    echo '<dd class="name">'.$name1.' '.$name2.'</dd>';
                    echo '<dd class="text">'.$profile.'</dd>';
                    endforeach;
                    unset($loop_2);
                    ?>
                </dl>

                <dl class="lecture_num clearfix">
                    <dt>レクチャー数<span><?=$obj->count_lecture('tbl_lc_lecture', 'where cid = '.$cid); ?></span></dt>
                    <dd class="items">
                        <p>未購入<span><?=$obj->count_lecture('tbl_lc_lecture', 'where cid = '.$cid) - $obj->count_free('tbl_lc_lecture', 'where cid = '.$cid.' AND price = 0 ');?></span></p>
                        <p>購入済<span>0</span></p>
                        <p>Free<span><?=$obj->count_free('tbl_lc_lecture', 'where cid = '.$cid.' AND price = 0 '); ?></span></p>
                    </dd>
                    <dd class="progress">
                        <span class="progress_txt">コースの<span class="progress_label"><?=$obj->count_lecture('tbl_ut_lecture','where cid ='.$cid);?><span class="progress_innertxt">分の</span><?=$obj->count_lecture('tbl_lc_lecture','where cid ='.$cid);?></span>が受講済です</span>
                        <span class="progress_bar"><img src="img/other/course_progress.gif" width="28%" height="100%"></span>
                    </dd>
                </dl>
                <p class="btn_red"><a href="#" class="w390 fs18 h45">次のレクチャーを受ける</a></p>
                <?php    }
                ?>

            </section><!-- /#basicInfo -->
            <section id="lecture">
                <table>
                    <thead>
                        <tr>
                            <th colspan="4" scope="col">LECTURE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $table_3 = array('tbl_lc_lecture', 'tbl_lc_course');
                        $column_name_3 = array('tbl_lc_lecture.ltype','tbl_lc_lecture.lname', 'tbl_lc_lecture.prg_time','tbl_lc_lecture.price', 'tbl_lc_lecture.free', 'tbl_lc_lecture.intro_data', 'tbl_lc_lecture.lno' );
                        $table_column_3 = array('tbl_lc_course.cid', 'tbl_lc_lecture.cid');
                        foreach ($obj->select_w_join($table_3, $table_column_3, ' INNER JOIN ', 'tbl_lc_lecture.cid', $cid , " ", $column_name_3, " ") as $loop_3) :
                            extract($loop_3);
                        //var_dump($loop_3);
                        echo '<td class="icon">';
                        if ($ltype == 'mu') {
                            echo '<span class="fa fa-film fa-3x"></span>';
                        } elseif ($ltype == 'm') {
                            echo '<span class="fa fa-video-camera fa-3x"></span>';
                        } elseif ($ltype == 'pdf') {
                            echo '<span class="fa fa-file-pdf-o fa-3x"></span>';
                        } elseif ($ltype == 'p') {
                            echo '<span class="fa fa-file-pdf-o fa-3x"></span>';
                        }
                        echo '</td>';
                        echo '<td class="content">';
                        echo '<dt>'.$lname.'</dt>';
                        echo '<dd class="text">'.$intro_data.'</dd>';
                        echo '<dd class="format">レクチャー形式&nbsp;<span>';
                        if ($ltype == 'mu') {
                            echo '動画付プレゼン';
                            $page = "st_mashup.php";
                        } elseif ($ltype == 'm') {
                            echo '動画';
                            $page = "st_movie.php";
                        } elseif ($ltype == 'pdf') {
                            echo 'PDF';
                            $page = "st_pdf.php";
                        } elseif ($ltype == 'p') {
                            echo "PPT";                            
                            $page = "st_ppt.php";
                        } elseif ($ltype == 'v') {
                            echo "Audio";                            
                            $page = "st_music.php";
                        }
                        echo '</span></dd>';
                        echo '<dd class="time">時間&nbsp;'.$prg_time.'</dd>';
                        echo '</td><td class="purchase">
                        <p class="price"><span style="font-size: 22px">'.number_format($loop_3['price']).'</span>円</p>'; ?>                                                                                                                                              
                        <?php if(!$_COOKIE['lc_login_id']){
                            echo($loop_3['price'] != 0)? '<p class="btn_yellow"><a rel="leanModal" href="#m_login"class="fs12 w130">カートに入れる</a>' : '<p class="btn_red"><a rel="leanModal" href="#m_login"class="fs12 w130">受講する</a>';
                        }  else {  
                            if($img_user['uid'] == $catdetails['uid']){
                                echo '<p class="btn_red"><a href="'.$page.'?lno='.$loop_3['lno'].'&&cid='.$cid.'" class="w130 fs13">受講する</p>';
                            } else {
                                $paid = $obj->count_lecture('tbl_ut_lecture', 'WHERE cid = '.$cid.' AND lno = '.$lno.' AND uid = '.$img_user['uid'].' AND (con = "S" OR con = "P")'); 
                                if($loop_3['price'] == 0 || $paid == 1){
                                    echo '<p class="btn_red"><a href="'.$page.'?lno='.$loop_3['lno'].'&&cid='.$cid.'" class="w130 fs13">受講する</p>';
                                } 
                                else{
                                    echo '<p class="btn_yellow"><a href="st_non_purchased.php?lno='.$loop_3['lno'].'&&cid='.$cid.'" class="fs12 w130">カートに入れる</a></p>';
                                }

                            }
                            
                        } ?>        
                        <?php  '</td>'; 
                        echo '</tr>';
                        endforeach;
                        unset($loop_3);
                        ?>
                    </tbody>
                </table>
            </section><!-- /#lecture -->
        </div><!-- /#mainContents -->
        <div id="subContents">
            <img style="width: 320px;" src="img/other/<?php echo $course_img; ?>">
            <div id="priceArea">
                <dl class="price clearfix">
                    <dt>受講料</dt>
                    <dd><span><?=number_format($catdetails['price'])?></span>円</dd>
                </dl>
                <div class="btn clearfix">
                    <?php if (!$_COOKIE['lc_login_id']) { ?>
                    <p class="btn_yellow"><a rel="leanModal" href="#m_login"class="fs12 w130">カートに入れる</a></p>		
                    <p class="btn_black"><a rel="leanModal" href="#m_login"class="fs12 w130">検討中リストへ</a></p>   					
                    <?php } else { ?>	     			
                    <form action="" method="POST">
                        <p class="btn_yellow">                                
                            <?php $obj->outputKey(); ?>
                            <input type="submit" class="fs12 w130" value="カートに入れる" name="add_cart_course">
                        </p>
                        <p class="btn_black">                                     
                            <input type="submit" class="fs12 w130" value="検討中リストへ" name="add_to_wishlist">
                        </p>
                    </form>
                    <?php } ?>
                    <!--<p class="btn_black"><a href="#" class="fs12 w130">検討中リストへ</a></p>-->
                </div>
            </div><!-- /#priceArea -->
            <section class="unit">
                <h3>コースを受講するのにおすすめの環境</h3>
                <ul>
                    <li><?=$environment?></li>
                </ul>
            </section>
            <section class="unit">
                <h3>準備するもの</h3>                
                <ul>
                    <li><?=$preparation?></li>
                </ul>
            </section>
            <div class="sns">
                <ul class="clearfix">
                    <li class="twitter">
                        <a href="https://twitter.com/share" class="twitter-share-button" data-via="user_name" data-count="vertical">Tweet</a>
                        <script>!function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                            if (!d.getElementById(id)) {
                                js = d.createElement(s);
                                js.id = id;
                                js.src = p + '://platform.twitter.com/widgets.js';
                                fjs.parentNode.insertBefore(js, fjs);
                            }
                        }(document, 'script', 'twitter-wjs');</script>
                    </li>
                    <li class="facebook">
                        <div class="fb-like" data-href="http://www../" data-layout="box_count" data-action="like" data-show-faces="false" data-share="true"></div>
                    </li>
                    <li class="googleplus">
                        <div class="g-plusone" data-size="tall"></div>
                        <script type="text/javascript">
                        window.___gcfg = {lang: 'ja'};
                        (function () {
                            var po = document.createElement('script');
                            po.type = 'text/javascript';
                            po.async = true;
                            po.src = 'https://apis.google.com/js/platform.js';
                            var s = document.getElementsByTagName('script')[0];
                            s.parentNode.insertBefore(po, s);
                        })();
                        </script>
                    </li>
                </ul>
            </div>
        </div><!-- /#priceArea -->
    </div><!-- /#subContents -->
    <div id="relation" class="inner">
        <ul class="lectList carousel">
            <?php 
            foreach ($obj->category_slider() as $loop_4) :
                extract($loop_4); 
            echo '<li>
            <a href="course_detail.php?cid='.$loop_4['cid'].'">
            <span class="img"><img src="img/other/'.$loop_4['course_img'].'" alt=""></span>
            <span class="ttl">'.$loop_4['title'].'</span>
            <span class="name">'.ucfirst($loop_4['name1']).' '.ucfirst($loop_4['name2']).'</span>
            <span class="price">受講料<span>'.number_format($loop_4['c_price']).'</span>円</span>
            <span class="lecture">1レクチャー <span>'.number_format($loop_4['l_price']).'</span>円</span>
            </a>
            </li>';
            endforeach;
            unset($loop_4);
            ?>
        </ul>
    </div>
</div><!-- /.inner -->
</form>
<?php unset($catdetails); include 'footer.php'; ?>
