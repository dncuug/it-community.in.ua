<?php
require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/api-handler.php');

/* Begin Add Assets */
add_action('wp_insert_modules_css', 'wp_insert_module_google_css', 0);
function wp_insert_module_google_css() {
	wp_register_style('wp-insert-module-google-css', WP_INSERT_URL.'includes/modules/general/google/css/module.css', array('wp-insert-css'), WP_INSERT_VERSION.((WP_INSERT_DEBUG)?rand(0,9999):''));
	wp_enqueue_style('wp-insert-module-google-css');
}

add_action('wp_insert_modules_js', 'wp_insert_module_google_js', 0);
function wp_insert_module_google_js() {
	wp_register_script('wp-insert-module-google-js', WP_INSERT_URL.'includes/modules/general/google/js/module.js', array('wp-insert-js'), WP_INSERT_VERSION.((WP_INSERT_DEBUG)?rand(0,9999):''));
	wp_enqueue_script('wp-insert-module-google-js');
}
/* End Add Assets */

/* Begin Add Card in Admin Panel */
add_action('wp_insert_plugin_card', 'wp_insert_google_plugin_card', 11);
function wp_insert_google_plugin_card() {
	echo '<div class="plugin-card google-card">';
		if(wp_insert_google_api_get_access_token()) {
			wp_insert_google_plugin_card_content(true);				
		} else {
			wp_insert_google_plugin_card_content(false);
		}
	echo '</div>';
}

function wp_insert_google_plugin_card_content($isLoggedin = false, $isAjaxRequest = false) {
	if(!$isLoggedin) {		
		echo '<div class="plugin-card-top">';
			echo '<div class="plugin-card-top-header">';
				echo '<h4>Adsense by Google (Optional - Recommended)</h4>';
			echo '</div>';
			echo '<div class="plugin-card-top-content" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
				echo '<p>Connect your Adsense account to Wp-Insert to enable seamless integration and view your Adsense Stats right here</p>';	
				echo '<ul>';
					echo '<li>No need to copy and paste Ad codes</li>';
					echo '<li>View stats and earnings in Wp-Insert dashboard</li>';
					echo '<li>Recognize which ads are performing and which are not</li>';
					echo '<li>View Adsense Alerts and Messages in Wp-Insert dashboard</li>';
					echo '<li>Ease of Use</li>';
				echo '</ul>';
				echo '<p>PS : You can continue to use adsense ads without linking your account to Wp-Insert.  This feature is just an added convinence.</p>';

				echo '<pre>';

				echo '</pre>';
				
			echo '</div>';
		echo '</div>';
		echo '<div class="plugin-card-bottom" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
			echo '<a id="wp_insert_google_login" href="javascript:;" class="button button-secondary">Log In / Authorize</a>';
			echo '<a id="wp_insert_google_signup" href="http://google.com/adsense" target="_blank" class="button button-primary">Sign Up</a>';
		echo '</div>';		
	} else {
		echo '<div class="plugin-card-top">';
			echo '<div class="plugin-card-top-header">';
				echo '<h4>Adsense by Google (Optional - Recommended)</h4>';
			echo '</div>';
			echo '<div class="plugin-card-top-content" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
				echo '<p>You can see your earnings and stats below.<br />You can use Adsense ad units in supported Ad types without having to copy and paste the Ad code.</p>';					
			echo '<div id="wp_insert_google_earnings_wrapper">';
				echo '<div class="wp_insert_ajaxloader"></div>';
			echo '</div>';					
			echo '</div>';
		echo '</div>';
		echo '<div class="plugin-card-bottom" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
			echo '<a id="wp_insert_google_dashboard" href="http://google.com/adsense" target="_blank" class="button button-primary alignleft">Publisher Dashboard</a>';
			echo '<a id="wp_insert_google_logout" href="javascript:;" class="button button-secondary">Log Out / Deauthorize</a>';
			echo '<div id="wp_insert_google_ad_units_wrapper">';
				echo '<div class="wp_insert_ajaxloader"></div>';
			echo '</div>';
		echo '</div>';
	}
}
add_action('wp_ajax_wp_insert_google_get_ad_units', 'wp_insert_google_get_ad_units');
function wp_insert_google_get_ad_units() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	$adUnits = wp_insert_google_api_get_ad_units();
	if(is_array($adUnits)) {
		echo '###SUCCESS###';
		echo '<div id="wp_insert_google_active_ad_units">';
		foreach($adUnits as $adUnit) {
			if($adUnit['status'] == 'ACTIVE') {
				echo '<p>';
					echo '<a class="wp_insert_ad_unit_title" title="Ad Unit" onclick="wp_insert_google_adunit_stats_handler(\''.$adUnit['id'].'\', \''.$adUnit['name'].'\', \''.$adUnit['accountID'].'\')" id="wp_insert_google_active_ad_'.$adUnit['id'].'" href="javascript:;">'.$adUnit['name'].'</a>';
					//echo '<span class="dashicons dashicons-no wp_insert_delete_icon" title="Delete Ad Unit"></span>';
					//echo '<span class="dashicons dashicons-format-gallery wp_insert_duplicate_icon" title="Duplicate Ad Unit"></span>';						
				echo '</p>';
			}
		}
		echo '</div>';
		/*echo '<div id="wp_insert_google_inactive_ad_units">';
			echo '<p id="wp_insert_google_inactive_ad_units_button" onclick="wp_insert_google_inactive_ad_units_toggle()">Show Inactive Ad Units<span class="dashicons dashicons-arrow-down" title="Show Inactive Ad Units"></span></p>';
		foreach($adUnits as $adUnit) {
			if($adUnit['status'] == 'INACTIVE') {
				echo '<p>';
					echo '<a class="wp_insert_ad_unit_title" title="Ad Unit" id="wp_insert__ad_" href="javascript:;">'.$adUnit['name'].'</a>';
					//echo '<span class="dashicons dashicons-no wp_insert_delete_icon" title="Delete Ad Unit"></span>';
					//echo '<span class="dashicons dashicons-format-gallery wp_insert_duplicate_icon" title="Duplicate Ad Unit"></span>';						
				echo '</p>';
			}
		}
		echo '</div>';*/
	}
	die();
}

add_action('wp_ajax_wp_insert_google_get_chart', 'wp_insert_google_get_chart');
function wp_insert_google_get_chart() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	$accessToken = wp_insert_google_api_get_access_token();
	$accounts = wp_insert_google_api_get_accounts($accessToken);
	if(($accounts != false) && is_array($accounts) && isset($accounts[0]['id'])) {
		$revenueData = wp_insert_google_api_get_revenue_data($accounts[0]['id'], date('Y-m-d', strtotime('-1 month')), date('Y-m-d'), $accessToken);
	}
	if(isset($revenueData) && is_array($revenueData)) {
		echo '###SUCCESS###';
		echo '<div id="wp_insert_google_earnings">';
			echo '<span id="wp_insert_google_earnings_label">Total Earnings</span>';
			echo '<span id="wp_insert_google_earnings_value">'.$revenueData['revenue']['currency'].' '.$revenueData['revenue']['revenue'].'</span>';
		echo '</div>';
		echo '<div id="wp_insert_google_chart_wrapper">';
			echo '<canvas id="wp_insert_google_chart" width="348" height="139"></canvas>';
			echo '<textarea id="wp_insert_google_chart_data" style="display: none;">[';
			if(isset($revenueData['report']) && is_array($revenueData['report']) & (count($revenueData['report']) > 0)) {
				$isFirstItem = true;
				foreach($revenueData['report'] as $reportData) {
					if(!$isFirstItem) {
						echo ',';
					}
					$date = DateTime::createFromFormat('Y-m-d', $reportData['date']);
					echo '{"x": "'.$date->format('m/d/Y').'", "y": "'.$reportData['revenue'].'"}';
					$isFirstItem = false;;
				}
			} else {
				echo '{"x": "'.date('m/d/Y').'", "y": "0.00"}';
			}				
			echo ']</textarea>';
		echo '</div>';
		echo '<div class="clear"></div>';
	} else {
		echo '<p class="googleError">There was an error processing your request, our team was notified. Try clearing your browser cache, log out and log in again.</p>';
		echo '<div id="wp_insert_google_earnings_wrapper">';
			echo '<div id="wp_insert_google_earnings">';
				echo '<span id="wp_insert_google_earnings_label">Total Earnings</span>';
				echo '<span id="wp_insert_google_earnings_value"><img src="'.WP_INSERT_URL.'includes/assets/images/no-data.jpg?'.WP_INSERT_VERSION.'"></span>';
			echo '</div>';
			echo '<div id="wp_insert_google_chart_wrapper">';
				echo '<img width="348" height="139" src="'.WP_INSERT_URL.'includes/assets/images/empty-graph.jpg?'.WP_INSERT_VERSION.'">';
			echo '</div>';
			echo '<div class="clear"></div>';
		echo '</div>';
	}
	die();
}
/* End Add Card in Admin Panel */
?>