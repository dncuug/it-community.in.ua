function wp_insert_inpostads_primary_ad_code_location_change_action(identifier) {
	jQuery('input[name="wp_insert_inpostads['+identifier+'][location]"]').click(function() {
		var location = jQuery('input[name="wp_insert_inpostads['+identifier+'][location]"]:checked').val();
		if((location == 'above') || (location == 'middle') || (location == 'paragraphtop')) {
			jQuery('#primary_ad_code_type_vicode').parent().parent().parent().show();
			jQuery('#primary_ad_code_type_generic').show();
			jQuery('#primary_ad_code_type_generic').parent().find('.isSelectedIndicatorText').html('Generic / Custom Ad Code (Primary Network)');
			jQuery('#primary_ad_code_type_generic').parent().parent().parent().css({'width': 'calc(50% - 40px)', 'margin': '0 20px 5px', 'float': 'left'});
			//jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').val('generic');
		} else {
			jQuery('#primary_ad_code_type_vicode').parent().parent().parent().hide();
			jQuery('#primary_ad_code_type_generic').hide();
			jQuery('#primary_ad_code_type_generic').parent().find('.isSelectedIndicatorText').html('Ad Code (Primary Network)');
			jQuery('#primary_ad_code_type_generic').parent().parent().parent().css({'width': '100%', 'margin': '15px 0', 'float': 'none'});
			jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').val('generic');
		}
		
		if(location == 'middle') {
			jQuery('.wp_insert_inpostads_location_middle_panel').show();
		} else {
			jQuery('.wp_insert_inpostads_location_middle_panel').hide();
		}
	});
	var location = jQuery('input[name="wp_insert_inpostads['+identifier+'][location]"]:checked').val();
	if((location == 'above') || (location == 'middle') || (location == 'paragraphtop')) {
		jQuery('#primary_ad_code_type_vicode').parent().parent().parent().show();
		jQuery('#primary_ad_code_type_generic').show();
		jQuery('#primary_ad_code_type_generic').parent().find('.isSelectedIndicatorText').html('Generic / Custom Ad Code (Primary Network)');
		jQuery('#primary_ad_code_type_generic').parent().parent().parent().css({'width': 'calc(50% - 40px)', 'margin': '0 20px 5px', 'float': 'left'});
		//jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').val('generic');
	} else {
		jQuery('#primary_ad_code_type_vicode').parent().parent().parent().hide();
		jQuery('#primary_ad_code_type_generic').hide();
		jQuery('#primary_ad_code_type_generic').parent().find('.isSelectedIndicatorText').html('Ad Code (Primary Network)');
		jQuery('#primary_ad_code_type_generic').parent().parent().parent().css({'width': '100%', 'margin': '15px 0', 'float': 'none'});
		jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').val('generic');
	}
	
	if(location == 'middle') {
		jQuery('.wp_insert_inpostads_location_middle_panel').show();
	} else {
		jQuery('.wp_insert_inpostads_location_middle_panel').hide();
	}
}

function wp_insert_inpostads_primary_ad_code_type_change(identifier) {
	jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').parent().hide();
	jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').change(function() {
		jQuery('.isSelectedIndicator').removeClass('active');
		jQuery('#primary_ad_code_type_'+jQuery(this).val()).addClass('active');
	});
	jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').change();
	
	jQuery('#primary_ad_code_type_generic').click(function() {
		jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').val('generic');
		jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').change();
	});
	jQuery('#primary_ad_code_type_generic').parent().click(function() {
		jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').val('generic');
		jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').change();
	});
	
	jQuery('#primary_ad_code_type_vicode').click(function() {
		if(!jQuery('#primary_ad_code_type_vicode').hasClass('disabled')) {
			jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').val('vicode');
			jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').change();
		}
	});
	jQuery('#primary_ad_code_type_vicode').parent().click(function() {
		if(!jQuery('#primary_ad_code_type_vicode').hasClass('disabled')) {
			jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').val('vicode');
			jQuery('#wp_insert_inpostads_'+identifier+'_primary_ad_code_type').change();
		}
	});
}

function wp_insert_inpostads_vi_customize_adcode() {
	jQuery('#wp_insert_inpostads_vi_customize_adcode').click(function() {
		jQuery('.ui-dialog-titlebar').find('button').last().button('enable').click();
		jQuery('#wp_insert_vi_customize_adcode').click();
	});
}