<?php include 'header.php'; ?>
<div id="contents" class="clearfix">
    <div class="inner">

        <div id="mainContents" class="clearfix">
            <section>
                <h2 class="pageTitle">新しいパスワードを入力してください。</h2>
                <form action="" method="post">
                    <div class="tblA">
                        <?php
                        $reset = $obj->singleData($_GET['d'], 'gen_code', 'tbl_forgot_pass_log');
                        extract($reset);
                        if ($status == 0) {
                            if (isset($_POST['reset'])) {
                                if ($_POST['newpass'] == $_POST['c_password']) {
                                    $data = array('status' => 1);
                                    $w = " WHERE gen_code = '" . $_GET['d'] . "'";
                                    $obj->update("tbl_forgot_pass_log", $data, $w);

                                    $password_hash = password_hash($_POST['newpass'], PASSWORD_BCRYPT, array('cost' => 10));
                                    $reset_pass = array('pwd' => $password_hash);
                                    $r = " WHERE email = '$email'";
                                    $obj->update("tbl_ut_pass", $reset_pass, $r);
                                    echo "you have successfully changed your password";
                                } else {
                                    echo "password did not match!";
                                }
                            }
                            echo '<form method="pos">'
                            . ' <dl>
                            <dt>パスワード</dt>
                            <dd>
                                <input type="password" name="newpass" onchange="form.cnewpass.pattern = this.value;" class="size1" required>
                            </dd>
                            <dt>確認用パスワード</dt>
                            <dd>
                                <input type="password" name="c_password" class="size1" required>
                            </dd>
                            <dd>
                                <p class="btn btn_red">
                                    <input type="submit" name="reset" value="登録する">
                                </p>
                            </dd>
                        </dl></form>';
                        } else {
                            echo "Invalid URL";
                        }
                        ?>
                        <!-- -->
                    </div>

                </form>
            </section>
        </div><!-- /#mainContents -->

        <?php include 'sideNav.php'; ?>

    </div><!-- /.inner -->

</div><!-- /#contents -->

<?php include 'footer.php'; ?>