<?php
include 'include/header.php';
include 'include/nav.php';
?>
<style>
.colorgraph {
    height: 5px;
    border-top: 0;
    background: #c4e17f;
    border-radius: 5px;
    background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
    background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
    background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
    background-image: linear-gradient(right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
}
</style>
<div class="container">    
    <div class="row" style="height: auto; width: 400px; margin-top:60px; padding: 10px;">
        <?php
        if (isset($_POST['login_submit'])) {
            $form_email = $_POST['form_email'];
            $form_password = $_POST['form_password'];
            if (!isset($_POST['form_key']) || !$obj->validate()) {
                $error_token = '<div data-alert class="alert-box alert radius">Invalid submission</div>';
            } else {
                $form_email_error = $form->email($form_email);
                $errors = $form_email_error;
                if (empty($errors)) {
                    $data_ = $obj->singleData($form_email, "email", "tbl_ut_pass");
                    extract($data_);
                    if (password_verify($form_password, $pwd)) {
                        $data = $obj->checkUser($form_email, $pwd, 'tbl_ut_pass');
                        header("Refresh: 5; url=clist.php"); 
                        $success = '<div data-alert class="alert-box success">Logged in successfully. You will now be redirected to a new page, after 3 seconds...</div>';
                    } else {
                        $login_error = '<div data-alert class="alert-box alert">Invalid username or password!</div>';
                    }
                }
            }
        }
        ?>  
        <form data-abide method="post" action="" >
            <?php $obj->outputKey(); ?>
            <h3 class="sign-up-title" style="color:dimgray;">Welcome back! Please sign in</h3>
            <hr class="colorgraph">
            <?= ($error_token) ? $error_token : ''; ?>
            <?= ($success) ? $success : ''; ?>
            <?= ($login_error) ? $login_error : ''; ?>
            <div class="name-field">
                <label>Your email <small>required</small>
                    <input type="email" name="form_email" required >
                </label>
                <small class="error">Your email address is required.</small>
            </div>
            <div class="email-field">
                <label>Password <small>required</small>
                    <input type="password" name="form_password" id="password" required="" aria-invalid="true" data-invalid="">
                </label>
                <small class="error">Your password is required.</small>
            </div>
            <input type="submit" class="button success expand" value="Login" name="login_submit" aria-invalid="false">
        </form>
    </div>
</div>
<?php
include 'include/footer.php';
