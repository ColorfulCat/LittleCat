//send message
function sendMessage() {
	var text = document.getElementById('inputText').value;
	if (text.length > 0) {
		document.getElementById('chatContent').innerHTML += '<li class="me">' + text + '</li>';
		document.getElementById('inputText').value = "";
		//移动到底端
		//scrollBy(0, document.body.scrollHeight);
		document.getElementById('chatContentFootDiv').scrollIntoView(); //pager里面用这句

		if (text == "我的位置" || text == "我在哪") {
			getLocation(text);
			return;
		}
		sendMessageRequest(text);
	}
}



//接收到信息
function setReceivedMessage(message) {
		if (message.length > 0) {
			//			alert("setReceivedMessage" + message);
			showMessage(message);
		}
	}
	//发送请求

function sendMessageRequest(message) {
	if (message.length > 0) {
		message = message.replace(/\+/g, '%2B');
		message = message.replace(/\&/g, '%26');
	}

	var xmlhttp;
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			//			alert("send successful");
			setReceivedMessage(xmlhttp.responseText);
		} else {}
	}
	xmlhttp.open("GET", "http://www.catandroid.com/server.php?message=" + message + "&userId=" + userId, true);
	xmlhttp.send();

}

function showMessage(message) {
	document.getElementById('chatContent').innerHTML += '<li class="you">' + message + '</li>';
	//	scrollBy(0, document.body.scrollHeight);
	document.getElementById('chatContentFootDiv').scrollIntoView(); //pager里面用这句
}


//页面初始化监听
var userId = 0;

function onPageInit() {
		if (IsPC()) {
			//			alert("请尽量在手机上访问本喵大人好嘛！");
		}
		document.getElementById('chatContentFootDiv').style.height = document.getElementById('inputDiv').offsetHeight + "px";
		userId = localStorage.getItem("userId");
		if (userId == null || userId.length <= 0) {
			userId = new Date().getTime();
			localStorage.setItem("userId", userId);
		} 
		sendMessageRequest("get_greet_request"); 

	}
	//键盘事件监听

function onKeyDown() {
	if (window.event.keyCode == 13) { //ENTER
		sendMessage();
	} else if (window.event.keyCode == 0) {

	}
}

//是否PC
function IsPC() {
	var userAgentInfo = navigator.userAgent;
	var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
	var flag = true;
	for (var v = 0; v < Agents.length; v++) {
		if (userAgentInfo.indexOf(Agents[v]) > 0) {
			flag = false;
			break;
		}
	}
	return flag;
}

//获取经纬度
function getLocation(type) {
	if (navigator.geolocation) {
		if (type == "我的位置") {
			navigator.geolocation.getCurrentPosition(showPosition, showError);
		} else if (type == "我在哪") {
			navigator.geolocation.getCurrentPosition(showPlace, showError);
		}
	} else {
		showMessage("不好意思啦，你的设备获取不到经纬度~");
	}
}

function showPlace(position) {
	var latlon = position.coords.latitude + "," + position.coords.longitude;
	sendMessageRequest("地理位置" + latlon);
}


function showPosition(position) {
	var lonlat = (position.coords.longitude + 0.01375) + "," + (position.coords.latitude + 0.0048);
	var img_url = "http://api.map.baidu.com/staticimage?center=" + lonlat + "&width=200&height=200&zoom=15";
	showMessage("你在这里哦！</br> <img class='messageImg' width='200' height='200' src='" + img_url + "' />");
}

function showError(error) {
	switch (error.code) {
		case error.PERMISSION_DENIED:
			showMessage("不让看你的经纬度，我咋知道你在哪里呀！");
			break;
		case error.POSITION_UNAVAILABLE:
			showMessage("你这个地方太隐蔽了，找不着~");
			break;
		case error.TIMEOUT:
			showMessage("哎呀，找了好久，还是没找到你在哪里~");
			break;
		case error.UNKNOWN_ERROR:
			showMessage("地球已经被外星人攻占了，我也找不到你在哪里啦！");
			break;
	}
}