<?php include 'header.php'; 
redir();
?>
<div id="contents" class="clearfix">
    <div class="inner">

        <div id="mainContents" class="clearfix">
            <section>
                <h2 class="pageTitle">新しいパスワードを入力してください。</h2>
                <form action="" method="post">
                    <div class="tblA">
                        <dl>
                            <dt></dt>
                            <dd>
                                <?php
                                if ($_POST['submit_cpassword']) {
                                    $oldpass = $_POST['oldpass'];
                                    $newpass = $_POST['newpass'];
                                    $valid_pass = $obj->singleData($_COOKIE['lc_login_id'], "email", "tbl_ut_pass");
                                    extract($valid_pass);
                                    if (password_verify($oldpass, $pwd)) {
                                        $password_hash = password_hash($newpass, PASSWORD_BCRYPT, array('cost' => 10));
                                        $obj->pwd = $password_hash;
                                        $obj->updatePassword($uid);
                                        echo "<script>window.location.replace('profile_edit.php');</script>";
                                        header('location: profile_edit.php');
                                    } else {
                                        echo "invalid password";
                                    }
                                    //var_dump($valid_pass);
                                }
                                ?>
                            </dd>
                            <dt>今までお使いのパスワード</dt>
                            <dd>
                                <input type="text" name="oldpass"  class="size1" required>
                            </dd>
                            <dt>新しいパスワード</dt>
                            <dd>
                                <input type="password" name="newpass" onchange="form.cnewpass.pattern = this.value;" class="size1" required>
                            </dd>
                            <dt>新しいパスワード（確認用)</dt>
                            <dd>
                                <input type="password" name="cnewpass" class="size1" required>
                            </dd>
                            <dd>
                                <p class="btn btn_red">
                                    <input type="submit" name="submit_cpassword" value="変更する">
                                </p>
                            </dd>
                        </dl>
                    </div>

                </form>
            </section>
        </div><!-- /#mainContents -->

        <?php include 'sideNav.php'; ?>

    </div><!-- /.inner -->

</div><!-- /#contents -->

<?php include 'footer.php'; ?>