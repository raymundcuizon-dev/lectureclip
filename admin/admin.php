<?php
session_start();

function __autoload($class) {
    include_once '../lib/' . $class . '.php';
}

$form = new validator();
$obj = new obj();
?>
<?php
if (isset($_POST['submit'])) {
    $name_cat = $_POST['name_category'];
    $name_cat_error = $form->walang_laman($name_cat, "Category");
    $errors = $name_cat_error;
    if (!isset($_POST['form_key']) || !$obj->validate()) {
        $error = 'Invalid Token!';
    } else {
        if (empty($errors)) {
            $data = array('catname' => $name_cat);
            $obj->insert('tbl_m_category', $data);
            $success = "yesssssssssssss";
        }
    }
}
//echo $_SESSION['form_key'];
?>
<?= ($error) ? $error : ''; ?>
<?= ($success) ? $success : ''?>
<form action="" method="post">
<?php $obj->outputKey(); ?>
    <input type="text" name="name_category">
    <?= ($errors) ? $errors : ''; ?>
    <input type="submit" name="submit">
</form>
