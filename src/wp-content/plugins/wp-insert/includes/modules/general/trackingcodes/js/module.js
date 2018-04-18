jQuery(document).ready(function() {
	wp_insert_click_handler(
		'wp_insert_trackingcodes_google_analytics',
		'Tracking Codes : Google Analytics',
		'480',
		'480',
		function() {
			jQuery('#wp_insert_trackingcodes_analytics_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
		},
		function() {
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': 'wp_insert_trackingcodes_google_analytics_form_save_action',
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
					'wp_insert_trackingcodes_analytics_status': jQuery('#wp_insert_trackingcodes_analytics_status').prop('checked'),
					'wp_insert_trackingcodes_analytics_code': jQuery('#wp_insert_trackingcodes_analytics_code').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	wp_insert_click_handler(
		'wp_insert_trackingcodes_header',
		'Tracking Codes : Header',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('#wp_insert_trackingcodes_header_code').css('height', (jQuery("body").height() * 0.5)+'px');
			jQuery('#wp_insert_trackingcodes_header_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
		},
		function() {
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': 'wp_insert_trackingcodes_header_form_save_action',
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
					'wp_insert_trackingcodes_header_status': jQuery('#wp_insert_trackingcodes_header_status').prop('checked'),
					'wp_insert_trackingcodes_header_code': jQuery('#wp_insert_trackingcodes_header_code').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	wp_insert_click_handler(
		'wp_insert_trackingcodes_footer',
		'Tracking Codes : Footer',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('#wp_insert_trackingcodes_footer_code').css('height', (jQuery("body").height() * 0.5)+'px');
			jQuery('#wp_insert_trackingcodes_footer_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
		},
		function() {
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': 'wp_insert_trackingcodes_footer_form_save_action',
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
					'wp_insert_trackingcodes_footer_status': jQuery('#wp_insert_trackingcodes_footer_status').prop('checked'),
					'wp_insert_trackingcodes_footer_code': jQuery('#wp_insert_trackingcodes_footer_code').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
});