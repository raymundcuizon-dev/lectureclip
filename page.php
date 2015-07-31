<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $page_title; ?></title>

    <!-- some custom CSS -->
    <style>
    .left-margin{
        margin:0 .5em 0 0;
    }

    .right-button-margin{
        margin: 0 0 1em 0;
        overflow: hidden;
    }
    </style>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    </head>
    <body>
        <div class="container">
            <?php
            /*
             * Developer: Rex Adrivan
             * Description: OOP pagination class
             */

            class Pagination {

                private $localhost = "localhost";
                private $username = "root";
                private $password = "root";
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

                function readAll($page, $from_record_num, $records_per_page) {
                    $query = "SELECT * FROM tbl_ut_user LIMIT  {$from_record_num}, {$records_per_page}";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();

                    return $stmt;
                }

            }
            // page given in URL parameter, default page is one
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            // set number of records per page
            $records_per_page = 5;
            // calculate for the query LIMIT clause
            $from_record_num = ($records_per_page * $page) - $records_per_page;

            $pag = new Pagination();

            $stmt = $pag->readAll($page, $from_record_num, $records_per_page);
            $num = $stmt->rowCount();
            // display the products if there are any
            if ($num > 0) {

                echo "<table class='table table-hover table-responsive table-bordered'>";
                echo "<tr>";
                echo "<th>Product</th>";
                echo "</tr>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    extract($row);

                    echo "<tr>";
                    echo "<td>{$name1}</td>";
                    echo "</tr>";
                }

                echo "</table>";

                // paging buttons will be here
                $page_dom = "page.php";

                echo "<ul class=\"pagination\">";

// button for first page
                if ($page > 1) {
                    echo "<li><a href='{$page_dom}' title='Go to the first page.'>";
                    echo "<<";
                    echo "</a></li>";
                }

// count all products in the database to calculate total pages
                $total_rows = 12;
                $total_pages = ceil($total_rows / $records_per_page);

// range of links to show
                $range = 2;

// display links to 'range of pages' around 'current page'
                $initial_num = $page - $range;
                $condition_limit_num = ($page + $range) + 1;

                for ($x = $initial_num; $x < $condition_limit_num; $x++) {

                    // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
                    if (($x > 0) && ($x <= $total_pages)) {

                        // current page
                        if ($x == $page) {
                            echo "<li class='active'><a href=\"#\">$x <span class=\"sr-only\">(current)</span></a></li>";
                        }

                        // not current page
                        else {
                            echo "<li><a href='{$page_dom}?page=$x'>$x</a></li>";
                        }
                    }
                }

// button for last page
                if ($page < $total_pages) {
                    echo "<li><a href='" . $page_dom . "?page={$total_pages}' title='Last page is {$total_pages}.'>";
                    echo ">>";
                    echo "</a></li>";
                }

                echo "</ul>";
            }

// tell the user there are no products
            else {
                echo "<div>No products found.</div>";
            }
            ?>
        </div>
    </body>
    </html>