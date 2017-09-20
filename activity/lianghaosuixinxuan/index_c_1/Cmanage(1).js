var collapsed = getcookie('TvE_collapse');

/*
* 通过元素的ID特性来获取元素
*/
function $Obj(objname){
	return document.getElementById(objname);
}

/*
function $(id) {
	return document.getElementById(id);
}
*/

/*
* 获取当前网站根目录 如http://www.xx.com
*/
function getBasePath() {
    var curWwwPath = window.document.location.href;
    var pathName = window.document.location.pathname;
    var pos = curWwwPath.indexOf(pathName);
    var localhostPaht = curWwwPath.substring(0, pos);
    return (localhostPaht);
}

/**
 * 得到上传目录日期格式
 * @return 201503
 */
function getDate() {
	var now = new Date();
	return now.getFullYear()+((now.getMonth()+1)<10?"0":"")+(now.getMonth()+1)+(now.getDate()<10?"0":"")+now.getDate();
}

function collapse_change(menucount) {
	if($Obj('menu_' + menucount).style.display == 'none') {
		$Obj('menu_' + menucount).style.display = '';collapsed = collapsed.replace('[' + menucount + ']' , '');
		$Obj('menuimg_' + menucount).src = 'images/menu_reduce.gif';
	} else {
		$Obj('menu_' + menucount).style.display = 'none';collapsed += '[' + menucount + ']';
		$Obj('menuimg_' + menucount).src = 'images/menu_add.gif';
	}
	setcookie('TvE_collapse', collapsed, 2592000);
}

/*
*hidden html
*/
function addbodyhidden(){
	$('#secqaabody').append($("#secqaabodyhidden").html());
	//if (window.attachEvent) {
		//var newnode = $Obj('secqaabodyhidden').firstChild.cloneNode(true);
		//$Obj('secqaabody').appendChild(newnode);
	//}else{
		//var newnode = $Obj('secqaabodyhidden').innerHTML;
		//$Obj('secqaabody').innerHTML=$Obj('secqaabody').innerHTML+newnode;
	//}
}

function setcopy(text, alertmsg){
	if(is_ie) {
		clipboardData.setData('Text', text);
		alert(alertmsg);
	} else if(prompt('Press Ctrl+C Copy to Clipboard', text)) {
		alert(alertmsg);
	}
}

function docopy(value){
	//<a href="javascript:docopy('要复制的内容')">点击复制</a>
	window.clipboardData.setData("Text",value);
	alert("复制成功！");
}

function getcookie(name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}
function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
	+ (expires ? '; expires=' + expires.toGMTString() : '')
	+ (path ? '; path=' + path : '/')
	+ (domain ? '; domain=' + domain : '')
	+ (secure ? '; secure' : '');
}


function AllCheck(type, form, value, checkall, changestyle) {
	var checkall = checkall ? checkall : 'chkall';
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(type == 'option' && e.type == 'radio' && e.value == value && e.disabled != true) {
			e.checked = true;
		} else if(type == 'value' && e.type == 'checkbox' && e.getAttribute('chkvalue') == value) {
			e.checked = form.elements[checkall].checked;
		} else if(type == 'prefix' && e.name && e.name != checkall && (!value || (value && e.name.match(value)))) {
			e.checked = form.elements[checkall].checked;
			if(changestyle && e.parentNode && e.parentNode.tagName.toLowerCase() == 'li') {
				e.parentNode.className = e.checked ? 'checked' : '';
			}
		}
	}
}

function CheckAll(form)
{
	for (var i=0;i<form.elements.length-1;i++)
	{
		var e = form.elements[i];
		if( e.type == 'checkbox'){
			e.checked = ifcheck;
		}
	}
	ifcheck = ifcheck == false ? true : false;
}
ifcheck = true;


function addMouseEvent(obj){
	var checkbox,atr,ath,i;
	atr=obj.getElementsByTagName("tr");
	for(i=0;i<atr.length;i++){
		atr[i].onclick=function(){
			ath=this.getElementsByTagName("th");
			checkbox=this.getElementsByTagName("input")[0];
			if(!ath.length && checkbox.getAttribute("type")=="checkbox"){
				if(this.className!="table-IptIn"){
					this.className="table-IptIn";
					checkbox.checked=true;
				}else{
					this.className="";
					checkbox.checked=false;
				}
			}
		}
	}
}

//删除DIV
function closediv(obj) {
	//$("#"+obj).fadeOut(500);
	var o = $Obj(obj);
	var i = 1; s = 0.1;
	var opacitytimer = setInterval(function () {
		i += s; s = i < 0 ? 0.01 : (i > 1 ? -0.01 : s);
		if (i < 0.03) {
			clearInterval(opacitytimer);
			o.style.opacity = 1;
			o.style.display = "none";
		} else {
			o.style.opacity = i;
		}
	}, 1);
}

function noneblock(targetid,atargetid){
	var input = document.getElementsByTagName("div"); //获取页面所有div 的ID
	var ainput = document.getElementsByTagName("a");

	for(var i=0;i<input.length;i++)
	{
		  if(input.item(i).id.indexOf("h") >= 0 )//判断input的id中是否包含h字符串
		   {
				$Obj(input.item(i).id).style.display = "none";//隐藏控件
		   }
	}

	for(var i=0;i<ainput.length;i++)
	{
		  if(ainput.item(i).id.indexOf("i") >= 0 )
		   {
				$Obj(ainput.item(i).id).className = "";
		   }
	}

    var target=$Obj(targetid);
	var atarget=$Obj(atargetid);
	target.style.display = target.style.display=="none" ? "block" : "none";
	atarget.className = atarget.className=="" ? "current" : "";
}

function blocknone(targetid){
	var input = document.getElementsByTagName("tr"); //获取页面所有input

	for(var i=0;i<input.length;i++)
	{
		  if(input.item(i).id.indexOf("pm_") >= 0 )//判断input的id中是否包含h字符串
		   {
			   if(input.item(i).id != targetid)
			   {
					$Obj(input.item(i).id).style.display = "none";//隐藏控件
			   }
		   }
	}

	var ifview = $Obj(targetid);
	ifview.style.display = ifview.style.display == 'none' ? '' : 'none';
}

function ShowUrlTr(){
	var jumpTest = $Obj('isjump');
	if(jumpTest.checked){
		$("#redirecturltr").removeAttr("style");
		$("#detail").css("display","none");
	} else {
		$("#redirecturltr").css("display","none");
		$("#detail").removeAttr("style");
	}
}

function HidUrlTr(){
	$("#redirecturltr").removeAttr("style");
	$("#detail").css("display","none");
}

function SeePic(img,f){
   if ( f.value != "" ) { img.src = f.value; }
}

function checkbox(obj,num){
  var id;
  for (i=1;i<=num;i++){
	id=obj+i;
	if($Obj(id).checked==""){
		$Obj(id).checked="checked";
	}
	else{
		$Obj(id).checked="";
	}
  }
}

/**
 * 判断是否为手机号码
*/
function is_phone(phone){
	if(phone=='empty' || !phone.match(/^0{0,1}(13[0-9]|14[0-9]|15[0-9]|18[0-9])[0-9]{8}$/)){
		return false;
	}else{
		return true;
	}
}

function in_array(needle, haystack) {
	if(typeof needle == 'string' || typeof needle == 'number') {
		for(var i in haystack) {
			if(haystack[i] == needle) {
				return true;
			}
		}
	}
	return false;
}

/*保留最后一个/符号后面的内容并去掉.后面的字符
 * 如 /attachment/201501/pre_1420765468xsadx.jpg 将得到pre_1420765468xsadx
*/
function queryId(data){
	return data.substring(data.lastIndexOf("/")+1,data.lastIndexOf("."))
}

/*loadin效果*/
function loading(text) { // Ajax加载效果
	if(text === false){	//关闭效果
		layer.close(loadi);
	}else{	//打开加载效果
		loadi = layer.load(text);
	}
}

/*加载js*/
function loadjs(filename){
	var fileref = document.createElement('script');
	fileref.setAttribute("type","text/javascript");
	fileref.setAttribute("src",filename);
	if(typeof fileref != "undefined"){
        document.getElementsByTagName("head")[0].appendChild(fileref);
    }
}

/*加载css*/
function loadcss(filename){
	var fileref = document.createElement('link');
	fileref.setAttribute("rel","stylesheet");
	fileref.setAttribute("type","text/css");
	fileref.setAttribute("href",filename);
	if(typeof fileref != "undefined"){
		document.getElementsByTagName("head")[0].appendChild(fileref);
	}
}

/*获取URL GET参数*/
function request(strParame) {
	var args = new Object( );
	var query = location.search.substring(1);

	var pairs = query.split("&"); // Break at ampersand
	for(var i = 0; i < pairs.length; i++) {
		var pos = pairs[i].indexOf('=');
		if (pos == -1) continue;
		var argname = pairs[i].substring(0,pos);
		var value = pairs[i].substring(pos+1);
		value = decodeURIComponent(value);
		args[argname] = value;
	}
	return args[strParame];
}

/*如果是错误提示去掉error:标示*/
function replaceErroe(message){
    if(message && message.indexOf('error:')!=-1){
		message = message.replace(/error:/,'');
	}
	return message;
}

/*iframe窗体*/
function setbg(boxtitle, pwidth, pheight, url) {
	$.layer({
		type: 2,
		title: boxtitle,
		maxmin: false,
		shadeClose: true, //开启点击遮罩关闭层
		area : [pwidth , pheight],
		offset : ['100px', ''],
		iframe: {src: url}
	});
}

/*弹出窗口 type1-16图标样式*/
function setbox(msg, title, type) {
	msg = replaceErroe(msg);
	if(!type){type = parseInt('-1');}else{type = parseInt(type);}
	if(title==='auto'){
		layer.msg(msg , 2, type);	//第二个参数为多少秒后关闭
	}else if(!title){
		layer.alert(msg, type, false);
	}else{
		layer.alert(msg, type, title);
	}
}

/*手机端弹出窗口 type1-16图标样式*/
function setboxM(msg, title) {
	msg = replaceErroe(msg);
	if(title==='auto'){
        layer.open({
            content: msg,
            style: 'background-color:#000; color:#fff; border:none;opacity:0.6;',
            time: 2
        });
    } else {
        layer.open({
            content: msg,
            btn: ['OK'],
            //yes: function(index){
                //layer.close(index);
                //alert("click");
            //}
        });
    }
}

/*弹出窗口 传入自定义html*/
function setboxHtml(html) {
	$.layer({
	   type: 1,   //0-4的选择,
		title: false,
		border: [0],
		closeBtn: [0],
		shadeClose: true,
		area: ['auto', 'auto'],
		page: {
			html: html
		}
	})
}

/*AJAX提示信息并且关掉弹出窗口 type1-16图标样式*/
function setboxAjax(msg, type) {
	msg = replaceErroe(msg);
	if(!type){type = parseInt('-1');}else{type = parseInt(type);}
	//获取当前窗口索引
	var index = parent.layer.getFrameIndex(window.name);
	parent.layer.msg(msg, 1, type);	//第二个参数为多少秒后关闭
	parent.layer.close(index);
}

/*提示信息确定跳转地址*/
function setboxUrl(msg, url, type) {
	if(msg==''){
		document.location.href = url;
	} else {
        setbox(msg, 'auto', type);
        setTimeout(function() {	//延时刷新
            document.location.href = url;
            },
        2000);
    }
}

/*手机端提示信息跳转地址*/
function setboxUrlM(msg, url) {
    layer.open({
        content: msg,
        style: 'background-color:#000; color:#fff; border:none;opacity:0.6;',
        time: 20000,
    });
    setTimeout(function() {	//延时刷新
            document.location.href = url;
        },
    2000);
}

/*
* 弹出输入框
* funcname 输入后调用的方法名val传值
* param 自定义传值到调用方法里
* title_value 标题 | 分割title value
* length输入长度默认9个字符
* 调用 setboxInput('回调的方法名_test','自定义传值_param','标题|输入框默认值','限制长度')
* 使用方法
	loadjs("../js/layer/extend/layer.ext.js");
	function test(val, param){

	}
*/
function setboxInput(funcname,param,title_value,length){
	if(!length){length = parseInt('9');}else{length = parseInt(length);}
	title_value = title_value.split("|");
	boxtitle = title_value[0];	//标题
	boxval = title_value[1];	//默认值
	layer.prompt(
		{
			title: boxtitle,
			type:0,	//支持普通文本框（0）、密码框（1）、文件框（2）、多行文本框（3）,
			val:boxval,
			length:length	//长度
		},
		function(val, index, elem){	//确定
			//val输入值，index为该层索引，elem为表单元素。
			try{
				window[funcname](val,param);
			}catch(e){
				setbox(e+' '+funcname+'()');
			}
			//setbox('您输入了：'+val, 'auto');
			layer.close(index);
		},
		function(){	//取消
			//setbox('已取消', 'auto');
		}
	)
}

/*新效果弹出窗口*/
/*
	$(".setNBox").css('color',"#fff");	//字体颜色
	$(".setNBox").css('background-color', "#E5005A");	//背景颜色
	$(".setNBox_title").html('标题');	//标题
	$(".setNBox_description").html('副标题');	//副标题
	$(".setNBox_content").html('内容<br>换行asdf');	//内容
	$(".setNBox_btn").css('color', "#E5005A");	//按钮字体颜色
	$(".setNBox_btn").css('background-color', "#E89706");	//按钮颜色
*/
function setNBox() {
    html = '<div class="setNBox" id="setNBox"><style type="text/css">.setNBox{display:block;color:#fff;position:fixed;width:80%;left:10%;top:13%;background-color:#1B94C0;border:solid 2px #fff;-webkit-border-radius:5px;border-radius:5px;z-index:2002}.setNBox .close{overflow:hidden;}.setNBox .close span{float:right;margin:5px;font-size:20px;font-weight:900;color:#fff}.setNBox .setNBox_title{width:80%;margin-left:10%;font-size:20px;font-weight:bold;}.setNBox .setNBox_description{width:80%;margin-left:10%;font-size:16px;margin-top:10px;}.setNBox .setNBox_content{width:80%;margin-left:10%;font-size:12px;margin-top:10px;line-height:16px;}.setNBox .nb_bottom{margin:10px 0}.setNBox .setNBox_btn{width:60%;left:20%;position:relative;height:40px;border:0px;-webkit-border-radius:5px;border-radius:5px;font-size:20px;color:#fff;font-weight:bold;}</style><div class="close"><span id="setNBoxbtnX">×</div><div class="setNBox_title"></div><div class="setNBox_description"></div><div class="setNBox_content"></div><div class="nb_bottom"><button class="setNBox_btn" id="setNBoxbtn">确 认</button></div></div>';
    if($(".setNBox").length==0){
        $("body").append(html);
    }
    $("#setNBoxbtn").on("click", function () {
        $("#setNBox").remove();
    })
    $("#setNBoxbtnX").on("click", function () {
        $("#setNBox").remove();
    })
}

/*提示信息 点击确定执行方法 type1-16图标样式*/
function setboxAction(value, type){
	if(!type){type = parseInt('-1');}else{type = parseInt(type);}
	$.layer({
		shade: [0],
		area: ['auto','auto'],
		dialog: {
			msg: '提示语名',
			btns: 2,
			type: type,
			btn: ['确定','取消'],
			yes: function(){
				//执行方法
				setbox('执行方法', 'auto');
			}, no: function(){
				//执行方法
				return;
			}
		}
	});
}