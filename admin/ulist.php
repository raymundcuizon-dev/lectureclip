<?php
include 'include/header.php';
include 'include/nav.php';
include 'include/sidenav.php';
?>
<div class="large-10 columns">
    <h3><a href="#">Post Title</a></h3>
    <br>
    <div class="large-12 columns"><ul class="tabs" data-tab>
            <li class="tab-title active"><a href="#panel1">All active users</a></li>
            <li class="tab-title"><a href="#panel2">New users</a></li>
            <li class="tab-title"><a href="#panel3">Inactive users</a></li>
        </ul>
        <div class="tabs-content">
            <div class="content active" id="panel1">
                <form>
                    <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User type</th>
                                <th>Date joined</th>
                                <th>Last updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User type</th>
                                <th>Date joined</th>
                                <th>Last updated</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($obj->userslist() as $value) : extract($value); ?>
                            <tr> 
                                <td><img src="" /></td>
                                    <td><a data-reveal-id="myModal" href="?view=1"><?= $name1 . " " . $name2 ?></a></td>
                                    <td><?= $email ?></td>
                                    <td></td>
                                    <td><?= $regdate ?></td>
                                    <td><?= $update ?></td>
                                    <td><a href="">Edit</a> | <a href="">Delete</a>
                                        <!--
                                            <div class="row">
                                                <div class="glyph">
                                                    <div class="preview-glyphs">
                                                        <a href="" class="action"><i class="fi-page-edit size-21"></i> Edit</a> 
                                                        <span> &nbsp&nbsp | &nbsp&nbsp </span>
                                                        <a href="" class="action"><i class="fi-page-delete size-21"></i> Delete</a> 
                                                        <span> &nbsp&nbsp | &nbsp&nbsp </span>
                                                        <a href="" class="action" onClick="return confirm('Are you sure you want to block this account?');"><i class="fi-dislike size-21" ></i> Block</a> 
                                                    </div>
                                                </div>
                                            </div> 
                                        -->
                                </td>
                            </tr>
                            <?php endforeach; unset($value); ?>    
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="content" id="panel2">
            </div>
            <div class="content" id="panel3">
            </div>
        </div>
    </div>
</div>
<!--Modal-->

<div id="myModal" class="reveal-modal" data-reveal>
    <?php
    var_dump($_GET);
    ?>
    <a class="close-reveal-modal">&#215;</a>
</div>

<?php
include 'include/footer.php';
