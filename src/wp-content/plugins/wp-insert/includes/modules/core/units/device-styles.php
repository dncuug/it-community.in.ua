<?php
function wp_insert_form_accordion_tabs_devices_styles($control, $identifier, $location) {
	echo '<h3>Devices & Styles</h3>';
	echo '<div>';
		$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'device_exclude_large_desktop'));
		$control->HTML .= '<p style="text-align: center; min-height: 40px;"><small>This setting will apply to all devices which are greater than the Device Width set below</small></p>';
		$control->add_control(array('type' => 'number', 'label' => 'Device Width (px)', 'optionName' => 'device_large_desktop_width', 'helpText' => 'The width of the browser screen.', 'value' => ((!isset($control->values['device_large_desktop_width']) || ($control->values['device_large_desktop_width'] == ''))?'1200':$control->values['device_large_desktop_width'])));
		$control->add_control(array('type' => 'number', 'label' => 'Ad Width (px)', 'optionName' => 'device_large_desktop_adwidth', 'helpText' => 'The width of the ad block at the given browser screen width. (Leave empty for auto width)'));				
		$control->add_control(array('type' => 'textarea', 'label' => 'Styles', 'optionName' => 'device_large_desktop_styles', 'helpText' => 'You can add CSS to customize your ad block for this device type'));				
		$control->create_section('Device - Large Desktop');
		$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
		echo $control->HTML;
		$control->clear_controls();
		
		$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'device_exclude_medium_desktop'));
		$control->HTML .= '<p style="text-align: center; min-height: 40px;"><small>This setting will apply to all devices which are greater than the Device Width set below but smaller than Device Width set for Large Desktop</small></p>';
		$control->add_control(array('type' => 'number', 'label' => 'Device Width (px)', 'optionName' => 'device_medium_desktop_width', 'helpText' => 'The width of the browser screen.', 'value' => ((!isset($control->values['device_medium_desktop_width']) || ($control->values['device_medium_desktop_width'] == ''))?'992':$control->values['device_medium_desktop_width'])));
		$control->add_control(array('type' => 'number', 'label' => 'Ad Width (px)', 'optionName' => 'device_medium_desktop_adwidth', 'helpText' => 'The width of the ad block at the given browser screen width. (Leave empty for auto width)'));				
		$control->add_control(array('type' => 'textarea', 'label' => 'Styles', 'optionName' => 'device_medium_desktop_styles', 'helpText' => 'You can add CSS to customize your ad block for this device type'));	
		$control->create_section('Devices - Medium Desktop');
		$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
		echo $control->HTML;
		$control->clear_controls();
		
		$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'device_exclude_tablet'));
		$control->HTML .= '<p style="text-align: center; min-height: 40px;"><small>This setting will apply to all devices which are greater than the Device Width set below but smaller than Device Width set for Medium Desktop</small></p>';
		$control->add_control(array('type' => 'number', 'label' => 'Device Width (px)', 'optionName' => 'device_tablet_width', 'helpText' => 'The width of the browser screen.', 'value' => ((!isset($control->values['device_tablet_width']) || ($control->values['device_tablet_width'] == ''))?'768':$control->values['device_tablet_width'])));
		$control->add_control(array('type' => 'number', 'label' => 'Ad Width (px)', 'optionName' => 'device_tablet_adwidth', 'helpText' => 'The width of the ad block at the given browser screen width. (Leave empty for auto width)'));				
		$control->add_control(array('type' => 'textarea', 'label' => 'Styles', 'optionName' => 'device_tablet_styles', 'helpText' => 'You can add CSS to customize your ad block for this device type'));	
		$control->create_section('Devices - Tablet');
		$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
		echo $control->HTML;
		$control->clear_controls();
		
		$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'device_exclude_mobile'));
		$control->HTML .= '<p style="text-align: center; min-height: 40px;"><small>This setting will apply to all devices which are smaller than the Device Width set for Tablets</small></p>';
		$control->add_control(array('type' => 'number', 'label' => 'Device Width (px)', 'optionName' => 'device_mobile_width', 'helpText' => 'The width of the browser screen.', 'value' => ((!isset($control->values['device_mobile_width']) || ($control->values['device_mobile_width'] == ''))?'480':$control->values['device_mobile_width'])));
		$control->add_control(array('type' => 'number', 'label' => 'Ad Width (px)', 'optionName' => 'device_mobile_adwidth', 'helpText' => 'The width of the ad block at the given browser screen width. (Leave empty for auto width)'));				
		$control->add_control(array('type' => 'textarea', 'label' => 'Styles', 'optionName' => 'device_mobile_styles', 'helpText' => 'You can add CSS to customize your ad block for this device type'));	
		$control->create_section('Devices - Mobile (Normal)');
		$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
		echo $control->HTML;
		$control->clear_controls();
		
		$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'device_exclude_small_mobile'));
		$control->HTML .= '<p style="text-align: center; min-height: 40px;"><small>This setting will apply to all devices which are smaller than the Device Width set for Mobile (Normal)</small></p>';
		$control->add_control(array('type' => 'number', 'label' => 'Ad Width (px)', 'optionName' => 'device_small_mobile_adwidth', 'helpText' => 'The width of the ad block at the given browser screen width. (Leave empty for auto width)'));				
		$control->add_control(array('type' => 'textarea', 'label' => 'Styles', 'optionName' => 'device_small_mobile_styles', 'helpText' => 'You can add CSS to customize your ad block for this device type'));	
		$control->create_section('Devices - Mobile (Small)');
		$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
		echo $control->HTML;
		$control->clear_controls();
		
		$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'styles', 'helpText' => 'You can add CSS to customize your ad block'));
		$control->create_section('Styles (Common)');
		echo $control->HTML;
		$control->clear_controls();
	echo '</div>';
	return $control;
}


?>