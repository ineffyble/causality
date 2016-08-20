var jcrop_api;

var casualityUploadHandler = function() {
	input = this;
	if (input.files && input.files[0]) {
		casualityInitCropInterface(input.files[0]);
	}
}

var casualityInitCropInterface = function(file) {
	var reader = new FileReader();
	reader.onload = function (e) {
		var read = e.target.result;
		profileImage = new Image();
		profileImage.id = 'causality_jcrop_img';
		profileImage.onload = function() {
			jQuery("#causality_jcrop").append(profileImage);
			jQuery("#causality_jcrop").Jcrop({aspectRatio: 1}, function() {
				jcrop_api = this;
			});
			var dim = jcrop_api.getBounds();
			var x = 0, y = 0, x_ = dim[0], y_ = dim[1];

			var x_r = (x_ / 1) - y_;
			var y_r = (y_ / 1) - x_;

			if (x_r > 0) {
			    x = x_r / 2;
			}
			if (y_r > 0) {
			    y = y_r / 2;
			}

			jcrop_api.setSelect([x, y, dim[0], dim[1]]);
		}
		profileImage.src = read;
	}
	reader.readAsDataURL(file);	
}

var casualityCrop = function() {
	var selection = jcrop_api.tellSelect();
	console.log(selection);
	causalityRenderProfile(selection.x, selection.y, selection.w, selection.h, selection.x2, selection.y2);
}

var causalityRenderProfile = function(x, y, w, h, x2, y2) {
	var canvas = document.getElementById("causality_canvas");
	var ctx = canvas.getContext("2d");
	var overlayImage = new Image();
	overlayImage.src = causality_overlay;
	overlayImage.onload = function() {
		console.log(x);
		console.log(y);
		ctx.drawImage(profileImage, x, y, w, h, 0, 0, 960, 960);
		ctx.drawImage(overlayImage, 0, 0, 960, 960);
		data = canvas.toDataURL();
		jQuery("#causality_output").html('<img src="' + data + '" width="500px" height="500px"/>');		
	}

}

jQuery('#causality_upload').change(casualityUploadHandler);
jQuery("#causality_crop_button").click(casualityCrop);
