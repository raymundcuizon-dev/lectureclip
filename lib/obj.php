<?php

class obj {

    private $localhost = "localhost";
    private $username = "root";
    // private $password = "e@sycomdatabase";
    private $password = "root";
    private $database = "db_lectureclip";
    private $conn;
    public $NickName;
    public $Password;
    public $UserPasswordConfirm;
    public $LastName;
    public $FirstName;
    public $FuriOne;
    public $FuriTwo;
    public $UserMail;
    public $Gender;
    public $Birthday;
    public $Job;
    public $Zipcode;
    public $State;
    public $City;
    public $Addr1;
    public $Addr2;
    public $Usertel;
    public $Usertel2;
    public $infoMail;
    public $profile_img;
    public $age;
    public $pwd;
    private $formKey;
    private $old_formKey;

    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->localhost . ";dbname=" . $this->database, $this->username, $this->password, array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->conn->exec("set names utf8");
        } catch (\PDOException $e) {
            echo "Database Connection Error! " . $e->getMessage();
        }

        if (isset($_SESSION['form_key'])) {
            $this->old_formKey = $_SESSION['form_key'];
        }
    }

    //comment try
    public function newReg($name1, $name2, $table) {
        $insert = "insert into $table set name1 = :name1 , name2 = :name2";
        $statement = $this->conn->prepare($insert);
        $statement->execute(array(':name1' => $name1, ':name2' => $name2));
        return true;
    }

    public function updatePassword($uid) {
        $update = "update tbl_ut_pass set pwd = :pwd where uid = $uid";
        $s = $this->conn->prepare($update);
        $s->bindParam(':pwd', $this->pwd);
        $s->execute();
        return true;
    }

// you can use this function to select data using id
    public function showLastId($table, $id) {
        $query = "SELECT * FROM $table order by $id desc limit 1";
        $q = $this->conn->query($query);
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $r;
        }
        return $data;
    }

//for new registration. 
    public function newRegEmail($uid, $pwd, $email, $usertype, $table) {
        $insert = "insert into $table set uid = :uid, pwd = :pwd , email = :email, usertype = :usertype";
        $statement = $this->conn->prepare($insert);
        $statement->execute(array(':uid' => $uid, ':pwd' => $pwd, ':email' => $email, ':usertype' => $usertype));
        return true;
    }

//finaliezed 
    public function finalized($uid, $table) {
        $update = "update  $table set nic = :nic, name1 = :name1, name2 = :name2, furi1 = :furi1, furi2 = :furi2, birthday = :birthday, gender = :gender, job = :job, zipcd = :zipcd, state = :state, city = :city, addr1 = :addr1, addr2 = :addr2, tel = :tel, tel2 = :tel2 where uid = $uid";
        $s = $this->conn->prepare($update);
        $s->bindParam(':nic', $this->NickName);
        $s->bindParam(':name1', $this->LastName);
        $s->bindParam(':name2', $this->FirstName);
        $s->bindParam(':furi1', $this->FuriOne);
        $s->bindParam(':furi2', $this->FuriTwo);
        $s->bindParam(':birthday', $this->Birthday);
        $s->bindParam(':gender', $this->Gender);
        $s->bindParam(':job', $this->Job);
        $s->bindParam(':zipcd', $this->Zipcode);
        $s->bindParam(':state', $this->State);
        $s->bindParam(':city', $this->City);
        $s->bindParam(':addr1', $this->Addr1);
        $s->bindParam(':addr2', $this->Addr2);
        $s->bindParam(':tel', $this->tel);
        $s->bindParam(':tel2', $this->tel2);
        $s->execute();
        return true;
    }

    public function profileupdate($uid, $table) {
        $update = "update $table set age = :age, gender = :gender, profile_img = :profile_img where uid = $uid";
        $s = $this->conn->prepare($update);
        $s->bindParam(':age', $this->age);
        $s->bindParam(':gender', $this->Gender);
        $s->bindParam(':profile_img', $this->profile_img);
        $s->execute();
        return true;
    }


    public function category($catid = NULL, $page = NULL, $from_record_num = NULL, $records_per_page = NULL) {
        $category = "select tbl_lc_course.cid,
        tbl_lc_course.title,
        tbl_lc_course.course_img,
        tbl_lc_course.price as c_price,
        tbl_ut_user.name1,
        tbl_ut_user.name2,
        tbl_lc_lecture.price as l_price from tbl_lc_course inner join tbl_ut_user on tbl_lc_course.uid = tbl_ut_user.uid 
        inner join tbl_lc_lecture on tbl_lc_course.cid = tbl_lc_lecture.cid
        where tbl_lc_course.publish_flg = 1 AND tbl_lc_course.catid = $catid group by cid LIMIT {$from_record_num}, {$records_per_page} ";
        $statement = $this->conn->query($category);
        while ($r = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $r;
        }
        return $data;
    }

    //SELECT COURSES PER CATEGORY
    public function category2($catid = NULL) {
        $category = "select tbl_m_category.catname,
        tbl_lc_course.cid,
        tbl_lc_course.title,
        tbl_lc_course.course_img,
        tbl_lc_course.price as c_price,
        tbl_ut_user.name1,
        tbl_ut_user.name2,
        tbl_lc_lecture.price as l_price
        from tbl_lc_course 
        inner join tbl_ut_user on tbl_lc_course.uid = tbl_ut_user.uid 
        inner join tbl_lc_lecture on tbl_lc_course.cid = tbl_lc_lecture.cid
        inner join tbl_m_category on tbl_lc_course.catid = tbl_m_category.catid
        where tbl_m_category.catid = $catid AND tbl_lc_course.price != 0 group by cid";
        $statement = $this->conn->query($category);
        while ($r = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $r;
        }
        return $data;
    }

    public function category_slider(){
        $category = 'SELECT tbl_lc_course.cid, tbl_lc_course.title, tbl_lc_course.course_img, tbl_lc_course.price AS c_price, tbl_ut_user.name1, tbl_ut_user.name2, tbl_lc_lecture.price AS l_price
        FROM tbl_lc_course
        INNER JOIN tbl_ut_user ON tbl_lc_course.uid = tbl_ut_user.uid
        INNER JOIN tbl_lc_lecture ON tbl_lc_course.cid = tbl_lc_lecture.cid WHERE catid is NOT NULL
        GROUP BY cid';
        $statement = $this->conn->query($category);
        while ($r = $statement->fetch()) {
            $data[] = $r;
        }
        return $data;        
    }

    public function checkUser($email, $pwd, $table) {
        $statement = $this->conn->prepare("SELECT * FROM $table where pwd = :pwd AND email = :email");
        $statement->execute(array(':email' => $email, ':pwd' => $pwd));
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    public function resize_upload($tmpFile, $img, $size_1,$size_2){ 
        $thumb = new Imagick($tmpFile);
        $thumb->resizeImage($size_1,$size_2,Imagick::FILTER_LANCZOS,1);
        $thumb->writeImage($img);
        $thumb->destroy();
    }
    function resize($width, $height, $img, $folder, $destination) {
        list($w, $h) = getimagesize($_FILES['image']['tmp_name']);
        $ratio = max($width / $w, $height / $h);
        $h = ceil($height / $ratio);
        $x = ($w - $width / $ratio) / 2;
        $w = ceil($width / $ratio);
        $path = $folder . '' . $img;
        $imgString = file_get_contents($_FILES['image']['tmp_name']);
        $image = imagecreatefromstring($imgString);
        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
        switch ($_FILES['image']['type']) {
            case 'image/jpeg':
            imagejpeg($tmp, $path, 100);
            break;
            case 'image/png':
            imagepng($tmp, $path, 0);
            break;
            case 'image/gif':
            imagegif($tmp, $path);
            break;
            default:
            exit;
            break;
        }
        return $path;
        imagedestroy($image);
        imagedestroy($tmp);
    }

    public function list_course($cid) {
        $query = "select tbl_lc_lecture.lno, tbl_lc_lecture.status, tbl_lc_lecture.ltype, tbl_lc_lecture.lname, tbl_lc_lecture.prg_time, tbl_lc_lecture.price, tbl_lc_course.uid from tbl_lc_course LEFT JOIN tbl_lc_lecture ON tbl_lc_course.cid = tbl_lc_lecture.cid WHERE tbl_lc_course.cid = {$cid} && tbl_lc_lecture.status = 0";
        $statement = $this->conn->query($query);
        while ($r = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $r;
        }
        return $data;
    }

    //extract.
    public function singleData($data, $where, $table, $order = "") {
        $q = "select * from $table where $where = :data $order";
        $statement = $this->conn->prepare($q);
        $statement->execute(array(':data' => $data));
        $d = $statement->fetch(PDO::FETCH_ASSOC);
        return $d;
    }

    //extract
    public function doubleData($data, $where, $data2, $where2, $table, $order = "") { 
        $q = "select * from $table where $where = :data and $where2 = $data2 $order";
        $statement = $this->conn->prepare($q);
        $statement->execute(array(':data' => $data));
        $d = $statement->fetch(PDO::FETCH_ASSOC);
        return $d;
    }

    public function nextData($data, $where, $var1, $col, $table, $order = "") {
                //SELECT * FROM foo WHERE lno > 4 ORDER BY id LIMIT 1;
        $q = "select * from $table where $where > :data and $col = $var1  $order";
        $statement = $this->conn->prepare($q);
        $statement->execute(array(':data' => $data));
        $d = $statement->fetch(PDO::FETCH_ASSOC);
        return $d;
    }

    //foreach ($obj->userslist() as $value) {}
    public function userslist($where = "", $order = "", $limit = ""){
        $q = "SELECT tbl_ut_pass.email, tbl_ut_user.name1, tbl_ut_user.name2, tbl_ut_user.regdate, tbl_ut_user.update FROM tbl_ut_user LEFT JOIN tbl_ut_pass ON tbl_ut_user.uid = tbl_ut_pass.uid {$where} {$order} {$limit}";
        $stmt = $this->conn->query($q);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    //select with join
    public function select_w_join($table, $table_column, $join, $where_c, $where_1, $where_2 = NULL, $data = NULL, $limit = NULL){
       $data = implode(' , ', $data);
       $table = implode($join, $table);
       $table_column = implode(' = ', $table_column);
       $query = "SELECT $data FROM $table ON $table_column WHERE $where_c = $where_1 $where_2 $limit ";
       $stmt = $this->conn->query($query);
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
           $datas[] = $row;
       }
       return $datas;
   }

    public function sum($op = NULL, $table = NULL,  $where = NULL){
        $query = "select $op from $table $where";
        $result = $this->conn->query($query);
        $row = $result->fetch(PDO::FETCH_NUM);
        return $row[0];
    }

    public function select_w_join_2($data = NULL, $table, $where){
       $data = implode(' , ', $data);
       $query = "SELECT $data FROM $table $where";
       $stmt = $this->conn->query($query);
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
           $datas[] = $row;
       }
       return $datas;
           //var_dump($query);
    }
    //foreach ($obj->select_data_where() as $value) {}
    public function select_data_where($table, $where = NULL, $orderby = NULL){
        $read = "SELECT * FROM $table $where $orderby";
        $stmt = $this->conn->query($read);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }



    public function paging($page_dom = "", $records_per_page = "",$total_rows = "",$range = "", $page = ""){
        echo "<div class='page'><ul><li><a href='{$page_dom}'><span class='fa fa-fast-backward'></span></a></li>";
        $total_pages = ceil($total_rows / $records_per_page);
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range) + 1;
        for ($x = $initial_num; $x < $condition_limit_num; $x++) {
            if (($x > 0) && ($x <= $total_pages)) {
                if ($x == $page) {
                    echo "<li><a href='#'>$x</a></li>";
                } else {
                    echo "<li><a href='{$page_dom}page=$x' class='active'>$x</a></li>";
                }
            }
        }
        echo "<li><a href='" . $page_dom . "page={$total_pages}'><span class='fa fa-fast-forward'></span></a></li></ul></div>";
    }

    //foreach ($obj->readall() as $value) {}
    public function readall($table, $orderby = "") {
        $read = "SELECT * FROM $table $orderby";
        $stmt = $this->conn->query($read);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
   //count 
    public function count_lecture($table, $where = NULL){
        $query = "select count(*) from $table $where";
        $result = $this->conn->query($query);
        $row = $result->fetch(PDO::FETCH_NUM);
        return $row[0];
    }

    public function count_free($table, $where = NUL){
        $query = "select count(*) from $table $where";
        $result = $this->conn->query($query);
        $row = $result->fetch(PDO::FETCH_NUM);
        return $row[0];
    }
    //    

    public function insert($table_name, $form_data = NULL) {
        $fields = implode(',', array_keys($form_data));
        $value = implode(',', array_fill(0, count($form_data), '?'));
        $sth = $this->conn->prepare("INSERT INTO $table_name ($fields) VALUES ($value)");
        $sth->execute(array_values($form_data));
        return true;
    }

    public function update($table_name, $form_data = NULL, $where = "") {
        $sets = array();
        $statement = "UPDATE $table_name SET";
        foreach ($form_data as $key => $value) {
            $sets[] = " " . $key . " = '" . $value . "'";
        }
        $statement .= implode(' , ', $sets);
        $statement .= $where;
        $stmt = $this->conn->prepare($statement);
        $stmt->execute(array_values($form_data));
        return true;
    }

    public function delete($table_name, $where = "") {
        $this->conn->exec("DELETE FROM $table_name $where");
    }


    public function logout() {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach ($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time() - 1000);
            setcookie($name, '', time() - 1000, '/');
        }
        session_destroy();
        header("location:index.php");
    }

    //*This function is to avoid the multiple submition of the page*//

    private function generateKey() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $uniqid = uniqid(mt_rand(), true);
        return md5($ip . $uniqid);
    }

    public function outputKey() {
        $this->formKey = $this->generateKey();
        $_SESSION['form_key'] = $this->formKey;
        echo "<input type='hidden' name='form_key' id='form_key' value='" . $this->formKey . "' />";
    }

    public function validate() {
        if ($_POST['form_key'] == $this->old_formKey) {
            return true;
        } else {
            return false;
        }
    }

    public function makethumbnail($page, $pdf_file, $thumb_path){
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

    public function emptysession($target, $message){
        echo "<div id='contents'>
        <h1>".$message."</h1><br>
        <p class='btn_yellow'><a href=".$target." class='w390 fs18 h45'>Continue Shopping</a></p>
        </div>";
    }

}
