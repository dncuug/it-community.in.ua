<?php 
/* Begin Analytics Embed */
add_action('wp_ajax_wp_insert_trackingcodes_google_analytics_form_get_content', 'wp_insert_trackingcodes_google_analytics_form_get_content');
function wp_insert_trackingcodes_google_analytics_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	
	$trackingCodes = get_option('wp_insert_trackingcodes');
	echo '<div class="wp_insert_popup_content_wrapper">';
		$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_trackingcodes[analytics]', 'values' => $trackingCodes['analytics']));
		$control->add_control(array('type' => 'ipCheckbox', 'optionName' => 'status'));
		$control->add_control(array('type' => 'text', 'optionName' => 'code', 'label' => 'Google Analytics Tracker ID', 'helpText' => 'Your Google Analytics Tracker ID (XX-XXXXX-X)'));
		echo $control->HTML;
		echo '<script type="text/javascript">';
		echo $control->JS;
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_wp_insert_trackingcodes_google_analytics_form_save_action', 'wp_insert_trackingcodes_google_analytics_form_save_action');
function wp_insert_trackingcodes_google_analytics_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$trackingCodes = get_option('wp_insert_trackingcodes');
	$trackingCodes['analytics']['status'] = ((isset($_POST['wp_insert_trackingcodes_analytics_status']) && ($_POST['wp_insert_trackingcodes_analytics_status'] == 'true'))?'1':'');
	$trackingCodes['analytics']['code'] = ((isset($_POST['wp_insert_trackingcodes_analytics_code']))?$_POST['wp_insert_trackingcodes_analytics_code']:'');
	update_option('wp_insert_trackingcodes', $trackingCodes);
	die();
}

add_action('wp_footer', 'wp_insert_trackingcodes_google_analytics_wp_footer');
function wp_insert_trackingcodes_google_analytics_wp_footer() {
	$trackingCodes = get_option('wp_insert_trackingcodes');
	if(isset($trackingCodes['analytics']['status']) && wp_validate_boolean($trackingCodes['analytics']['status']) && isset($trackingCodes['analytics']['code']) && !empty($trackingCodes['analytics']['code'])) {
		echo '<script type="text/javascript">';
			echo 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");';
			echo 'document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));';
		echo '</script>';
		echo '<script type="text/javascript">';
			echo 'var pageTracker = _gat._getTracker("'.$trackingCodes['analytics']['code'].'");';
			echo 'pageTracker._trackPageview();';
		echo '</script>';
	}

}
/* End Analytics Embed */

/* Begin Header Code Embed */
add_action('wp_ajax_wp_insert_trackingcodes_header_form_get_content', 'wp_insert_trackingcodes_header_form_get_content');
function wp_insert_trackingcodes_header_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	
	$trackingCodes = get_option('wp_insert_trackingcodes');
	echo '<div class="wp_insert_popup_content_wrapper">';
		$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_trackingcodes[header]', 'values' => $trackingCodes['header']));
		$control->add_control(array('type' => 'ipCheckbox', 'optionName' => 'status'));
		$control->add_control(array('type' => 'textarea', 'optionName' => 'code', 'label' => 'Embed Code'));
		echo $control->HTML;
		echo '<script type="text/javascript">';
		echo $control->JS;
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_wp_insert_trackingcodes_header_form_save_action', 'wp_insert_trackingcodes_header_form_save_action');
function wp_insert_trackingcodes_header_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$trackingCodes = get_option('wp_insert_trackingcodes');
	$trackingCodes['header']['status'] = ((isset($_POST['wp_insert_trackingcodes_header_status']) && ($_POST['wp_insert_trackingcodes_header_status'] == 'true'))?'1':'');
	$trackingCodes['header']['code'] = ((isset($_POST['wp_insert_trackingcodes_header_code']))?$_POST['wp_insert_trackingcodes_header_code']:'');
	update_option('wp_insert_trackingcodes', $trackingCodes);
	
	if(function_exists('wp_insert_adstxt_adsense_admin_notice_reset')) {
		wp_insert_adstxt_adsense_admin_notice_reset();
	}
	die();
}

add_action('wp_head', 'wp_insert_trackingcodes_header_wp_head');
function wp_insert_trackingcodes_header_wp_head() {
	$trackingCodes = get_option('wp_insert_trackingcodes');
	if(isset($trackingCodes['header']['status']) && wp_validate_boolean($trackingCodes['header']['status']) && isset($trackingCodes['header']['code']) && !empty($trackingCodes['header']['code'])) {
		echo stripslashes($trackingCodes['header']['code']);
	}
}
/* End Header Code Embed */

/* Begin Footer Code Embed */
add_action('wp_ajax_wp_insert_trackingcodes_footer_form_get_content', 'wp_insert_trackingcodes_footer_form_get_content');
function wp_insert_trackingcodes_footer_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	
	$trackingCodes = get_option('wp_insert_trackingcodes');
	echo '<div class="wp_insert_popup_content_wrapper">';
		$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_trackingcodes[footer]', 'values' => $trackingCodes['footer']));
		$control->add_control(array('type' => 'ipCheckbox', 'optionName' => 'status'));
		$control->add_control(array('type' => 'textarea', 'optionName' => 'code', 'label' => 'Embed Code'));
		echo $control->HTML;
		echo '<script type="text/javascript">';
		echo $control->JS;
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_wp_insert_trackingcodes_footer_form_save_action', 'wp_insert_trackingcodes_footer_form_save_action');
function wp_insert_trackingcodes_footer_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$trackingCodes = get_option('wp_insert_trackingcodes');
	$trackingCodes['footer']['status'] = ((isset($_POST['wp_insert_trackingcodes_footer_status']) && ($_POST['wp_insert_trackingcodes_footer_status'] == 'true'))?'1':'');
	$trackingCodes['footer']['code'] = ((isset($_POST['wp_insert_trackingcodes_footer_code']))?$_POST['wp_insert_trackingcodes_footer_code']:'');
	update_option('wp_insert_trackingcodes', $trackingCodes);
	
	if(function_exists('wp_insert_adstxt_adsense_admin_notice_reset')) {
		wp_insert_adstxt_adsense_admin_notice_reset();
	}
	die();
}

add_action('wp_footer', 'wp_insert_trackingcodes_footer_wp_footer');
function wp_insert_trackingcodes_footer_wp_footer() {
	$trackingCodes = get_option('wp_insert_trackingcodes');
	if(isset($trackingCodes['footer']['status']) && wp_validate_boolean($trackingCodes['footer']['status']) && isset($trackingCodes['footer']['code']) && !empty($trackingCodes['footer']['code'])) {
		echo stripslashes($trackingCodes['footer']['code']);
	}
}
/* End Footer Code Embed */
?>