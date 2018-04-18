jQuery(document).ready(function() {
	wp_insert_click_handler(
		'wp_insert_legalpages_privacy_policy',
		'Legal Pages : Privacy Policy',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() { },
		function() {
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': 'wp_insert_legalpages_privacy_policy_form_save_action',
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
					'wp_insert_legalpages_privacy_policy_content': jQuery('#wp_insert_legalpages_privacy_policy_content').val(),
					'wp_insert_legalpages_privacy_policy_assigned_page': jQuery('#wp_insert_legalpages_privacy_policy_assigned_page').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	wp_insert_click_handler(
		'wp_insert_legalpages_terms_conditions',
		'Legal Pages : Terms and Conditions',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() { },
		function() {
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': 'wp_insert_legalpages_terms_conditions_form_save_action',
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
					'wp_insert_legalpages_terms_conditions_content': jQuery('#wp_insert_legalpages_terms_conditions_content').val(),
					'wp_insert_legalpages_terms_conditions_assigned_page': jQuery('#wp_insert_legalpages_terms_conditions_assigned_page').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	wp_insert_click_handler(
		'wp_insert_legalpages_disclaimer',
		'Legal Pages : Disclaimer',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() { },
		function() {
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': 'wp_insert_legalpages_disclaimer_form_save_action',
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
					'wp_insert_legalpages_disclaimer_content': jQuery('#wp_insert_legalpages_disclaimer_content').val(),
					'wp_insert_legalpages_disclaimer_assigned_page': jQuery('#wp_insert_legalpages_disclaimer_assigned_page').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	wp_insert_click_handler(
		'wp_insert_legalpages_copyright',
		'Legal Pages : Copyright Notice',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() { },
		function() {
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': 'wp_insert_legalpages_copyright_form_save_action',
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
					'wp_insert_legalpages_copyright_content': jQuery('#wp_insert_legalpages_copyright_content').val(),
					'wp_insert_legalpages_copyright_assigned_page': jQuery('#wp_insert_legalpages_copyright_assigned_page').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
});

function wp_insert_legalpages_generate_page(target, title) {
	jQuery('.ui-dialog-buttonpane button:contains("Update")').button('disable');
	jQuery('#'+target+'_generate_page').hide();
	jQuery('#'+target+'_accordion .wp_insert_ajaxloader_flat').show();
	jQuery.post(
		jQuery('#wp_insert_admin_ajax').val(), {
			'action': target+'_form_generate_page_action',
			'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
		}, function(response) {
			if(response != '0') {
				jQuery('#'+target+'_assigned_page').append(jQuery('<option>', {value: response, text: title})).val(response);
			}
			jQuery('#'+target+'_generate_page').show();
			jQuery('#'+target+'_accordion .wp_insert_ajaxloader_flat').hide();
			jQuery('.ui-dialog-buttonpane button:contains("Update")').button('enable');
		}
	);	
}