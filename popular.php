<?php 
include 'header.php'; 
?>
            
    <div id="contents" class="clearfix column1">
        <div class="inner">
                <?php
                //COUNT ALL RECORDS FOR PAGING
                $category_rows = $obj->count_lecture('tbl_m_category', "");

                //FOR PAGING
                $limit = 4;
                $page = 0;
                if(isset($_GET['page'])) {
                    $page = $_GET['page'] - 1;
                    $page = ($page * $limit);
                }                    
                $total_rows = $category_rows;
                $total_page = $total_rows / $limit;
                $total_page = floor($total_page) + 1;                 

                //GET CATEGORIES
               $where = 'WHERE tbl_ut_lecture.cid NOT IN (SELECT cid FROM tbl_ut_lecture WHERE uid = ' .$uid. ') GROUP BY tbl_ut_lecture.cid ORDER BY tcnt DESC LIMIT ' .$limit. ' OFFSET ' .$page;
                $table = 'tbl_ut_lecture 
                    INNER JOIN tbl_top_chart ON tbl_ut_lecture.cid = tbl_top_chart.tcid
                    INNER JOIN tbl_lc_course ON tbl_ut_lecture.cid = tbl_lc_course.cid
                    INNER JOIN tbl_lc_lecture ON tbl_ut_lecture.lno = tbl_lc_lecture.lno
                    INNER JOIN tbl_m_category ON tbl_lc_course.catid = tbl_m_category.catid';
                $data = array('tbl_m_category.catid, tbl_m_category.catname');
                foreach ($obj->select_w_join_2($data, $table, $where) as $cat_info){                     
                    extract($cat_info); 
                ?> 
                <section class="unit">   
                <h2 class="title"><?php echo $catname; ?></h2>  
                <ul class="lectList clearfix">
                <?php
                    //GET COURSE FOR EACH CATEGORY 
                    $where = 'WHERE tbl_ut_lecture.cid NOT IN (SELECT cid FROM tbl_ut_lecture WHERE uid = ' .$uid. ') GROUP BY tbl_ut_lecture.cid ORDER BY tcnt DESC';
                    $table = 'tbl_ut_lecture 
                            INNER JOIN tbl_top_chart ON tbl_ut_lecture.cid = tbl_top_chart.tcid
                            INNER JOIN tbl_lc_course ON tbl_ut_lecture.cid = tbl_lc_course.cid
                            INNER JOIN tbl_lc_lecture ON tbl_ut_lecture.lno = tbl_lc_lecture.lno
                            INNER JOIN tbl_m_category ON tbl_lc_course.catid = tbl_m_category.catid';
                    $data = array('tbl_lc_course.cid', 'tbl_lc_course.course_img', 'tbl_lc_course.title', 'tbl_lc_course.uid','tbl_lc_course.price as c_price','MIN(tbl_lc_lecture.price) as l_price');  
                    $course_rows = 0;  //COURSE COUNT      

                    foreach ($obj->select_w_join_2($data, $table, $where) as $new_courses){                        
                        extract($new_courses);
                        $course_rows++;
                        if($course_rows <= 4){ //SHOW COURSES IF COUNT IS NOT MORE THAN 4
                ?>
                     <li>
                        <a href="course_detail.php?cid=<?= $new_courses['cid'] ?>">
                            <samp class="img"><img src="img/other/<?= $new_courses['course_img']; ?>" alt=""></samp>
                            <span class="ttl"><?= $new_courses['title']; ?></span>
                            <span class="name"><?= $new_courses['name1']; ?></span>
                            <span class="price">受講料<span><?= number_format($new_courses['c_price']); ?></span>円</span>
                            <span class="lecture">1レクチャー <span><?= number_format($new_courses['l_price']); ?></span>円</span>
                        </a>
                    </li>
                
                    <?php 
                        }//IF CLOSING
                    //FOREACH CLOSING
                    } 
                    unset($new_courses);                 
                    ?> 
                </ul>                 
                <?php
                    //SHOW BTN MORE IF COURSES ARE MORE THAN 4                    
                    if($course_rows > 4){
                        echo '<p class="btn_more"><a href="category.php?catid='.$catid.'">もっと見る</a></p>';            
                    }
                ?>
                </section>   
                <?php
                //FOREACH CLOSING                
                }
                unset($cat_info); 
                ?>  

            <?php
            //PAGINATION
            echo '<div class="pager">';
                echo '<ul>';                
                $curr_page = $page + 1;               
                $prev_page = $curr_page - 1;
                //FOR PREVIOUS PAGE
                if($prev_page != 0){
                    echo '<li><a href="popular.php?page=' . $prev_page . '"><i class="fa fa-angle-left"></i></a></li>';
                }
                //PAGES
                for($i = 1; $i < $total_page; $i++){  
                    if($curr_page == $i){
                        echo '<li><a class="active" href="popular.php?page=' . $i . '">' . $i . '</a></li>';
                    }else{
                        echo '<li><a href="popular.php?page=' . $i . '">' . $i . '</a></li>'; 
                    } 
                }
                $next_page = $curr_page + 1;
                //FOR NEXT PAGE
                if($next_page < $total_page){
                    echo '<li><a href="popular.php?page=' . $next_page . '"><i class="fa fa-angle-right"></i></a></li>';
                }
                
                echo '</ul>';
            echo "</div>"; //END DIV PAGER
            ?>
            
        </div><!-- /.inner -->
        
    </div><!-- /#contents -->
    
<?php include 'footer.php'; //redir();?>