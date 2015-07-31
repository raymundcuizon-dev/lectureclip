<?php include 'header.php'; redir();
?>
<div id="contents" class="clearfix">
    <div class="inner">

        <div id="mainContents" class="clearfix">
            <section>
                <h2 class="pageTitle">プロフィール編集</h2>
                <?php
                if (isset($_POST['submit_edit'])) {
                    $errors = false;
                    $show_errors = array();
                    $UserMail = $_POST['UserMail'];
                    $age = $_POST['age'];
                    $sex = $_POST['sex'];
                    $name1 = $_POST['name1'];
                    $name2 = $_POST['name2'];
                    $date_update = date('Y-m-d');

                    $UserMailConfirm = $_POST['UserMailConfirm'];
                    foreach ($show_errors as $show) {
                        echo'<div id="display-error"> <img src="img/common/dialog_warning.png" alt="Error" />' . $show . '</div>';
                    }

                    if (empty($show_errors)) {
                        if (!empty($_FILES['image']['name'])) {
                            $tmpFile = $_FILES["image"]["tmp_name"];
                            $newfilename = $_FILES['image']['name'];
                            $ext = pathinfo($newfilename, PATHINFO_EXTENSION);
                            $c_image = md5(rand(1, 9999999999)) . '.' . $ext;
                            $obj->resize_upload($tmpFile, 'img/user/'.$c_image, 90,90);
                            $data = array('name1' => $name1, 'name2' => $name2, 'age' => $age, 'gender' => $sex, 'profile_img' => $c_image, 'edit_update' => $date_update);
                            $obj->update("tbl_ut_user", $data, "WHERE uid = $uid");
                            echo "<script>window.location.replace('profile_edit.php');</script>";
                        } else {
                            $data = array('name1' => $name1, 'name2' => $name2, 'age' => $age, 'gender' => $sex, 'edit_update' => $date_update);
                            $obj->update("tbl_ut_user", $data, "WHERE uid = $uid");
                            echo "<script>window.location.replace('profile_edit.php');</script>";
                        }
                    }
                }

                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="tblA">
                        <dl>
                            <dt>Last name</dt>
                            <!--<dd>栗富太郎</dd> -->
                            <dd>
                                <input type="text" name="name1" value="<?= (isset($name1)) ? $name1 : ''; ?>" placeholder="Lastname"  class="size1" required>
                            </dd>
                            <dt>First name</dt>
                            <!--<dd>栗富太郎</dd> -->
                            <dd>
                                <input type="text" name="name2" value="<?= (isset($name2)) ? $name2 : ''; ?>" placeholder="Firstname"  class="size1" required>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Email address</dt>
                            <dd>
                                <p class="password"><?= $_COOKIE['lc_login_id'] ?></p>
                                <div class="btnArea"><p class="btn_black"><a href="cemail.php">Change Email address</a></p></div>
                            </dd>
                        </dl>

                        <dl>
                            <dt>パスワード</dt>
                            <dd>
                                <p class="password">***********</p>
                                <div class="btnArea"><p class="btn_black"><a href="password.php">パスワード変更</a></p></div>
                            </dd>
                        </dl>
                        <dl>
                            <dt>年代</dt>
                            <dd>
                                <ul class="btns_select">
                                    <li><input type="radio" name="age" id="age10" value="10" <?php echo ($age == 10 ) ? "checked=''" : "" ?>><label for="age10">10代</label></li>
                                    <li><input type="radio" name="age" id="age20" value="20" <?php echo ($age == 20 ) ? "checked=''" : "" ?>><label for="age20">20代</label></li>
                                    <li><input type="radio" name="age" id="age30" value="30" <?php echo ($age == 30 ) ? "checked=''" : "" ?>><label for="age30">30代</label></li>
                                    <li><input type="radio" name="age" id="age40" value="40" <?php echo ($age == 40 ) ? "checked=''" : "" ?>><label for="age40">40代</label></li>
                                    <li><input type="radio" name="age" id="age50" value="50" <?php echo ($age == 50 ) ? "checked=''" : "" ?>><label for="age50">50代</label></li>
                                    <li><input type="radio" name="age" id="age60" value="60" <?php echo ($age == 60 ) ? "checked=''" : "" ?>><label for="age60">60代</label></li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>性別</dt>
                            <dd>
                                <ul class="btns_select">
                                    <li><input type="radio" name="sex" id="male" value="male" <?php echo ($gender == "male" ) ? "checked=''" : "" ?>><label for="male">男性</label></li>
                                    <li><input type="radio" name="sex" id="female" value="female" <?php echo ($gender == "female" ) ? "checked=''" : "" ?>><label for="female">女性</label></li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>プロフィール画像</dt>
                            <dd>
                                <div class="thum"><img src="img/common/thum_img.jpg" alt=""></div>
                                <div class="btnArea2">
                                    <p class="caution">※縦90px 横90px以上、○MB以下、JPEG、GIF、PNG形式</p>
                                    <p class="btn_black mt15">
                                        <input type="file" name="image" accept="image/*" />
                                        <!-- <a href="#">画像を変更</a> -->
                                    </p>
                                </div>
                            </dd>
                        </dl>
                        <dl>
                            <dt>メールによるお知らせ</dt>
                            <dd>
                                <ul class="btns_select">
                                    <li><input type="radio" name="infoMail" id="receive" value="receive" checked=""><label for="receive">受け取る</label></li>
                                    <li><input type="radio" name="infoMail" id="notReceive" value="notReceive"><label for="notReceive">受け取らない</label></li>
                                </ul>
                            </dd>
                        </dl>
                    </div>
                    <div class="btns">
                        <p class="btn_red"><input type="submit" name="submit_edit" class="w240 fs18 h45" value="更新する"></p>
                    </div>
                </form>
            </section>
        </div><!-- /#mainContents -->
        <?php include 'sideNav.php'; ?>
    </div><!-- /.inner -->
</div><!-- /#contents -->
<?php include 'footer.php'; ?>

