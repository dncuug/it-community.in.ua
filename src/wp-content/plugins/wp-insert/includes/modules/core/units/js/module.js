function wp_insert_ads_click_handler(type, identifier, title, isNew) {
	var isDuplicate = false;
	if(identifier.indexOf("###DUPLICATE###") != -1) {
		isDuplicate = true;
		identifier = identifier.replace("###DUPLICATE###","");
	}
	var preTitle = jQuery('#wp_insert_'+type+'_ad_'+identifier).attr('data-pre-title');
	jQuery('<div id="wp_insert_'+type+'_'+identifier+'_dialog"></div>').html('<div class="wp_insert_ajaxloader"></div>').dialog({
		'modal': true,
		'resizable': false,
		'width': jQuery("body").width() * 0.8,
		'maxWidth': jQuery("body").width() * 0.8,
		'maxHeight': jQuery("body").height() * 0.9,
		'title': preTitle+' : '+title,
		position: { my: 'center', at: 'center', of: window },
		open: function (event, ui) {
			jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
			jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
			jQuery('.ui-dialog-buttonpane button:contains("Update")').button('disable');
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': 'wp_insert_'+type+'_get_ad_form',
					'wp_insert_identifier': ((isDuplicate)?'###DUPLICATE###':'')+identifier,
					'wp_insert_type': type,
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val()
				}, function(response) {
					jQuery('.wp_insert_ajaxloader').hide();
					jQuery('.ui-dialog-content').html(response);
					jQuery('.ui-accordion .ui-accordion-content').css('max-height', (jQuery("body").height() * 0.45));
					jQuery('.ui-dialog-buttonpane button:contains("Update")').button('enable');
					jQuery('.wp_insert_'+type+'_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
					jQuery('.ui-dialog').css({'position': 'fixed'});
					jQuery('#wp_insert_'+type+'_'+identifier+'_dialog').delay(500).dialog({position: { my: 'center', at: 'center', of: window }});
				}			
			);
		},
		buttons: {
			'Update': {
				text: 'Update',
				icons: { primary: "ui-icon-gear" },
				click: function() {
					if(isNew) {
						var newIdentifier = jQuery('.wp_insert_'+type+'_identifier').val();
						var adLink = jQuery("<a></a>");
						adLink.attr('id', 'wp_insert_'+type+'_ad_'+newIdentifier);
						adLink.attr('href', 'javascript:;');
						adLink.attr('class', 'wp_insert_ad_unit_title');
						adLink.attr('data-pre-title', preTitle);
						adLink.attr('onClick', "wp_insert_ads_click_handler(\'"+type+"\', \'"+newIdentifier+"\', \'"+jQuery('#wp_insert_'+type+'_'+newIdentifier+'_title').val()+"\', false)");
						adLink.html(preTitle+' : '+jQuery('#wp_insert_'+type+'_'+newIdentifier+'_title').val());
						var deleteButton = jQuery('<span></span>');
						deleteButton.attr('class', 'dashicons dashicons-no wp_insert_delete_icon');
						deleteButton.attr('title', 'Delete Ad Unit');
						deleteButton.attr('onClick', "wp_insert_ad_delete_handler(\'"+type+"\', \'"+newIdentifier+"\')");
						var duplicateButton = jQuery('<span></span>');
						duplicateButton.attr('class', 'dashicons dashicons-format-gallery wp_insert_duplicate_icon');
						duplicateButton.attr('title', 'Duplicate Ad Unit');
						duplicateButton.attr('onClick', "wp_insert_ads_click_handler(\'"+type+"\', \'###DUPLICATE###"+newIdentifier+"\', \'"+newIdentifier+"\', \'"+jQuery('#wp_insert_'+type+'_'+newIdentifier+'_title').val()+" Duplicate\', false)");
						jQuery('#wp_insert_'+type+'_ad_new').parent().before(jQuery('<p></p>').append(adLink, deleteButton, duplicateButton));
						wp_insert_ad_update_handler(type, newIdentifier);
					} else {
						jQuery("#wp_insert_"+type+"_ad_"+identifier).html(preTitle+' : '+jQuery('#wp_insert_'+type+'_'+identifier+'_title').val());
						jQuery("#wp_insert_"+type+"_ad_"+identifier).attr('onClick', "wp_insert_ads_click_handler(\'"+type+"\', \'"+identifier+"\', \'"+jQuery('#wp_insert_'+type+'_'+identifier+'_title').val()+"\', false)");
						wp_insert_ad_update_handler(type, identifier);
					}					
					jQuery(this).dialog('close');
				}
			},
			Cancel: {
				text: 'Cancel',
				icons: { primary: "ui-icon-cancel" },
				click: function() {
					jQuery(this).dialog('close');
				}
			}
		},
		close: function() {
			jQuery(this).dialog('destroy');
		}
	});
}

function wp_insert_ad_update_handler(type, identifier) {
	inputElements = jQuery('#wp_insert_'+type+'_'+identifier+'_accordion').find('.input').map(function() { return jQuery(this).attr('name'); }).get();
	inputElements = jQuery.grep(inputElements, function(v, i) { return jQuery.inArray(v, inputElements) === i });

	autoArgs = {};
	if(jQuery('#wp_insert_'+type+'_'+identifier+'_status').length > 0) {
		autoArgs['wp_insert_'+type+'_'+identifier+'_status'] = jQuery('#wp_insert_'+type+'_'+identifier+'_status').prop('checked');
	}
	jQuery.each(inputElements, function(key, value) {
		currentElementID = value.replace('[', '_').replace(']', '');
		while(currentElementID.includes('[') || currentElementID.includes(']')) {
			var currentElementID = currentElementID.replace('[', '_').replace(']', '');
		}
		
		currentElement = jQuery('[name="'+value+'"]');
		if(currentElement.prop('nodeName') == 'SELECT') {
			if(currentElement.prop('multiple')) {
				autoArgs[currentElementID] = jQuery.map(jQuery('[name="'+value+'"] :selected'), function(e) { return jQuery(e).val(); });
			} else {
				autoArgs[currentElementID] = currentElement.val();
			}
		} else if(currentElement.prop('nodeName') == 'INPUT') {
			if(currentElement.attr('type') == 'checkbox') {
				autoArgs[currentElementID] = currentElement.prop('checked');
			} else if(currentElement.attr('type') == 'radio') {
				autoArgs[currentElementID] = jQuery('[name="'+value+'"]:checked').val();
			} else {
				autoArgs[currentElementID] = currentElement.val();
			}			
		} else {
			autoArgs[currentElementID] = currentElement.val();
		}		
	});
	
	parameters = [];
	jQuery.each(autoArgs, function(key, value) {
		parameters.push(key);
	});	
	
	autoArgs['action'] = 'wp_insert_'+type+'_save_ad_data';
	autoArgs['wp_insert_nonce'] = jQuery('#wp_insert_nonce').val();
	autoArgs['wp_insert_identifier'] = identifier;
	autoArgs['wp_insert_type'] = type;
	autoArgs['wp_insert_parameters'] = parameters;
	/*alert(JSON.stringify(autoArgs, null, 4));*/
	jQuery.post(
		jQuery('#wp_insert_admin_ajax').val(), autoArgs, function(response) { }
	);
}

function wp_insert_ad_delete_handler(type, identifier) {
	jQuery("<p>Are you Sure you want to remove this Ad Unit?</p>").dialog({
		'modal': true,
		'resizable': false,
		'title': 'Deletion Confirmation',
		position: { my: 'center', at: 'center', of: window },
		open: function (event, ui) {
			jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
			jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
		},
		buttons : {
			'Confirm': function() {
				jQuery('#wp_insert_'+type+'_ad_'+identifier).parent().remove();
				jQuery.post(
					jQuery('#wp_insert_admin_ajax').val(), {
						'action': 'wp_insert_'+type+'_delete_ad_data',
						'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
						'wp_insert_type': type,
						'wp_insert_identifier': identifier						
					}, function(response) {
					}			
				);
				jQuery(this).dialog("close");
			},
			'Cancel': function() {
				jQuery(this).dialog("close");
			}
		},
		close: function() {
			jQuery(this).dialog('destroy');
		}
	});
}