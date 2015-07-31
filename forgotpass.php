<?php

class obj {

    private $localhost = "localhost";
    private $username = "root";
    private $password = "e@sycomph";
    private $database = "db_lectureclip";
    private $conn;

    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->localhost . ";dbname=" . $this->database, $this->username, $this->password, array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->conn->exec("set names utf8");
        } catch (Exception $e) {
            echo "Database Connection Error! " . $e->getMessage();
        }
    }

    public function insert($table_name, $form_data = NULL) {
        $fields = implode(',', array_keys($form_data));
        $value = implode(',', array_fill(0, count($form_data), '?'));
        $sth = $this->conn->prepare("INSERT INTO $table_name ($fields) VALUES ($value)");
        $sth->execute(array_values($form_data));
        return true;
    }

}

$obj = new obj();

$code = substr(md5(rand()), 0, 45);

if (isset($_POST['submit'])) {

    $to = $_POST['email'];
    $subject = 'the subject';
    $message = 'http://raymund.com/lectureclip/resetpass.php?d=' . $code;
    $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

    $data = array('gen_code' => $code);
    $obj->insert("tbl_forgot_pass_log ", $data);
    mail($to, $subject, $message, $headers);
    echo 'send';
}
?>

<form action="" method="post">
    <input type="text" name="email">
    <input type="submit" name="submit">
</form>
