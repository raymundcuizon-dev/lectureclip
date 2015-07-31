// JavaScript Document

//ロールオーバー
var preLoadImg = new Object();

function initRollOvers(){
	$("img.rollover").each(function(){
		var imgSrc = this.src;
		var sep = imgSrc.lastIndexOf('.');
		var onSrc = imgSrc.substr(0, sep) + '_on' + imgSrc.substr(sep, 4);
		preLoadImg[imgSrc] = new Image();
		preLoadImg[imgSrc].src = onSrc;
		$(this).hover(
			function() { this.src = onSrc; },
			function() { this.src = imgSrc; }
		);
	});
}
$(function(){
	initRollOvers();
});

// page scroll
$(function () {
   	$('a[href^=#]').click(function() {
      	// スクロールの速度
      	var speed = 400;// ミリ秒
      	// アンカーの値取得
      	var href= $(this).attr("href");
      	// 移動先を取得
     		var target = $(href == "#" || href == "" ? 'html' : href);
     		// 移動先を数値で取得
      	var position = target.offset().top;
      	// スムーススクロール
      	$('body,html').animate({scrollTop:position}, speed, 'swing');
      	return false;
   	});

});

//スマートフォンではtoggleメニュー
$(function() {
  $(".mainMenu").naver({
      animated: true
  });
});

//トップスライダー
function animateSlider() {
	var timer = 7000; 
	var totalDiv = 0;
	var fadeSpeed = 1500;

	var parentContainer = $('#mainVisual'); 
	var totalDiv = parentContainer.children('.bgfade').length;

	//HIDE DIV AND TEXT ONLOAD
	parentContainer.children('.bgfade').hide(); 
    parentContainer.children('.title1').hide();
    parentContainer.children('.title2').hide(); 

	var currentIndex = 0;

	//ONLOAD
	parentContainer.children('.bgfade').eq(0).fadeIn(fadeSpeed, function(){ //FADE IN DIV
		parentContainer.children('.title1').eq(0).fadeIn(fadeSpeed, function(){ //ON COMPLETE, FADE TEXT
			parentContainer.children('.title2').eq(0).fadeIn(fadeSpeed); //ONCOMPLETE, FADE TEXT
		});
	});

	var counter = setInterval(function() {
		parentContainer.children('.bgfade').eq(currentIndex).fadeOut(fadeSpeed); //FADE OUT DIV
		parentContainer.children('.title1').eq(currentIndex).fadeOut(fadeSpeed); //FADE OUT TEXT
		parentContainer.children('.title2').eq(currentIndex).fadeOut(fadeSpeed); //FADE OUT TEXT

		if (currentIndex >= (totalDiv - 1)) { //LOOP
			currentIndex = 0;
		} else {
			currentIndex++;
		}

		parentContainer.children('.bgfade').eq(currentIndex).fadeIn(fadeSpeed, function(){ //FADE IN DIV
			parentContainer.children('.title1').eq(currentIndex).fadeIn(fadeSpeed, function(){ //ON COMPLETE, FADE TEXT
				parentContainer.children('.title2').eq(currentIndex).fadeIn(fadeSpeed); //ON COMPLETE, FADE TEXT
			});
		});

	}, timer);


}
