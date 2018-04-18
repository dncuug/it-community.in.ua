jQuery(document).ready(function() {	
	wp_insert_click_handler(
		'wp_insert_adstxt_generate',
		'Create / Update ads.txt',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('#wp_insert_adstxt_content').css('height', (jQuery('body').height() * 0.5)+'px');
			jQuery('.ui-dialog-buttonset').find('button').first().unbind('click').click(function() {
				jQuery('.ui-dialog-buttonset').find('button').last().button('disable');
				jQuery('.ui-dialog-titlebar').find('button').last().button('disable');
				var wp_insert_adstxt_content =  jQuery('#wp_insert_adstxt_content').val();
				jQuery('.ui-dialog-content').html('<div class="wp_insert_ajaxloader"></div>');
				jQuery('.wp_insert_ajaxloader').show();
				jQuery.post(
					jQuery('#wp_insert_admin_ajax').val(), {
						'action': 'wp_insert_adstxt_generate_form_save_action',
						'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
						'wp_insert_adstxt_content': wp_insert_adstxt_content,
					}, function(response) {
						if(response == '###SUCCESS###') {
							jQuery('.ui-dialog-titlebar').find('button').last().button('enable').click();
						} else {
							jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
							jQuery('.ui-dialog-buttonset').find('button').last().button('enable');
							jQuery('.ui-dialog-titlebar').find('button').last().button('enable');
							jQuery('.ui-dialog-content').html(response);
						}
					}
				);
			});
		},
		function() { },
		function() { }
	);
	
	if(window.location.href.indexOf('#wp_insert_adstxt_adsense_auto_update') > -1) {
		wp_insert_adstxt_adsense_auto_update();
	}
});

function wp_insert_adstxt_adsense_auto_update() {
	jQuery.post(
		jQuery('#wp_insert_adstxt_adsense_admin_notice_ajax').val(), {
			'action': 'wp_insert_adstxt_adsense_auto_update',
			'wp_insert_adstxt_adsense_admin_notice_nonce': jQuery('#wp_insert_adstxt_adsense_admin_notice_nonce').val(),
		}, function(response) {
			if(response != '###SUCCESS###') {
				jQuery(response).dialog({
					'modal': true,
					'resizable': false,
					'title': 'Ads.txt Auto Updation Failed',
					'width': jQuery("body").width() * 0.5,
					'maxWidth': jQuery("body").width() * 0.5,
					'maxHeight': jQuery("body").height() * 0.9,
					position: { my: 'center', at: 'center', of: window },
					open: function (event, ui) {
						jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
						jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
					},
					buttons : {
						'Cancel': function() {
							jQuery(this).dialog("close");
						}
					},
					close: function() {
						jQuery(this).dialog('destroy');
					}
				});
			} else {
				jQuery('.wp_insert_adstxt_adsense_notice').hide();
			}
		}
	);
}

function wp_insert_adstxt_add_entry() {
	var wp_insert_adstxt_new_entry_domain = jQuery("#wp_insert_adstxt_new_entry_domain").val();
	var wp_insert_adstxt_new_entry_pid = jQuery("#wp_insert_adstxt_new_entry_pid").val();
	var wp_insert_adstxt_new_entry_type = jQuery("#wp_insert_adstxt_new_entry_type").val();
	var wp_insert_adstxt_new_entry_certauthority = jQuery("#wp_insert_adstxt_new_entry_certauthority").val();
	var wp_insert_adstxt_content = jQuery("#wp_insert_adstxt_content").val();
	var defaultBorderColor = jQuery("#wp_insert_adstxt_new_entry_domain").css("border-color");
	var defaultLabelColor = jQuery("#wp_insert_adstxt_new_entry_domain").parent().find("small").css("color");
	
	var isValidated = true;
	jQuery("#wp_insert_adstxt_new_entry_domain").css({"border-color": defaultBorderColor}).parent().find("small").css({"color": defaultLabelColor});
	jQuery("#wp_insert_adstxt_new_entry_pid").css({"border-color": defaultBorderColor}).parent().find("small").css({"color": defaultLabelColor});
	
	
	if(wp_insert_adstxt_new_entry_domain == '') {
		jQuery("#wp_insert_adstxt_new_entry_domain").css({"border-color": "#B20303"}).parent().find("small").css({"color": "#B20303"});
		isValidated = false;
	}
	if(wp_insert_adstxt_new_entry_pid == '') {
		jQuery("#wp_insert_adstxt_new_entry_pid").css({"border-color": "#B20303"}).parent().find("small").css({"color": "#B20303"});
		isValidated = false;
	}
	
	if(isValidated) {
		if((wp_insert_adstxt_content != '') && (jQuery.inArray((wp_insert_adstxt_content[wp_insert_adstxt_content.length -1]), ["\r", "\n"]) == -1)) {
			wp_insert_adstxt_content += '\r\n';
		}
		wp_insert_adstxt_content += wp_insert_adstxt_new_entry_domain + ', ' + wp_insert_adstxt_new_entry_pid + ', ' + wp_insert_adstxt_new_entry_type;
		if(wp_insert_adstxt_new_entry_certauthority != '') {
			wp_insert_adstxt_content += ', ' + wp_insert_adstxt_new_entry_certauthority;
		}
		jQuery("#wp_insert_adstxt_content").val(wp_insert_adstxt_content);
		
		jQuery("#wp_insert_adstxt_new_entry_domain").val('');
		jQuery("#wp_insert_adstxt_new_entry_pid").val('');
		jQuery("#wp_insert_adstxt_new_entry_type").val('DIRECT');
		jQuery("#wp_insert_adstxt_new_entry_certauthority").val('');
		
		jQuery("#wp_insert_adstxt_accordion").accordion({active: 0});
		jQuery("#wp_insert_adstxt_content").focus();
	}
}

function wp_insert_adstxt_content_download() {
	var blob = new Blob([jQuery("#wp_insert_adstxt_content").val()], {type: 'text/csv'});
	if(window.navigator.msSaveOrOpenBlob) {
		window.navigator.msSaveBlob(blob, 'ads.txt');
	}
	else{
		var elem = window.document.createElement('a');
		elem.href = window.URL.createObjectURL(blob);
		elem.download = 'ads.txt';        
		document.body.appendChild(elem);
		elem.click();        
		document.body.removeChild(elem);
	}
}