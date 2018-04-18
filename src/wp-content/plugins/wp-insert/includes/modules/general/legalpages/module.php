<?php
require_once(dirname(__FILE__).'/functions.php');

/* Begin Add Assets */
add_action('wp_insert_modules_js', 'wp_insert_module_legalpages_js', 0);
function wp_insert_module_legalpages_js() {
	wp_register_script('wp-insert-module-legalpages-js', WP_INSERT_URL.'includes/modules/general/legalpages/js/module.js', array('wp-insert-js'), WP_INSERT_VERSION.((WP_INSERT_DEBUG)?rand(0,9999):''));
	wp_enqueue_script('wp-insert-module-legalpages-js');
}
/* End Add Assets */

/* Begin Add Card in Admin Panel */
add_action('wp_insert_plugin_card', 'wp_insert_legalpages_plugin_card', 70);
function wp_insert_legalpages_plugin_card() {
	echo '<div class="plugin-card">';
		echo '<div class="plugin-card-top">';
			echo '<h4>Legal Pages</h4>';
			echo '<p>Legal Page Templates to kick start your Legal Notices.</p>';
		echo '</div>';
		echo '<div class="plugin-card-bottom">';
			echo '<p><a id="wp_insert_legalpages_privacy_policy" href="javascript:;">Privacy Policy</a></p>';
			echo '<p><a id="wp_insert_legalpages_terms_conditions" href="javascript:;">Terms and Conditions</a></p>';
			echo '<p><a id="wp_insert_legalpages_disclaimer" href="javascript:;">Disclaimer</a></p>';
			echo '<p><a id="wp_insert_legalpages_copyright" href="javascript:;">Copyright Notice</a></p>';
			echo '<!--<p><a href="#">EU Cookie Compliance</a></p>-->';
		echo '</div>';
	echo '</div>';
}
/* End Add Card in Admin Panel */
?>