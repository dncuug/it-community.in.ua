jQuery(document).ready(function() {
	jQuery('.wp_insert_notice').on('click', '.notice-dismiss', function() {
		jQuery.post(
			jQuery('#wp_insert_admin_notice_ajax').val(), {
				'action': 'wp_insert_admin_notice_dismiss',
				'wp_insert_admin_notice_nonce': jQuery('#wp_insert_admin_notice_nonce').val(),
			}, function(response) { }
		);
	});
	
	jQuery('.wp_insert_adstxt_adsense_notice').on('click', '.notice-dismiss', function() {
		jQuery.post(
			jQuery('#wp_insert_adstxt_adsense_admin_notice_ajax').val(), {
				'action': 'wp_insert_adstxt_adsense_admin_notice_dismiss',
				'wp_insert_adstxt_adsense_admin_notice_nonce': jQuery('#wp_insert_adstxt_adsense_admin_notice_nonce').val(),
			}, function(response) { }
		);
	});
});