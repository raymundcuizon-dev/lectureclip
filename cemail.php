<?php
include 'header.php';
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
                                if (isset($_POST['submit_cemail'])) {
                                    $new_email_form = $_POST['new_email'];
                                    $cnewemail_form = $_POST['cnewemail'];
                                    $password_form = $_POST['password'];
                                    $valid_pass = $obj->singleData($_COOKIE['lc_login_id'], "email", "tbl_ut_pass");
                                    extract($valid_pass);
                                    if (password_verify($password_form, $pwd)) {
                                        if ($new_email_form != $cnewemail_form && empty($new_email_form) && empty($cnewemail_form)) {
                                            echo'<div style="color: red"> Confirmation email did not match! </div>';
                                        } else {
                                            $cemail_data = array('new_email' => $cnewemail_form, 'old_email' => $_COOKIE['lc_login_id']);
                                            $obj->insert("tbl_change_email_log", $cemail_data);
                                            $update_email_data = array('email' => $new_email_form);
                                            $where = "where passid =  $passid";
                                            $obj->update("tbl_ut_pass", $update_email_data, $where);
                                            // header("Refresh: 5; url=profile_edit.php");
                                            echo 'you have successfully changed your email';
                                        }
                                    } else {
                                        echo '<div style="color: red">invalid password </div>';
                                    }
                                }
                                ?>
                            </dd>
                            <dt>New email</dt>
                            <dd>
                                <input type="email" name="new_email" value="<?= ($new_email_form) ? $new_email_form : '' ?>" onchange="form.cnewemail.pattern = this.value;" class="size1">
                            </dd>
                            <dt>Confirm Email</dt>
                            <dd>
                                <input type="email" name="cnewemail" value="<?= ($cnewemail_form) ? $cnewemail_form : '' ?>" class="size1">
                            </dd>
                            <dt>Password</dt>
                            <dd>
                                <input type="password" name="password" value="<?= ($password_form) ? $password_form : '' ?>" class="size1">
                            </dd>
                            <dd>
                                <p class="btn btn_red">
                                    <input type="submit" name="submit_cemail" value="Change email">
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