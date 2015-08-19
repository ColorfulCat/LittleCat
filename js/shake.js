var color = new Array('#fff', '#ff0', '#f00', '#000', '#00f', '#0ff');
var shakeCount = 0;
if (window.DeviceMotionEvent) {
	var speed = 25;
	var x = y = z = lastX = lastY = lastZ = 0;
	window.addEventListener('devicemotion', function() {
		var acceleration = event.accelerationIncludingGravity;
		x = acceleration.x;
		y = acceleration.y;
		if (Math.abs(x - lastX) > speed || Math.abs(y - lastY) > speed) {
			shakeCount++;
			if (shakeCount > 3) {
				shakeCount = 0;
				//	document.body.style.backgroundColor = color[Math.round(Math.random() * 10) % 6];
				sendMessageRequest("get_greet_request");
				//alert('主人！别摇了！我听话还不行嘛！');
			}
		}
		lastX = x;
		lastY = y;
	}, false);
}