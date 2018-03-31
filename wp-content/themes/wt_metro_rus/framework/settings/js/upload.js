jQuery(document).ready(function() {

	jQuery('#wt_logo_upload_button').click(function() {
	
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#wt_options\\[wt_logo_url\\]').val(imgurl);
			tb_remove();
		}
		
		tb_show('Upload Logo Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
		return false;
	
	});
	
	jQuery('#wt_favicon_button').click(function() {
	
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#wt_options\\[wt_favicon\\]').val(imgurl);
			tb_remove();
		}
		
		tb_show('Upload Favicon Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
		return false;
	
	});
	
	jQuery('#wt_apple_touch_button').click(function() {
	
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#wt_options\\[wt_apple_touch\\]').val(imgurl);
			tb_remove();
		}
		
		tb_show('Upload Apple Touch Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
		return false;
	
	});
	
	jQuery('#wt_bg_upload_button').click(function() {
	
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#wt_options\\[wt_custom_bg\\]').val(imgurl);
			tb_remove();
		}
		
		tb_show('Upload Custom Background Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
		return false;
	
	});	
});