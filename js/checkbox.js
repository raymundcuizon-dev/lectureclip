$(function(){
	//checkedだったら最初からチェックする
	$('td.buy input').each(function(){
		if ($(this).attr('checked') == 'checked') {
			$(this).next().addClass('checked');

		}
	});
	//クリックした要素にクラス割り当てる
	$('td.buy label').click(function(){
		if ($(this).hasClass('checked')) {
			$(this)
				.removeClass('checked')
				.prev('input').removeAttr('checked');
		} else {
			$(this)
				.addClass('checked')
				.prev('input').attr('checked','checked');
		}
	});
});