<?php include 'header.php'; 
redir();
?>
<div id="contents" class="clearfix">
    <div class="inner">

        <div id="mainContents" class="clearfix">
            <section>
                <h2 class="pageTitle">新規登録</h2>
                <?php
                $errors = false;
                $show_errors = array();
                if (isset($_POST['submit'])) {
                    $NickName = $_POST['NickName'];
                    $Password = $_POST['Password'];
                    $UserPasswordConfirm = $_POST['UserPasswordConfirm'];
                    $LastName = $_POST['LastName'];
                    $FirstName = $_POST['FirstName'];
                    $FuriOne = $_POST['FuriOne'];
                    $FuriTwo = $_POST['FuriTwo'];
                    $UserMail = $_POST['UserMail'];
                    $Year = $_POST['Year'];
                    $Month = $_POST['Month'];
                    $Day = $_POST['Day'];
                    $Birthday = $Year . "-" . $Month . "-" . $Day;
                    //$Sex = $_POST['Sex'];
                    $Gender = $_POST['gender'];
                    $Job = $_POST['Job'];
                    $Zipcode = $_POST['Zipcode'];
                    $State = $_POST['State'];
                    $City = $_POST['City'];
                    $Addr1 = $_POST['Addr1'];
                    $Addr2 = $_POST['Addr2'];
                    $Usertel = $_POST['Usertel'];
                    $Usertel2 = $_POST['Usertel2'];
                    $infoMail = $_POST['infoMail'];
                    // back up validation just incase javascript is disabled
                    if (empty($Password) OR
                        empty($UserPasswordConfirm) OR
                        empty($LastName) OR
                        empty($FirstName) OR
                        empty($FuriOne) OR
                        empty($FuriTwo) OR
                        empty($UserMail) OR
                        empty($Year) OR
                        empty($Month) OR
                        empty($Day) OR
                        empty($Gender) OR
                        empty($Job) OR
                        empty($Zipcode) OR
                        empty($State)) {
                        $show_errors[] = "Please fill up all required fields.";
                    $errors = true;
                }
                if ($Password != $UserPasswordConfirm) {
                    $show_errors[] = "Password did not macth";
                    $errors = true;
                }

                $user = $obj->singleData($UserMail, 'email', 'tbl_ut_pass');
                    //print_r($user);
                extract($user);
                if (!empty($user)) {
                    if (!password_verify($Password, $pwd)) {
                        $show_errors[] = 'Incorect Password';
                        $errors = true;
                    }
                } else {
                    $show_errors[] = 'Email Adress does not exists.';
                    $errors = true;
                }

                foreach ($show_errors as $show) {
                    echo'<div id="display-error"> <img src="img/common/dialog_warning.png" alt="Error" />' . $show . '</div>';
                }

                if (empty($show_errors)) {
                    $obj->NickName = $NickName;
                    $obj->LastName = $LastName;
                    $obj->FirstName = $FirstName;
                    $obj->FuriOne = $FuriOne;
                    $obj->FuriTwo = $FuriTwo;
                    $obj->Birthday = $Birthday;
                    $obj->Gender = $Gender;
                    $obj->Job = $Job;
                    $obj->Zipcode = $Zipcode;
                    $obj->State = $State;
                    $obj->City = $City;
                    $obj->Addr1 = $Addr1;
                    $obj->Addr2 = $Addr2;
                    $obj->tel = $Usertel;
                    $obj->tel2 = $Usertel2;
                    $obj->finalized($uid, "tbl_ut_user");
                    echo "<script>window.location.replace('mypage.html');</script>";
                        //header("location: mypage.html");
                }
            }
            ?>

            <form action="" method="post">
                <div class="tblA">
                    <dl>
                        <dt>アカウント名</dt>
                        <dd>
                            <input value="<?= (isset($_POST['submit']) ? $_POST['NickName'] : '') ?>" type="text" name="NickName" class="size1">
                            <p class="caution mt10">半角英数記号5文字以上</p>
                        </dd>
                    </dl>
                    <dl>
                        <dt>パスワード<span class="necessary">*必須</span></dt>
                        <dd>
                            <input value="<?= (isset($_POST['submit']) ? $_POST['Password'] : '') ?>" type="password"  name="Password" class="size1">
                            <p class="caution mt10">半角英数記号8文字以上</p>
                        </dd>
                    </dl>
                    <dl>
                        <dt>確認用パスワード<span class="necessary">*必須</span></dt>
                        <dd>
                            <input value="<?= (isset($_POST['submit']) ? $_POST['UserPasswordConfirm'] : '') ?>" type="password" name="UserPasswordConfirm" class="size1">
                        </dd>
                    </dl>
                    <dl>
                        <dt>ユーザー名<span class="necessary">*必須</span></dt>
                        <dd>
                            <p class="clearfix uName">
                                <input type="text" name="LastName" value="<?= (isset($_POST['submit']) ? $_POST['LastName'] : '') ?>" class="Separate float_l" size="15" placeholder="姓" value="">
                                <input type="text" name="FirstName" value="<?= (isset($_POST['submit']) ? $_POST['FirstName'] : '') ?>" size="15" placeholder="名" class="Separate float_r" value="">
                            </p>
                        </dd>
                    </dl>
                    <dl>
                        <dt>フリガナ<span class="necessary">*必須</span></dt>
                        <dd>
                            <p class="clearfix uName">
                                <input type="text" value="<?= (isset($_POST['submit']) ? $_POST['FuriOne'] : '') ?>" name="FuriOne" class="Separate float_l" size="15" placeholder="姓" value="">
                                <input type="text" value="<?= (isset($_POST['submit']) ? $_POST['FuriTwo'] : '') ?>" name="FuriTwo" size="15" placeholder="名" class="Separate float_r" value="">
                            </p>
                        </dd>
                    </dl>
                    <dl>
                        <dt>メールアドレス<span class="necessary">*必須</span></dt>
                        <dd>
                            <input type="text" value="<?= (isset($_POST['submit']) ? $_POST['UserMail'] : $_SESSION['login']) ?>" name="UserMail" class="size1">
                        </dd>
                    </dl>
                    <dl>
                        <dt>生年月日<span class="necessary">*必須</span></dt>
                        <dd>
                            <select name="Year" class="w100">
                                <option value="">--</option>
                                <?php
                                $year = date(" Y ");
                                for ($x = 1900; $x <= $year; $x++) {
                                    ?>
                                    <option value="<?= $x; ?>"<?php if (isset($_POST['Year']) && ($_POST['Year'] == $x)) echo 'selected'; ?>><?= $x; ?></option>
                                    <?php } ?>
                                </select>
                                年 
                                <SELECT name="Month" class="w60">
                                    <option value="">--</option>
                                    <?php for ($x = 1; $x <= 12; $x++) { ?>
                                    <option value="<?= $x; ?>"<?php if (isset($_POST['Month']) && ($_POST['Month'] == $x)) echo 'selected'; ?>><?= $x ?></option>
                                    <?php } ?>
                                </SELECT>
                                月 
                                <SELECT name="Day" class="w60">
                                    <option value="">--</option>
                                    <?php for ($x = 1; $x <= 31; $x++) { ?>
                                    <option value="<?= $x; ?>"<?php if (isset($_POST['Day']) && ($_POST['Day'] == $x)) echo 'selected'; ?>><?= $x ?></option>
                                    <?php } ?>
                                </select>
                                日
                            </dd>
                        </dl>
                        <dl>
                            <dt>性別<span class="necessary">*必須</span></dt>
                            <dd>
                                <ul class="btns_select">
                                    <li><input type="radio" name="gender" id="male" value="male" <?php
                                    if ($_POST['gender'] == "male") {
                                        echo 'checked="checked"';
                                    }
                                    ?>  checked=""><label for="male">男性</label></li>
                                    <li><input type="radio" name="gender" id="female" value="female" <?php
                                    if ($_POST['gender'] == "female") {
                                        echo 'checked="checked"';
                                    }
                                    ?> ><label for="female">女性</label></li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>職業<span class="necessary">*必須</span></dt>
                            <dd>
                                <input type="text" name="Job" value="<?= (isset($_POST['submit']) ? $_POST['Job'] : '') ?>" class="size1">
                            </dd>
                        </dl>
                        <dl>
                            <dt>住所<span class="necessary">*必須</span></dt>
                            <dd>
                                <dl>
                                    <dt>郵便番号</dt>
                                    <dd><input type="text" name="Zipcode" value="<?= (isset($_POST['submit']) ? $_POST['Zipcode'] : '') ?>" class="w240" placeholder="123-4567"></dd>
                                </dl>
                                <dl>
                                    <dt>都道府県</dt>
                                    <dd>
                                        <select name="State">
                                            <option value="北海道"<?php if (isset($_POST['State']) && ($_POST['State'] == '北海道')) echo 'selected'; ?>>北海道</option>
                                            <option value="青森県"<?php if (isset($_POST['State']) && ($_POST['State'] == '青森県')) echo 'selected'; ?>>青森県</option>
                                            <option value="岩手県"<?php if (isset($_POST['State']) && ($_POST['State'] == '岩手県')) echo 'selected'; ?>>岩手県</option>
                                            <option value="宮城県"<?php if (isset($_POST['State']) && ($_POST['State'] == '宮城県')) echo 'selected'; ?>>宮城県</option>
                                            <option value="秋田県"<?php if (isset($_POST['State']) && ($_POST['State'] == '秋田県')) echo 'selected'; ?>>秋田県</option>
                                            <option value="山形県"<?php if (isset($_POST['State']) && ($_POST['State'] == '山形県')) echo 'selected'; ?>>山形県</option>
                                            <option value="福島県"<?php if (isset($_POST['State']) && ($_POST['State'] == '福島県')) echo 'selected'; ?>>福島県</option>
                                            <option value="茨城県"<?php if (isset($_POST['State']) && ($_POST['State'] == '茨城県')) echo 'selected'; ?>>茨城県</option>
                                            <option value="栃木県"<?php if (isset($_POST['State']) && ($_POST['State'] == '栃木県')) echo 'selected'; ?>>栃木県</option>
                                            <option value="群馬県"<?php if (isset($_POST['State']) && ($_POST['State'] == '群馬県')) echo 'selected'; ?>>群馬県</option>
                                            <option value="埼玉県"<?php if (isset($_POST['State']) && ($_POST['State'] == '埼玉県')) echo 'selected'; ?>>埼玉県</option>
                                            <option value="千葉県"<?php if (isset($_POST['State']) && ($_POST['State'] == '千葉県')) echo 'selected'; ?>>千葉県</option>
                                            <option value="東京都"<?php if (isset($_POST['State']) && ($_POST['State'] == '東京都')) echo 'selected'; ?>>東京都</option>
                                            <option value="神奈川県"<?php if (isset($_POST['State']) && ($_POST['State'] == '神奈川県')) echo 'selected'; ?>>神奈川県</option>
                                            <option value="新潟県"<?php if (isset($_POST['State']) && ($_POST['State'] == '新潟県')) echo 'selected'; ?>>新潟県</option>
                                            <option value="富山県"<?php if (isset($_POST['State']) && ($_POST['State'] == '富山県')) echo 'selected'; ?>>富山県</option>
                                            <option value="石川県"<?php if (isset($_POST['State']) && ($_POST['State'] == '石川県')) echo 'selected'; ?>>石川県</option>
                                            <option value="福井県"<?php if (isset($_POST['State']) && ($_POST['State'] == '福井県')) echo 'selected'; ?>>福井県</option>
                                            <option value="山梨県"<?php if (isset($_POST['State']) && ($_POST['State'] == '山梨県')) echo 'selected'; ?>>山梨県</option>
                                            <option value="長野県"<?php if (isset($_POST['State']) && ($_POST['State'] == '長野県')) echo 'selected'; ?>>長野県</option>
                                            <option value="岐阜県"<?php if (isset($_POST['State']) && ($_POST['State'] == '岐阜県')) echo 'selected'; ?>>岐阜県</option>
                                            <option value="静岡県"<?php if (isset($_POST['State']) && ($_POST['State'] == '静岡県')) echo 'selected'; ?>>静岡県</option>
                                            <option value="愛知県"<?php if (isset($_POST['State']) && ($_POST['State'] == '愛知県')) echo 'selected'; ?>>愛知県</option>
                                            <option value="三重県"<?php if (isset($_POST['State']) && ($_POST['State'] == '三重県')) echo 'selected'; ?>>三重県</option>
                                            <option value="滋賀県"<?php if (isset($_POST['State']) && ($_POST['State'] == '滋賀県')) echo 'selected'; ?>>滋賀県</option>
                                            <option value="京都府"<?php if (isset($_POST['State']) && ($_POST['State'] == '京都府')) echo 'selected'; ?>>京都府</option>
                                            <option value="大阪府"<?php if (isset($_POST['State']) && ($_POST['State'] == '大阪府')) echo 'selected'; ?>>大阪府</option>
                                            <option value="兵庫県"<?php if (isset($_POST['State']) && ($_POST['State'] == '青森県')) echo 'selected'; ?>>兵庫県</option>
                                            <option value="奈良県"<?php if (isset($_POST['State']) && ($_POST['State'] == '兵庫県')) echo 'selected'; ?>>奈良県</option>
                                            <option value="和歌山県"<?php if (isset($_POST['State']) && ($_POST['State'] == '和歌山県')) echo 'selected'; ?>>和歌山県</option>
                                            <option value="鳥取県"<?php if (isset($_POST['State']) && ($_POST['State'] == '鳥取県')) echo 'selected'; ?>>鳥取県</option>
                                            <option value="島根県"<?php if (isset($_POST['State']) && ($_POST['State'] == '島根県')) echo 'selected'; ?>>島根県</option>
                                            <option value="岡山県"<?php if (isset($_POST['State']) && ($_POST['State'] == '岡山県')) echo 'selected'; ?>>岡山県</option>
                                            <option value="広島県"<?php if (isset($_POST['State']) && ($_POST['State'] == '広島県')) echo 'selected'; ?>>広島県</option>
                                            <option value="山口県"<?php if (isset($_POST['State']) && ($_POST['State'] == '山口県')) echo 'selected'; ?>>山口県</option>
                                            <option value="徳島県"<?php if (isset($_POST['State']) && ($_POST['State'] == '徳島県')) echo 'selected'; ?>>徳島県</option>
                                            <option value="香川県"<?php if (isset($_POST['State']) && ($_POST['State'] == '香川県')) echo 'selected'; ?>>香川県</option>
                                            <option value="愛媛県"<?php if (isset($_POST['State']) && ($_POST['State'] == '愛媛県')) echo 'selected'; ?>>愛媛県</option>
                                            <option value="高知県"<?php if (isset($_POST['State']) && ($_POST['State'] == '高知県')) echo 'selected'; ?>>高知県</option>
                                            <option value="福岡県"<?php if (isset($_POST['State']) && ($_POST['State'] == '福岡県')) echo 'selected'; ?>>福岡県</option>
                                            <option value="佐賀県"<?php if (isset($_POST['State']) && ($_POST['State'] == '佐賀県')) echo 'selected'; ?>>佐賀県</option>
                                            <option value="長崎県"<?php if (isset($_POST['State']) && ($_POST['State'] == '長崎県')) echo 'selected'; ?>>長崎県</option>
                                            <option value="熊本県"<?php if (isset($_POST['State']) && ($_POST['State'] == '青森県')) echo 'selected'; ?>>熊本県</option>
                                            <option value="大分県"<?php if (isset($_POST['State']) && ($_POST['State'] == '大分県')) echo 'selected'; ?>>大分県</option>
                                            <option value="宮崎県"<?php if (isset($_POST['State']) && ($_POST['State'] == '宮崎県')) echo 'selected'; ?>>宮崎県</option>
                                            <option value="鹿児島県"<?php if (isset($_POST['State']) && ($_POST['State'] == '鹿児島県')) echo 'selected'; ?>>鹿児島県</option>
                                            <option value="沖縄県"<?php if (isset($_POST['State']) && ($_POST['State'] == '沖縄県')) echo 'selected'; ?>>沖縄県</option>
                                        </select>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>市区町村</dt>
                                    <dd><input type="text" value="<?= (isset($_POST['submit']) ? $_POST['City'] : '') ?>" name="City" class="w240"></dd>
                                </dl>
                                <dl>
                                    <dt>住所</dt>
                                    <dd><input type="text" name="Addr1" value="<?= (isset($_POST['submit']) ? $_POST['Addr1'] : '') ?>" class="w240"></dd>
                                </dl>
                                <dl>
                                    <dt>マンション名等</dt>
                                    <dd><input type="text" name="Addr2" value="<?= (isset($_POST['submit']) ? $_POST['Addr2'] : '') ?>" class="w240"></dd>
                                </dl>
                            </dd>
                        </dl>
                        <dl>
                            <dt>電話番号<span class="necessary">*必須</span></dt>
                            <dd>
                                <input type="text" name="UserTel" value="<?= (isset($_POST['submit']) ? $_POST['UserTel'] : '') ?>" class="size1" placeholder="646-123-4567">
                            </dd>
                        </dl>
                        <dl>
                            <dt>携帯電話番号</dt>
                            <dd>
                                <input type="text" name="UserTel2" value="<?= (isset($_POST['submit']) ? $_POST['UserTel2'] : '') ?>" class="size1" placeholder="090-123-456">
                            </dd>
                        </dl>
                        <dl>
                            <dt>メルマガの配信<span class="necessary">*必須</span></dt>
                            <dd>
                                <ul class="btns_select">
                                    <li><input type="radio" name="infoMail" id="receive" value="receive" <?php
                                    if ($_POST['infoMail'] == "receive") {
                                        echo 'checked="checked"';
                                    }
                                    ?> checked=""><label for="receive">必要</label></li>
                                    <li><input type="radio" name="infoMail" id="notReceive" value="notReceive" <?php
                                    if ($_POST['infoMail'] == "notReceive") {
                                        echo 'checked="checked"';
                                    }
                                    ?> ><label for="notReceive">不必要</label></li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>学習したいカテゴリー<br><span class="choice">※複数選択可能</span></dt>
                            <dd class="checkAdd">
                                <ul class="btns_select clearfix">
                                    <li><input type="checkbox" name="Category" id="it" value="it" checked=""><label for="it" class="fs14 chek">テクノロジー・IT</label></li>
                                    <li><input type="checkbox" name="Category" id="business" value="business"><label for="business" class="chek">ビジネススキル</label></li>
                                    <li><input type="checkbox" name="Category" id="company" value="company"><label for="company" class="chek">企業・経営</label></li>
                                    <li><input type="checkbox" name="Category" id="Economy" value="Economy"><label for="Economy" class="chek">政治・経済</label></li>
                                    <li><input type="checkbox" name="Category" id="design" value="design"><label for="design" class="chek">デザイン・CG</label></li>
                                    <li><input type="checkbox" name="Category" id="language" value="language"><label for="language" class="fs14 chek">教養・教育・語学</label></li>
                                    <li><input type="checkbox" name="Category" id="sport" value="sport"><label for="sport" class="chek">健康・スポーツ</label></li>
                                    <li><input type="checkbox" name="Category" id="hobby" value="hobby"><label for="hobby" class="chek">趣味</label></li>
                                </ul>
                            </dd>
                        </dl>
                    </div>
                    <p class="align_c mt40"><input type="checkbox" name="flgAgreement" value="on" class="check" id="check"><label for="check"><a href="#" class="bd">利用規約</a>に同意する</label></p>
                    <div class="btns">
                        <p class="btn_red"><input type="submit" name="submit" class="w240 fs18 h45" value="登録する"></p>
                    </div>
                </form>
            </section>
        </div><!-- /#mainContents -->

        <?php include 'sideNav.php';?>

    </div><!-- /.inner -->

</div><!-- /#contents -->
<?php include 'footer.php'; ?>