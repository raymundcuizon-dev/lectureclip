<?php
ob_start();
include 'header.php'; 
redir();
if (isset($_POST['send'])) {
	if(isset($_POST['payment'])){	
		header("Location: confirm.php");
	}	
}
if(isset($_POST['back'])){	
		header("Location: cart.php");
	}
ob_end_flush();

if(!isset($_SESSION['add_cart'])){ 
    $obj->emptysession("index.php","There are no items in this cart.");
} else { 

   $add_cart_count = $obj->count_lecture('tbl_otwk_cart', 'where cno = '.$_SESSION['add_cart']);
   if($add_cart_count == 0){ $obj->emptysession("index.php","There are no items in this cart."); } else { ?>

	<div id="contents" class="clearfix">
		<div class="inner">
			<div id="cartBox" class="clearfix">
				<ul>
					<li class="box">カートの中身</li>
					<li class="box active">支払い設定</li>
					<li class="box">購入内容の確認</li>
					<li class="boxEnd">購入完了</li>
				</ul>
			</div><!-- /[div#cartBox] -->
			
			<section class="cartInner">
				<h2 class="title">支払い設定</h2>				
				<form name="pay" action="" method="post">
				<div style="margin-left:90px;">
					<label for="test2">
						<input id="test2" type="radio" name="payment" value="paypal">
					    <img style="margin-left:10px; width:200px " src="img/common/paypal.jpg" alt="Paypal">					    
					</label>
				</div>				
				<ul class="btn">
                    <li class="btn_black" style="float:left; margin-left:90px;"><!--<a href="confirm.php">--><input type="submit" id="other" name="back" value="カートの中身へ戻る" class="other  fs18 w390 h50"><!--</a>--></li>
                    <li class="btn_red" style="float:left; margin-left:20px;"><!--<a href="confirm.php">--><input type="submit" id="send" name="send" value="購入内容の確認" class="send fs18 w390 h50"><!--</a>--></li>
                </ul>
				</form>
			</section>
			
			
		</div><!-- /.inner -->
		
	</div><!-- /#contents -->
	
    <?php } }   include 'footer.php'; ?>