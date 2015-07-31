<?php
include_once("config.php");
include_once("paypal.class.php");

function __autoload($class) {
	include_once 'lib/' . $class . '.php';
}
$obj = new obj();

$paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';
if($_POST)
{	
	$cno = $_SESSION['add_cart'];
	$col = array('tbl_lc_course.cid', 'tbl_lc_course.intro_data as c_intro_data', 'tbl_lc_course.title', 'tbl_lc_course.course_img', 'tbl_lc_course.price as c_price', 'tbl_lc_lecture.ltype', 'tbl_lc_lecture.lname', 'tbl_lc_lecture.intro_data as l_intro_data', 'tbl_lc_lecture.price as l_price', 'tbl_lc_lecture.prg_time', 'tbl_otwk_cart.p_target');
	$table = 'tbl_otwk_cart	LEFT JOIN tbl_lc_course ON tbl_otwk_cart.cid = tbl_lc_course.cid LEFT JOIN tbl_lc_lecture ON tbl_otwk_cart.lno = tbl_lc_lecture.lno LEFT JOIN tbl_ut_user ON tbl_otwk_cart.tip = tbl_ut_user.uid';
	$where = 'WHERE cno =  '.$cno;	
	$data = $obj->select_w_join_2($col, $table, $where);
	extract($data);
	
	$item_total = 0;
	$subtotal = 0;
	$stringConnect = "";
	$ctr = 0;
	$item_list = array();
	foreach ($data as $value)
	{
		if($value['p_target'] == 'L'){
			$stringConnect = $stringConnect . '&L_PAYMENTREQUEST_0_NAME' . $ctr . '='.urlencode($value['lname']).
			'&L_PAYMENTREQUEST_0_NUMBER' . $ctr . '='.urlencode($value['lno']).
			'&L_PAYMENTREQUEST_0_DESC' . $ctr . '='.urlencode($value['lname']).
			'&L_PAYMENTREQUEST_0_AMT' . $ctr . '='.urlencode($value['l_price']).
			'&L_PAYMENTREQUEST_0_QTY' . $ctr . '='. urlencode(1);
			$subtotal = ((float)$value['l_price'] * 1);
			$item_total = ($item_total + $subtotal);
			$ctr++;
			$temp_item_array = array();
			$temp_item_array['name'] = $value['lname'];
			$temp_item_array['number'] = $value['lno'];
			$temp_item_array['desc'] = $value['lname'];
			$temp_item_array['amt'] = $value['l_price'];
			$temp_item_array['qty'] = 1;
			array_push($item_list, $temp_item_array);
		} else {
			$stringConnect = $stringConnect . '&L_PAYMENTREQUEST_0_NAME' . $ctr . '='.urlencode($value['title']).
			'&L_PAYMENTREQUEST_0_NUMBER' . $ctr . '='.urlencode($value['cid']).
			'&L_PAYMENTREQUEST_0_DESC' . $ctr . '='.urlencode($value['title']).
			'&L_PAYMENTREQUEST_0_AMT' . $ctr . '='.urlencode($value['c_price']).
			'&L_PAYMENTREQUEST_0_QTY' . $ctr . '='. urlencode(1);
			$subtotal = ((float)$value['c_price'] * 1);
			$item_total = ($item_total + $subtotal);
			$ctr++;
			$temp_item_array = array();
			$temp_item_array['name'] = $value['title'];
			$temp_item_array['number'] = $value['cid'];
			$temp_item_array['desc'] = $value['title'];
			$temp_item_array['amt'] = $value['c_price'];
			$temp_item_array['qty'] = 1;
			array_push($item_list, $temp_item_array);
		}
	}

	
	$GrandTotal = $item_total;
	$padata = 	'&METHOD=SetExpressCheckout'.
	'&RETURNURL='.urlencode($PayPalReturnURL ).
	'&CANCELURL='.urlencode($PayPalCancelURL).
	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
	$stringConnect.'&NOSHIPPING=1'.'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($item_total).
	'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
	'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
	'&LOCALECODE=GB'.'&CARTBORDERCOLOR=FFFFFF'.'&ALLOWNOTE=1';
	$_SESSION['ItemList'] = $item_list;
	$_SESSION['ItemTotalPrice'] 	=  $item_total; //(Item Price x Quantity = Total) Get total amount of product; 	
	$_SESSION['GrandTotal'] 		=  $GrandTotal;
	$paypal= new MyPayPal();		
	$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
	{
		$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	}else{		?>
	<?php $form = new validator();

	$mail = new PHPMailer;


	if (isset($_GET['q'])) {
		$obj->logout();
	}
	function redir() {
		if (!$_COOKIE['lc_login_id']) {
       // echo "<script>alert('Login first.');</script>";
			echo "<script>window.location.replace('index.php');</script>";
		}
	}
	function test($test_value){
		echo "<pre>";
		print_r($test_value);
		echo "</pre>";
	}
	?>
	<!DOCTYPE html>
	<html lang="ja" prefix="og: http://ogp.me/ns#">
	<head>
		<meta charset="UTF-8">
		<title>レクチャークリップ</title>
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="robots" content="all">
		<meta property="og:title" content="レクチャークリップ">
		<meta property="og:type" content="website">
		<meta property="og:url" content="./">
		<meta property="og:image" content="img/common/icon_facebook.jpg">
		<link rel="shortcut icon" href="img/common/favicon.ico">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/page.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link href="css/jquery.bxslider.css" rel="stylesheet">
		<link href="./skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet">

		<style>
			#display-error
			{
				width: 730px;
				min-height: 40px;
				border: 1px solid #D8D8D8;
				padding: 5px;
				margin-bottom: 3px;
				border-radius: 5px;
				font-family: Arial;
				font-size: 11px;
				text-transform: uppercase;
				background-color: rgb(255, 249, 242);
				color: rgb(211, 0, 0);
			}
			#display-error img
			{
				float: left;
				height: 25px;
				width: 25px;
			}
			.user-image{
				height: 40px;
				width: 40px;
				/* padding: 1px; */
			}
			.success{
				border: 1px solid;
				margin: 10px 0px;
				padding:15px 10px 15px 50px;
				background-repeat: no-repeat;
				background-position: 10px center;
				color: #4F8A10;
				background-color: #DFF2BF;
				/*background-image:url('success.png'); */
			}
			.error_mess{
				border: 1px solid;
				margin: 10px 0px;
				padding:15px 10px 15px 50px;
				background-repeat: no-repeat;
				background-position: 10px center;
				color: #BE0600;
				background-color: #FFCBAA;
			}
		</style>
        <!--[if lte IE 9]>
        <script src="js/html5shiv.js"></script>
        <!--<![endif]-->
        <script>
        	if (window.navigator.userAgent.toLowerCase().indexOf("msie") > -1) {
        		document.documentElement.className += 'ie';
        	}
        </script>
        <script src="js/jquery-1.10.1.min.js"></script>
        <script src="js/jquery.leanModal.min.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script src="js/common.js"></script>
        <script src="js/checkbox.js"></script>
        <!-- ▼スライダー -->
        <script src="js/jquery.bxslider.min.js"></script>
        <script src="js/jquery.media.js"></script>
        <script src="js/jquery.jplayer.min.js"></script>
        <script src="js/waypoints.min.js"></script>
        <script src="js/waypoints-sticky.min.js"></script>      
        <script>
        	var ua, isIE, array, version;

        // UserAgetn を小文字に正規化
        ua = window.navigator.userAgent.toLowerCase();

        // IE かどうか判定
        isIE = (ua.indexOf('msie') >= 0 || ua.indexOf('trident') >= 0);

        // IE の場合、バージョンを取得
        if (isIE) {
        	document.documentElement.className += "ie";
        }
    </script>  
    <script>
    	$(function () {
    		$('.bxslider').bxSlider({
    			auto: true,
    			speed: 1000,
    			mode: 'fade',
    			captions: false,
    			pager: false,
    		});
    	});
    </script>
    <script>
    	$(function () {
    		$('.my-sticky-element').waypoint('sticky');
    	});
    </script>
    <script type="text/javascript">
    	$(function () {
    		$('a[rel*=leanModal]').leanModal({
                                top: 80, // モーダルウィンドウの縦位置を指定
                                overlay: 0.7, // 背面の透明度 
                                closeButton: ".closeBtn"  // 閉じるボタンのCSS classを指定
                            });
    	});
            //Cookieに情報があれば遷移（暫定処理）
            //locationURL();
        </script>

        <!-- for course details -->
        <script>
        	$(function(){
        		$('.carousel').bxSlider({
        			auto: false,
        			speed: 1000,
                        displaySlideQty: 3, //一画面に表示する数
            moveSlideQty: 3, //移動時にずれる数
            prevText: '<',
            nextText: '>',
            pager: false,
            slideWidth: 230,
            minSlides: 3,
            maxSlides: 3,
            slideMargin: 20,
                        infiniteLoop: true, //ループさせるか否か
                        hideControlOnEnd: true
                    });
        	});
        </script>

    </head>
    <?php 
    $basename =  basename($_SERVER['SCRIPT_NAME']);
    ?>
    <body class="<?php 
    if($basename == 'course_detail.php') { echo 'course_detail'; } 
    elseif($basename == 'confirm.php'){ echo 'confirm';} 
    elseif($basename == 'cl_lecture_list.php') { echo 'cl_lecture_list'; } 
    elseif($basename == 'cl_edit.php') { echo 'course_detail'; } 
    elseif ($basename == 'cart.php') {echo 'cart';} 
    elseif($basename == 'complete.php'){echo 'complete';}
    elseif ($basename == 'purchase_list.php') { echo 'purchase_list '; }
    elseif ($basename == 'review_list.php') { echo 'review_list'; } 
    elseif ($basename == 'create_course.php') { echo 'create_course'; }
    elseif ($basename == 'edit_mu.php') { echo 'create_course'; }
    elseif ($basename == 'course_edit.php') { echo 'create_info'; }?> "?>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
    	var js, fjs = d.getElementsByTagName(s)[0];
    	if (d.getElementById(id)) return;
    	js = d.createElement(s); js.id = id;
    	js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.0";
    	fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<div id="wrap">
	<?php if (!$_COOKIE['lc_login_id']) { ?>
	<header>
		<div class="inner">
			<h1><a href="/"><img src="img/common/logo.gif" alt="レクチャークリップ"></a></h1>
			<nav id="hmenu">
				<ul class="clearfix">
					<li><a href="#">LecutreClipとは <?php //echo $_SESSION['login'];       ?></a></li>
					<li><a href="#">ご利用方法</a></li>
					<li><a href="#">ヘルプ</a></li>
					<li class="login"><a rel="leanModal" href="#m_login">ログイン</a></li>
				</ul>
			</nav>
			<p class="newMember">
				<a rel="leanModal" href="#m_registration" onclick="javascript:displayControl('#m_login', '#lean_overlay', '', '', 0);">新規登録(無料)</a>
			</p>
		</div>
	</header><!-- /header -->
	<?php } else { ?>
	<header>
		<div class="inner">
			<h1><a href="./"><img src="img/common/logo.gif" alt="レクチャークリップ"></a></h1>
			<nav id="hmenu">
				<ul class="clearfix">
					<li><a href="#">LecutreClipとは <?php // echo $_SESSION['login'];       ?></a></li>
					<li><a href="#">ご利用方法</a></li>
					<li><a rel="leanModal" href="#m_createCourse">コース作成</a></li>
					<li><a href="#">ヘルプ</a></li>
					<li><a href="?q=logout">Logout</a></li>
				</ul>
			</nav>
			<ul id="hbtns" class="clearfix">
				<li class="cart"><a href="cart.php"><span class="icon-shopping2"></span>カートを見る</a></li>
				<?php
				$img_email = $obj->singleData($_COOKIE['lc_login_id'], 'email', 'tbl_ut_pass');
				extract($img_email);
				$img_user = $obj->singleData($uid, 'uid', 'tbl_ut_user');
				extract($img_user);
				?>
				<li class="loginInfo"><a href="profile_edit.php"><span><img class="user-image"  src="img/user/<?= $profile_img; ?>" alt="栗富 太郎太郎"></span><?php echo ucfirst($name2) . " " . ucfirst($name1); ?></a></li>
			</ul>
		</div>
	</header><!-- /header -->
	<?php } ?>
	<nav id="gnav" class="my-sticky-element">
		<ul class="clearfix inner">
			<li class="home"><a href="./" class="active"><span class="icon-home2"><img src="img/common/logo_h.gif" alt="レクチャークリック"></span></a></li>
			<li><a href="">カテゴリー</a>
				<ul>
					<?php foreach ($obj->readall("tbl_m_category", "order by catname ASC") as $categorylist): extract($categorylist); ?>
						<li><a href="category.php?catid=<?= $catid ?>"><?= $catname; ?></a></li>
					<?php endforeach; unset($categorylist); ?>
				</ul>
			</li>
			<li><a href="free.php">無料</a></li>
			<li><a href="top_chart.php">トップチャート</a></li>
			<li><a href="new.php">新着</a></li>
			<li><a href="mypage.php">マイページ</a></li>
		</ul>
	</nav><!-- /#gnav -->
</div>
<?php	echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
//echo '<pre>';
//print_r($httpParsedResponseAr);
//echo '</pre>';
include 'footer.php';
}
}

?>
