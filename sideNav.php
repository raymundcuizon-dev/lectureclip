<div id="subContents">
    <aside class="unit">
        <form>
            <div class="search">
                <input type="text" name="search" class="textBox"><input type="submit" value="検索" class="btn">
            </div>
        </form>
    </aside>
    <ul class="unit">
        <li><a href="new.php">新着おすすめ</a></li>
        <li><a href="free.php">無料レクチャー</a></li>
        <li><a href="top_chart.php">トップチャート</a></li>
    </ul>
    <dl class="unit acMenu">
        <dt>カテゴリー</dt>
        <dd>
            <ul>
               <?php foreach ($obj->readall("tbl_m_category", "order by catname ASC") as $categorylist): extract($categorylist); ?>
               <li><a href="category.php?catid=<?= $catid ?>"><?= $catname; ?></a></li>
           <?php endforeach; unset($categorylist); ?>
       </ul>
   </dd>
</dl><!-- /.acMenu -->
<div class="mypage">
    <ul class="unit">
        <li class="ttl"><a href="mypage.php">マイページ</a></li>
        <li class="profile"><a href="profile_edit.php">プロフィール変更</a></li>
    </ul>
    <ul class="unit">
        <li><a href="purchase_list.php">購入済みのコース</a></li>
        <li><a href="review_list.php">検討中リスト</a></li>
        <li><a href="#">クリップ</a></li>
    </ul>
    <ul class="unit">
        <li><a href="create_course.php">作成コース</a></li>
    </ul>
</div><!-- /[div.mypage] -->
</div><!-- /#subContents -->