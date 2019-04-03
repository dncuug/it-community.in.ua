<?php
require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/api/vi.php');
require_once(dirname(__FILE__).'/vi-constants.php');
require_once(dirname(__FILE__).'/gdpr.php');

/* Begin Add Assets */
add_action('wp_insert_modules_css', 'wp_insert_module_vi_css', 0);
function wp_insert_module_vi_css() {
	wp_register_style('wp-insert-module-vi-css', WP_INSERT_URL.'includes/modules/general/videointelligence/css/module.css', array('wp-insert-css'), WP_INSERT_VERSION.((WP_INSERT_DEBUG)?rand(0,9999):''));
	wp_enqueue_style('wp-insert-module-vi-css');
}

add_action('wp_insert_modules_js', 'wp_insert_module_vi_js', 0);
function wp_insert_module_vi_js() {
	wp_register_script('wp-insert-module-vi-js', WP_INSERT_URL.'includes/modules/general/videointelligence/js/module.js', array('wp-insert-js'), WP_INSERT_VERSION.((WP_INSERT_DEBUG)?rand(0,9999):''));
	wp_enqueue_script('wp-insert-module-vi-js');
}
/* End Add Assets */

/* Begin Add Card in Admin Panel */
add_action('wp_insert_plugin_card', 'wp_insert_vi_plugin_card', 10);
function wp_insert_vi_plugin_card() {
	echo '<div class="plugin-card vi-card">';
			if(wp_insert_vi_api_is_loggedin()) {
				wp_insert_vi_plugin_card_content(true);				
			} else {
				wp_insert_vi_plugin_card_content(false);
			}
	echo '</div>';
}

function wp_insert_vi_plugin_card_content($isLoggedin = false, $isAjaxRequest = false) {
	if(!$isLoggedin) {
		echo '<div class="plugin-card-top">';
			echo '<div class="plugin-card-top-header">';
				echo '<h4>Video content and video advertising – powered by video intelligence</h4>';
			echo '</div>';
			echo '<div class="plugin-card-top-content" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
				echo '<p>Advertisers pay more for video advertising when it’s matched with video content. This new video player will insert both on your page. It increases time on site, and commands a higher CPM than display advertising.</p>';	
				echo '<ul>';
					echo '<li>The set up takes only a few minutes</li>';
					echo '<li>Up to 10x higher CPM than traditional display advertising</li>';
					echo '<li>Users spend longer on your site thanks to professional video content</li>';
					echo '<li>The video player is customizable to match your site</li>';
				echo '</ul>';
				echo '<p>You\'ll see video content that is matched to your sites keywords straight away. A few days after activation you’ll begin to receive revenue from advertising served before this video content.</p>';
				echo '<p><b>This Panel will be withdrawn from the next version.<br />Existing users will be able to continue using vi ads.</b></p>';
				//echo '<p>Watch a <a href="http://demo.vi.ai/ViewsterBlog_Nintendo.html" target="_blank">demo</a> of how <b>vi stories</b> works.</p>';				
			echo '</div>';
		echo '</div>';
		echo '<div class="plugin-card-bottom" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
			//echo '<span>By clicking Sign Up button you agree to send current domain, email and affiliate ID to video intelligence.</span>';
			echo '<a id="wp_insert_vi_login" href="javascript:;" class="button button-secondary">Log In</a>';
			//echo '<a id="wp_insert_vi_signup" href="javascript:;" class="button button-primary">Sign Up</a>';
		echo '</div>';
	} else {
		$dashboardURL = wp_insert_vi_api_get_dashboardurl();
		echo '<div class="plugin-card-top">';
			echo '<div class="plugin-card-top-header">';
				echo '<h4>Video content and video advertising – powered by video intelligence</h4>';
			echo '</div>';
			echo '<div class="plugin-card-top-content" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
				echo '<p>Below you can see your current revenues.<br />Don’t see anything? Consult the <a target="_blank" href="https://www.vi.ai/frequently-asked-questions-vi-stories-for-wordpress/?utm_source=WordPress&utm_medium=Plugin%20FAQ&utm_campaign=WP%20Insert">FAQs</a>.<br /><b>This Panel will be withdrawn from the next version.<br />Existing users will be able to continue using vi ads.</b></p>';
				echo '<div id="wp_insert_vi_earnings_wrapper">';
					echo '<div class="wp_insert_ajaxloader"></div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="plugin-card-bottom" '.(($isAjaxRequest)?'style="opacity: 0;"':'').'>';
			echo '<a id="wp_insert_vi_dashboard" href="'.$dashboardURL.'" target="_blank" class="button button-primary alignleft">Publisher Dashboard</a>';
			echo '<a id="wp_insert_vi_customize_adcode" href="javascript:;" class="button button-primary alignleft">Configure vi Code</a>';
			echo '<a id="wp_insert_vi_logout" href="javascript:;" class="button button-secondary">Log Out</a>';					
		echo '</div>';
	}
}

add_action('wp_ajax_wp_insert_vi_get_chart', 'wp_insert_vi_get_chart');
function wp_insert_vi_get_chart() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	$revenueData = wp_insert_vi_api_get_revenue_data();
	if(isset($revenueData) && is_array($revenueData)) {
		echo '###SUCCESS###';
		echo '<div id="wp_insert_vi_earnings">';
			echo '<span id="wp_insert_vi_earnings_label">Total Earnings</span>';
			echo '<span id="wp_insert_vi_earnings_value">USD '.$revenueData['netRevenue'].'</span>';
		echo '</div>';
		echo '<div id="wp_insert_vi_chart_wrapper">';
			echo '<canvas id="wp_insert_vi_chart" width="348" height="139"></canvas>';
			echo '<textarea id="wp_insert_vi_chart_data" style="display: none;">[';
			if(isset($revenueData['mtdReport']) && is_array($revenueData['mtdReport']) & (count($revenueData['mtdReport']) > 0)) {
				$isFirstItem = true;
				foreach($revenueData['mtdReport'] as $reportData) {
					if(!$isFirstItem) {
						echo ',';
					}
					$date = DateTime::createFromFormat('d-m-Y', $reportData['date']);
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
		echo '<p class="viError">There was an error processing your request, our team was notified. Try clearing your browser cache, log out and log in again.</p>';
		echo '<div id="wp_insert_vi_earnings_wrapper">';
			echo '<div id="wp_insert_vi_earnings">';
				echo '<span id="wp_insert_vi_earnings_label">Total Earnings</span>';
				echo '<span id="wp_insert_vi_earnings_value"><img src="'.WP_INSERT_URL.'includes/assets/images/no-data.jpg?'.WP_INSERT_VERSION.'"></span>';
			echo '</div>';
			echo '<div id="wp_insert_vi_chart_wrapper">';
				echo '<img width="348" height="139" src="'.WP_INSERT_URL.'includes/assets/images/empty-graph.jpg?'.WP_INSERT_VERSION.'">';
			echo '</div>';
			echo '<div class="clear"></div>';
		echo '</div>';
	}
	die();
}
/* End Add Card in Admin Panel */
?>