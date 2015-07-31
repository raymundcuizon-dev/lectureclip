<footer>
    <div class="inner clearfix">
        <p><a href="./"><img src="img/common/logo_f.gif" alt="レクチャークリップ"></a></p>
        <nav id="fnav" class="clearfix">
            <dl class="sns">
                <dt>Official Account</dt>
                <dd>
                    <ul class="clearfix">
                        <li><span class="fa fa-facebook-square fa-lg"></span></li>
                        <li><span class="fa fa-twitter fa-lg"></span></li>
                    </ul>
                </dd>
            </dl>
            <dl class="about">
                <dt>LectureClipとは</dt>
                <dd>
                    <ul>
                        <li><a href="#">ご利用方法</a></li>
                        <li><a href="#">受講の仕方</a></li>
                        <li><a href="#">コースの作り方</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="courseSearch">
                <dt>コース検索</dt>
                <dd>
                    <ul>
                        <li><a href="category.php">カテゴリー</a></li>
                        <li><a href="free.php">無料</a></li>
                        <li><a href="top_chart.php">トップチャート</a></li>
                        <li><a href="new.php">新着</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="regist_mypage">
                <dt>会員登録・マイページ</dt>
                <dd class="clearfix">
                    <ul>
                        <li><a rel="leanModal" href="#m_registration">会員登録（無料）</a></li>
                        <li><a rel="leanModal" href="#m_login">ログイン</a></li>
                    </ul>
                    <ul>
                        <li><a href="profile_edit.php">プロフィール編集</a></li>
                        <li><a href="purchase_list.php">購入済みのコース</a></li>
                        <li><a href="review_list.php">検討中リスト</a></li>
                        <li><a href="create_course.php">作成コース</a></li>
                    </ul>
                    <ul>
                        <li><a rel="leanModal" href="#m_createCourse">コース作成</a></li>
                    </ul>
                </dd>
            </dl>
        </nav>
        <ul id="fmenu" class="clearfix">
            <li><a href="#">サイトマップ</a></li>
            <li><a href="#">運営会社情報</a></li>
            <li><a href="#">利用規約</a></li>
            <li><a href="#">ヘルプ</a></li>
            <li><a href="#">よくあるご質問</a></li>
            <li><a href="#">お問い合わせ</a></li>
            <li><a href="#">プライバシーポリシー</a></li>
        </ul>
    </div>
    <p id="copyright">&copy; 2014 ./ All Rights Reserved.</p>
</footer><!-- /footer -->

</div><!-- /#wrap -->

<p id="pagetop"><a href="#wrap"><img src="img/common/pagetop.png" alt="ページトップへ"></a></p>


<!-- ▼モーダルウィンドウ設置 -->
<div class="overlay"></div>

<div id="m_registration" class="modal">
    <div class="modalHead">
        <strong>新規登録</strong>
        <a href="javascript:;" class="closeBtn btn"><i class="fa fa-times"></i></a>
    </div><!-- /[div.modalHead] -->
    <div class="modalMain table">
        <div class="cell loginFbWrapper">
            <p>Facebookアカウントでログイン</p>
            <p class="btn"><a href="javascript:;"><img src="img/common/login_fb_btn.gif" width="240" height="45" alt=""></a></p>
        </div><!-- /[div.float_l] -->
        <?php
        if (isset($_POST['reg'])) {
            $userMail = $_POST['userMail'];
            $pass = $_POST['pass'];
            $lastName = $_POST['lastName'];
            $givenName = $_POST['givenName'];

            if (empty($userMail) OR empty($pass) OR empty($lastName) OR empty($givenName)) {
                echo "<script>alert('Please fill up all required fields');</script>";
            } else {
                $obj->newReg($lastName, $givenName, "tbl_ut_user");
                foreach ($obj->showLastId("tbl_ut_user", "uid") as $value) {
                    extract($value);
                    $uid = $value['uid'];
                }
                $password_hash = password_hash($pass, PASSWORD_BCRYPT, array('cost' => 10));
                $obj->newRegEmail($uid, $password_hash, $userMail, "tbl_ut_pass");

                $mail->From = 'lectureclip@easycom.com';
                $mail->FromName = 'LectureClip';
                $mail->addAddress($userMail, $givenName.' '.$lastName);
                $mail->addReplyTo('no-reply@easycom.com', '');
                $mail->addCC('raymundcuizon@gmail.com');

                $mail->Subject = 'Here is the subject';
                $mail->Body    = 'Congratulations! You have successfully registered.';


                echo "<script>window.location.replace('index.php');</script>";
                
                if(!$mail->send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
            }
        }
        ?>
        <div class="cell formWrapper">
            <form action="" method="post">
                <p><input type="email" name="userMail" class="userMail" id="username" required placeholder="ID（メールアドレス）"> <span id="user-result"></span></p>
                <p><input type="text" required name="lastName" class="userName" size="15" placeholder="姓"></p>
                <p><input type="text" required name="givenName" class="userName" size="15" placeholder="名"></p>
                <p><input type="password" name="pass" onchange="form.passConfirm.pattern = this.value;" class="pass" size="15" required placeholder="パスワード"></p>
                <p><input type="password" name="passConfirm" class="pass" size="15" required placeholder="パスワード(確認用)"></p>
                <div class="align_c">
                    <p><input type="checkbox" name="flgAgreement" value="on" required class="check" id="check"><label for="check"><a href="#">利用規約</a>に同意する</label></p>
                    <p><input type="submit" id="user-reg" name="reg" value="登録する"></p>
                </div><!-- /[div.align_c] -->
            </form>
        </div><!-- /[div.float_r] -->
    </div><!-- /[div.clearfix] -->
</div><!-- /[div#modal.modal] -->

<div id="m_login" class="modal">
    <div class="modalHead">
        <strong>ログイン</strong>
        <a href="javascript:;" class="closeBtn btn"><i class="fa fa-times"></i></a>
    </div><!-- /[div.modalHead] -->
    <div class="modalMain table">
        <div class="cell loginFbWrapper">
            <p>Facebookアカウントでログイン</p>                
            <!--<p class="btn"><a href="javascript:;" onclick="fb_login();"><img src="img/common/login_fb_btn.gif" width="240" height="45" alt=""></a></p>-->
            <p class="btn"><a href="#" onclick="fb_login();"><img src="img/common/login_fb_btn.gif" width="240" height="45" alt=""></a></p>
        </div><!-- /[div.float_l] -->
        <div class="cell formWrapper">
            <form method="post" name="lc_login_m" id="lc_login_m" action="" onSubmit="return lc_LoginCheck('UserMail_m', 'UserPass_m');">
                <div style="color:red;" id="add_err"> </div>
                <p><input type="text" id="UserMail_m" name="UserMail_m" class="userMail" size="15" placeholder="ID（メールアドレス）"></p>
                <p><input type="password" name="UserPass_m" class="pass" size="15" placeholder="パスワード"></p>
                <div class="align_c">
                    <p><input type="submit" name="login" value="ログイン"></p>
                    <p><a rel="leanModal" href="#m_registration" onClick="javascript:displayControl('#m_login', '#lean_overlay', '', '', 0);">新規登録</a>　　　<a rel="leanModal" href="#m_forgot" onClick="javascript:displayControl('#m_login', '#lean_overlay', '', '', 0);">パスワードを忘れた方はこちら</a></p>
                </div><!-- /[div.align_c] -->
            </form>
        </div><!-- /[div.float_r] -->
    </div><!-- /[div.clearfix] -->
</div><!-- /[div#modal.modal] -->

<div id="m_forgot" class="modal">
    <div class="modalHead"> <strong>パスワードを忘れた方</strong>
        <a href="javascript:;" class="closeBtn btn"><i class="fa fa-times"></i></a>
    </div><!-- /[div.modalHead] -->
    <div class="generalMain">
        <p class="txt">ご登録のメールアドレスを入力してください。<br>パスワードの再登録のご案内メールを送信します。</p>
        <?php
        if (isset($_POST['submit_forgot'])) {
            $code = substr(md5(rand()), 0, 50);
            $to = $_POST['UserMail_forgot'];
            //$subject = 'the subject';
            $message_c = 'http://173.255.196.47/lectureclip/resetpass.php?d='.$code;
            $message = mb_convert_encoding($message_c, "ISO-2022-JP", "auto");
            $to_send = mb_convert_encoding($to, "ISO-2022-JP", "auto");
            $subject_C = "The subject";
            $subject = mb_encode_mimeheader(mb_convert_encoding($subject_C, "ISO-2022-JP", "auto"));
            $headers= "From: " . mb_encode_mimeheader(mb_convert_encoding("lectureclip.com", "ISO-2022-JP", "auto")) . " <no-reply@sample.com>\r\n";
            $headers .= "Content-Type: text/plain; charset=iso-2022-jp\n";
            $headers .= "Content-Transfer-Encoding: 7bit\n";
            $headers.= "X-Mailer: PHP/" . phpversion() . "\r\n";
            $headers.= "MIME-Version: 1.0" . "\r\n";
            
            $data = array('gen_code' => $code, 'email' => $to);
            $obj->insert("tbl_forgot_pass_log", $data);
            mail($to_send, $subject, $message, $headers);
            
            echo "<script>window.location.replace('index.php')</script>";
        }
        ?>
        <form action="" class="mailAdd" method="post">
            <dl>
                <dt>メールアドレス</dt>
                <dd>
                    <input type="text" name="UserMail_forgot" id="UserMail_forgot" class="">
                    <!-- <div style="color:red;" id="emailError"> </div> -->
                </dd>
            </dl>
            <p class="btn btn_red">
                <input type="submit" name="submit_forgot" value="送信">
            </p>
        </form>
    </div><!-- /[div.generalMain] -->
</div><!-- /[div#modal.modal] -->
<?php
if (isset($_POST['submit'])) {    
    $data = array('uid' => $uid, 'title' => $_POST['CourseName'], 'catid' => $_POST['Category']);
    echo $data['uid'];
    $obj->insert("tbl_lc_course", $data);
    $CourseName_list = $obj->singleData($uid, 'uid', 'tbl_lc_course', 'ORDER BY cid DESC limit 1');
    extract($CourseName_list);
    $_SESSION['cid'] = $cid;
    unset($CourseName_list);
    echo "<script>window.location.replace('cl_upload.php');</script>";
}
//print_r($_SESSION);
?>
<div id="m_createCourse" class="modal">
    <div class="modalHead"> <strong>コースを作成</strong>
        <a href="javascript:;" class="closeBtn btn"><i class="fa fa-times"></i></a>
    </div><!-- /[div.modalHead] -->
    <div class="generalMain">
        <p class="txt">新しく作成するコース名を入力してください。</p>
        <form action="" method="POST" class="mailAdd">
            <dl>
                <dt>コース名</dt>
                <dd>
                    <input type="text" name="CourseName" id="CourseName" required class="" pattern=".{5,30}" required title="5 to 30 characters" > <!--<span id="user-result"></span> --> 
                </dd>
                <dd>
                    <input type="submit" id="myBtn" name="submit" disabled value="新規作成">&nbsp;<span id="CourseName-result"></span>
                </dd>
            </dl>
        </form>
    </div><!-- /[div.generalMain] -->
</div><!-- /[div#modal.modal] -->
<script type="text/javascript">

$(document).ready(function () {
    $("#username").keyup(function (e) {
            //removes spaces from username
            $(this).val($(this).val().replace(/\s/g, ''));

            var username = $(this).val();
            if (username.length < 4) {
                $("#user-result").html('');
                return;
            }
            if (username.length >= 4) {
                $("#user-result").html('<img src="img/ajax-loader.gif" />');
                $.post('check_username.php', {'username': username}, function (data) {
                    $("#user-result").html(data);
                });
            }
        });
});

$(document).ready(function () {
    $("#CourseName").keyup(function (e) {
        var CourseName = $(this).val();
        if (CourseName.length < 4) {
            $("#CourseName-result").html('');
            return;
        }
        if (CourseName.length >= 4) {
            $("#CourseName-result").html('<img src="img/ajax-loader.gif" />');
            $.post('check_username.php', {'CourseName': CourseName}, function (data) {
                $("#CourseName-result").html(data);
            });
        }
    });
});
</script>
</body>
</html>