<?php
session_start();
require_once("obj.php");
$api_function = $_POST['api_function'];

switch ($api_function) {
    case 'checkUser': {
        checkUserAPI();
        break;
    }
    case 'checkEmail': {
        checkUserEmail();
        break;
    }
    case 'sendEmail': {
        sendMail();
        break;
    }
    case 'insertLecture': {
        addLecture();
        break;
    }
    case 'savePrice': {
        insertPrice();
        break;
    }
    case 'insertThumbnail': {
        saveThumbnail();
        break;
    }
    case 'updateThumbnail': {
        changeThumbnail();
        break;
    }
    case 'uploadMashup': {
        uploadMashup();
        break;
    }
    case 'displayLD': {
        displayLD();
        break;
    }
    case 'saveNotes': {
        insertNotes();
        break;
    }
    case 'setFBdetails': {
        saveFBdetails();
        break;
    }
    default:
    break;
}

function insertNotes(){
    $obj = new obj;
    $notes = $_POST['notes'];

    $form_data = array("memo" => $notes);
    $obj->insert("tbl_ut_lecture", $form_data);

    echo $uid;
}

function displayLD(){
    $obj = new obj;
    $lec_no = $_POST['lecture_no'];

    $data = $obj->singleData($lec_no, 'lno', 'tbl_lc_lecture', 'ORDER BY lno DESC limit 1');    
    extract($data); 

    echo json_encode($data);
}

function changeThumbnail() {
    $obj = new obj;
    $array = $_POST['thumbnail_data'];
    $cid = $_POST['cid'];
    $lno = $_POST['lno'];

    $data = $obj->delete('tbl_mu_thubnail', 'WHERE cid = '.$cid.' AND lno = '.$lno);
    

    for ($i = 0; $i < count($array); $i++) {
        $split = explode("-", $array[$i]);

        $new_array[$i]['lno'] = $lno;
        $new_array[$i]['cid'] = $cid;
        $new_array[$i]['pid'] = $split[0];
        $new_array[$i]['ptime'] = $split[1];
        $new_array[$i]['ppage'] = $split[2];
    }
    print_r($new_array);
    $count = count($new_array) - 1;
    for ($x = 0; $x <= $count; $x++) {
        
        $obj->insert("tbl_mu_thubnail", $new_array[$x]);
    }

    echo "Success";
}

//SAVE THUMBNAIL DETAILS INTO DB (FROM STEP 2)
function saveThumbnail() {
    $obj = new obj;
    $array = $_POST['thumbnail_data'];
    $cid = $_POST['cid'];

    $lecture_info = $obj->singleData($cid, 'cid', 'tbl_lc_lecture', 'ORDER BY lno DESC limit 1');
    extract($lecture_info);

    for ($i = 0; $i < count($array); $i++) {
        $split = explode("-", $array[$i]);

        $new_array[$i]['lno'] = $lno;
        $new_array[$i]['cid'] = $cid;
        $new_array[$i]['pid'] = $split[0];
        $new_array[$i]['ptime'] = $split[1];
        $new_array[$i]['ppage'] = $split[2];
    }
    print_r($new_array);
    $count = count($new_array) - 1;
    for ($x = 0; $x <= $count; $x++) {
        
        $obj->insert("tbl_mu_thubnail", $new_array[$x]);
    }

    echo "Success";
}



function saveThumbnailedit($s) {
    $obj = new obj;
    $array = $_POST['thumbnail_data'];
    $cid = $s;

    $lecture_price = $obj->singleData($cid, 'cid', 'tbl_lc_lecture', 'ORDER BY lno DESC limit 1');
    extract($lecture_price);

    for ($i = 0; $i < count($array); $i++) {
        $split = explode("-", $array[$i]);

        $new_array[$i]['lno'] = $lno;
        $new_array[$i]['cid'] = $cid;
        $new_array[$i]['pid'] = $split[0];
        $new_array[$i]['ptime'] = $split[1];
        $new_array[$i]['ppage'] = $split[2];
    }

    $count = count($new_array) - 1;
    for ($x = 0; $x <= $count; $x++) {
        $obj->insert("tbl_mu_thubnail", $new_array[$x]);
    }

    echo "Success";
}

function insertPrice() {
    $obj = new obj;
    $lecprice = $_POST['lecture_price'];
    $cid = $_POST['cid'];
    $lecture_price = $obj->singleData($cid, 'cid', 'tbl_lc_lecture', 'ORDER BY lno DESC limit 1');
    extract($lecture_price);
    $where = "where lno = " . $lno;
    $form_data = array('price' => $lecprice);
    $obj->update("tbl_lc_lecture", $form_data, $where);

    echo "Success";
}

function uploadMashup() {
    $obj = new obj;
    $lectype = $_POST['lecture_type'];
    $lecture_name = $_POST['lectureName'];
    $intro_data = $_POST['intro_data'];
    $lecture_movie = $_POST['lectureMovie'];
    $movie_time = $_POST['movieTime'];
    $movie_time = gmdate("H:i:s", $movie_time);
    $lecture_pdf = $_POST['lecturePdf'];
    $cid = $_POST['cid'];

    //MOVIE AND PDF
    if ((isset($_FILES["lcMovie"]["type"])) && (isset($_FILES["lcPdf"]["type"]))) {
        //MOVIE        
        $validextensions = array("flv", "mov", "mp4");
        $temporary = explode(".", $_FILES["lcMovie"]["name"]);
        $file_extension = end($temporary);
        $lecture_movie = md5(rand(1, 9999999999)) . '.' . $file_extension; //RANDOM FILENAME
        //PDF
        $validextensions_pdf = array("pdf");
        $temporary_pdf = explode(".", $_FILES["lcPdf"]["name"]);
        $file_extension_pdf = end($temporary_pdf);
        $lecture_pdf = md5(rand(1, 9999999999)) . '.' . $file_extension_pdf; //RANDOM FILENAME
        
        if ((($_FILES["lcMovie"]["size"] < 100000000) && in_array($file_extension, $validextensions)) && (($_FILES["lcPdf"]["size"] < 100000000) && in_array($file_extension_pdf, $validextensions_pdf))) {
            if((file_exists("../media/movie/" . $lecture_movie)) && (file_exists("../media/pdf/" . $lecture_pdf))) {
                echo "Movie Already Exists!";                
            }
            else {
                //MOVE MOVIE TO MEDIA FOLDER
                $sourcePath = $_FILES['lcMovie']['tmp_name']; // Storing source path of the file in a variable                
                $targetPath = "../media/video/" . $lecture_movie;        
                move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file
                chmod($targetPath, 0777);
                
                //PDF
                $sourcePath2 = $_FILES['lcPdf']['tmp_name']; // Storing source path of the file in a variable
                $targetPath2 = "../media/pdf/" . $lecture_pdf;
                move_uploaded_file($sourcePath2, $targetPath2); // Moving Uploaded file
                chmod($targetPath2, 0777);                              

                //GET TOTAL NUMBER OF PAGES - PDF
                $file = $targetPath2;
                $page_num = exec("pdfinfo $file | grep Pages: | awk '{print $2}'");
                $_SESSION['page_num'] = $page_num;

                //RANDOM NAME FOR FOLDER
                $thumb_random_foldername = time().''.rand(1, 10000);
                $_SESSION['thumb_foldername'] = $thumb_random_foldername;
                $thumb_path = '../img/thumbnails/' . $thumb_random_foldername;                
                mkdir($thumb_path, true);
                chmod($thumb_path, 0777);

                //GENERATE THUMBNAIL
                for ($i=0; $i < $page_num; $i++) {
                    //$targetPath2 IS WHERE THE UPLOADED PDF FILE LOCATED
                    //$thumb_path IS WHERE THE THUMBNAIL IMAGES WILL BE LOCATED
                    makethumbnail($i, $targetPath2, $thumb_path);
                }

                //SAVE TO DB
                $form_data = array("lname" => $lecture_name, "intro_data" => $intro_data, "cid" => $cid, "ltype" => $lectype, "folder_name" => $thumb_random_foldername, "ldata1" => $lecture_movie, "prg_time" => $movie_time, "ldata2" => $lecture_pdf);
                $obj->insert("tbl_lc_lecture", $form_data);

                //RETURN MOVIE AND PDF FILE NAME ON SUCCESS
                $data[0] = "Success";
                $data[1] = $lecture_movie;
                $data[2] = $lecture_pdf;
                echo json_encode($data);
            }
        } 
        else {
            echo "Invalid movie file Size or Type!";
        }
    } 
    else{
        echo "Invalid";
    }
}

//GENERATE INDIVIDUAL IMAGES PER PDF PAGE AND SAVE TO FOLDER
function makethumbnail($page, $pdf_file, $thumb_path){
    $pdf_file   = $pdf_file;
    $thumb_page = $page + 1;
    $img_save_to = $thumb_path.'/thumbnail_' . $thumb_page . '.jpg';  

    $img = new imagick($pdf_file."[" . $page . "]");
    $img->scaleImage(76,100);
    $img->setImageFormat('jpg');
    $img = $img->flattenImages();
    $img->writeImage($img_save_to);
    chmod($img_save_to, 0777);
}

function addLecture() {
    $obj = new obj;
    $lectype = $_POST['lecture_type'];
    $lecture_name = $_POST['lecture_name'];
    $intro_data = $_POST['intro_data'];

    $file_name = $_POST['file_name'];
    $cid = $_POST['cid'];

    switch ($lectype) {
        case 'pdf':
        $validextensions = array("pdf");
        $folder_name = "pdf/";
        break;
        case 'p':
        $validextensions = array("ppt");
        $folder_name = "ppt/";
        break;
        case 'v':
        $validextensions = array("mp3", "wav");
        $folder_name = "audio/";
        break;
        case 'm':
        $validextensions = array("mp4", "mov", "flv");
        $folder_name = "video/";
        break;
    }

    if (isset($_FILES["filetoUpload"]["type"])) {        
        $temporary = explode(".", $_FILES["filetoUpload"]["name"]);
        $file_extension = end($temporary);
        //RANDOM FILENAME   
        $file_name = md5(rand(1, 9999999999)) . '.' . $file_extension;    

        if (($_FILES["filetoUpload"]["size"] < 100000000) && in_array($file_extension, $validextensions)) {
            if ($_FILES["filetoUpload"]["error"] > 0) {
                echo "Return Code: " . $_FILES["filetoUpload"]["error"] . "<br/><br/>";
            } else {
                if (file_exists("../media/" . $folder_name . $file_name)) {
                    echo "File Already Exists!";
                } else {
                    $sourcePath = $_FILES['filetoUpload']['tmp_name']; // Storing source path of the file in a variable
                    //$sourcePath = $file_name;
                    $targetPath = "../media/" . $folder_name . $file_name; // Target path where file is to be stored
                    move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file
                    chmod($targetPath, 0777);
                    //SAVE TO DB
                    $form_data = array("lname" => $lecture_name, "intro_data" => $intro_data, "cid" => $cid, "ltype" => $lectype, "ldata1" => $file_name);
                    $obj->insert("tbl_lc_lecture", $form_data);
                    echo "Success";
                }
            }
        } else {
            echo "Invalid file Size or Type!";
        }
    } 
    else {
        echo "Invalid";
    }
}

function sendMail() {
    $data = true;
    $to = 'fatima_dnl21@yahoo.com';
    $subject = 'Retrieve Password';
    $message = 'Sending your password.';
    $headers = 'From: lectureclip.COM <no-reply@lectureclip.com>' . "\r\n" . 'Reply-To: lectureclip.COM <no-reply@lectureclip.com>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    exit(json_encode($data));
}

function checkUserEmail() {
    $email = $_POST['email'];
    $data = false;
    $user = new obj;
    $data = $user->singleData($email, "email", "tbl_ut_pass");
    exit(json_encode($data));
}

function checkUserAPI() {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $data[0] = false;
    $user = new obj;
    $data_ = $user->singleData($email, "email", "tbl_ut_pass");
    extract($data_);
    if (password_verify($password, $pwd)) {
        $data[0] = $user->checkUser($email, $pwd, 'tbl_ut_pass');
    }
    //exit(json_encode($data));  
    $_SESSION['uid'] = $uid;
 
    $data[1] = $_SERVER["HTTP_REFERER"];
    echo json_encode($data);  
}

function saveFBdetails() {
    $obj = new obj;
    $data = false;    

    $_SESSION['fb_login'] = true;
    $_SESSION['fb_lastname'] = $_POST['fb_lastname'];    
    $_SESSION['fb_firstname'] = $_POST['fb_firstname']; 
    $_SESSION['fb_email'] = $_POST['fb_email'];
    $_SESSION['fb_pic'] = $_POST['fb_pic'];

    //CHECK IF EMAIL EXISTS
    $checkUser = $obj->singleData($_SESSION['fb_email'], "email", "tbl_ut_pass");    
    if($checkUser != NULL){
        extract($checkUser);
        //IF ALREADY HAVE LC ACCOUNT
        if($usertype == 1){
            $usertype = 3;
            $fb_update = array('usertype' => $usertype);                                
            $obj->update('tbl_ut_pass', $fb_update, "WHERE uid = ".$uid); 
            $_SESSION['uid'] = $uid;
        }
        //ALREADY HAVE FB OR LC ACCOUNT
        else{
            $_SESSION['uid'] = $uid;
        }
    }
    //EMAIL DOES NOT EXIST
    else{
        $usertype = 2;
        $obj->newReg($_SESSION['fb_firstname'], $_SESSION['fb_lastname'], "tbl_ut_user");
        foreach ($obj->showLastId("tbl_ut_user", "uid") as $value) {
            extract($value);
            $_SESSION['uid'] = $value['uid'];
        } 
        $fb_data = array('uid' => $uid, 'email' => $_SESSION['fb_email'], 'usertype' => $usertype);                                
        $obj->insert('tbl_ut_pass', $fb_data);  
    }

    $_SESSION['user_lastname'] = $_POST['fb_lastname'];    
    $_SESSION['user_firstname'] = $_POST['fb_firstname']; 
    $_SESSION['user_pic'] = $_POST['fb_pic'];
    
    $data = $_SESSION['uid'];
    echo json_encode($data);  
}