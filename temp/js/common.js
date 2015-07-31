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
var timer; 
var counter
var fadeSpeed;

var parentContainer; 
var totalDiv;
var currentIndex;

function animateSlider() {
	timer = 7000; 
	totalDiv = 0;
	fadeSpeed = 1500;

	parentContainer = $('#mainVisual'); 
	totalDiv = parentContainer.children('.bgfade').length;
	currentIndex = 0;

	//HIDE DIV AND TEXT ONLOAD
	parentContainer.children('.bgfade').hide(); 
    parentContainer.children('.title1').hide();
    parentContainer.children('.title2').hide();	

	//ONLOAD
	parentContainer.children('.bgfade').eq(0).fadeIn(fadeSpeed, function(){ //FADE IN DIV
		parentContainer.children('.title1').eq(0).fadeIn(fadeSpeed, function(){ //ON COMPLETE, FADE TEXT
			parentContainer.children('.title2').eq(0).fadeIn(fadeSpeed); //ONCOMPLETE, FADE TEXT
		});
	});

	counter = setInterval(animateRight, timer);
}

function animateRight(){				
	parentContainer.children('.bgfade').eq(currentIndex).stop( true, true ).fadeOut(fadeSpeed); //FADE OUT DIV
	parentContainer.children('.title1').eq(currentIndex).stop( true, true ).fadeOut(fadeSpeed); //FADE OUT TEXT
	parentContainer.children('.title2').eq(currentIndex).stop( true, true ).fadeOut(fadeSpeed); //FADE OUT TEXT

	if (currentIndex >= (totalDiv - 1)) { //LOOP
		currentIndex = 0;
	} else {
		currentIndex++;
	}

	parentContainer.children('.bgfade').eq(currentIndex).stop( true, true ).fadeIn(fadeSpeed, function(){ //FADE IN DIV
		parentContainer.children('.title1').eq(currentIndex).stop( true, true ).fadeIn(fadeSpeed, function(){ //ON COMPLETE, FADE TEXT
			parentContainer.children('.title2').eq(currentIndex).stop( true, true ).fadeIn(fadeSpeed); //ON COMPLETE, FADE TEXT
		});
	});	
}

function animateLeft(){				
	parentContainer.children('.bgfade').eq(currentIndex).stop( true, true ).fadeOut(fadeSpeed); //FADE OUT DIV
	parentContainer.children('.title1').eq(currentIndex).stop( true, true ).fadeOut(fadeSpeed); //FADE OUT TEXT
	parentContainer.children('.title2').eq(currentIndex).stop( true, true ).fadeOut(fadeSpeed); //FADE OUT TEXT
	
	if (currentIndex == 0) { //LOOP
		currentIndex = totalDiv - 1;
	} 
	else {
		currentIndex--;
	}

	parentContainer.children('.bgfade').eq(currentIndex).stop( true, true ).fadeIn(fadeSpeed, function(){ //FADE IN DIV
		parentContainer.children('.title1').eq(currentIndex).stop( true, true ).fadeIn(fadeSpeed, function(){ //ON COMPLETE, FADE TEXT
			parentContainer.children('.title2').eq(currentIndex).stop( true, true ).fadeIn(fadeSpeed); //ON COMPLETE, FADE TEXT
		});
	});	
}

$(document).ready(function(){
	$(".right").click(function() {
		clearInterval(counter);
		animateRight();
		counter = setInterval(animateRight, timer);
	});
	$(".left").click(function() {
		clearInterval(counter);
		animateLeft();
		counter = setInterval(animateRight, timer);
	});
});