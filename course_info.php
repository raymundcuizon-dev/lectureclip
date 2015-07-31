<?php include 'header.php'; 
redir();
?>
<div id="contents" class="clearfix column1">
    <div class="inner">
        <section class="clWrapper">
            <div class="clTtl table">
                <h2 class="title cell">コース情報入力</h2>
                <div class="cell align_r">
                    <dl class="lecture_num">
                        <dt>レクチャー数</dt>
                        <dd>7</dd>
                    </dl>
                </div><!-- /[div.cell] -->
            </div><!-- /[div.stTtl] -->
            <div class="courseInfo">

                <?php
                if (isset($_POST['btn02_C_INFO'])) {
                    $profile_form = $_POST['profile'];
                    $recomendation_form = $_POST['recomendation'];
                    $prepared_form = $_POST['prepared'];
                    $category_form = $_POST['category'];
                    $UserMail_form = $_POST['UserMail'];
                    $visibility_form = $_POST['visibility'];
                    $price_form = $_POST['price'];
                    $testimonials_form = $_POST['testimonials'];
                    $keyword_form = $_POST['keyword'];
                    $faq_form = $_POST['faq'];
                    
                    $tmpFile = $_FILES["image"]["tmp_name"];
                    $newfilename = $_FILES['image']['name'];
                    $ext = pathinfo($newfilename, PATHINFO_EXTENSION);
                    $c_image = md5(rand(1, 9999999999)) . '.' . $ext;

                    $profile_error = $form->min_max_lenght($profile_form, "Teacher profile", 2, 1024);
                    $recomendation_error = $form->min_max_lenght($recomendation_form, "Recomendation", 2, 1024);
                    $prepared_error = $form->min_max_lenght($prepared_form, "Prepare", 2, 1024);
                    $category_error = $form->walang_laman($category_form, "Category");
                    $email_error = $form->email_validation($UserMail_form);
                    $visibility_error = $form->walang_laman($visibility_form, "Visibility");
                    $price_error = $form->numeric($price_form, "Price", 1, 8);
                    $testimonials_error = $form->min_max_lenght($testimonials_form, "Testimonials", 2, 1024);
                    $keyword_error = $form->min_max_lenght($keyword_form, "Keyword", 2, 128);
                    $faq_error = $form->walang_laman($faq_form, "Acceptance of question");
                    $error = $faq_error . $keyword_error . $profile_error . $recomendation_error . $prepared_error . $category_error . $email_error . $visibility_error . $price_error . $testimonials_error . $keyword_error;
                    
                    if ($error == null) {

                        $obj->resize_upload($tmpFile, 'img/other/'.$c_image, 224,126);
                        $form_data = array('course_img' => $c_image, 'profile' => $profile_form, 'environment' => $recomendation_form, 'preparation' => $prepared_form, 'catid' => $category_form, 'q_mail' => $UserMail_form, 'publish_flg' => $visibility_form, 'price' => $price_form, 'intro_data' => $testimonials_form, 'keyword' => $keyword_form, 'question_flg' => $faq_form);
                        //echo "CID: " . $_SESSION['cid'];
                        $where = "where cid = " . $_SESSION['cid'];
                        $obj->update('tbl_lc_course', $form_data, $where);
                        session_unset($_SESSION['cid']);
                        printf("<script>location.href='mypage.php'</script>");
                        //echo "<script>window.location.replace('mypage.php');</script>";
                        //echo "<p class='success'>Your course is created <a href='mypage.php'> Click here to go to mypage </a> </p>";
                    } else {
                        echo "<p class='error_mess'>Error!</p>";
                    }
                }
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="tblA">
                        <dl>
                            <dt>コース名</dt>
                            <dd>
                                <?php
                                //$CourseName_list = $obj->singleData(2, 'cid', 'tbl_lc_course');
                                $CourseName_list = $obj->singleData($_SESSION['cid'], 'cid', 'tbl_lc_course');
                                extract($CourseName_list);
                                echo $title;
                                unset($CourseName_list);
                                ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>イメージ画像</dt>
                            <dd class="table">
                                <div class="fileBox cell">                                    
                                    <input type="file" name="image" accept="image/*" required>
                                </div>
                            </dd>
                        </dl>
                        <dl>
                            <dt>講師プロフィール</dt>
                            <dd>
                                <textarea name="profile" cols="100" rows="10" required><?= (isset($profile_form) ? $profile_form : '') ?></textarea>
                                <?= (isset($profile_error)) ? $profile_error : '' ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>紹介文</dt>
                            <dd>
                                <textarea name="testimonials"  cols="100" rows="10" required><?= (isset($testimonials_form) ? $testimonials_form : '') ?></textarea>
                                <?= (isset($testimonials_error)) ? $testimonials_error : '' ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>おすすめの環境</dt>
                            <dd>
                                <textarea name="recomendation" cols="100" rows="10" required><?= (isset($recomendation_form) ? $recomendation_form : '') ?></textarea>
                                <?= (isset($recomendation_error)) ? $recomendation_error : '' ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>準備するもの</dt>
                            <dd>
                                <textarea name="prepared" cols="100" rows="10" required><?= (isset($prepared_form) ? $prepared_form : '') ?></textarea>
                                <?= (isset($prepared_error)) ? $prepared_error : '' ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>カテゴリー</dt>
                            <dd>
                                <ul class="btns_select clearfix">
                                    <?php
                                    foreach ($obj->readall("tbl_m_category", "order by catname ASC") as $categorylist):
                                        extract($categorylist);
                                    if (isset($category_form)) {
                                        echo '<li><input type="radio" required id='.$categorylist['catname'].' checked name="category" value='.$categorylist['catid'].' ><label for='.$categorylist['catname'].'>' .$categorylist['catname']. '</label></li>';
                                    } else {
                                        echo '<li><input type="radio" required id='.$categorylist['catname'].' name="category" value=' . $categorylist['catid'] . ' ><label for='.$categorylist['catname'].'>' . $categorylist['catname']. '</label></li>';
                                    }
                                    endforeach;
                                    unset($categorylist);
                                    ?>                                   
                                </ul>
                                <?= (isset($category_error)) ? $category_error : '' ?>
                            </dd>
                        </dl>

                        <dl>
                            <dt>キーワード</dt>
                            <dd>
                                <input type="text" name="keyword" required class="size1" value="<?= (isset($keyword_form)) ? $keyword_form : '' ?>">
                                <?= (isset($keyword_error)) ? $keyword_error : '' ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>質問の受付</dt>
                            <dd>
                                <ul class="generalRadio">
                                    <?php
                                    $faq_array = array(1 => '有', 2 => '無');
                                    foreach ($faq_array as $key => $value):
                                        if (isset($faq_form)) {
                                            echo '<li><input type="radio" required name="faq" checked id="yes" value=' . $key . '><label for="yes">' . $value . '</label></li>';
                                        } else {
                                            echo '<li><input type="radio" required name="faq" id="yes" value=' . $key . '><label for="yes">' . $value . '</label></li>';
                                        }
                                        endforeach;
                                        ?>
                                    </ul>
                                    <?= (isset($faq_error)) ? $faq_error : '' ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>質問受付用メールアドレス</dt>
                                <dd>
                                    <input type="text" name="UserMail" required class="size1" value="<?= (isset($UserMail_form)) ? $UserMail_form : '' ?>">
                                    <?= (isset($email_error)) ? $email_error : '' ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>公開設定</dt>
                                <dd>
                                    <ul class="generalRadio">
                                        <?php
                                        $visibility_array = array(1 => '公開', 2 => '非公開');
                                        foreach ($visibility_array as $key => $value):
                                            if (isset($visibility_form)) {
                                                echo '<li><input type="radio" name="visibility" checked required  id="publication" value=' . $key . '><label for="publication">' . $value . '</label></li>';
                                            } else {
                                                echo '<li><input type="radio" name="visibility" required  id="publication" value=' . $key . '><label for="publication">' . $value . '</label></li>';
                                            }
                                            endforeach;
                                            ?>
                                        </ul>
                                        <?= (isset($visibility_error)) ? $visibility_error : '' ?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>コース受講料</dt>
                                    <dd class="price">
                                        <input type="number" name="price" required value="<?= (isset($price_form)) ? $price_form : '' ?>" class="size1">円
                                        <?= (isset($price_error)) ? $price_error : '' ?>
                                    </dd>
                                </dl>
                            </div>
                            <div class="clearfix">
                                <p class="float_l btn btn_black"><a href="cl_lecture_list.html"><input type="button" name="btn01" class="w370 fs18 h45" value="作成済のレクチャー一覧"></a></p>
                                <p class="float_r btn btn_red"><input type="submit" name="btn02_C_INFO" class="w370 fs18 h45" value="作成"></p>
                            </div>
                        </form>
                    </div><!-- /[div.courseInfo] -->
                </section>

            </div><!-- /.inner -->

        </div><!-- /#contents -->
        <?php include 'footer.php'; ?>