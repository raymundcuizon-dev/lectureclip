<?php

function __autoload($class) {
    include 'lib/' . $class . '.php';
}

$obj = new obj;
?>
<form action="" method="post">
    <p>Account Name: <input type="text" name="accountName"/></p>
    <p>Password Confirmation: <input type="password" name="password"/></p>
    <p><b>Username:</b> <br>
        <span>Last name: <input type="text" name="lastname" /></span>
        <span>First name: <input type="text" name="firstname" /> </span>
    </p>
    <p>Kana: <input type="text" name="furi" /></p>
    <p>confirm email: <input type="email" name="email" /></p>
    <p> Birth day: 
        <select name="year">
            <option>-year-</option>
            <?php
            for ($x = 1950; $x <= 2014; $x++) {
                echo "<option value=" . $x . ">" . $x . "</option>";
            }
            ?>
        </select>
        <select name="month">
            <option>-month-</option>
            <?php
            for ($x = 1; $x <= 12; $x++) {
                echo "<option value=" . $x . ">" . $x . "</option>";
            }
            ?>
        </select>
        <select name="day">
            <option>-day-</option>
            <?php
            for ($x = 1; $x <= 31; $x++) {
                echo "<option value=" . $x . ">" . $x . "</option>";
            }
            ?>
        </select>
    </p>
    <p>Gender: 
        <input type="radio" name="gen" value="male" /> Male
        <input type="radio" name="gen" value="female" /> Female
    </p>
    <p>Occupation: <input type="text" name="oct" /></p>
    <p>Zip code: <input type="text" name="zipcd" /></p>
    <p>State: <input type="text" name="state" /></p>
    <p>city: <input type="text" name="city" /></p>
    <p>Address 1: <input type="text" name="addr1" /></p>
    <p>Address 2: <input type="text" name="addr2" /></p>
    <p>tel: <input type="text" name="tel" /></p>
    <p>tel 2: <input type="text" name="tel2" /></p><p>Address 1: <input type="text" name="dmail_flg" /></p>
    <input type="submit" name="submit" value="update" />
</form>
<?php
if (isset($_POST['submit'])) {
    $accountName = $_POST['accountName'];
    $password = $_POST['password'];
    $user = $obj->singleData($accountName, 'email', 'tbl_ut_pass');
    extract($user);
    if (!empty($user)) {
        if (password_verify($password, $pwd)) {
            echo 'Password is valid!';
        } else {
            echo 'Invalid password.';
        }
    } else {
        echo "username does not exist";
    }
}
?>