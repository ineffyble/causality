var jcrop_api, causalityStyle;

var causalityActivateUpload = function() {
	jQuery("#causality_activate_upload, #causality_facebook_login").fadeOut(function() {jQuery("#causality_upload").fadeIn();});
}

var causalityInitFacebookSDK = function() {
	jQuery.ajaxSetup({ cache: true });
	jQuery.getScript('//connect.facebook.net/en_US/sdk.js', function() {
		FB.init({
			appId: causality_fb_app_id,
			version: 'v2.7'
		});
	});
}

var causalityFacebookLogin = function() {
	FB.getLoginStatus(function(response) {
		if (response.status === 'connected') {
			causalityGetFacebookPhoto();
		} else {
			FB.login(function() {
				causalityGetFacebookPhoto();
			});
		}
	});
}

var causalityGetFacebookPhoto = function() {
	FB.api('/me/picture?width=960&height=960', function(response) {
		if (response && !response.error) {
			casualityInitCropInterface(response.data.url);
		}
	});
}

var casualityUploadHandler = function() {
	var input = this;
	if (input.files && input.files[0]) {
		causalityReadFile(input.files[0]);
		casualityInitCropInterface(input.files[0]);
	}
};

var causalityReadFile = function(file) {
	var reader = new FileReader();
	reader.onload = function(e) {
		casualityInitCropInterface(e.target.result);
	}
	reader.readAsDataURL(file);
};

var casualityInitCropInterface = function(imagesrc) {
	jQuery("#causality_facebook_login, #causality_activate_upload, #causality_upload").fadeOut(function(){jQuery("#causality_jcrop_container").fadeIn()});;
	jQuery("#causality_jcrop_container").html('<div id="causality_jcrop"></div>');
	profileImage = new Image();
	profileImage.crossOrigin = "Anonymous";
	profileImage.id = 'causality_jcrop_img';
	profileImage.onload = function() {
		jQuery("#causality_jcrop").html(profileImage);
		jQuery(profileImage).width(jQuery(profileImage).width()); //Set the width to an explicit px based on current width (which should be max-width: 100%). This is required because jCrop computes the max-width wrong (ignores padding).
		jQuery("#causality_jcrop").Jcrop({aspectRatio: 1, trueSize: [profileImage.naturalWidth, profileImage.naturalHeight]}, function() {
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
		jQuery("#causality_crop").fadeIn();
	};
	profileImage.src = imagesrc;
};

var causalityCrop = function() {
	var selection = jcrop_api.tellSelect();
	console.log(selection);
	causalityRenderProfile(selection.x, selection.y, selection.w, selection.h, selection.x2, selection.y2);
};

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
		jQuery("#causality_jcrop_container, #causality_crop").fadeOut(function() {jQuery("#causality_output, #causality_overlay").fadeIn();});;
	};

};

var causalitySetOverlay = function(url) {
	causality_overlay = url;
	if (!causalityStyle) {
		causalityStyle = document.createElement('style');
		causalityStyle.type = 'text/css';
		jQuery("head").append(causalityStyle);
	}
	jQuery(causalityStyle).html(".jcrop-holder div div .jcrop-tracker{background: url('" + causality_overlay + "');");
	if (jQuery("#causality_output").html()) {
		causalityCrop();
	} else {
		jQuery("#causality_overlay").fadeOut(function(){jQuery("#causality_facebook_login, #causality_activate_upload").fadeIn();});
	}
}

jQuery('#causality_upload').change(casualityUploadHandler);
jQuery("#causality_crop").click(causalityCrop);
jQuery("#causality_overlay").change(function() {
	if (this.value) {
		causalitySetOverlay(this.value);
	}
});
causalityInitFacebookSDK();	
