<?php
require_once(dirname(__FILE__).'/functions.php');

/* Begin Add Assets */
add_action('wp_insert_modules_js', 'wp_insert_module_trackingcodes_js', 0);
function wp_insert_module_trackingcodes_js() {
	wp_register_script('wp-insert-module-trackingcodes-js', WP_INSERT_URL.'includes/modules/general/trackingcodes/js/module.js', array('wp-insert-js'), WP_INSERT_VERSION.((WP_INSERT_DEBUG)?rand(0,9999):''));
	wp_enqueue_script('wp-insert-module-trackingcodes-js');
}
/* End Add Assets */

/* Begin Add Card in Admin Panel */
add_action('wp_insert_plugin_card', 'wp_insert_trackingcodes_plugin_card', 80);
function wp_insert_trackingcodes_plugin_card() {
	echo '<div class="plugin-card">';
		echo '<div class="plugin-card-top">';
			echo '<h4>Tracking Codes</h4>';
			echo '<p>Google Analytics and other embeddable codes directly inserted into the site.</p>';
		echo '</div>';
		echo '<div class="plugin-card-bottom">';
			echo '<p><a id="wp_insert_trackingcodes_google_analytics" href="javascript:;">Google Analytics</a></p>';
			echo '<p><a id="wp_insert_trackingcodes_header" href="javascript:;">Embed Code in Header</a></p>';
			echo '<p><a id="wp_insert_trackingcodes_footer" href="javascript:;">Embed Code in Footer</a></p>';
		echo '</div>';
	echo '</div>';
}
/* End Add Card in Admin Panel */
?>