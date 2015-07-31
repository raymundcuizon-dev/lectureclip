<?php include 'header.php'; 
//print_r(array_keys(get_defined_vars()));
redir();
?>
<div id="contents" class="clearfix">
    <div class="inner">

        <div id="mainContents" class="clearfix">
            <section class="unit">
                <h2 class="title">購入済みのコース</h2>
                <ul class="lectList clearfix">
                    <?php
                    $uid = 1;
                    echo "UID: " . $uid;
                    //DISPLAY PURCHASED COURSE
                    $where = 'WHERE tbl_ut_lecture.uid = ' . $uid . '  AND (tbl_ut_lecture.con = "P" OR tbl_ut_lecture.con = "S") GROUP BY tbl_lc_course.cid ORDER BY tbl_ut_lecture.regdate DESC';
                    $table = 'tbl_ut_lecture 
                    inner join tbl_lc_lecture on tbl_ut_lecture.lno = tbl_lc_lecture.lno
                    inner join tbl_lc_course on tbl_ut_lecture.cid = tbl_lc_course.cid
                    INNER JOIN tbl_ut_user on tbl_lc_course.cid = tbl_lc_lecture.cid';
                    $data = array('tbl_lc_course.cid', 'tbl_lc_course.course_img','tbl_lc_course.title', 'tbl_ut_user.name1','tbl_lc_course.price AS c_price', 'MIN(tbl_lc_lecture.price) AS l_price');
                    $data_count =  0;
                    foreach ($obj->select_w_join_2($data, $table, $where) as $data_purchased) :
                        extract($data_purchased);
                    $data_count++;
                    if($data_count <= 3){                       
                        ?>                                      
                        <li>
                            <a href="course_detail.php?cid=<?= $data_purchased['cid'] ?>">
                                <samp class="img"><img src="img/other/<?= $data_purchased['course_img']; ?>" alt=""></samp>
                                <span class="ttl"><?= $data_purchased['title']; ?></span>
                                <span class="name"><?= $data_purchased['name1']; ?></span>
                                <span class="price">受講料<span><?= number_format($data_purchased['c_price']); ?></span>円</span>
                                <span class="lecture">1レクチャー <span><?= number_format($data_purchased['l_price']); ?></span>円～</span>
                            </a>
                        </li> 
                        <?php
                    }
                    endforeach;                    

                    //IF NO PURCHASED 
                    if(empty($data_purchased)){
                        echo "<div id='contents'>There are no purchased courses.</div>";
                    }
                    elseif ($data_count > 3) {
                        echo '</ul>
                        <p class="btn_more"><a href="purchase_list.php">もっと見る</a></p>';
                    }
                    unset($data_count); 
                    unset($data_purchased);                     
                    ?> 
                </ul>
            </section>
            <section class="unit">
                <h2 class="title">検討中リスト</h2>
                <ul class="lectList clearfix">

                    <?php
                    //DISPLAY WISHLIST
                    $where1 = 'WHERE tbl_ut_lecture.uid = ' . $uid . ' AND tbl_ut_lecture.con = "R" GROUP BY tbl_ut_lecture.cid ORDER BY tbl_ut_lecture.regdate DESC';
                    $table1 = 'tbl_ut_lecture
                    INNER JOIN tbl_lc_course on tbl_ut_lecture.cid = tbl_lc_course.cid
                    INNER JOIN tbl_lc_lecture on tbl_lc_course.cid = tbl_lc_lecture.cid
                    INNER JOIN tbl_ut_user on tbl_lc_course.cid = tbl_lc_lecture.cid';
                    $data1 = array('tbl_lc_course.cid', 'tbl_lc_course.course_img','tbl_lc_course.title', 'tbl_ut_user.name1','tbl_lc_course.price AS c_price', 'MIN(tbl_lc_lecture.price) AS l_price');
                    $data_count1 =  0;
                    foreach ($obj->select_w_join_2($data1, $table1, $where1) as $data_wishlist) :                    
                        extract($data_wishlist);
                    $data_count1++;
                    if($data_count1 <= 3){
                        ?>                                      
                        <li>
                            <a href="course_detail.php?cid=<?= $data_wishlist['cid'] ?>">
                                <samp class="img"><img src="img/other/<?= $data_wishlist['course_img']; ?>" alt=""></samp>
                                <span class="ttl"><?= $data_wishlist['title']; ?></span>
                                <span class="name"><?= $data_wishlist['name1']; ?></span>
                                <span class="price">受講料<span><?= number_format($data_wishlist['c_price']); ?></span>円</span>
                                <span class="lecture">1レクチャー <span><?= number_format($data_wishlist['l_price']); ?></span>円～</span>
                            </a>
                        </li>
                        <?php
                    }
                    endforeach;
                    
                    //IF NO WISHLIST
                    if(empty($data_wishlist)){
                        echo "<div id='contents'>There are no Wishlist.</div>";
                    }
                    elseif ($data_count1 > 3) {
                        echo '</ul>
                        <p class="btn_more"><a href="review_list.php">もっと見る</a></p>';
                    }
                    unset($data_count1);
                    unset($data_wishlist);                          
                    ?> 
                </ul>
            </section>


            <section class="unit">
                <h2 class="title">おすすめのコース</h2>
                <ul class="lectList clearfix">
                    <?php
                    $data2 = array('tbl_lc_course.cid','tbl_lc_course.course_img', 'tbl_lc_course.title', 'tbl_lc_course.uid','tbl_lc_course.price as c_price','MIN(tbl_lc_lecture.price) as l_price');
                    $where2 = 'WHERE tbl_ut_lecture.cid NOT IN (SELECT cid FROM tbl_ut_lecture WHERE uid = '.$uid.') GROUP BY tbl_ut_lecture.cid ORDER BY tcnt DESC LIMIT 3';
                    $table2 = ' tbl_ut_lecture INNER JOIN tbl_top_chart ON tbl_ut_lecture.cid = tbl_top_chart.tcid
                    INNER JOIN tbl_lc_course ON tbl_ut_lecture.cid = tbl_lc_course.cid
                    INNER JOIN tbl_lc_lecture ON tbl_ut_lecture.lno = tbl_lc_lecture.lno';
                    foreach ($obj->select_w_join_2($data2, $table2, $where2) as $data_reco) { ?>
                    <li>
                        <a href="course_detail.php?cid=<?= $data_reco['cid'] ?>">
                            <samp class="img"><img src="img/other/<?= $data_reco['course_img']; ?>" alt=""></samp>
                            <span class="ttl"><?= $data_reco['title']; ?></span>
                            <span class="name"><?php foreach ($obj->select_data_where('tbl_ut_user', 'WHERE uid = '.$data_reco['uid'], '') as $name_rec) {
                                echo $name_rec['name1'] . ' ' . $name_rec['name2'];
                            }?></span>
                            <span class="price">受講料<span><?= number_format($data_reco['c_price']); ?></span>円</span>
                            <span class="lecture">1レクチャー <span><?= number_format($data_reco['l_price']); ?></span>円～</span>
                        </a>
                    </li>
                    <?php   }
                    ?>
                </ul>
            </section>

        </div><!-- /#mainContents -->

        <?php include 'sideNav.php';?>

    </div><!-- /.inner -->

</div><!-- /#contents -->

<?php include 'footer.php'; ?>