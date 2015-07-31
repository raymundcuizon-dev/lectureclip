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
                $where = 'WHERE tbl_lc_course.publish_flg = 1 AND tbl_lc_lecture.price = 0 GROUP BY tbl_m_category.catid ORDER BY MAX(tbl_lc_lecture.price), MAX(tbl_lc_lecture.regdate) DESC LIMIT ' .$limit. ' OFFSET ' .$page;
                $table = 'tbl_m_category 
		                INNER JOIN tbl_lc_course on tbl_m_category.catid = tbl_lc_course.catid  
		                INNER JOIN tbl_lc_lecture on tbl_lc_course.cid = tbl_lc_lecture.cid';
                $data = array('tbl_m_category.catid, tbl_m_category.catname');
                foreach ($obj->select_w_join_2($data, $table, $where) as $cat_info){
                    extract($cat_info);                    
                ?> 
                <section class="unit">   
                <h2 class="title"><?php echo $catname; ?></h2>  
                <ul class="lectList clearfix">
                <?php
                    //GET NUMBER OF RECORDS FOR PAGING
                    //$course_rows = $obj->count_lecture('tbl_lc_course', 'WHERE catid = ' .$catid);

                    //GET COURSE FOR EACH CATEGORY 
                    $where = 'WHERE tbl_m_category.catid =' .$catid. ' AND tbl_lc_course.publish_flg = 1 AND tbl_lc_lecture.price = 0 GROUP BY tbl_lc_course.cid ORDER BY MAX(tbl_lc_lecture.price), MAX(tbl_lc_lecture.regdate) DESC LIMIT 4';
                    $table = 'tbl_lc_course 
                            INNER JOIN tbl_ut_user on tbl_lc_course.uid = tbl_ut_user.uid 
                            INNER JOIN tbl_lc_lecture on tbl_lc_course.cid = tbl_lc_lecture.cid
                            INNER JOIN tbl_m_category on tbl_lc_course.catid = tbl_m_category.catid';
                    $data = array('tbl_lc_course.cid',
                            'tbl_lc_course.title',
                            'tbl_lc_course.course_img',
                            'tbl_lc_course.price as c_price',
                            'tbl_ut_user.name1',                          
                            'MAX(tbl_lc_lecture.price) as l_price');    
                    $course_rows = 0;                         
                    foreach ($obj->select_w_join_2($data, $table, $where) as $course_info){
                        extract($course_info); 
                        $course_rows++;
                        if($course_rows <= 4){ //SHOW COURSES IF COUNT IS NOT MORE THAN 4
                ?>
					<li>
						<a href="course_detail.php?cid=<?= $course_info['cid'] ?>">
                            <samp class="img"><img src="img/other/<?= $course_info['course_img']; ?>" alt=""></samp>
                            <span class="ttl"><?= $course_info['title']; ?></span>
                            <span class="name"><?= $course_info['name1']; ?></span>
                            <span class="price">受講料<span><?= number_format($course_info['c_price']); ?></span>円</span>
                            <span class="lecture">1レクチャー <span><?= number_format($course_info['l_price']); ?></span>円</span>
						</a>
					</li>
					<?php 
                        }//IF CLOSING
					} 
					unset($course_info); 
					?>										
				</ul>				
				<?php
	                //SHOW BTN MORE IF COURSES ARE MORE THAN 3	                
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
                    echo '<li><a href="new.php?page=' . $prev_page . '"><i class="fa fa-angle-left"></i></a></li>';
                }
                //PAGES
                for($i = 1; $i < $total_page; $i++){  
                    if($curr_page == $i){
                        echo '<li><a class="active" href="new.php?page=' . $i . '">' . $i . '</a></li>';
                    }else{
                        echo '<li><a href="new.php?page=' . $i . '">' . $i . '</a></li>'; 
                    } 
                }
                $next_page = $curr_page + 1;
                //FOR NEXT PAGE
                if($next_page < $total_page){
                    echo '<li><a href="new.php?page=' . $next_page . '"><i class="fa fa-angle-right"></i></a></li>';
                }
                
                echo '</ul>';
            echo "</div>"; //END DIV PAGER
            ?>
			
		</div><!-- /.inner -->
		
	</div><!-- /#contents -->
	
<?php include 'footer.php'; //redir();?>