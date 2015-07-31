<?php 
include 'header.php'; 
//redir();
?>
<!-- <div id='contents'>
    <h1>No column or data to fetch</h1><br>
</div> -->

<?php $category_count = $obj->count_lecture('tbl_lc_course', 'WHERE catid = '.$_GET['catid']); 

if(empty($category_count)){ 
    echo "<div id='contents'>
    <h1>No column or data to fetch</h1><br>
    </div>";
} else {


    ?>

    <div id="contents" class="clearfix column1">
        <div class="inner">
            <section class="unit">
                <h2 class="title">
                    <?php
                    $cattitle = $obj->singleData($_GET['catid'], 'catid', 'tbl_m_category', "");
                    extract($cattitle);
                    echo $catname;
                    unset($cattitle);
                    ?>
                </h2>
                <ul class="lectList clearfix">
                    <?php
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $records_per_page = 16;                
                    $from_record_num = ($records_per_page * $page) - $records_per_page;                
                    foreach ($obj->category($_REQUEST['catid'], $page, $from_record_num, $records_per_page) as $value) {
                        extract($value);
                        //var_dump($value);
                        ?>
                        <li>
                            <a href="course_detail.php?cid=<?= $value['cid'] ?>">
                                <samp class="img"><img src="img/other/<?= $value['course_img']; ?>" alt=""></samp>
                                <span class="ttl"><?= $value['title']; ?></span>
                                <span class="name"><?= $value['name1']; ?></span>
                                <span class="price">受講料<span><?= number_format($value['c_price']); ?></span>円</span>
                                <span class="lecture">1レクチャー <span><?= number_format($value['l_price']); ?></span>円</span>
                            </a>
                        </li>
                        <?php } unset($value); ?>
                    </ul>
                </section>
                <?php
                $page_dom = "category.php?catid=" . $_REQUEST['catid'] . "&&";
                echo "<div class='pager'><ul>";
                if ($page > 1) {
                    echo "<li><a href='{$page_dom}' title='Go to the first page.'><</li>";
                }
                $total_rows = $obj->count_lecture('tbl_lc_course', 'where catid = '.$_REQUEST['catid']);
                $total_pages = ceil($total_rows / $records_per_page);
                $range = $obj->count_lecture('tbl_lc_course', 'where catid = '.$_REQUEST['catid']);
                $initial_num = $page - $range;
                $condition_limit_num = ($page + $range) + 1;
                for ($x = $initial_num; $x < $condition_limit_num; $x++) {
                    if (($x > 0) && ($x <= $total_pages)) {
                        if ($x == $page) {
                            echo "<li><a href='#'>$x</a></li>";
                        } else {
                            echo "<li><a href='{$page_dom}page=$x' class='active'>$x</a></li>";
                        }
                    }
                }
                if ($page < $total_pages) {
                    echo "<li><a href='" . $page_dom . "page={$total_pages}'title='Last page is {$total_pages}.'>></a></li>";
                }
                echo "</ul></div>";
                ?>
            </div><!-- /.inner -->
        </div><!-- /#contents -->
        <?php } include 'footer.php'; ?>