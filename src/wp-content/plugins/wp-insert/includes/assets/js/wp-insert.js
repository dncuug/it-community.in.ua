jQuery(document).ready(function() {	
	jQuery(window).resize(function() {
		jQuery('.wp-insert .plugin-card').each(function() {	
			jQuery(this).css('height', 'auto');
		});
		jQuery('.wp-insert .plugin-card').each(function() {		
			var initialCard = jQuery(this);
			var rowTop = initialCard.position().top;
			jQuery('.wp-insert .plugin-card').each(function() {		
				if(rowTop == jQuery(this).position().top) {
					if(initialCard.height() < jQuery(this).height()) {
						initialCard.height(jQuery(this).height());
					}
				}
			});
		});
	});
	jQuery(window).resize();
	
	wp_insert_click_handler(
		'wp_insert_abtesting_configuration',
		'Multiple Ad Networks / A-B Testing : Configuration',
		'480',
		'480',
		function() { },
		function() {
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': "wp_insert_abtesting_configuration_form_save_action",
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
					'wp_insert_abtesting_mode': jQuery('input[name=wp_insert_abtesting_mode]:checked').val()
				}, function(response) { }			
			);
		},
		function() { }
	);
});

function wp_insert_click_handler(target, title, width, height, openAction, UpdateAction, closeAction) {
	jQuery('#'+target).click(function() {
		jQuery('<div id="'+target+'_dialog"></div>').html('<div class="wp_insert_ajaxloader"></div>').dialog({
			'modal': true,
			'resizable': false,
			'width': width,
			'maxWidth': width,
			'maxHeight': height,
			'title': title,
			position: { my: 'center', at: 'center', of: window },
			open: function (event, ui) {
				jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
				jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
				jQuery('.ui-dialog-buttonpane button:contains("Update")').button('disable');
				
				jQuery.post(
					jQuery('#wp_insert_admin_ajax').val(), {
						'action': target+'_form_get_content',
						'wp_insert_nonce': jQuery('#wp_insert_nonce').val()
					}, function(response) {
						jQuery('.wp_insert_ajaxloader').hide();
						jQuery('.ui-dialog-content').html(response);
						jQuery('.ui-accordion .ui-accordion-content').css('max-height', (jQuery("body").height() * 0.45));
						jQuery('.ui-dialog-buttonpane button:contains("Update")').button('enable');
						openAction();
						jQuery('.ui-dialog').css({'position': 'fixed'});
						jQuery('#'+target+'_dialog').delay(500).dialog({position: { my: 'center', at: 'center', of: window }});
						
					}			
				);
			},
			buttons: {
				'Update': {
					text: 'Update',
					icons: { primary: "ui-icon-gear" },
					click: function() {
						if(UpdateAction() != 'false') {
							jQuery(this).dialog('close');
						}
					}
				},
				Cancel: {
					text: 'Cancel',
					icons: { primary: "ui-icon-cancel" },
					click: function() {
						if(closeAction() != 'false') {
							jQuery(this).dialog('close');
						}
					}
				}
			},
			close: function() {
				closeAction();
				jQuery(this).dialog('destroy');
			}
		})
	});
}