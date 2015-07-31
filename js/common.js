// JavaScript Document

var arrval = []; //レクチャー用配列
var viewpdf = 1; //レクチャー用現在表示PDF

// Cookieの有無で初期値のセット
$(function(){
	
	var lc_cookie_id = $.cookie("lc_login_id"); 
	var lc_cookie_pw = $.cookie("lc_login_pw");
	  
	if(lc_cookie_id !="" && lc_cookie_pw != ""){
		
		 $(':text[name="UserMail_m"]').val(lc_cookie_id);
		 $(':password[name="UserPass_m"]').val(lc_cookie_pw);		 
	}
	
});

// ログイン処理
function lc_LoginCheck(mailname, passname){	
	// 入力情報のセット
	var val_usermail = $(':text[name="'+mailname+'"]').val();
	var val_userpass = $(':password[name="'+passname+'"]').val();

	//VALID INPUT
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val_usermail)){  		
		$.ajax({
			url : "lib/api.php",
			type: "POST",
			dataType: "json",   
			data : {
				api_function: 'checkUser',
				email: val_usermail,
				password: val_userpass
			},
			success: function(data){   
				//USER EXISTS IN DB
				//console.log(data);
				console.log("DATA: " + data);	
				if (data[0] !== false) {
					//$("#add_err").html("Valid User!");						
					
					//Cookieのセット
					$.cookie("lc_login_id", val_usermail, {expires:30, path:'/'});
					$.cookie("lc_login_pw", data.pwd, {expires:30, path:'/'});
					//window.location.replace("mypage.html");		
					console.log("REFERRER: " + data[1]);			
					locationURL(data[1]);
				}
				//USER DOES NOT EXISTS IN DB
				else{
					$("#add_err").html("Invalid User!");
				} 
			},
			error: function (data)
			{
				//console.log(errorThrown);
			}
		});
		return false;
	} 
	//INVALID INPUT
	else{
		document.getElementById("add_err").innerHTML = "Please input valid email.";
		return false;  
	}				
}
//- See more at: http://www.w3resource.com/javascript/form/email-validation.php#sthash.MBzJPYFC.dpuf

// ログイン後処理
function locationURL(referrer){
	if($.cookie("lc_login_id") != "" && $.cookie("lc_login_id") != undefined && $.cookie("lc_login_pw") != "" && $.cookie("lc_login_pw") != undefined){
		console.log("referrer: " + referrer);
		location.href = referrer;
		/*if(referrer == "http://192.168.1.2/lectureclip/course_detail.php"){
			//location.href = "course_detail.php";
		}
		else{
			location.href = "mypage.php"; //暫定遷移処理	
		}*/
		
	}	
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
//1
function fb_login() {
    FB.login(function(response){
      statusChangeCallback(response);
    });
}

window.fbAsyncInit = function() {
    FB.init({
    appId      : '1403191029972159',
    cookie     : true,  // enable cookies to allow the server to access 
      // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use version 2.1
});

    //2
   // FB.getLoginStatus(function(response) {
    //	console.log('Loading this.');
    //    statusChangeCallback(response);
    //});
};

//3
// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
	console.log('statusChangeCallback');
	console.log(response);
	console.log('Cookie: ' + $.cookie("lc_login_id"));
	//if($.cookie("lc_login_id") != ""){
	    if (response.status === 'connected') {    	
	    	testAPI();
	    	console.log('Loading again.');
	    	$.cookie("lc_login_id", "val_usermail", {expires:30, path:'/'});
			$.cookie("lc_login_pw", "data.pwd", {expires:1, path:'/'});
	    	//location.href = "index.php";
	    	//locationURL();	
	    	
	    } else if (response.status === 'not_authorized') {
	    	//document.getElementById('status').innerHTML = 'Please log ' + 'into this app.';
	    } else {
	    	//document.getElementById('status').innerHTML = 'Please log ' + 'into Facebook.';
	    }
	//}
}

// Load the SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
    FB.api('/me', function(response) {
	    console.log('Successful login for: ' + response.name);

	    //PANGIT NG FUNCTIONS
	    //NASA LOOB YUNG FUNCTION NG PROFILE PIC	    	   
	    var fbLastName = response.last_name;
	    var fbFirstName = response.first_name;
	    var fbEmail = response.email;

	    FB.api('/me/picture?type=normal', function (response) {	  	    
	    	var fbPic = response.data.url;	
			
			$.ajax({
				url : "lib/api.php",
				type: "POST",
				dataType: "json",
				data : {
					api_function: 'setFBdetails',
					fb_lastname: fbLastName,
					fb_firstname: fbFirstName,
					fb_email: fbEmail,
					fb_pic: fbPic
				},
				success: function(data){   
					console.log("FB DATA: " + data);
					if (data !== false) {	
						//Cookieのセット
						//$.cookie("lc_login_id", "User123", {expires:30, path:'/'});				
						location.href = "index.php";
					}
					//USER DOES NOT EXISTS IN DB
					else{
						$("#add_err").html("Invalid User!");
					} 
				},
				error: function (data)
				{
					//console.log(errorThrown);
				}
			});
			return false;
        });
    });
}

function fb_logout(){
	alert("Logout!");
	FB.logout(function(response) {
	// user is now logged out
	});
}

function pw_forgotCheck(){
	var val_usermail = document.getElementById("UserMail_forgot").value;

	//VALID INPUT
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val_usermail)){ 
		$.ajax({
			url : "lib/api.php",
			type: "POST",
			dataType: "json",
			data :{
				api_function: 'checkEmail',
				email: val_usermail				
			},
			success: function(data){   
				console.log(data);
				if (data !== false){
					$("#emailError").html("Valid User!");	
					
					//SEND EMAIL
					$.ajax({
						url : "lib/api.php",
						type: "POST",
						dataType: "json",
						data : {
							api_function: 'sendEmail',
							email: val_usermail				
						},
						success: function(data){   
							console.log(data); 
						},
						error: function (data){
							//console.log(errorThrown);
						}
					});
				}
				else {
					$("#emailError").html("Invalid User!");
				} 
			},
			error: function (data){
				//console.log(errorThrown);
			}
		});
		return false;
	}
	else{
		document.getElementById("emailError").innerHTML = "Please input valid email.";
		return false;
	}	
}

function validatePassword(){
	var oldpass = document.getElementById("oldpass").value;
	var email = document.getElementById("password").value;
	var confirmpass = document.getElementById("confirmpass").value;

	if((email != "" && confirmpass != "" && oldpass != "") && (email  == confirmpass)){
		alert("OK!");
		return true;
	}
	else{
		alert("ERROR!");
		return false;
	}
}


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


//ページトップボタン
$(function() {
	var topBtn = $('#pagetop');	
	topBtn.hide();
	//スクロールが100に達したらボタン表示
	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			topBtn.fadeIn();
		} else {
			topBtn.fadeOut();
		}
	});
	//スクロールしてトップ
    topBtn.click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 500);
		return false;
    });
});


//アコーディオン
$(function(){
	$(".acMenu dt").on("click", function() {
		$(this).next().slideToggle();
		$(this).toggleClass("active");//追加部分
	});
});

//確認ダイアログの表示
function confirm_diag(msg, flg){
	
	//ダイアログ表示用テキスト
	ret = confirm(msg);
	
	if (ret == true){
		
		//ダイアログ表示時の処理
		if(flg==0){ //カート内容の削除処理へ
			alert('商品を削除しました（便宜的にアラートで表示）');
		} else { //その他の処理
			//処理
		}
	}
}

//Cookieの処理
function act_login(){
	
	//Cookieの利用可否
	if(window.navigator.cookieEnabled){ //Cookie利用可能な場合の処理
		
	} else { //Cookie利用不可の場合の処理
		alert('お使いのブラウザはCookieの利用ができません');
	}
	
}

//モーダルウィンドウ制御
function displayControl(firstid, secondid, thirdid, fourthid, type){
	
	if(type == 0){//パスワードを忘れた方、新規会員登録表示
	
		$(firstid).css("display", "none");
		$(secondid).css("display", "none");

		
	} else if(type == 1){//レクチャー作成
		
		$(secondid).css('display', 'block');
		$(firstid).css('display', 'none');
		
		//ボタン切り替え
		$("#lcb_create").css('display', 'block');
		$("#lcb_next").css('display', 'none');
	
	} else if(type == 2){//コース作成
		
		if($(firstid).css('display') == 'none'){
			$(firstid).css('display', '');
		} else {
			$(firstid).css('display', 'none');
		}
		
	} else if(type == 3){//コース作成
		
		$(thirdid).removeClass('over');
		$(fourthid).addClass('over');
		
		$(secondid).css('display', 'none');
		$(firstid).css('display', 'inline');
	}	
}

//ファイルアップロード部分表示変更処理
/*function lc_uploadContents(arr_count){
	
	$("#lc_mashup_setting").css('display', 'none');
	
	var arr_contentsid = ['mashup','pdf','ppt', 'html', 'music', 'movie'];
	
	if(eval(arr_count) == 0){
		$('#lcb_create').css('display', 'none');
		$('#lcb_next').css('display', 'block');
	} else {
		$('#lcb_create').css('display', 'block');
		$('#lcb_next').css('display', 'none');
	}
	
	for(i=0; i<arr_contentsid.length; i++){
		
		if(arr_contentsid[i] == arr_contentsid[eval(arr_count)]){
//			alert(1);
			$('#lc_'+arr_contentsid[i]).css('display', 'block');
			$('#lcm_'+arr_contentsid[i]).addClass('over');
		} else {
//			alert(2);
			$('#lc_'+arr_contentsid[i]).css('display', 'none');
			$('#lcm_'+arr_contentsid[i]).removeClass('over');
		}
		
	}
}*/


//CREATE LECTURE
function loadContents(arr_count){
	//LOAD PAGES EXCEPT MASHUP
	//MASHUP UPLOAD/ NEXT PAGE	
	var filename = "create_lecture_" + arr_count + ".php"; 	
	var cid = sessionStorage.course_id;
	//var nowdate = getNewDate();

	//UPLOAD MASHUP; STEP 1
	console.log("THIS IS PAGE LOAD. PAGE: " + arr_count);
	if(arr_count == 1){		
		sessionStorage.temp_page = 1;
    	var lcName = document.getElementById("lcName").value;	
    	var lcMovie = document.getElementById("lcMovie").value;	
    	var lcMovietime = document.getElementById("lcMovietime").value;
		var lcPdf = document.getElementById("lcPdf").value;	
		var intro_data = document.getElementById("intro_data").value;		

		if(cid != "" && lcName != "" && intro_data != "" && lcMovie != "" && lcMovietime != "" &&  lcPdf != ""){	
			var fd = new FormData();   
			fd.append( 'lcMovie', $( '#lcMovie' )[0].files[0] );
			fd.append( 'lcPdf', $( '#lcPdf' )[0].files[0] );  
			fd.append( 'api_function', 'uploadMashup');
			fd.append( 'cid', cid);
			fd.append( 'lecture_type', 'mu');
			fd.append( 'lectureName', lcName);
			fd.append( 'intro_data', intro_data);
			fd.append( 'lectureMovie', lcMovie);
			fd.append( 'movieTime', lcMovietime);
			fd.append( 'lecturePdf', lcPdf);				

			//ADD MASHUP
			$.ajax({
				url : "lib/api.php",
				type: "POST",
		        data: fd, 					// Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       	// The content type used when sending data to the server.
				cache: false,   
				dataType: "json",          	// To unable request pages to be cached
				processData:false, 

				//PRELOADER
				beforeSend: function(xhr){
			       $('#preloader').show();
			   	},

				success: function(data){ 
					//console.log("DATA: " + data);
					if(data[0] == "Success"){
						//SHOW NEXT PAGE/STEP 2
						$('#lcb_create').css('display', 'block');
						$('#lcb_next').css('display', 'none');								
						$("#load_file").html();
						$("#load_file").load(filename);

						sessionStorage.filename_data = lcName;
						sessionStorage.movie_filename = data[1];						
						sessionStorage.pdf_filename = data[2];
						sessionStorage.pdf_file = data[3];
					}
					else{
						$('#preloader').hide();
						alert("PLEASE INPUT CORRECT DATA!");
					}
				},

				//PRELOADER
				complete: function(xhr, textStatus){
					$('#preloader').hide();  // #info must be defined somehwere
				},

				error: function (){
					//console.log(errorThrown);
				}
			});
		}
		else{
			alert("PLEASE INPUT CORRECT DATA!");
		}

	}
	//LOAD MASHUP PAGE IF 0
	else if(arr_count == 0){
		sessionStorage.temp_page = 0;
		$('#lcb_create').css('display', 'none');
		$('#lcb_next').css('display', 'block');
		$("#load_file").html();
		$("#load_file").load(filename);	
	}
	else{
		//LOAD PAGE
		switch(arr_count) {			
		    case 2://PDF
		    	sessionStorage.temp_page = 2;
		    	break;
		    case 3://PPT
		    	sessionStorage.temp_page = 3;
		   		break;
		   	case 5://VIDEO
		    	sessionStorage.temp_page = 5;
		    	break;
		    case 6://PPT
		    	sessionStorage.temp_page = 6;
		   		break;

		}	
		//LOAD PAGE TAB
		$('#lcb_create').css('display', 'block');
		$('#lcb_next').css('display', 'none');
		$("#load_file").html();
		$("#load_file").load(filename);	
    }
}

//画像差替処理
function changeImageFile(imageFile, changeid){
	
	$(changeid).attr("src", imageFile);
}



function uploadFile() { 	
	//1 - STEP 2 MASHUP; UPLOAD THUMBNAILS AND SHOW PRICE MODAL
    //0 - STEP 1 MASHUP; UPLOAD FILES     
    //2-6 - UPLOAD FILES; GO TO FEE MODAL
	var cid = sessionStorage.course_id;
	var temp_page = sessionStorage.temp_page;

	console.log("UPLOAD FUNCTION. TEMP PAGE: " + temp_page);
    
    //STEP 2
    if(temp_page == 1){	  

	    //GET THUMBNAIL ARRAY
	    var thumb_arr = lectureCreateArr();
	    console.log("THUMBNAIL ARRAY: " + thumb_arr);

		$.ajax({
	        url : "lib/api.php",
	        type: "POST",
	        data :{
					api_function: 'insertThumbnail',
					thumbnail_data: thumb_arr,
					cid: cid			
				},

			beforeSend: function(xhr){
				$('#preloader').show();  // #info must be defined somehwere
			},                 

	        success: function(data){ 
	            //console.log("DATA: " + data);
	            //SHOW FEE MODAL
	            console.log("STOP VIDEO");
	            $("#video_player").jPlayer("stop"); 
	            $("#filename_data1").html(sessionStorage.filename_data);
	    		$("#modaltrigger").click();
	    		                 
	        },

	        //PRELOADER
			complete: function(xhr, textStatus){
				$('#preloader').hide();  // #info must be defined somehwere
			},

	        error: function (){
	            //console.log("ERROR");
	        }
	    });			
    }

    //UPLOAD MASHUP; STEP 1
   	else if(temp_page == 0){   		
    	var lcName = document.getElementById("lcName").value;	
    	var intro_data = document.getElementById("intro_data").value;	
    	var lcMovie = document.getElementById("lcMovie").value;	
    	var lcMovietime = document.getElementById("lcMovietime").value;
		var lcPdf = document.getElementById("lcPdf").value;

		if(cid != "" && lcName != "" && intro_data != "" && lcMovie != "" &&  lcPdf != ""){	

			var fd = new FormData();   
			fd.append( 'lcMovie', $( '#lcMovie' )[0].files[0] ) ;
			fd.append( 'lcPdf', $( '#lcPdf' )[0].files[0] );  
			fd.append( 'api_function', 'uploadMashup');
			fd.append( 'cid', cid);
			fd.append( 'lecture_type', 'mu');
			fd.append( 'lectureName', lcName);
			fd.append( 'intro_data', intro_data);
			fd.append( 'lectureMovie', lcMovie);
			fd.append( 'movieTime', lcMovietime);
			fd.append( 'lecturePdf', lcPdf);				

			//ADD MASHUP
			$.ajax({
				url : "lib/api.php",
				type: "POST",
		        data: fd, 					// Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       	// The content type used when sending data to the server.
				cache: false,    
				dataType: "json",           	// To unable request pages to be cached
				processData:false, 

				beforeSend: function(xhr){
			       $('#preloader').show();  // #info must be defined somehwere
			   	},

				success: function(data){ 
					console.log("DATA: " + data);
					if(data[0] == "Success"){
						//SHOW PAGE/STEP 2
						$('#lcb_create').css('display', 'block');
						$('#lcb_next').css('display', 'none');								
						$("#load_file").html();
						$("#load_file").load("create_lecture_1.php");	

						sessionStorage.filename_data = lcName;
						sessionStorage.movie_filename = data[1];						
						sessionStorage.pdf_filename = data[2];
					}
					else{
						$('#preloader').hide();
						alert("PLEASE INPUT CORRECT DATA!");
					}
				},

				complete: function(xhr, textStatus){
					$('#preloader').hide();  // #info must be defined somehwere
				},

				error: function (){
					//console.log(errorThrown);
				}
			});
		}
		else{
			alert("PLEASE INPUT CORRECT DATA!");
		}

   	}
   	//UPLOAD FILES
    else{	    
		var lecture_name = document.getElementById("lcName").value; 
		var intro_data = document.getElementById("intro_data").value;
		var file_name = document.getElementById("filetoUpload").value;	

    	//SWITCH DAPAT
		if(temp_page == 2){
			var lecture_type = "pdf"; 
		} 
		else if(temp_page == 3){
			var lecture_type = "p";
		}
		else if(temp_page == 5){
			var lecture_type = "v";
		}
		else if(temp_page == 6){
			var lecture_type = "m";
		}

	    //VALIDATE INPUT
	    if(cid != "" && lecture_name != "" && intro_data != "" && file_name != ""){  
	        var fd = new FormData();    
	        fd.append( 'filetoUpload', $( '#filetoUpload' )[0].files[0] );      
	        fd.append( 'lecture_name', lecture_name);
	        fd.append( 'intro_data', intro_data);
	        fd.append( 'file_name', file_name);

	        fd.append( 'lecture_type', lecture_type);   
	        fd.append( 'api_function', 'insertLecture');
	        fd.append( 'cid', cid);                 

	        $.ajax({
	            url : "lib/api.php",
	            type: "POST",
	            
	            data: fd,                   // Data sent to server, a set of key/value pairs (i.e. form fields and values)
	            contentType: false,         // The content type used when sending data to the server.
	            cache: false,               // To unable request pages to be cached
	            processData:false, 

	            beforeSend: function(xhr){
			       $('#preloader').show();
			   	},

	            success: function(data){ 
	                console.log("DATA: " + data);               
	                if(data == "Success"){
	                    //SHOW LECTURE FEEFEE MODAL  
	                    sessionStorage.filename_data1 = lecture_name; 
	                    $("#filename_data1").html(sessionStorage.filename_data1);
	                    $("#modaltrigger").click();
	               	}
	               	else{
	               		$('#preloader').hide();
						alert("PLEASE INPUT CORRECT DATA!");
					}
	            },

				//PRELOADER
				complete: function(xhr, textStatus){
					$('#preloader').hide();  // #info must be defined somehwere
				},

	            error: function (){
	                //console.log("ERROR");
	            }
	        });	 
	    }
	    else{
	        alert("PLEASE INPUT CORRECT DATA!")	        
	    }
    }
    return false;
}

function selectPriceOption(option){  
    sessionStorage.priceOption = option;
}

function savePrice(){
	var cid = sessionStorage.course_id;
	
	console.log("lprice: " + lprice);
	console.log("priceOption: " + sessionStorage.priceOption);

	if(sessionStorage.priceOption == "fixed"){
		var lprice = $( "#price_sel option:selected" ).text();
	}
	else if(sessionStorage.priceOption == "notfixed"){
		var lprice = document.getElementById("price_inp").value;
	}
	else{
		var lprice = $( "#price_sel option:selected" ).text();	
	}

	console.log("PRICE: " + lprice);
	
	if(!isNaN(lprice) && lprice != ""){

		$.ajax({
	        url : "lib/api.php",
	        type: "POST",
	        data :{
					api_function: 'savePrice',
					lecture_price: lprice,	
					cid: cid				
				}, 

			beforeSend: function(xhr){
		       $('#preloader').show();  // #info must be defined somehwere
		   	},

	        success: function(data){ 
	            console.log("DATA: " + data);              
	            if(data == "Success"){
	                //REDIRECT TO LECTURE 
	                delete sessionStorage.priceOption;
					location.href = "cl_lecture_list.php";
	           }
	        },

	        //PRELOADER
			complete: function(xhr, textStatus){
				$('#preloader').hide();  // #info must be defined somehwere
			},

	        error: function (){
	            //console.log("ERROR");
	        }
	    });
	}
    else{
	    alert("PLEASE INPUT CORRECT DATA!");
	}
	return false;
}


function checkTransaction(idname, val){
		console.log("val: " + val);
		if(val == 1){				    
			var lecture_no = document.getElementById("lecture_no_"+idname).value;
	    	console.log("lecture_no: " + lecture_no);
		}
		else{
			var course_no = document.getElementById("course_no").value;
	    	console.log("course_no: " + course_no);
		}

}

//DISPLAY LECTURES
function displayL(number){
	lecture_no = document.getElementById("lecture_no_"+number).value;
	console.log("LECTURE NUM: " + lecture_no);
	//var data = new Array(); $.cookie("lc_login_id")

	$.ajax({
        url : "lib/api.php",
        type: "POST",
        dataType:"json",
        data :{
				api_function: 'displayLD',
				lecture_no: lecture_no
			}, 

        success: function(data){             
			console.log("LECTURE TYPE: " + data['ltype']);			
			var lecture_type = data['ltype'];			

			switch(lecture_type) {		
				case "mu"://MASHUP
					sessionStorage.st_movie = data['ldata1'];
					sessionStorage.st_pdf= data['ldata2'];					
			    	location.href = "st_mashup.html";			    	
			    	break;	
			    case "pdf"://PDF
			    	sessionStorage.pdf_st = data['ldata1'];
			    	location.href = "st_pdf.html";
			    	break;
			    case "p"://PPT
			    	
			   		break;
			   	case "m"://VIDEO
			    	
			    	break;
			    case "v"://PPT
			    	
			   		break;
			}	
        },
    });
	
	return false;
}

function saveNotes(){
	var notes = document.getElementById("notes").value;
	var nowdate = getNewDate();

	$.ajax({
        url : "lib/api.php",
        type: "POST",
        data :{
				api_function: 'saveNotes',
				notes: notes			
			}, 

        success: function(data){ 
            console.log("DATA: " + data);              
        },
    });
}

//送信処理
function uploadFunction(formid, flg, changeid, modal){ //flb=0（アップロードファイルなし） flg=1（アップロードファイルあり）
	
	//ファイル名のみの取得処理
	//var fn = document.getElementById("FileNameMUSIC").value;
	//var fnr = file_name_js.replace("C:\\fakepath\\", "");
	
	var form = document.getElementById(formid);
    var arr = [];
	var imagefile ="";
	
    for(i=0; i<form.length; i++){
		
		var el = form.elements[i];
		arr.push(el.name + '=' + el.value);
    }
	
//    var sendparam = arr.join('&');

	
	//送信処理
	
	//画像の差替処理（本来は送信処理後戻り値をセットする）
	if(flg == 1){
		
		imageFile = "img/other/" + "img_thumb02.jpg";
		changeImageFile(imageFile, changeid);
	}
	
	
	$('.modal').fadeOut();
	$('#lean_overlay').fadeOut();
}

//文字のバイト数制限
function checkTxtByte(str){
	
	var y = 0;
	var z = 0;
	var txtByte = 0;
	var txtDetailTxt = "";
	
//	esstr = escape(str);
	
	for (x=0; x<str.length; x++){
		
		if(txtByte < 130){
		
			n = escape(str.charAt(x));
			
			if (n.length < 4) txtByte++; else txtByte+=2;
			
			txtDetailTxt += str.charAt(z);
			
			z++;
			
		} else {
			
			y++;
			
		}
	
	}
	
	if(y > 0){
		txtDetailTxt += "...";
	}
	
	return txtDetailTxt;
}

//マッシュアップ作成
function createMashup(pdfpage, thumb_foldername){
	var foldername = thumb_foldername;

	//console.log("foldername: " + foldername)
	
	
//	console.log($(".insert_pdf li").length);

	if($(".insert_pdf") && $(".insert_pdf li").length < 30){
		
		//再生時間の取得
		var playtime = $(".jp-current-time").html();
		var arrMovTime = movPlayTime(playtime);
		var pts = arrMovTime[2];
		var spi_arrval_arrmt = [];
		var insertflg = 0;
		
		//配列の再生成
		arrval = lectureCreateArr();	

		//console.log("createMashup: " + arrval);
				
		//現在より未来の再生時間の登録がないかチェック
		for(i=0; i <arrval.length; i++){
			
			sp_arrval = arrval[i].split("-");
 
			spi_arrval_arrmt.push(sp_arrval[1]);
			
		}
		
		for(j=0; j<spi_arrval_arrmt.length; j++){
			
			if(spi_arrval_arrmt[j] > pts){
				
				insertflg = 1;
				
			}
			
		}		
		
		//htmlの生成
		var htmlcode = "";
		var addcode = "";
		
		if(insertflg == 0){ //過去の時間の内容は登録させない
		
			//リストの生成  ANO TO??
			if($(".editplaytime p").length > 0){
				
				var listval = parseInt($('#listval').val()) + 1;
				
				htmlcode += '<ul class="insert_pdf clearfix">';				
				htmlcode += '<li name="' + listval + '-' + pts + '-' + pdfpage + '" class="className_' + listval + '"><a href="javascript:controlMedia(' + "'" + pts + "'" + '), pdfReload(' + "'" + pdfpage + "'" + ');"><img src="./img/other/img_thum.gif" width="59" /></a><span><a href="javascript:removeList(' + "'.className_" + listval + "'" + ', ' + pdfpage + ');">削除</a></span></li>';
				htmlcode += '</ul>';
				
				$('#listval').val(listval);
				
				$(".editplaytime").html(htmlcode);
				
			} else {
							
				var listval = parseInt($('#listval').val()) + 1;																																								
				
				addcode = '<li name="' + listval + '-' + pts + '-' + pdfpage + '" class="className_' + listval + '"><a href="javascript:controlMedia(' + "'" + pts + "'" + '), pdfReload(' + "'" + pdfpage + "'" + ');"><img src="./img/thumbnails/'+thumb_foldername+'/thumbnail_'+pdfpage+'.jpg" width="59" /></a><span><a href="javascript:removeList(' + "'.className_" + listval + "'" + ', ' + pdfpage + ');">削除</a></span></li>';				

				//console.log("NAME: " + listval + '-' + pts + '-' + pdfpage);

				if($(".insert_pdf")){
					$('#listval').val(listval);
					$(".insert_pdf").append(addcode);
				}
				
			}
			
			$(".error_area").css("display", "none");
			
		} else {
			
			$(".error_area").css("display", "block");
			$(".error_area p").text("現在の時間より未来の内容が登録済みです");
			
		}
		
		//押下サムネイルのオーバー設定
		displayThum();
	
	} else {
		
		$(".error_area").css("display", "block");
		$(".error_area p").text("登録数が上限です");
		
	}
	
}

//pdfの再表示
function pdfReload(page){
	var iframesrc = $(".media").attr("src");
	var spifs = iframesrc.split("/");
	var fnc = spifs.length - 1;
	var filepath = "";
	
	for(i=0; i<spifs.length; i++){
		
		if(i == fnc){
			
			var spifslsp = spifs[fnc].split("#");
			filepath += spifslsp[0] + '#page=' + page;
			
		} else {
			
			filepath +=  spifs[i] + '/';
			
		}
		
	}
	
	$(".media").attr("name", "frameID");
	$(".media").attr("id", "frameID");
	$(".media").attr("src", "");
	$(".media").css("visibility", "hidden");
	$(".media").append('<p class="ifloader"><img src="./css/images/bx_loader.gif"></p>');
	
	//ieでiframeのリロードが実行できないため、一度iframeのsecをブランクにして再設定
	$(function(){
		setTimeout(function(){
			$(".media").attr("src", filepath);
		},100);
	});
	
	$(function(){
		setTimeout(function(){
			$(".media .ifloader").remove();
			$(".media").css("visibility", "visible");
		},1200);
	});
	
}

//動画の再生時間制御
function controlMedia(playtime){
	$("#video_player").jPlayer("play", parseInt(playtime));
}

//リストの削除
function removeList(idname, pdfpage){
	
	$(idname).remove();

	//DEDUCT 1 FROM PDF NO EVERYTIME THUMBNAIL IS REMOVED
	var listval = parseInt($('#listval').val()) - 1;
	$('#listval').val(listval);
	
	if($(".editplaytime ul li").length == 0){
		
		if($(".insert_pdf li").length <= 0){
			pdfReload(1);
			viewpdf = 1;
		} else if($(".insert_pdf li").length > 0) {
			
			//配列の取得
			arrval = lectureCreateArr();
			sp_arrval = arrval[0].split("-");
			
			if(sp_arrval[1] != 0){
				
				pdfReload(1);
				viewpdf = 1;
				
			}
			
		}
		
		$(".editplaytime").html('<p class="insert_pdf_init">初期表示テキスト</p>');
		arrval = [];
		
	} else {
		
		//配列の再生性
		lectureCreateArr();
		
	}
	
	//サムネイルの非オーバー設定
	hiddenThum(pdfpage);
	
}

//動画再生時間の加工
function movPlayTime(time){
	
	var arrMovTime = [];
	
	//再生時間の取得
	var sppt = time.split(":");
	arrMovTime.push(parseInt(sppt[0]));
	arrMovTime.push(parseInt(sppt[1]));
	arrMovTime.push(arrMovTime[0] * 60 + arrMovTime[1]);
	
	return arrMovTime;
	
}

//レクチャー登録数制御
function lectureCreateArr(){
	
	//サムネイルの登録数が2以上の場合
	//KUNG GREATER THAN 0 ANG LI PARA SA LIST NG SELECTED THUMBNAILS
	if($(".insert_pdf li").length > 0){
		arrval = [];

		for(i=0; i<$(".insert_pdf li").length; i++){
			
			j = i + 1;

			var createidname = ".insert_pdf li:nth-child(" + j + ")";
			
			//DADAGDAG NG LI SA CLASS INSERT_PDF 
			//PANO NAGIGING 1-0-1???
			arrval.push($(createidname).attr("name"));
			
		}

	} else {
		
		arrval = [];		
		
	}

	return arrval;
	
}

//レクチャーサムネイル押下時のオーバー設定
function displayThum(){
	
	arrval = lectureCreateArr();
	var spi_arrval_arrpd = [];
	
	for(k=0; k<arrval.length; k++){
			
		sp_arrval = arrval[k].split("-");
		spi_arrval_arrpd.push(sp_arrval[2]);
		
	}
	
	for(l=0; l<spi_arrval_arrpd.length; l++){
		
		classname = ".thummask_" + spi_arrval_arrpd[l];
		
		if($(classname).css("display") == "none"){
			$(classname).css("display", "block");
		}
		
	}
	
}

//レクチャーサムネイル押下時の非オーバー設定
function hiddenThum(pdfpage){
	
	arrval = lectureCreateArr();
	var spi_arrval_arrpd = [];
	
	classname = ".thummask_" + pdfpage;
	
	if($(classname).css("display") == "block"){
		$(classname).css("display", "none");
	}
	
}

//日付の生成
function getNewDate(){
	
	dateval = new Date();
	 
	dateY = dateval.getFullYear();
	dateM = dateval.getMonth() + 1;
	dateD = dateval.getDate();
	dateH = dateval.getHours();
	dateMi = dateval.getMinutes();
	dateS = dateval.getSeconds();
	
	if(dateM < 10){
		dateM = "0" + dateM;
	}
	
	if(dateD < 10){
		dateD = "0" + dateD;
	}
	
	if(dateH < 10){
		dateH = "0" + dateH;
	}
	
	if(dateMi < 10){
		dateMi = "0" + dateMi;
	}
	
	if(dateMi < 10){
		dateS = "0" + dateS;
	}
	
	var createdate = dateY + dateM + dateD + dateH + dateMi + dateS;
	
	return createdate;
	
}