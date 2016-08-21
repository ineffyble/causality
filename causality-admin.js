var causalityAddOverlayField = function(e) {
	jQuery("#causality_overlay_fields").append("<div class='causality_overlay_field'><b>Title</b><input type='text' class='causality_overlay_title' value=''><b>URL</b><input type='text' class='causality_overlay_url' value=''></div><br>");
	e.preventDefault();
}

jQuery("#causality_settings_form").submit(function(event) {
	overlays = {};
	jQuery.each(jQuery(".causality_overlay_field"), function(i, f) {
		title = jQuery(this).find(".causality_overlay_title").val();
		url = jQuery(this).find(".causality_overlay_url").val();
		if (title && url) {
			overlays[title] = url;
		}
	});
	jQuery("#causality_overlays").val(JSON.stringify(overlays));
});