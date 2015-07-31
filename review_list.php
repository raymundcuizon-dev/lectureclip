<?php 

include 'header.php'; redir();
$uid = $_SESSION['uid'];

$data2 = array(
	'tbl_ut_course.cid as cid', 
	'tbl_lc_course.title as cname',
	'tbl_lc_course.intro_data as cdetail',
	'(SELECT concat(name1, " " ,name2) from tbl_ut_user WHERE tbl_ut_user.uid = tbl_lc_course.uid) as tname',
	'tbl_lc_course.price as price',
	'(SELECT count(*) FROM tbl_lc_lecture where cid = tbl_lc_course.cid) as leccnt',
	'tbl_lc_course.course_img as cimg',
	'(SELECT catname from tbl_m_category WHERE catid = tbl_lc_course.catid) as catname',
	'tbl_ut_course.regdate as regdate');
$table2 = 'tbl_ut_course inner join tbl_lc_course on tbl_ut_course.cid = tbl_lc_course.cid';
$where2 = 'where tbl_ut_course.uid = '.$uid.' AND tbl_ut_course.con = "R" ORDER by tbl_lc_course.regdate DESC';
foreach ($obj->select_w_join_2($data2, $table2, $where2) as $s) {
	extract($s); $sm[] = $s;
}
file_put_contents('./json/review_'.$uid.'_course.json',json_encode($sm, JSON_PRETTY_PRINT));  



$data = array(
	'tbl_ut_lecture.cid as cid', 
	'tbl_ut_lecture.lno as lid', 
	'tbl_lc_lecture.prg_time as ltime',
	'(SELECT title from tbl_lc_course where cid = tbl_ut_lecture.cid) as cname',
	'(SELECT intro_data from tbl_lc_course where cid = tbl_ut_lecture.cid) as cdetail',
	'(SELECT concat(name1, " " ,name2) from tbl_ut_user WHERE tbl_ut_user.uid = tbl_lc_course.uid) as tname',
	'tbl_lc_lecture.lname as lname',
	'tbl_lc_course.course_img as cimg',
	'tbl_lc_lecture.price as price',
	'tbl_lc_lecture.ltype as ltype',
	'(SELECT catname from tbl_m_category WHERE catid = tbl_lc_course.catid) as catname',
	'tbl_ut_lecture.regdate as regdate');
$table = 'tbl_ut_lecture 
inner join tbl_lc_lecture on tbl_ut_lecture.lno = tbl_lc_lecture.lno
inner join tbl_lc_course on tbl_ut_lecture.cid = tbl_lc_course.cid';
$where = 'where tbl_ut_lecture.uid = '.$uid.' AND tbl_ut_lecture.con = "R" ORDER BY tbl_ut_lecture.regdate DESC';
foreach ($obj->select_w_join_2($data, $table, $where) as $ss) {
	extract($ss); $sn[] = $ss;
}
file_put_contents('./json/review_'.$uid.'_lecture.json',json_encode($sn, JSON_PRETTY_PRINT));  


$jsonpath = './json/review_'.$uid.'_course.json';
$jsonpath1 = './json/review_'.$uid.'_lecture.json';

?>
<div id="contents" class="clearfix">
	<div class="inner">
		<div id="mainContents" class="clearfix">
			<section>
				<h2 class="pageTitle">検討中リスト</h2>
				<form action="#">
					<div class="categoryBox">
						<dl>
							<dt>表示項目</dt>
							<dd id="categoryName">すべて</dd>
						</dl>
						<div class="table">
							<div class="cell">
								<p>カテゴリー選択</p>
								<select id="selectCategory" name="selectCategory" class="category">
									<option value="すべて" selected>すべて</option>
									<?php 
									foreach ($obj->readall('tbl_m_category') as $m) {
										extract($m); ?>
										<option value="<?=$m['catname']?>" <?=($selectCategory)? 'selected' : '' ;?> ><?=$m['catname']; ?></option>
										<?php } unset($m); ?>
									</select>
								</div><!-- /[div.cell] -->
								<div class="cell">
									<p>並び替え</p>
									<select id="sortList" name="sortList" class="day">
										<option value="1" selected>日付（降順）</option>
										<option value="2">日付（昇順）</option>
										<option value="3" selected>カテゴリ（降順）</option>
										<option value="4">カテゴリ（昇順）</option>
									</select>
								</div><!-- /[div.cell] -->
								<div class="search">
									<p>キーワード検索</p>
									<input type="text" id="search_txt" name="search_txt" class="textBox"><input type="button" value="検索" class="btn" onClick="javascript:searchJSON();">
								</div>
							</div><!-- /[div.table] -->
							<div class="serch">
								<input type="button" value="初期状態に戻す" onClick="javascript:initView();" class="cell">
								<input id="loadflg" name="loadflg" type="hidden" value="0">
							</div><!-- /[div.serch] -->
						</div><!-- /[div.categoriBox] -->
					</form>
					<div class="selection table">
						<p class="cell selectionL"><a id="selection_course" href="javascript:init(0); typeOver('selection_course');" class="active">コース</a></p>
						<p class="cell selectionR"><a id="selection_lecture" href="javascript:init(1); typeOver('selection_lecture');">レクチャー</a></p>
					</div><!-- /[div.selection] -->

					<table id="result_list"></table>
					<div class="pager"></div><!-- /[div.pager] -->

				</section><!-- /#lecture -->

			</div><!-- /#mainContents -->
			<?php include 'subContent.php';?>
		</div><!-- /.inner -->
	</div><!-- /#contents -->
	<script type="text/javascript">
//==================================================
//JSON処理
//==================================================

//JSON配列のソート処理
var sort_by = function(field, reverse, primer){
	reverse = (reverse) ? -1 : 1;
	return function(a,b){
		a = a[field];
		b = b[field];
		if (typeof(primer) != 'undefined'){
			a = primer(a);
			b = primer(b);
		}
		if (a<b) return reverse * -1;
		if (a>b) return reverse * 1;
		return 0;
	}
}

var $resultHTML=$("#result_list");
var $selectCategory=$("#selectCategory");
var arr=[];
var alldata=[]; //すべてのJSONデータ
var filterdata=[]; //フィルタしたJSONデータ
var searchdata=[]; //検索したJSONデータ
var ex_searchdata=[]; //カテゴリで絞りこまれたJSONデータ

init(0);

function init(pathflg){
	
	var filepath = "";
	var coursePath = '<?php echo $jsonpath; ?>';
	var lecturePath = '<?php echo $jsonpath1; ?>'; 
	
	if(pathflg == 0){
		filepath = coursePath;
	} else if(pathflg == 1) {
		filepath = lecturePath;
	} else {
		alert('該当するデータがありません');
	}
	
	$("#loadflg").val(pathflg);
	
	$.getJSON(filepath, initOutput);
}

//初期の関数
function initOutput(data){
	
	alldata = data;
	
	//最初に読み込んだときは全部標示する
	filterdata = alldata;
	searchdata = alldata;
	ex_searchdata = alldata;
	
	//表示させる
	display(alldata, 3, '', 0);
	
	console.log(alldata);

}

//HTMLの出力
function display(arr, sort_flg, paging, pagingswitch){
	
	if(arr == ""){
		arr = ex_searchdata;
	}
	
	//ループ用変数の初期化
	var loopcount = 0;
	var j = 0;
	
	//リスト書き出し用HTMLの初期化
	$resultHTML.empty();
	
	if(sort_flg == 1){
		arr.sort(sort_by('regdate', true, parseInt)); //日付降順
	} else if(sort_flg == 2) {
		arr.sort(sort_by('regdate', false, parseInt));//日付昇順
	} else if(sort_flg == 3) {
		arr.sort(sort_by('catname', true, function(a){return a.toUpperCase()}));//カテゴリ降順
	} else if(sort_flg == 4) {
		arr.sort(sort_by('catname', false, function(a){return a.toUpperCase()}));//カテゴリ昇順
	}
	
	//ループ回数の設定
	if(paging == undefined){

		if(arr.length > loopcount){
			
			loopcount = 6;
			
		} else {
			loopcont = arr.length;
			
		}
		
		//ループの開始番号
		j = 0;
		
	} else {
		
		if(arr.length - (6 * paging) > 6){
			
			loopcount = 6;
			
		} else {
			
			loopcount = arr.length - (6 * paging);
			
		}
		
		//ループの開始番号
		j = 6 * paging;
	}
	
	//リスト出力用変数初期化
	var output_list = "";
	
	output_list = "<tbody>";
	
	//コース・レクターの出力切り替え
	if($("#loadflg").val() == 0){
		
		//リスト表示用HTMLの生成
		for (i=0; i<loopcount; i++) {
			
			var k = i + j;
			var l = k + 1;
			
			output_list += '<tr>';
			output_list += '<th scope="row" class="number">' + l + '</th>';
			output_list += '<td class="img"><img src="img/other/' + arr[k].cimg + '" width="192" height="108" alt=""></span></td>';
//output_list += '<td class="img"><img src="img/other/img_thumb01.jpg" width="192" height="108" alt=""></span></td>';
output_list += '<td class="content">';
output_list += '<dl>';
output_list += '<dt class="ttl">' + arr[k].cname + '</dt>';
output_list += '<dd class="name">講師名<span>' + arr[k].tname + '</span></dd>';

var cDetailTxt = checkTxtByte(arr[k].cdetail);

output_list += '<dd class="txt">' + cDetailTxt + '</dd>';
output_list += '<dd class="table">';
output_list += '<dl class="cell register clearfix">';
output_list += '<dt>登録レクチャー数</dt>';
output_list += '<dd>' + arr[k].leccnt + '</dd>';
output_list += '</dl>';
output_list += '<p class="cell btn btn_black"><a href="course_detail.php?cid='+arr[k].cid+'" class="w150">コース詳細へ</a></p>';
output_list += '</dd>';
output_list += '<td class="purchase">';
output_list += '<p class="price"><span>' + arr[k].price + '</span>円</p>';
output_list += '<p class="btn_yellow"><a href="#" class="w110 fs14">カートに入れる</a></p>';
output_list += '</td>';
output_list += '</tr>';

}

} else {

		//リスト表示用HTMLの生成
		for (i=0; i<loopcount; i++) {
			
			var k = i + j;
			var l = k + 1;
			
			output_list += '<tr>';
			output_list += '<th scope="row" class="number">' + l + '</th>';
//			output_list += '<td class="img"><img src="img/other/' + arr[k].cimg + '" width="192" height="108" alt=""></span></td>';
output_list += '<td class="img"><img src="img/other/img_thumb01.jpg" width="192" height="108" alt=""></span></td>';
output_list += '<td class="content">';
output_list += '<dl>';
output_list += '<dt class="ttl">' + arr[k].lname + '</dt>';
output_list += '<dd class="lname">' + arr[k].cname + '</dd>';
output_list += '<dd class="name">講師名<span>' + arr[k].tname + '</span></dd>';
output_list += '<dd class="txt">' + arr[k].cdetail + '</dd>';
output_list += '<dd class="table">';
output_list += '<p class="cell btn btn_black"><a href="st_mashup.html"  class="w150">レクチャー詳細へ</a></p>';
output_list += '</dd>';
output_list += '<td class="purchase">';
output_list += '<p class="price"><span>' + arr[k].price + '</span>円</p>';
output_list += '<p class="btn_yellow"><a href="#" class="w110 fs14">カートに入れる</a></p>';
output_list += '</td>';
output_list += '</tr>';

}

}

output_list += "</tbody>";

	//リストの出力
	$resultHTML.append(output_list);
	
	//ページングの出力
	if(pagingswitch == 0){
		outpuPaging(arr, paging);
	}
	
}

//ページングの出力処理
function outpuPaging(arr, paging){
	
	var $pagingHTML=$(".pager");
	
	//ページング書き出し用HTMLの初期化
	$pagingHTML.empty();
	
	//ページング出力用変数初期化
	var output_paging = "";
	
	//ページングオーバー設定
	var over_txt = "";
	
	//ページング表示用HTMLの生成
	output_paging = '<ul>';
	
	//ページング出力ループ回数
	var pageloopcount = Math.ceil(arr.length / 6);
	
	if(paging == undefined){
		paging = 0;
	} 
	
	pageloopcount = pageloopcount - paging;
	
	if(pageloopcount > 5){
		pageloopcount = 5;
	}
	
	var pagingprev = paging - 5;
	
	//前ページングの表示
	if(paging >= 5){
		output_paging += '<li><a href="javascript:display(' + "'',''," + pagingprev + ',0);"><i class="fa fa-angle-left"></i></a></li>';
	}
	
	for(i=0; i<pageloopcount; i++){
		
		var j = i + 1;
		var k = Number(paging) + i;
		var l = Number(paging) + i + 1;
		
		if(paging == k){
			over_txt = ' class="active"';
		} else {
			over_txt = '';
		}
		
		output_paging += '<li><a id="pagingid' + k + '" href="javascript:display(' + "'',''," + k + '); pagingOver(' + "'pagingid" + k + "'" + ')"' + over_txt + '>' + l + '</a></li>';
		
	}
	
	//次ページングの表示
	if(pageloopcount >= 5){
		output_paging += '<li><a href="javascript:display(' + "'',''," + l + ',0);"><i class="fa fa-angle-right"></i></a></li>';
	}
	
	output_paging += '</ul>';
	
	//ページング部分の出力
	$pagingHTML.append(output_paging);
	
}


//レクチャー・コースのオーバー設定
function typeOver(idname){
	$(".selection p a").removeClass('active');
	$("#" + idname).addClass('active');
	initView();
}

//ページングのオーバー設定
function pagingOver(idname){
	$(".pager ul li a").removeClass('active');
	$("#" + idname).addClass('active');
}

//配列の検索
function searchJSON(){
	
	var stxt = $("#search_txt").val();
	
	//フィルタ用配列の初期化
	searchdata = [];
	ex_searchdata = [];
	
	if(stxt === ""){

		searchdata = alldata;
		ex_searchdata = alldata;

	} else {
		
		searchdata = $.grep(alldata,function(n){
			
			if($("#loadflg").val() == 0){

				if (n.cname.indexOf(stxt) !== -1) {
					return n.cname;
				}

			} else if($("#loadflg").val() == 1){
				
				if (n.cname.indexOf(stxt) !== -1 || n.lname.indexOf(stxt) !== -1) {
					return n.cname;
				}
				
			}
		});
		
		ex_searchdata = searchdata;
	}
	
	$("#categoryName").html('すべて')
	createSelector(searchdata);
	display(searchdata, 3, '', 0);
	
}

//初期表示
function initView(){
	
	//フィルタ用配列の初期化
	filterdata = [];
	searchdata = [];
	ex_searchdata = [];
	
	filterdata = alldata;
	searchdata = alldata;
	ex_searchdata = alldata;
	
	$("#categoryName").html('すべて')
	createSelector(alldata);
	display(alldata, 3, '', 0);
	
}

//セレクタの生成
function createSelector(arr){
	
	$selectCategory.empty();
	
	var cname_txt = "";
	var sname_txt = "";
	
	$('#selectCategory').empty();
	$('#sortList').empty();
	
	$('#selectCategory').append(
		'<option value="すべて">すべて</option>'
		)
	
	for(i=0; i < arr.length; i++){
		
		if(cname_txt != arr[i].catname){

			cname_txt = arr[i].catname;

			$('#selectCategory').append(
				'<option value="' + arr[i].catname + '">' + arr[i].catname + '</option>'
				);
		}

	}
	
	$('#sortList').append(
		'<option value="1">日付（降順）</option><option value="2">日付（昇順）</option><option value="3" selected>カテゴリ（降順）</option><option value="4">カテゴリ（昇順）</option>'
		)
	
}


//カテゴリ変更時に絞り込み
$('select#selectCategory').change(function(){

	//セレクタの状態により絞り込み
	var cate = $(this).val();
	
	//フィルタ用配列の初期化
	ex_searchdata = [];
	
	if(cate === "すべて"){

		ex_searchdata = searchdata;

	} else {

		ex_searchdata = $.grep(searchdata,function(n){

			return n.catname === cate;

		});
	}
	
	$("#categoryName").html(cate)
	display(ex_searchdata, '', 0, 0);

});

//ソート条件変更時に絞り込み
$('select#sortList').change(function(){
	
	var sortflg = $(this).val();
	
	display(ex_searchdata, sortflg, 0, 0);

});

</script>
<?php include 'footer.php'; ?>