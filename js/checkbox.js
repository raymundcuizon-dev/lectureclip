$(function(){
	//checked��������ŏ�����`�F�b�N����
	$('td.buy input').each(function(){
		if ($(this).attr('checked') == 'checked') {
			$(this).next().addClass('checked');

		}
	});
	//�N���b�N�����v�f�ɃN���X���蓖�Ă�
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