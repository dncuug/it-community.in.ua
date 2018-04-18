<?php
function wp_insert_form_accordion_tabs_adcode($control, $identifier, $location) {
	echo '<h3>Ad Code</h3>';
	echo '<div>';
		$abtestingMode = get_option('wp_insert_abtesting_mode');
		
		if($location == 'inpostads') {
			$adTypes = array(
				array('text' => 'Use Generic / Custom Ad Code', 'value' => 'generic'),
				array('text' => 'vi stories', 'value' => 'vicode'),
			);
			$control->add_control(array('type' => 'select', 'label' => 'Ad Type', 'optionName' => 'primary_ad_code_type', 'options' => $adTypes));
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'primary_ad_code'));
			$control->create_section('<span id="primary_ad_code_type_generic" class="isSelectedIndicator"></span><span class="isSelectedIndicatorText">Generic / Custom Ad Code (Primary Network)</span>');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$IsVILoggedin = wp_insert_vi_api_is_loggedin();
			$isJSTagGenerated = ((wp_insert_vi_api_get_vi_code() === false)?false:true);
			$isVIDisabled = false;
			$viMessage = '';
			if(!$IsVILoggedin && !$isJSTagGenerated) {
				$isVIDisabled = true;
				$viMessage = '<p>Introducing <b>vi stories</b> – the video content and advertising player.</p>';
				$viMessage .= '<p>Before you can use <b>vi stories</b>, you must configure it. Once you’ve signed up, in the <i>video intelligence</i> panel, click <i>Sign in</i> then click <i>Configure</i></p>';
			} else if($IsVILoggedin && !$isJSTagGenerated) {
				$isVIDisabled = true;
				$viMessage .= '<p>Before you can use <b>vi stories</b>, you must configure it. In the <i>video intelligence</i> panel, click <i>Configure</i></p>';
				//$viMessage .= '<p><a id="wp_insert_inpostads_vi_customize_adcode" href="javascript:;" class="button button-primary aligncenter">Configure vi Code</a></p>'; /*Button being temporarily removed to avoid confusion for users*/
			} else if(!$IsVILoggedin && $isJSTagGenerated) {
				$isVIDisabled = false;
				$viMessage = '<p>Before you can use <b>vi stories</b>, you must configure it. Once you’ve signed up, in the <i>video intelligence</i> panel, click <i>Sign in</i> then click <i>Configure</i></p>';
			} else {
				$isVIDisabled = false;
				$viMessage = wp_insert_vi_customize_adcode_get_settings();
				$viMessage .= '<p>To configure <b>vi stories</b>, go to the <i>video intelligence</i> panel, click <i>Configure</i></p>';
				//$viMessage .= '<p><a id="wp_insert_inpostads_vi_customize_adcode" href="javascript:;" class="button button-primary aligncenter">Configure vi Code</a></p>'; /*Button being temporarily removed to avoid confusion for users*/
			}
			
			$control->HTML .= $viMessage;
			$control->create_section('<span id="primary_ad_code_type_vicode" class="isSelectedIndicator '.(($isVIDisabled)?'disabled':'').'"></span><span class="isSelectedIndicatorText">vi stories (Primary Network)</span>');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();
		} else {
			$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'primary_ad_code'));
			$control->create_section('Ad Code (Primary Network)');
			echo $control->HTML;
			$control->clear_controls();
		}
		
		$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'secondary_ad_code'));
		$control->create_section('Ad Code (Secondary Network)');
		if($abtestingMode != '2' && $abtestingMode != '3') {	
			$control->set_HTML('<div style="display: none;">'.$control->HTML.'</div>');
		}
		echo $control->HTML;
		$control->clear_controls();
		
		$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'tertiary_ad_code'));
		$control->create_section('Ad Code (Tertiary Network)');
		if($abtestingMode != '3') {	
			$control->set_HTML('<div style="display: none;">'.$control->HTML.'</div>');
		}
		echo $control->HTML;
		$control->clear_controls();
	echo '</div>';
	return $control;
}
?>