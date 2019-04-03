<?php
/* Begin Signup Form */
add_action('wp_ajax_wp_insert_vi_signup_form_get_content', 'wp_insert_vi_signup_form_get_content');
function wp_insert_vi_signup_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	$signupURL = wp_insert_vi_api_get_signupurl();
	if(($signupURL != false) && ($signupURL != '')) {
		echo '<div class="wp_insert_popup_content_wrapper">';
			echo '<iframe src="'.$signupURL.'?email='.get_bloginfo('admin_email').'&domain='.wp_insert_get_domain_name_from_url(get_bloginfo('url')).'&aid=WP_insert" style="width: 100%; max-width: 870px; min-height: 554px;"></iframe>';
			echo '<script type="text/javascript">';
				echo 'jQuery(".ui-dialog-buttonset").find("button").first().remove();';
				echo 'jQuery(".ui-dialog-buttonset").find("button").first().find("span:nth-child(2)").hide().after("<span class=\'ui-button-text\'>Close</span>");';
			echo '</script>';
		echo '</div>';
	} else {
		echo '<div class="wp_insert_popup_content_wrapper">';
			echo '<p>There was an error processing your request, our team was notified. Try clearing your browser cache, log out and log in again.</p>';
		echo '</div>';
	}
	die();
}
/* End Signup Form */

/* Begin Login Form */
add_action('wp_ajax_wp_insert_vi_login_form_get_content', 'wp_insert_vi_login_form_get_content');
function wp_insert_vi_login_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	echo '<div class="wp_insert_popup_content_wrapper">';
		echo '<div class="wp_insert_vi_loginform_wrapper">';
			wp_insert_vi_login_form_get_controls();
		echo '</div>';
		echo '<script type="text/javascript">';
			//echo $control->JS;
			echo 'jQuery(".ui-dialog-buttonset").find("button").first().find("span:nth-child(2)").hide().after("<span class=\'ui-button-text\'>Login</span>");';
			echo 'jQuery(".ui-dialog-buttonset").find("button").first().find("span:nth-child(1)").attr("class", "ui-button-icon-primary ui-icon ui-icon-key");';
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_wp_insert_vi_login_form_save_action', 'wp_insert_vi_login_form_save_action');
function wp_insert_vi_login_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	if(isset($_POST['wp_insert_vi_login_username']) && ($_POST['wp_insert_vi_login_username'] != '') && isset($_POST['wp_insert_vi_login_password']) && ($_POST['wp_insert_vi_login_password'] != '')) {
		$token = wp_insert_vi_api_login($_POST['wp_insert_vi_login_username'], $_POST['wp_insert_vi_login_password']);
		if(is_array($token) && (isset($token['status'])) && ($token['status'] == 'error')) {
			wp_insert_vi_login_form_get_controls();
			if($token['errorCode'] == 'WIVI008') {
				echo '<p class="wp_insert_vi_login_error">'.$token['message'].'</p>';
			} else {
				echo '<p class="wp_insert_vi_login_error">Error Code: '.$token['errorCode'].'<br />Please contact support or try again later!'.'</p>';
			}
		} else {
			echo '###SUCCESS###';
			wp_insert_vi_plugin_card_content(true, true);
				
			if(function_exists('wp_insert_adstxt_adsense_admin_notice_reset')) {
				wp_insert_adstxt_adsense_admin_notice_reset();
			}
		}		
	}
	die();
}

function wp_insert_vi_login_form_get_controls() {
	$control = new smartlogixControls();
	$control->HTML .= '<p>Please log in with the received credentials to complete the integration:</p>';
	$control->add_control(array('type' => 'text', 'id' => 'wp_insert_vi_login_username', 'name' => 'wp_insert_vi_login_username', 'label' => 'Email', 'value' => ''));
	$control->add_control(array('type' => 'password', 'id' => 'wp_insert_vi_login_password', 'name' => 'wp_insert_vi_login_password', 'label' => 'Password', 'value' => ''));
	$control->create_section('Login');
	echo $control->HTML;
}

add_action('wp_ajax_wp_insert_vi_update_adstxt', 'wp_insert_vi_update_adstxt');
function wp_insert_vi_update_adstxt() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	
	$adstxtContent = wp_insert_adstxt_get_content();
	$adstxtContentData = array_filter(explode("\n", trim($adstxtContent)), 'trim');
	$viEntry = wp_insert_vi_api_get_adstxt_content();
	if(strpos(str_replace(array("\r", "\n", " "), '', $adstxtContent), str_replace(array("\r", "\n", " "), '', $viEntry)) !== false) {
		die();
	} else {
		$updatedAdstxtContent = '';
		if(strpos($adstxtContent, '# 41b5eef6') !== false) {
			foreach($adstxtContentData as $line) {
				if(strpos($line, '# 41b5eef6') !== false) {
					
				} else {
					$updatedAdstxtContent .= str_replace(array("\r", "\n", " "), '', $line)."\r\n";
				}
			}
			$updatedAdstxtContent .= $viEntry;
		} else {
			$updatedAdstxtContent .= $adstxtContent."\r\n".$viEntry;
		}
		
		if(wp_insert_adstxt_update_content($updatedAdstxtContent)) {
			echo '###SUCCESS###';
			echo '<div class="notice notice-warning wp_insert_adsstxt_notice is-dismissible" style="padding: 5px 15px;">';
				echo '<div style="float: left; max-width: 875px; font-size: 14px; font-family: Arial; line-height: 18px; color: #232323;">';
					echo '<p><b>ADS.TXT has been added</b></p>';
					echo '<p>Wp-Insert has updated your ads.txt file with lines that declare video intelligence as a legitimate seller of your inventory and enables you to make more money through video intelligence. Read the <a target="_blank" href="https://www.vi.ai/frequently-asked-questions-vi-stories-for-wordpress/?utm_source=WordPress&utm_medium=Plugin%20FAQ&utm_campaign=WP%20Insert">FAQ</a>.</p>';
				echo '</div>';
				echo '<img style="float: right; margin-right: 20px; margin-top: 13px;" src="'.WP_INSERT_URL.'includes/assets/images/vi-big-logo.png?'.WP_INSERT_VERSION.'" />';
				echo '<div class="clear"></div>';
				echo '<button type="button" class="notice-dismiss" onclick="javascript:jQuery(this).parent().remove()"><span class="screen-reader-text">Dismiss this notice.</span></button>';
			echo '</div>';
		} else {
			echo '###FAIL###';
			echo '<div class="notice notice-error wp_insert_adsstxt_notice is-dismissible" style="padding: 5px 15px;">';
				echo '<div style="float: left; max-width: 875px; font-size: 14px; font-family: Arial; line-height: 18px; color: #232323;">';
					echo '<p><b>ADS.TXT couldn’t be added</b></p>';
					echo '<p>Important note: Wp-Insert hasn’t been able to update your ads.txt file. Please make sure that you enter the following lines manually:</p>';
					echo '<p><code style="display: block;">'.trim(str_replace(array("\r\n", "\r", "\n"), "<br />", $viEntry)).'</code><br />Only by doing so, you\'ll be able to make more money through video intelligence (vi.ai).</p>';
				echo '</div>';
				echo '<img style="float: right; margin-right: 20px; margin-top: 13px;" src="'.WP_INSERT_URL.'includes/assets/images/vi-big-logo.png?'.WP_INSERT_VERSION.'" />';
				echo '<div class="clear"></div>';
				echo '<button type="button" class="notice-dismiss" onclick="javascript:jQuery(this).parent().remove()"><span class="screen-reader-text">Dismiss this notice.</span></button>';
			echo '</div>';
		}
	}
	die();
} 
/* End Login Form */

/* Begin Logout */
add_action('wp_ajax_wp_insert_vi_logout_action', 'wp_insert_vi_logout_action');
function wp_insert_vi_logout_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	wp_insert_vi_api_logout();
	echo '###SUCCESS###';
	wp_insert_vi_plugin_card_content(false, true);
	die();
}
/* End Logout */

/* Begin Configure vi Code */
add_action('wp_ajax_wp_insert_vi_customize_adcode_form_get_content', 'wp_insert_vi_customize_adcode_form_get_content');
function wp_insert_vi_customize_adcode_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	$vicodeSettings = get_option('wp_insert_vi_code_settings');
	$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_vi_code_settings', 'values' => $vicodeSettings));
	$control->HTML = '<div class="wp_insert_popup_content_wrapper">';
		$control->HTML .= '<p>Use this form to customize the look of the video unit. Use the same parameters as your WordPress theme for a natural look on your site.<br />You can use <b>vi stories</b> for <i>In-Post Ads: Ad - Above Post Content</i> and <i>In-Post Ads: Ad - Middle of Post Content</i></p>';
		$control->HTML .= '<div class="wp_insert_vi_popup_right_column">';
			$control->HTML .= '<img style="margin: 0 auto; display: block;" src="'.WP_INSERT_URL.'includes/assets/images/advertisement-preview.png?'.WP_INSERT_VERSION.'" />';
		$control->HTML .= '</div>';
		$control->HTML .= '<div class="wp_insert_vi_popup_left_column">';
			$control->HTML .= '<p id="wp_insert_vi_customize_adcode_keywords_required_error" style="display: none;" class="viError">Keywords contains invalid characters, Some required fields are missing</p>';
			$control->HTML .= '<p id="wp_insert_vi_customize_adcode_keywords_error" style="display: none;" class="viError">Keywords contains invalid characters</p>';
			$control->HTML .= '<p id="wp_insert_vi_customize_adcode_required_error" style="display: none;" class="viError">Some required fields are missing</p>';
			$adUnitOptions = array(
				/*array('text' => 'Select Ad Unit', 'value' => 'select'),*/
				array('text' => 'vi stories', 'value' => 'NATIVE_VIDEO_UNIT'),
				/*array('text' => 'Outstream', 'value' => 'FLOATING_OUTSTREAM')*/
			);
			$control->add_control(array('type' => 'select', 'label' => ' Ad Unit*', 'optionName' => 'ad_unit_type', 'helpText' => '</small><span class="tooltipWrapper"><span class="tooltip">- vi stories (video advertising + video content)</span></span><small>', 'options' => $adUnitOptions));/*<br />- out-stream (video advertising)*/
			$control->add_control(array('type' => 'textarea', 'label' => 'Keywords', 'optionName' => 'keywords', 'helpText' => '</small><span class="tooltipWrapper"><span class="tooltip">Comma separated values describing the content of the page e.g. \'cooking, grilling, pulled pork\'</span></span><small>'));
			$IABParentCategories = wp_insert_vi_get_constant_iab_parent_categories();
			$control->add_control(array('type' => 'select', 'label' => 'IAB Category*', 'optionName' => 'iab_category_parent', 'helpText' => '</small><a class="textTip" target="_blank" href="'.wp_insert_vi_api_get_iabCategoriesURL().'">See Complete List</a><small>', 'options' => $IABParentCategories));
			$IABChildCategories = wp_insert_vi_get_constant_iab_child_categories();
			$control->add_control(array('type' => 'select', 'label' => '&nbsp;', 'optionName' => 'iab_category_child', 'helpText' => '&nbsp;', 'options' => $IABChildCategories));
			$languages = wp_insert_vi_api_get_languages();			
			$languageOptions = array(
				array('text' => 'Select language', 'value' => 'select'),
			);
			if($languages != false) {
				foreach($languages as $key => $value) {
					$languageOptions[] = array('text' => $value, 'value' => $key);
				}
			}
			$control->add_control(array('type' => 'select', 'label' => 'Language*', 'optionName' => 'language', 'helpText' => '&nbsp;', 'options' => $languageOptions));
			$control->add_control(array('type' => 'minicolors', 'label' => 'Native Background color', 'optionName' => 'native_bg_color', 'helpText' => '&nbsp;'));
			$control->add_control(array('type' => 'minicolors', 'label' => 'Native Text color', 'optionName' => 'native_text_color', 'helpText' => '&nbsp;'));		
			$control->add_control(array('type' => 'select', 'label' => ' Native Text Font Family', 'optionName' => 'font_family', 'helpText' => '&nbsp;', 'options' => wp_insert_vi_get_constant_fonts()));		
			$control->add_control(array('type' => 'select', 'label' => 'Native Text Font Size', 'optionName' => 'font_size', 'helpText' => '&nbsp;', 'options' => wp_insert_vi_get_constant_font_sizes()));
			$control->HTML .= '<p class="wp_insert_vi_delay_notice">vi Ad Changes might take some time to take into effect</p>';
		$control->HTML .= '</div>';
		$control->HTML .= '<div class="clear"></div>';
	$control->HTML .= '</div>';
	$control->create_section(' vi stories: customize your video player ');
	echo $control->HTML;
	$control->clear_controls();
	
	$control->HTML .= '<p>Enable GDPR Compliance confirmation notice on your site for visitors from EU.<br />If you disable this option make sure you are using a data usage authorization system on your website to remain GDPR complaint.</p>';
	$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Do not Show GDPR Authorization Popup', 'checkedLabel' => 'Status : Show GDPR Authorization Popup', 'uncheckedLabel' => 'Status : Do not Show GDPR Authorization Popup', 'optionName' => 'show_gdpr_authorization'));
	$control->create_section(' vi stories: GDPR Compliance ');
	echo $control->HTML;
	echo '<script type="text/javascript">';
		echo $control->JS;
		echo 'wp_insert_vi_code_iab_category_parent_change();';
	echo '</script>';
	die();
}

add_action('wp_ajax_wp_insert_vi_customize_adcode_form_save_action', 'wp_insert_vi_customize_adcode_form_save_action');
function wp_insert_vi_customize_adcode_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	$vicodeSettings = array();
	$vicodeSettings['ad_unit_type'] = ((isset($_POST['wp_insert_vi_code_settings_ad_unit_type']))?$_POST['wp_insert_vi_code_settings_ad_unit_type']:'');
	$vicodeSettings['keywords'] = ((isset($_POST['wp_insert_vi_code_settings_keywords']))?$_POST['wp_insert_vi_code_settings_keywords']:'');
	$vicodeSettings['iab_category_parent'] = ((isset($_POST['wp_insert_vi_code_settings_iab_category_parent']))?$_POST['wp_insert_vi_code_settings_iab_category_parent']:'');
	$vicodeSettings['iab_category_child'] = ((isset($_POST['wp_insert_vi_code_settings_iab_category_child']))?$_POST['wp_insert_vi_code_settings_iab_category_child']:'');
	$vicodeSettings['language'] = ((isset($_POST['wp_insert_vi_code_settings_language']))?$_POST['wp_insert_vi_code_settings_language']:'');
	$vicodeSettings['native_bg_color'] = ((isset($_POST['wp_insert_vi_code_settings_native_bg_color']))?$_POST['wp_insert_vi_code_settings_native_bg_color']:'');
	$vicodeSettings['native_text_color'] = ((isset($_POST['wp_insert_vi_code_settings_native_text_color']))?$_POST['wp_insert_vi_code_settings_native_text_color']:'');
	$vicodeSettings['font_family'] = ((isset($_POST['wp_insert_vi_code_settings_font_family']))?$_POST['wp_insert_vi_code_settings_font_family']:'');
	$vicodeSettings['font_size'] = ((isset($_POST['wp_insert_vi_code_settings_font_size']))?$_POST['wp_insert_vi_code_settings_font_size']:'');
	
	$vicodeSettings['show_gdpr_authorization'] = ((isset($_POST['wp_insert_vi_code_settings_show_gdpr_authorization']))?$_POST['wp_insert_vi_code_settings_show_gdpr_authorization']:'');
	update_option('wp_insert_vi_code_settings', $vicodeSettings);
	$viCodeStatus = wp_insert_vi_api_set_vi_code($vicodeSettings);
	if(is_array($viCodeStatus) && (isset($viCodeStatus['status'])) && ($viCodeStatus['status'] == 'error')) {
		if($viCodeStatus['errorCode'] == 'WIVI108') {
			echo '###FAIL###';
			echo '<p class="viError">'.$viCodeStatus['message'].'</p>';
		} else {
			echo '###FAIL###';
			echo '<p class="viError">There was an error processing your request, our team was notified. Try clearing your browser cache, log out and log in again.</p>';
		}
	} else {
		echo '###SUCCESS###';
	}
	die();
}

function wp_insert_vi_customize_adcode_get_settings() {
	$vicodeSettings = get_option('wp_insert_vi_code_settings');
	
	$output = '';
	if(isset($vicodeSettings) && is_array($vicodeSettings)) {
		$output .= '<p class="wp_insert_vi_code_data_wrapper">';
		if(isset($vicodeSettings['ad_unit_type']) && ($vicodeSettings['ad_unit_type'] != '') && ($vicodeSettings['ad_unit_type'] != 'select')) {
			$output .= '<label>Ad Unit:</label><b>vi stories</b>';
		}
		
		if(isset($vicodeSettings['keywords']) && ($vicodeSettings['keywords'] != '')) {
			$output .= '<label>Keywords:</label><b>'.$vicodeSettings['keywords'].'</b>';
		}
		
		if(isset($vicodeSettings['iab_category_child']) && ($vicodeSettings['iab_category_child'] != '') && ($vicodeSettings['iab_category_child'] != 'select')) {
			$IABChildCategories = wp_insert_vi_get_constant_iab_child_categories();
			foreach($IABChildCategories as $IABChildCategoryItem) {
				if($vicodeSettings['iab_category_child'] == $IABChildCategoryItem['value']) {
					$output .= '<label>IAB Category:</label><b>'.$IABChildCategoryItem['text'].'</b>';
				}
			}
		}

		$languages = wp_insert_vi_api_get_languages();
		if(isset($vicodeSettings['language']) && ($vicodeSettings['language'] != '') && ($vicodeSettings['language'] != 'select')) {
			if($languages != false) {
				foreach($languages as $key => $value) {
					if($vicodeSettings['language'] == $key) {
						$output .= '<label>Language:</label><b>'.$value.'</b>';
					}
				}
			}
		}
		
		if(isset($vicodeSettings['native_bg_color']) && ($vicodeSettings['native_bg_color'] != '')) {
			$output .= '<label>Native Background color:</label><b>'.$vicodeSettings['native_bg_color'].'</b>';
		}
		
		if(isset($vicodeSettings['native_text_color']) && ($vicodeSettings['native_text_color'] != '')) {
			$output .= '<label>Native Text color:</label><b>'.$vicodeSettings['native_text_color'].'</b>';
		}
		
		if(isset($vicodeSettings['font_family']) && ($vicodeSettings['font_family'] != '') && ($vicodeSettings['font_family'] != 'select')) {
			$fontFamily = wp_insert_vi_get_constant_fonts();
			foreach($fontFamily as $fontFamilyItem) {
				if($vicodeSettings['font_family'] == $fontFamilyItem['value']) {
					$output .= '<label>Native Text Font Family:</label><b>'.$fontFamilyItem['text'].'</b>';
				}
			}
		}
		
		if(isset($vicodeSettings['font_size']) && ($vicodeSettings['font_size'] != '') && ($vicodeSettings['font_size'] != 'select')) {
			$fontSize = wp_insert_vi_get_constant_font_sizes();	
			foreach($fontSize as $fontSizeItem) {
				if($vicodeSettings['font_size'] == $fontSizeItem['value']) {
					$output .= '<label>Native Text Font Size:</label><b>'.$fontSizeItem['text'].'</b>';
				}
			}
		}
		$output .= '</p>';
	}
	return $output;
}
/* End Configure vi Code */
?>