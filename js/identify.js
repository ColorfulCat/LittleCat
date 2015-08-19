window.URL = window.URL || window.webkitURL;
var fileElem = document.getElementById("fileElem"),
	fileList = document.getElementById("fileList");

function handleFiles(obj) {
	var files = obj.files,
		img = new Image();
	if (window.URL) {
		//File API
		alert(files[0].name + "," + files[0].size + " bytes 1");
		img.src = window.URL.createObjectURL(files[0]); //创建一个object URL，并不是你的本地路径
		img.width = 200;
		img.onload = function(e) {
			window.URL.revokeObjectURL(this.src); //图片加载后，释放object URL
		}
		fileList.appendChild(img);
	} else if (window.FileReader) {
		//opera不支持createObjectURL/revokeObjectURL方法。我们用FileReader对象来处理
		var reader = new FileReader();
		reader.readAsDataURL(files[0]);
		reader.onload = function(e) {
			alert(files[0].name + "," + e.total + " bytes 2");
			img.src = this.result;
			img.width = 200;
			fileList.appendChild(img);
		}
	} else {
		//ie
		obj.select();
		obj.blur();
		var nfile = document.selection.createRange().text;
		document.selection.empty();
		img.src = nfile;
		img.width = 200;
		img.onload = function() {
			alert(nfile + "," + img.fileSize + " bytes 3");
		}
		fileList.appendChild(img);
		//fileList.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='image',src='"+nfile+"')";
	}
}