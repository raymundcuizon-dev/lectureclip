<?php
if(!isset($_GET['cid'])){ header('location: index.php'); }
else {
    include 'header.php';
    foreach ($obj->select_data_where('tbl_lc_course', 'WHERE cid = '.$_GET['cid'], '') as $c_edit) {
        if($uid != $c_edit['uid']) {
            echo "<p class='error_mess'>You don't have permission to access data</p>";
        } else {
            ?>
            <div id="contents" class="clearfix column1">
                <div class="inner">
                    <section class="clWrapper">
                        <div class="clTtl table">
                            <h2 class="title cell">コース情報入力</h2>
                            <div class="cell align_r">
                                <dl class="lecture_num">
                                   <!-- <dt>レクチャー数</dt>
                                    <dd>7</dd> -->
                                </dl>
                            </div><!-- /[div.cell] -->
                        </div><!-- /[div.stTtl] -->
                        <div class="courseInfo">
                            <?php 
                            if($_POST['btn02']){
                              $c_edit_name = $_POST['c_edit_name'];
                              $profile_form = $_POST['profile'];
                              $intro_data_form = $_POST['intro_data'];
                              $environment_form = $_POST['environment'];
                              $preparation_form = $_POST['preparation'];
                              $category_form = $_POST['category'];
                              $UserMail_form = $_POST['UserMail'];
                              $visibility_form = $_POST['visibility'];
                              $price_form = $_POST['price'];
                              $faq_form = $_POST['faq'];
                              $keyword_form = $_POST['keyword'];

                              $newfilename = $_FILES['image']['name'];
                              $ext = pathinfo($newfilename, PATHINFO_EXTENSION);
                              $c_image = md5(rand(1, 9999999999)) . '.' . $ext;

                              $c_edit_name_error = $form->walang_laman($c_edit_name, "Course Name");
                              $profile_error = $form->min_max_lenght($profile_form, "Teacher profile", 2, 1024);
                              $intro_data_error = $form->min_max_lenght($intro_data_form, "intro_data", 2, 1024);
                              $environment_error = $form->min_max_lenght($environment_form, "environment", 2, 1024);
                              $preparation_error = $form->min_max_lenght($preparation_form, "preparation", 2, 1024);
                              $category_error = $form->walang_laman($category_form, "Category");
                              $email_error = $form->email_validation($UserMail_form);
                              $visibility_error = $form->walang_laman($visibility_form, "Visibility");
                              $price_error = $form->numeric($price_form, "Price", 2, 8);
                              $keyword_error = $form->min_max_lenght($keyword_form, "Keyword", 2, 128);
                              $faq_error = $form->walang_laman($faq_form, "Acceptance of question");

                              $error = $c_edit_name_error.$profile_error.$intro_data_error.$environment_error.$preparation_error.$category_error.$email_error.
                              $visibility_error.$price_error.$keyword_error.$faq_error;

                              echo $error;

                              if ($error == null) {
                               if(!isset($_FILES['image']['name'])){
                                 $data = array(
                                    'title' => $c_edit_name, 
                                    'profile' => $profile_form, 
                                    'keyword' => $keyword_form, 
                                    'intro_data' => $intro_data_form, 
                                    'environment' => $environment_form,
                                    'preparation' => $preparation_form, 
                                    'catid' => $category_form, 
                                    'q_mail' => $UserMail_form, 
                                    'price' => $price_form,
                                    'question_flg' => $faq_form,
                                    'publish_flg' => $visibility_form);
                                 $obj->update("tbl_lc_course", $data, "WHERE cid = ".$_GET['cid']);
                                 echo "<script>window.location.replace('create_course.php');</script>";
                                //echo "<p class='success'>Your course is successfully updated <a href='mypage.php'> Click here to go to mypage </a> </p>";
                             } else {
                                $files = $obj->resize(224, 126,$c_image, "img/other/", "img/");
                                
                                $data = array(
                                    'title' => $c_edit_name,
                                    'course_img' => $c_image,
                                    'profile' => $profile_form, 
                                    'keyword' => $keyword_form, 
                                    'intro_data' => $intro_data_form, 
                                    'environment' => $environment_form,
                                    'preparation' => $preparation_form, 
                                    'catid' => $category_form, 
                                    'q_mail' => $UserMail_form, 
                                    'price' => $price_form,
                                    'question_flg' => $faq_form,
                                    'publish_flg' => $visibility_form);
                                $obj->update("tbl_lc_course", $data, "WHERE cid = ".$_GET['cid']);
                                unlink('img/other/'.$_POST['old_pic']);
                                chmod("img/other/".$c_image, 0777);
                                echo "<script>window.location.replace('create_course.php');</script>";
                                //echo "<p class='success'>Your course is successfully updated <a href='mypage.php'> Click here to go to mypage </a> </p>"; 
                            }
                        } else {
                            echo "<p class='error_mess'>Error!</p>";
                        } 
                    }

                    ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="tblA">
                            <dl>
                                <dt>コース名</dt>
                                <dd><input type='text' name='c_edit_name' value='<?=(isset($c_edit_name))? $c_edit_name : $c_edit["title"]?>'></dd>
                                <?= (isset($c_edit_name_error)) ? $c_edit_name_error : '' ?>
                            </dl>
                            <dl>
                                <dt>イメージ画像</dt>
                                <dd class="table">

                                    <div class="img cell">
                                        <input type="file" name="image" accept="image/*" style="float:left;">
                                        <input type="hidden" name="old_pic" value="<?=$c_edit["course_img"]?>"/>
                                        <img src="img/other/<?=$c_edit["course_img"]?>" width="224" height="126" alt="" id="courseImageArea">
                                    </div><!-- /[div.img] -->
                                </dd>
                            </dl>
                            <dl>
                                <dt>講師プロフィール</dt>
                                <dd>
                                    <textarea cols="100" name="profile" rows="10"><?=(isset($profile_form))? $profile_form : $c_edit["profile"]?></textarea>
                                    <?=(isset($profile_error))? $profile_error :'' ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>紹介文</dt>
                                <dd>
                                    <p class="sample btn btnBlack"><a href="#">サンプル文</a></p>
                                    <textarea   cols="100" name="intro_data" rows="10"><?=(isset($intro_data_error))? $intro_data_form :$c_edit['intro_data']?></textarea>
                                    <?=(isset($intro_data_error))? $intro_data_error: '' ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>おすすめの環境</dt>
                                <dd>
                                    <textarea   cols="100" name="environment" rows="10"><?=(isset($environment_form))? $environment_form :$c_edit['environment']?></textarea>
                                    <?=(isset($environment_error))?  $environment_error : ''?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>準備するもの</dt>
                                <dd>
                                    <textarea cols="100" name="preparation" rows="10"><?=(isset($preparation_form))? $preparation_form : $c_edit['preparation']?></textarea>
                                    <?=(isset($preparation_error))? $preparation_error: ''?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>カテゴリー</dt>
                                <dd>
                                    <ul class="btns_select clearfix">
                                        <?php
                                        foreach ($obj->readall("tbl_m_category", "order by catname ASC") as $categorylist):{ ?>
                                        <li><input type="radio" id="<?=$categorylist['catname']?>" required <?php echo ($c_edit['catid'] == $categorylist['catid']) ? 'checked' : ''?> name="category" value="<?=$categorylist['catid']?>"><label for="<?=$categorylist['catname']?>"><?=$categorylist['catname']?></label></li>
                                        <?php  } 
                                        endforeach;
                                        ?>
                                       <!-- <li><input type="radio" name="category" id="it" value="it" checked=""><label for="it">テクノロジー・IT</label></li>
                                        <li><input type="radio" name="category" id="business" value="business"><label for="business">ビジネススキル</label></li>
                                        <li><input type="radio" name="category" id="company" value="company"><label for="company">企業・経営</label></li>
                                        <li><input type="radio" name="category" id="Economy" value="Economy"><label for="Economy">政治・経済</label></li>
                                        <li><input type="radio" name="category" id="design" value="design"><label for="design">デザイン・CG</label></li>
                                        <li><input type="radio" name="category" id="language" value="language"><label for="language">教養・教育・語学</label></li>
                                        <li><input type="radio" name="category" id="sport" value="sport"><label for="sport">健康・スポーツ</label></li>
                                        <li><input type="radio" name="category" id="hobby" value="hobby"><label for="hobby">趣味</label></li> -->
                                    </ul>
                                </dd>
                            </dl>
                            <dl>
                                <dt>キーワード</dt>
                                <dd>
                                    <input type="text" name="keyword" class="size1" value="<?=(isset($keyword_form))? $keyword_form : $c_edit['keyword']?>">
                                    <?=(isset($keyword_error))? $keyword_error: ''?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>質問の受付</dt>
                                <dd>
                                    <ul class="generalRadio">
                                        <?php 
                                        if($c_edit['question_flg'] == 1){
                                            echo '<li><input type="radio" name="faq" id="yes"value="1" checked ><label for="yes">有</label></li>';
                                            echo '<li><input type="radio" name="faq" id="no" value="2"><label for="no">有</label></li>';
                                        } else { 
                                            echo '<li><input type="radio" name="faq" id="yes"value="1"><label for="yes">有</label></li>';
                                            echo '<li><input type="radio" name="faq" id="no" value="2" checked><label for="no">無</label></li>';
                                        }
                                        ?>
                                        
                                    </ul>
                                </dd>
                            </dl>
                            <dl>
                                <dt>質問受付用メールアドレス</dt>
                                <dd>
                                    <input type="text" name="UserMail" required class="size1" value="<?= (isset($UserMail_form)) ? $UserMail_form : $c_edit['q_mail'] ?>">
                                    <?= (isset($email_error)) ? $email_error : '' ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>公開設定</dt>
                                <dd>
                                    <ul class="generalRadio">


                                     <?php 
                                     if($c_edit['publish_flg'] == 1){
                                        echo '<li><input type="radio" name="visibility" id="publication"value="1" checked ><label for="publication">公開</label></li>';
                                        echo '<li><input type="radio" name="visibility" id="private" value="2"><label for="private">非公開</label></li>';
                                    } else { 
                                        echo '<li><input type="radio" name="visibility" id="publication"value="1"><label for="publication">公開</label></li>';
                                        echo '<li><input type="radio" name="visibility" id="private" value="2" checked><label for="private">非公開</label></li>';
                                    }
                                    ?>

                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>コース受講料</dt>
                            <dd class="price">
                                <input type="text" name="price" required value="<?= (isset($price_form)) ? $price_form : $c_edit['price'] ?>" class="size1">円
                                <?= (isset($price_error)) ? $price_error : '' ?>
                            </dd>
                        </dl>
                    </div>
                    <div class="clearfix">
                        <p class="float_l btn btn_black"><a href="cl_lecture_list.html"><input type="button" name="btn01" class="w370 fs18 h45" value="作成済のレクチャー一覧"></a></p>
                        <p class="float_r btn btn_red"><input type="submit" name="btn02"  class="w370 fs18 h45" value="UPDATE"></p>
                    </div>
                </form>
            </div><!-- /[div.courseInfo] -->
        </section>
    </div><!-- /.inner -->
</div><!-- /#contents -->
<?php } }include 'footer.php'; }?>