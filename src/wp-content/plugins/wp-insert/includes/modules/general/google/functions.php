<?php 
/* Begin Login / Authorization Form */
add_action('wp_ajax_wp_insert_google_login_form_get_content', 'wp_insert_google_login_form_get_content');
function wp_insert_google_login_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	echo '<div class="wp_insert_popup_content_wrapper">';
		echo '<div class="wp_insert_google_loginform_wrapper_step_1">';
			$authenticationData = get_option('wp_insert_google_api_authentication_data', true);
			$control = new smartlogixControls();
			
			$clientId = '';
			if(isset($authenticationData) && is_array($authenticationData) && ($authenticationData['clientId'] != '')) {
				$clientId = $authenticationData['clientId'];
			}
			$control->add_control(array('type' => 'text', 'id' => 'wp_insert_google_login_client_id', 'name' => 'wp_insert_google_login_client_id', 'label' => '', 'value' => $clientId, 'className' => "text ui-widget-content ui-corner-all widefat"));
			$control->create_section('Client ID : xxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com');
			$controlClientID = $control->HTML;
			$control->clear_controls();

			$clientSecret = '';
			if(isset($authenticationData) && is_array($authenticationData) && ($authenticationData['clientSecret'] != '')) {
				$clientSecret = $authenticationData['clientSecret'];
			}			
			$control->add_control(array('type' => 'text', 'id' => 'wp_insert_google_login_client_secret', 'name' => 'wp_insert_google_login_client_secret', 'label' => '', 'value' => $clientSecret, 'className' => "text ui-widget-content ui-corner-all widefat"));
			$control->create_section('Client Secret : xxxxxxxxxxxxxxxxxxxxxxxx');
			$controlClientSecret = $control->HTML;
			$control->clear_controls();

			$control->HTML .= '<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button" onclick="wp_insert_google_login_get_auth_code()">';
				$control->HTML .= '<span class="ui-button-text">Get Authorization Code</span>';
			$control->HTML .= '</button>';		
			$control->create_section('Get Authorization Code');
			$controlAuthButton = $control->HTML;
			$control->clear_controls();	

			$control->add_control(array('type' => 'text', 'id' => 'wp_insert_google_login_auth_code', 'name' => 'wp_insert_google_login_auth_code', 'label' => '', 'value' => '', 'className' => "text ui-widget-content ui-corner-all widefat"));
			$control->create_section('Auth Code : xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
			$controlAuthCode = $control->HTML;
			$control->clear_controls();	
			
			$control->HTML .= '<ol>';
				$control->HTML .= '<li>Go to the Google Developers Console at <a href="https://console.developers.google.com">https://console.developers.google.com.</a></li>';
				$control->HTML .= '<li>If you have not created a project yet, choose <b>Select a project</b> from the menu bar, and then choose <b>Create</b> a project.<br /><small>(Returning users can go straight to step 14)</small></li>';
				$control->HTML .= '<li>Enter <b>Wp-Insert</b> as the project name and click on the <b>Create</b> button</li>';
				$control->HTML .= '<li>Wait for the project to be created and select <b>Wp-Insert</b> as the current project</li>';
				$control->HTML .= '<li>Click on <b>ENABLE APIS AND SERVICES</b></li>';
				$control->HTML .= '<li>Search for <b>adsense</b> and click on / select <b>AdSense Management API</b> and then click on <b>Enable</b></li>';
				$control->HTML .= '<li>Click on <b>Create credentials</b></li>';
				$control->HTML .= '<li>Under <b>Where will you be calling the API from?</b>; Select <b>Other UI (e.g. Windows, CLI tool)</b></li>';
				$control->HTML .= '<li>Under <b>What data will you be accessing?</b>; Select <b>User data</b></li>';
				$control->HTML .= '<li>Click on <b>What credentials do I need?</b></li>';
				$control->HTML .= '<li>Under <b>Create an OAuth 2.0 client ID</b> Enter the name as <b>Wp-Insert</b></li>';
				$control->HTML .= '<li>Under <b>Set up the OAuth 2.0 consent screen</b> select your email address and enter the <b>Product name shown to users</b> as <b>Wp-Insert</b></li>';
				$control->HTML .= '<li>Click on <b>I will do this later</b> or <b>Done</b></li>';
				$control->HTML .= '<li>In the new screen under <b>OAuth 2.0 client IDs</b> click on <b>Wp-Insert</b></li>';
				$control->HTML .= '<li>';
					$control->HTML .= 'Copy <b>Client ID</b> and paste it below';						
					$control->HTML .= $controlClientID;
				$control->HTML .= '</li>';
				$control->HTML .= '<li>';
					$control->HTML .= 'Copy <b>Client Secret</b> and paste it below';						
					$control->HTML .= $controlClientSecret;
				$control->HTML .= '</li>';
				$control->HTML .= '<li>You can now close Google Developers Console</li>';
				$control->HTML .= '<li>';
					$control->HTML .= 'Click <b>Get Authorization Code</b> button below<br />';						
					$control->HTML .= $controlAuthButton;
				$control->HTML .= '</li>';
				$control->HTML .= '<li>';
					$control->HTML .= 'Copy <b>Authorization Code</b> and paste it below';						
					$control->HTML .= $controlAuthCode;
				$control->HTML .= '</li>';
			$control->HTML .= '</ol>';
			$control->create_section('Please follow the steps below to connect your Adsense account.');
			echo $control->HTML;
		echo '</div>';
		echo '<script type="text/javascript">';
			echo $control->JS;
			echo 'jQuery(".ui-dialog-buttonset").find("button").first().find("span:nth-child(2)").hide().after("<span class=\'ui-button-text\'>Login / Authorize</span>");';
			echo 'jQuery(".ui-dialog-buttonset").find("button").first().find("span:nth-child(1)").attr("class", "ui-button-icon-primary ui-icon ui-icon-key");';
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_wp_insert_google_login_generate_auth_url', 'wp_insert_google_login_generate_auth_url');
function wp_insert_google_login_generate_auth_url() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	if(isset($_POST['wp_insert_google_login_client_id']) && ($_POST['wp_insert_google_login_client_id'] != '')) {
		$wp_insert_google_api_get_auth_url = wp_insert_google_api_get_auth_url($_POST['wp_insert_google_login_client_id']);
		if(isset($wp_insert_google_api_get_auth_url) && ($wp_insert_google_api_get_auth_url != '')) {
			echo '###SUCCESS###';
			echo $wp_insert_google_api_get_auth_url;
		}
	}
	die();
}

add_action('wp_ajax_wp_insert_google_login_form_save_action', 'wp_insert_google_login_form_save_action');
function wp_insert_google_login_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	if(isset($_POST['wp_insert_google_login_client_id']) && ($_POST['wp_insert_google_login_client_id'] != '') && isset($_POST['wp_insert_google_login_client_secret']) && ($_POST['wp_insert_google_login_client_secret'] != '') && isset($_POST['wp_insert_google_login_auth_code']) && ($_POST['wp_insert_google_login_auth_code'] != '')) {
		$authenticationStatus = wp_insert_google_api_set_access_token($_POST['wp_insert_google_login_client_id'], $_POST['wp_insert_google_login_client_secret'], $_POST['wp_insert_google_login_auth_code']);
		if(isset($authenticationStatus) && ($authenticationStatus == true)) {
			echo '###SUCCESS###';
			wp_insert_google_plugin_card_content(true, true);			
		} else {
			echo '<p class="wp_insert_google_login_error">Login Error:<br />Please check your credentials and try again later!'.'</p>';
		}			
	}
	die();
}
/* End Login / Authorization Form */

/* Begin Logout / De-Authorization */
add_action('wp_ajax_wp_insert_google_logout_action', 'wp_insert_google_logout_action');
function wp_insert_google_logout_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	wp_insert_google_api_revoke_access_token();
	echo '###SUCCESS###';
	wp_insert_google_plugin_card_content(false, true);
	die();
}
/* End Logout / De-Authorization */

/* Begin Ad Unit Stats */
add_action('wp_ajax_wp_insert_google_adunit_get_stats', 'wp_insert_google_adunit_get_stats');
function wp_insert_google_adunit_get_stats() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	if(isset($_POST['wp_insert_google_account_id']) && ($_POST['wp_insert_google_account_id'] != '') && isset($_POST['wp_insert_google_adunit_id']) && ($_POST['wp_insert_google_adunit_id'] != '')) {
		$wp_insert_google_account_id = $_POST['wp_insert_google_account_id'];
		$wp_insert_google_adunit_id = $_POST['wp_insert_google_adunit_id'];
		
		$revenueData = wp_insert_google_api_get_adunit_revenue_data($wp_insert_google_adunit_id, $wp_insert_google_account_id, date('Y-m-d', strtotime('-3 month')), date('Y-m-d'), $accessToken);
		//echo '<pre>'; print_r($revenueData); echo '</pre>';
		if(isset($revenueData) && is_array($revenueData)) {
			echo '###SUCCESS###';
			echo '<div id="wp_insert_google_adunit_chart_wrapper">';
				echo '<canvas id="wp_insert_google_adunit_chart"></canvas>';
				echo '<textarea id="wp_insert_google_adunit_chart_data" style="display: none;">[';
				if(isset($revenueData['report']) && is_array($revenueData['report']) & (count($revenueData['report']) > 0)) {
					$isFirstItem = true;
					foreach($revenueData['report'] as $reportData) {
						if(!$isFirstItem) {
							echo ',';
						}
						$date = DateTime::createFromFormat('Y-m-d', $reportData['date']);
						echo '{"x": "'.$date->format('m/d/Y').'", "y": "'.$reportData['earnings'].'"}';
						$isFirstItem = false;;
					}
				} else {
					echo '{"x": "'.date('m/d/Y').'", "y": "0.00"}';
				}				
				echo ']</textarea>';
			echo '</div>';
		}
	}
	die();
}
/* End Ad Unit Stats */
?>