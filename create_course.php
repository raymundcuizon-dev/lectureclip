<?php
include 'header.php';
?>
<div id="contents" class="clearfix">
    <div class="inner">

        <div id="mainContents" class="clearfix">

            <?php
            $uid = $_SESSION['uid'];
            $data1 = array('tbl_lc_course.cid as cid',
                'tbl_lc_course.title as cname',
                'tbl_m_category.catname as catname',
                'tbl_lc_course.course_img as cimg',
                'DATE_FORMAT(tbl_lc_course.regdate, "%d/%m/%Y") as regdate',
                'DATE_FORMAT(tbl_lc_course.update, "%d/%m/%Y") as lastdate');
            $table1 = 'tbl_lc_course left join tbl_m_category on tbl_lc_course.catid = tbl_m_category.catid';
            $where1 = 'where tbl_lc_course.uid = ' . $uid . ' AND catname IS NOT NULL ORDER BY tbl_lc_course.regdate DESC';

            $data2 = array('tbl_lc_lecture.lno as lid',
                'tbl_lc_lecture.lname as lname',
                'tbl_lc_lecture.ltype as type');
            $table2 = 'tbl_lc_lecture';

            foreach ($obj->select_w_join_2($data1, $table1, $where1) as $s) {
                $array = array();
                $array['cid'] = $s['cid'];
                $array['cname'] = $s['cname'];
                $array['catname'] = $s['catname'];
                $array['cimg'] = $s['cimg'];
                $array['regdate'] = $s['regdate'];
                $array['lastdate'] = $s['lastdate'];
                $where2 = 'where tbl_lc_lecture.cid = ' . $s['cid'];
                foreach ($obj->select_w_join_2($data2, $table2, $where2) as $key => $value) {
                    if ($value['type'] == 'mu') {
                        $value['type'] = 'mashup';
                    } elseif ($value['type'] == 'm') {
                        $value['type'] = 'movie';
                    } elseif ($value['type'] == 'p') {
                        $value['type'] = 'ppt';
                    } elseif ($value['type'] == 'pdf') {
                        $value['type'] = 'pdf';
                    } elseif ($value['type'] == 'm') {
                        $value['type'] = 'music';
                    }
                    $array['lecture'][] = array('lid' => $value['lid'], 'lname' => $value['lname'], 'ltype' => $value['type']);
                }
                $q[] = $array;
            }
            file_put_contents('./json/create_' . $uid . '_course.json', json_encode($q, JSON_PRETTY_PRINT));
            $jsonpath = './json/create_' . $uid . '_course.json';
            ?>
            <section>
                <h2 class="pageTitle">作成コース</h2>
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
                                        extract($m);
                                        ?>
                                        <option value="<?= $m['catname'] ?>" <?= ($selectCategory) ? 'selected' : ''; ?> ><?= $m['catname']; ?></option>
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

                    <table id="result_list"></table>
                    <div class="pager"></div><!-- /[div.pager] -->

                </section><!-- /#lecture -->

            </div><!-- /#mainContents -->

            <?php include 'sideNav.php'; ?>
        </div><!-- /.inner -->

    </div><!-- /#contents -->

    <script type="text/javascript">

//==================================================
//JSON処理
//==================================================

//JSON配列のソート処理
var sort_by = function (field, reverse, primer) {
    reverse = (reverse) ? -1 : 1;
    return function (a, b) {
        a = a[field];
        b = b[field];
        if (typeof (primer) != 'undefined') {
            a = primer(a);
            b = primer(b);
        }
        if (a < b)
            return reverse * -1;
        if (a > b)
            return reverse * 1;
        return 0;
    }
}

var $resultHTML = $("#result_list");
var $selectCategory = $("#selectCategory");
var arr = [];
    var alldata = []; //すべてのJSONデータ
    var filterdata = []; //フィルタしたJSONデータ
    var searchdata = []; //検索したJSONデータ
    var ex_searchdata = []; //カテゴリで絞りこまれたJSONデータ

    init(0);

    function init(pathflg) {

        var filepath = '<?php echo $jsonpath; ?>';

        $("#loadflg").val(pathflg);

        $.getJSON(filepath, initOutput);
    }

//初期の関数
function initOutput(data) {

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
function display(arr, sort_flg, paging, pagingswitch) {

    if (arr == "") {
        arr = ex_searchdata;
    }

        //ループ用変数の初期化
        var loopcount = 0;
        var j = 0;

        //リスト書き出し用HTMLの初期化
        $resultHTML.empty();

        if (sort_flg == 1) {
            arr.sort(sort_by('regdate', true, parseInt)); //日付降順
        } else if (sort_flg == 2) {
            arr.sort(sort_by('regdate', false, parseInt));//日付昇順
        } else if (sort_flg == 3) {
            arr.sort(sort_by('catname', true, function (a) {
                return a.toUpperCase()
            }));//カテゴリ降順
        } else if (sort_flg == 4) {
            arr.sort(sort_by('catname', false, function (a) {
                return a.toUpperCase()
            }));//カテゴリ昇順
        }

        //ループ回数の設定
        if (paging == undefined) {

            if (arr.length > loopcount) {

                loopcount = 6;

            } else {
                loopcont = arr.length;

            }

            //ループの開始番号
            j = 0;

        } else {

            if (arr.length - (6 * paging) > 6) {

                loopcount = 6;

            } else {

                loopcount = arr.length - (6 * paging);

            }

            //ループの開始番号
            j = 6 * paging;
        }

        //リスト出力用変数初期化
        var output_list = "";

        //リスト表示用HTMLの生成
        for (i = 0; i < loopcount; i++) {

            var k = i + j;
            var l = k + 1;

            output_list += "<thead>";

            output_list += '<tr>';
            output_list += '<th scope="row" class="number">' + l + '</th>';
            output_list += '<td class="img" colspan="2"><img src="img/other/' + arr[k].cimg + '" width="192" height="108" alt=""></span></td>';
//		output_list += '<td class="img" colspan="2"><img src="img/other/img_thumb01.jpg" width="192" height="108" alt=""></span></td>';
output_list += '<td class="content">';
output_list += '<dl>';
output_list += '<dt>' + arr[k].cname + '</dt>';
output_list += '<dd>';
output_list += '<div class="register">';

if (arr[k].lecture.length > 0) {

    output_list += '<p class="btn_yellow"><a href="javascript:displayControl(' + "'#idname_" + arr[k].cid + "','','','',2" + ');">登録レクチャー数<span>(' + arr[k].lecture.length + ')</span><span class="fa fa-chevron-down"></span></a></p>';

} else {

    output_list += '<p class="btn_yellow"><a href="javascript:;">登録レクチャー数<span>(' + arr[k].lecture.length + ')</span><span class="fa fa-chevron-down"></span></a></p>';

}

output_list += '</div>';
output_list += '<div class="day">';
output_list += '<p>作成日<span>' + arr[k].regdate.substring(0, 4) + '/' + arr[k].regdate.substring(4, 6) + '/' + arr[k].regdate.substring(6) + '</span></p>';
output_list += '<p>最終更新日<span>' + arr[k].lastdate.substring(0, 4) + '/' + arr[k].lastdate.substring(4, 6) + '/' + arr[k].lastdate.substring(6) + '</span></p>';
output_list += '</div>';
output_list += '</dd>';
output_list += '</dl>';
output_list += '<p class="btn btn_black"><a href="course_edit.php?cid=' + arr[k].cid + '" class="w90 h35 fs13">編集</a></p>';
output_list += '</td>';
output_list += '</tr>';

output_list += "</tbody>";

if (arr[k].lecture.length > 0) {

    output_list += '<tbody id="idname_' + arr[k].cid + '" style="display: none;">';

    for (m = 0; m < arr[k].lecture.length; m++) {

        var n = m + 1;
        var ltype = "";

        if (arr[k].lecture[m].ltype == "mashup") {
            ltype = 'fa-film';
        } else if (arr[k].lecture[m].ltype == "movie") {
            ltype = 'fa-video-camera';
        } else if (arr[k].lecture[m].ltype == "music") {
            ltype = 'fa-music';
        } else if (arr[k].lecture[m].ltype == "ppt") {
            ltype = 'fa-file-powerpoint-o';
        } else if (arr[k].lecture[m].ltype == "pdf") {
            ltype = 'fa-file-pdf-o';
        } else if (arr[k].lecture[m].ltype == "html") {
            ltype = 'fa-file-code-o';
        } else {
            ltype = 'fa-question';
        }

        output_list += '<tr class="lc">';
        output_list += '<td scope="row" class="number2" colspan="2">' + n + '</td>';
        output_list += '<td class="icon"><span class="fa ' + ltype + ' fa-3x"></span></td>';
        output_list += '<td class="lcContent table">';
        output_list += '<p class="cell ttl">' + arr[k].lecture[m].lname + '</p>';
        output_list += '<p class="cell btn btn_red"><a href="cl_edit.php?lno=' + arr[k].lecture[m].lid + '" class="w90 h35 fs13">編集</a></p>';
        output_list += '</td>';
        output_list += '</tr>';
    }

    output_list += '</tbody>';

}

}

        //リストの出力
        $resultHTML.append(output_list);

        //ページングの出力
        if (pagingswitch == 0) {
            outpuPaging(arr, paging);
        }

    }

//ページングの出力処理
function outpuPaging(arr, paging) {

    var $pagingHTML = $(".pager");

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

        if (paging == undefined) {
            paging = 0;
        }

        pageloopcount = pageloopcount - paging;

        if (pageloopcount > 5) {
            pageloopcount = 5;
        }

        var pagingprev = paging - 5;

        //前ページングの表示
        if (paging >= 5) {
            output_paging += '<li><a href="javascript:display(' + "'',''," + pagingprev + ',0);"><i class="fa fa-angle-left"></i></a></li>';
        }

        for (i = 0; i < pageloopcount; i++) {

            var j = i + 1;
            var k = Number(paging) + i;
            var l = Number(paging) + i + 1;

            if (paging == k) {
                over_txt = ' class="active"';
            } else {
                over_txt = '';
            }

            output_paging += '<li><a id="pagingid' + k + '" href="javascript:display(' + "'',''," + k + '); pagingOver(' + "'pagingid" + k + "'" + ')"' + over_txt + '>' + l + '</a></li>';

        }

        //次ページングの表示
        if (pageloopcount >= 5) {
            output_paging += '<li><a href="javascript:display(' + "'',''," + l + ',0);"><i class="fa fa-angle-right"></i></a></li>';
        }

        output_paging += '</ul>';

        //ページング部分の出力
        $pagingHTML.append(output_paging);

    }


//レクチャー・コースのオーバー設定
function typeOver(idname) {
    $(".selection p a").removeClass('active');
    $("#" + idname).addClass('active');
    initView();
}

//ページングのオーバー設定
function pagingOver(idname) {
    $(".pager ul li a").removeClass('active');
    $("#" + idname).addClass('active');
}

//配列の検索
function searchJSON() {

    var stxt = $("#search_txt").val();

        //フィルタ用配列の初期化
        searchdata = [];
        ex_searchdata = [];

        if (stxt === "") {

            searchdata = alldata;
            ex_searchdata = alldata;

        } else {

            searchdata = $.grep(alldata, function (n) {

                if (n.cname.indexOf(stxt) !== -1) {
                    return n.cname;
                }
            });

            ex_searchdata = searchdata;
        }

        $("#categoryName").html('すべて')
        createSelector(searchdata);
        display(searchdata, 3, '', 0);

    }

//初期表示
function initView() {

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
function createSelector(arr) {

    $selectCategory.empty();

    var cname_txt = "";
    var sname_txt = "";

    $('#selectCategory').empty();
    $('#sortList').empty();

    $('#selectCategory').append(
        '<option value="すべて">すべて</option>'
        )

    for (i = 0; i < arr.length; i++) {

        if (cname_txt != arr[i].catname) {

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
$('select#selectCategory').change(function () {

        //セレクタの状態により絞り込み
        var cate = $(this).val();

        //フィルタ用配列の初期化
        ex_searchdata = [];

        if (cate === "すべて") {

            ex_searchdata = searchdata;

        } else {

            ex_searchdata = $.grep(searchdata, function (n) {

                return n.catname === cate;

            });
        }

        $("#categoryName").html(cate)
        display(ex_searchdata, '', 0, 0);

    });

//ソート条件変更時に絞り込み
$('select#sortList').change(function () {

    var sortflg = $(this).val();

    display(ex_searchdata, sortflg, 0, 0);

});

</script>
<?php include 'footer.php'; ?>