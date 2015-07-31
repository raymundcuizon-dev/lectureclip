<?php
include 'include/header.php';
include 'include/nav.php';
include 'include/sidenav.php';
?>
<div class="large-4 columns">
    <br>
    <h3><a href="#">Post Title</a></h3>
    <?php
    if (isset($_POST['form_submit'])) {
        $form_category = $_POST['form_category'];
        if (!isset($_POST['form_key']) || !$obj->validate()) {
            $error_token = '<div data-alert class="alert-box alert radius">Invalid submission</div>';
        } else {
            $form_category_error = $form->required($form_category, "Name of Category");
            $errors = $form_category_error;
            if (empty($errors)) {
                $user = $obj->singleData($form_category, "catname", "tbl_m_category");
                if ($user) {
                    $already_exist = '<div data-alert class="alert-box alert radius">Category is already exist. Please choose another category name</div>';
                } else {
                    $data = array('catname' => $form_category);
                    $obj->insert('tbl_m_category', $data);
                    $success_added = '<div data-alert class="alert-box success radius">You have successfully created a category. <a style="color: #C63F20" href="clist.php?pagenav=clist"> click here to view category list</a></div>';
                }
            }
        }
    }
    ?>
    <?= ($error_token) ? $error_token : ''; ?>
    <?= ($success_added) ? $success_added : ''; ?>
    <?= ($already_exist) ? $already_exist : ''; ?>
    <form data-abide method="post" action="">
        <?php $obj->outputKey(); ?>
        <div class="name-field">
            <div class="input-wrapper">
                <label>Name of category <small>required</small>
                    <input type="text"  name="form_category" value="<?= ($form_category) ? $form_category : '' ?>" required pattern="[a-zA-Z]+">
                    <?= ($errors) ? $errors : ''; ?>
                </label>
                <small class="error">Category is required and must be a string.</small>
            </div>
            <input type="submit" class="button success expand" value="Create" name="form_submit"/>
    </form>
</div>
</div>
<?php
include 'include/footer.php';
