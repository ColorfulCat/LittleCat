//设置div起始点坐标
var cloudx = -100,
	cloudy = 100;
var autoMoving = true; //是否自动移动
//云朵
var cloud = document.getElementById('floatdiv');
//设置div行进速度
var xSpeed = 2;
//云朵移动宽度
var w = document.body.clientWidth + 100;
//太阳
var sun = document.getElementById('sunny');

var touchstart = "touchstart",
	touchmove = "touchmove",
	touchend = "touchend";

addTouchListeners();
floatCloud();

function addTouchListeners() {
	//		if(IsPC()){
	//			touchstart = "mousedown";
	//			touchmove = "mousemove";
	//			touchend = "mouseup";
	//			
	//		}
	//太阳点击事件
//	sun.addEventListener(touchstart, function(event) {
//		event.preventDefault();
//		sunStartx = event.touches[0].pageX;
//		sunStarty = event.touches[0].pageY;
//	});
//	sun.addEventListener(touchmove, function(event) {});
//	sun.addEventListener(touchend, function(event) {
//		event.preventDefault();
//		sunEndx = event.changedTouches[0].pageX;
//		sunEndy = event.changedTouches[0].pageY;
//		if (sunEndx - sunStartx < 15 && sunEndy - sunStarty < 15) {
//			openIFrame("http://www.baidu.com/");
//		}
//	});

	//云朵触控事件
	cloud.addEventListener(touchstart, function(event) {
		event.preventDefault();
		autoMoving = false;
	});
	cloud.addEventListener(touchmove, function(event) {
		event.preventDefault(); //remove按键默认效果
		cloud.style.top = event.touches[0].pageY - 30 + "px";
		cloud.style.left = event.touches[0].pageX - 30 + "px";
	});
	cloud.addEventListener(touchend, function(event) {
		event.preventDefault();
		cloudy = event.changedTouches[0].pageY - 30;
		cloudx = event.changedTouches[0].pageX - 30;
		autoMoving = true;
		floatCloud();
	});
}

//function creatCloud(){
//	document.innerHTML = '<div id="floatdiv" style="left: -50;"><img src="img/cloud4.png" height="38" width="auto"></div>'
//}

//云朵自动移动
function floatCloud() {
	if (autoMoving) {
		//比较图片是否到达边界，如查到达边界 改变方向;如未到达边界
		if (cloudx > w || cloudx < -100) {
			xSpeed = -xSpeed;
		}
		cloudx += xSpeed;
		//设置坐标值，起始坐标+速度
		cloud.style.left = cloudx + "px";
		cloud.style.top = cloudy + "px";
		setTimeout("floatCloud()", 100); //延时循环执行
	}
}

function openIFrame(url) {
	
	document.body.innerHTML += "<iframe id='iframepage' class='myIFrame' src='" + url + "' width='" + document.body.clientWidth * 0.6 + "' height='" + document.body.clientHeight * 0.6 + "' scrolling='yes' onLoad='iFrameHeight()'></iframe>";
}

function iFrameHeight() {
	var ifm = document.getElementById("iframepage");

	var subWeb = document.frames ? document.frames["iframepage"].document :

		ifm.contentDocument;

	if (ifm != null && subWeb != null) {

		ifm.height = subWeb.body.clientHeight * 0.8;
		ifm.width = subWeb.body.clientWidth * 0.8;

	}
}