<?php
include 'header.php';
redir();
$catdetails = $obj->singleData($_GET['cid'], 'cid', 'tbl_lc_course', "");
extract($catdetails);

// $course_det = $obj->singleData($lecdetails['cid'], 'cid', 'tbl_lc_course', '');
// extract($course_det);
$_SESSION['add_cart'] == NULL;

?>	
<style>
.b_yellow{
    display: inline-block;
    height: 40px;
    line-height: 36px;
    background: #eebb49;
    text-align: center;
    font-weight: bold;	
    width: 340px;
    border: 0;
}
.b_black{
    display: inline-block;
    height: 40px;
    line-height: 36px;
    color: #FFF;
    background: #000;
    text-align: center;
    font-weight: bold;
    border: 0;
    width: 340px;
}
</style>
<div id="contents" class="clearfix column1">
    <div class="inner">
        <section class="stWrapper">
            <div class="stTtl table">
                <h2 class="title cell"><?= $title ?></h2>
                <div class="cell">
                    <dl class="lecture_num clearfix">
                        <dt>レクチャー数<span><?= $obj->count_lecture('tbl_lc_lecture', 'where cid = ' . $cid); ?></span></dt>
                        <dd class="items">
                            <p>未購入<span><?= $obj->count_lecture('tbl_lc_lecture', 'where cid = ' . $cid) - $obj->count_free('tbl_lc_lecture', 'where cid = ' . $cid . ' AND price = 0 '); ?></span></p>
                            <p>購入済<span>0</span></p>
                            <p>Free<span><?= $obj->count_free('tbl_lc_lecture', 'where cid = ' . $cid . ' AND price = 0 '); ?></span></p>
                        </dd>
                    </dl>
                </div><!-- /[div.cell] -->
            </div><!-- /[div.stTtl] -->
            <?php
            $lec_no = $_GET['lno'];
            $lecdetails = $obj->singleData($_GET['lno'], 'lno', 'tbl_lc_lecture', "");
            extract($lecdetails);
            $current_lno = $lno;
            if (isset($_POST['add_cart'])) {
                if (!isset($_POST['form_key']) || !$obj->validate()) {
                    $error = "<p class='error_mess'>Invalid submission!</p>";
                } else {
                    $bb = $obj->count_lecture('tbl_ut_lecture', 'WHERE lno = '.$lecdetails['lno'].' AND uid = '.$img_user['uid'].' AND con = "S"');
                    if($bb >= 1){
                        $error = "<p class='error_mess'>You already purchased this course.</p>";
                    } else {
                        //$sd = $obj->count_lecture('tbl_otwk_cart', ''); 
                        if($img_user['uid'] == $catdetails['uid']){
                            $error = "<p class='error_mess'>You can't buy this course.</p>";
                        } else {
                            if ($_SESSION['add_cart'] == NULL){
                                $seq = time().rand(1, 9999);
                                $_SESSION['add_cart'] = $seq;
                                $_SESSION['complete'] = $seq;
                                $data = array('cno' => $_SESSION['add_cart'], 'tip' => $uid, 'p_target' => 'L', 'lno' => $lno);
                                $obj->insert('tbl_otwk_cart', $data);
                                $success_add_cart = "<p class='success'>successfully added product to the cart</p>";
                            }else {
                                $b = $obj->count_lecture('tbl_otwk_cart', 'WHERE lno = '.$lno.' AND cno = '.$_SESSION['add_cart']);
                                if($b >= 1){
                                 $error = "<p class='error_mess'>This lecture is already in cart.</p>";
                             } else
                             {
                                $a = $obj->count_lecture('tbl_otwk_cart', 'WHERE cid = '.$cid.' AND cno = '.$_SESSION['add_cart']);
                                if($a >= 1){ 
                                    $error = "<p class='error_mess'>This Lecture is included into {$title} </p>";
                                } else {
                                   $bb = $obj->count_lecture('tbl_ut_lecture', 'WHERE lno = '.$lecdetails['lno'].' AND uid = '.$img_user['uid'].' AND con = "S"');
                                   if($bb >= 1){
                                    $error = "<p class='error_mess'>You already purchased this course.</p>";
                                } else {
                                    $data = array('cno' => $_SESSION['add_cart'], 'tip' => $uid, 'p_target' => 'L', 'lno' => $lno);
                                    $obj->insert('tbl_otwk_cart', $data);
                                    $success_add_cart = "<p class='success'>successfully added product to the cart</p>";
                                }
                            }
                        }
                    }
                }
            }     
        }          
    }
//WISHLIST
    if(isset($_POST['wishlist'])){
        $get_course = $obj->count_lecture('tbl_ut_lecture', 'WHERE cid = '.$cid.' AND lno = '.$lno.' AND uid = '.$img_user['uid'].' AND con = "R"');        
        if ($get_course >= 1){ 
            $error = "<p class='error_mess'>Your Selected Lecture is already added to Wishlist.</p>";
        }
        else{
// ADD TO WISHLIST       
            if($img_user['uid'] == $catdetails['uid']){
                $error = "<p class='error_mess'>You can't buy this course.</p>";
            } else {
                $arr_wl = array('cid' => $cid, 'lno' => $lno, 'uid' => $img_user['uid'], 'con' => 'R');
                $obj->insert('tbl_ut_lecture', $arr_wl);
                $success_add_cart =  "<p class='success'>Your Selected Lecture is added to Wishlist.</p>";                            
            }

        }        
    }
    ?>
    <?= ($error) ? $error : ''; ?>
    <?= ($success_add_cart) ? $success_add_cart : ''; ?>
    <div class="table">

        <div class="cell stLeft">
            <p class="ttl"><?= $lname ?></p>
            <div class="box">
                <p class="txt"><?= $intro_data ?></p>
                <dl class="price">
                    <dt>受講料</dt>
                    <dd><span><?= number_format($price); ?></span>円</dd>
                </dl>
                <div class="btnBox clearfix">
                    <form action="" method="post">
                        <?php $obj->outputKey(); ?>
                        <input type="submit" class="b_yellow" name="add_cart" value="カートに入れる"> 
                        <input type="submit" class="b_black" name="wishlist" value="検討中リストに入れる"> 
                    </form>
                </div><!-- /[div.table] -->
            </div><!-- /[div.box] -->
        </div><!-- /[div.cell] -->
        <?php unset($lecdetails); ?>
        <div class="cell stList">
            <?php 
//FOR PAGINATION
            $page = isset($_GET['page']) ? $_GET['page'] : 1;                    
            $records_per_page = 7;
            $from_record_num = ($records_per_page * $page) - $records_per_page;
            $page_dom = "st_mashup.php?lno=" . $_GET['lno'] . "&&";
            $total_rows = $obj->count_lecture('tbl_lc_lecture', 'where cid = ' . $cid); ;
            $range = $obj->count_lecture('tbl_lc_lecture', 'where cid = ' . $cid); ;
            $obj->paging($page_dom, $records_per_page, $total_rows, $range);

            $table_1 = array('tbl_lc_lecture', 'tbl_lc_course');
            $column_name_1 = array('tbl_lc_lecture.ltype', 'tbl_lc_lecture.lname', 'tbl_lc_lecture.prg_time', 'tbl_lc_lecture.price', 'tbl_lc_lecture.free', 'tbl_lc_lecture.intro_data', 'tbl_lc_lecture.lno');
            $table_column_1 = array('tbl_lc_course.cid', 'tbl_lc_lecture.cid');
            foreach ($obj->select_w_join($table_1, $table_column_1, ' INNER JOIN ', 'tbl_lc_lecture.cid', $cid, " ", $column_name_1, "  LIMIT {$from_record_num} , {$records_per_page}") as $loop_1) :
                extract($loop_1);                   
            ?>
            <div class="list">
                <?php 
//IF LECTURE FREE OR PAID
                $paid = $obj->count_lecture('tbl_ut_lecture', 'WHERE cid = '.$cid.' AND lno = '.$lno.' AND uid = '.$img_user['uid'].' AND (con = "S" OR con = "P")');  
                if($price == 0 OR ($price != 0 AND $paid)){
//DETERMINE WHAT LECTURE TYPE TO OPEN SPECIFIC PAGE
                    if($ltype == 'mu'){                                
                        $pages = "st_mashup.php";
                    } elseif ($ltype == 'pdf') {                                
                        $pages = "st_pdf.php";
                    }elseif ($ltype == 'm') {                                 
                        $pages = "st_movie.php";
                    } elseif ($ltype == 'p') {                                
                        $pages = "st_ppt.php";
                    }
                    elseif ($ltype == 'v') {                                
                        $pages = "st_music.php";
                    } 
                }
                else{
                    $pages = "st_non_purchased.php";
                }

//DETERMINES THE CURRENT PAGE
                if($current_lno == $lno){
                    echo '<a href="'.$pages.'?lno='.$lno.'&&cid='.$cid.'" class="active">'; 
                }
                else{
                    echo '<a href="'.$pages.'?lno='.$lno.'&&cid='.$cid.'">';
                }                               
                ?>

                <ul>
                    <li class="table active">
                        <div class="cell list_inner">                                       
                            <p class="listTtl"><?=$lname?></p>
                            <ul>
                                <li class="icon">

                                    <?php 
            //ICON
                                    if($ltype == 'mu'){
                                        echo '<span class="fa fa-film fa-3x"></span>';    
                                        $type = "動画";                                                    
                                    } elseif ($ltype == 'pdf') {
                echo '<span class="fa fa-file-pdf-o fa-3x"></span>'; # code...  
                $type = "PDF"; 
            } elseif ($ltype == 'p') {
                echo '<span class="fa fa-file-pdf-o fa-3x"></span>'; # code...  
                $type = "Power Point";                                                           
            }elseif ($ltype == 'm') {                                                       
                echo '<span class="fa fa-video-camera fa-3x"></span>'; # code...    
                $type = "動画";                                                                                                     
            } elseif ($ltype == 'v') {
                echo '<span class="fa fa-film fa-3x"></span>'; # code... 
                $type = "動画";                                                           
            }
            ?>
        </li>
        <?php 
        //FREE OR NOT                                              
        if($price == 0){
            echo '<li class="txt01">無料</li>';
        }elseif($price != 0 AND $paid){
            echo '<li class="txt01 yellow">購入済</li>';
        }else{
            echo '<li class="txt01 yellow">Not購入済</li>';
        }
        ?>
        <li class="txt02">形式
            <?php 
            //DESCRIPTION
            echo '<span>' . $type. '</span>';
            ?>
        </li>
        <?php 
        //PLAYTIME FOR MASHUP/MOVIE
        if($ltype == 'mu'){
            echo '<li class="txt03">時間<span>'.$prg_time.'</li>';
        } elseif ($ltype == 'pdf') {
            echo ''; # code...
        }elseif ($ltype == 'm') {
            echo '<li class="txt03">時間<span>'.$prg_time.'</li>';
        } elseif ($ltype == 'ppt') {
            echo '';
        }
        ?>

    </ul>                                        
</div>
</li>
</ul>
</a>                        
</div><!-- /[div.list] -->

<?php 
endforeach;                    
unset($loop_1);
?>

</div><!-- /[div.cell] -->
</div><!-- /[div.table] -->

<?php
//GET THE NEXT LECTURE
$arr_nextlec = $obj->nextData($lec_no, 'lno', $cid, 'cid', 'tbl_lc_lecture', 'ORDER BY lno limit 1');

//NOT LAST RECORD                    
if($arr_nextlec != false){
    extract($arr_nextlec);                     

//IF LECTURE FREE OR PAID
    if($price == 0 OR ($price != 0 AND $paid)){
//DETERMINE WHAT LECTURE TYPE TO OPEN SPECIFIC PAGE
        if($ltype == 'mu'){
            $next_page = "st_mashup.php";
        } elseif ($ltype == 'pdf') {
            $next_page = "st_pdf.php";
        }elseif ($ltype == 'm') { 
            $next_page = "st_movie.php";
        } elseif ($ltype == 'p') {
            $next_page = "st_ppt.php";
        } elseif ($ltype == 'v') {
            $next_page = "st_music.php"; 
        }

        echo "NEXT LECTURE: FREE";
    }
//NOT FREE
    else{
        $next_page = "st_non_purchased.php";
        echo "NEXT LECTURE: NOT FREE";
    }

    echo '<p class="btn btn_red"><a href="' .$next_page.'?lno='.$lno.'&&cid='.$cid.'" class="nextBtn">次のレクチャーへ進む</a></p>';
}
else{
//IF LAST RECORD
    echo "Last Record!";
}               
?>  

</section>
</div><!-- /.inner -->

</div><!-- /#contents -->
<?php 
unset($catdetails);
// unset($course_det);
include 'footer.php'; ?>