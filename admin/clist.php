<?php
include 'include/header.php';
include 'include/nav.php';
include 'include/sidenav.php';
?>
<div class="large-10 columns">
    <br>
    <h3><a href="#">Post Title</a></h3>
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="200">Category Name</th>
                <th>Created by</th>
                <th width="200">Date created</th>
                <th width="150">Last Update</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($obj->readall("tbl_m_category") as $value): extract($value); ?>                    
                <tr> 
                    <td><?= $catname ?></td>
                    <td>Sample name</td>
                    <td><?= $regdate ?></td>
                    <td></td>
                    <td><a href=""> Edit </a> | <a href=""> Delete </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>
<?php
include 'include/footer.php';
