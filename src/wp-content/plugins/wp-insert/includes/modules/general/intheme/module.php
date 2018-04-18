<?php
/* Begin UI Functions */
add_action('wp_insert_plugin_card', 'wp_insert_inthemeads_plugin_card', 40);
function wp_insert_inthemeads_plugin_card() {
	wp_insert_get_plugin_card(
		'In-Theme Ads',
		'<p>Ads embedded directly inside theme files (Advanced Users Only).</p>',
		'inthemeads',
		'In-Theme Ad'
	);
}

add_action('wp_ajax_wp_insert_inthemeads_get_ad_form', 'wp_insert_get_ad_form');
add_action('wp_ajax_wp_insert_inthemeads_save_ad_data', 'wp_insert_save_ad_data');
add_action('wp_ajax_wp_insert_inthemeads_delete_ad_data', 'wp_insert_delete_ad_data');

add_filter('wp_insert_inthemeads_form_accordion_tabs', 'wp_insert_inthemeads_form_accordion_tabs_code_snippet', 10, 3);
function wp_insert_inthemeads_form_accordion_tabs_code_snippet($control, $identifier, $location) {
	echo '<h3>Code Snippet</h3>';
	echo '<div>';
		$control->set_HTML('<p class="codeSnippet"><code>&lt;?php if(function_exists("wp_intheme_ad")) { wp_intheme_ad("'.$identifier.'"); } ?&gt;</code></p>');
		$control->create_section('Code to add to your theme files');
		echo $control->HTML;
		$control->clear_controls();
	echo '</div>';
	return $control;
}
add_filter('wp_insert_inthemeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_adcode', 20, 3);
add_filter('wp_insert_inthemeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_rules', 30, 3);
add_filter('wp_insert_inthemeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_geo_targeting', 40, 3);
add_filter('wp_insert_inthemeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_devices_styles', 50, 3);
add_filter('wp_insert_inthemeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_notes', 60, 3);
/* End UI Functions */

/* Begin In-Theme Ad Insertion */
function wp_template_ad($identifier) { wp_intheme_ad('templateads-'.$identifier); } /*Backward Compatibility */
function wp_intheme_ad($identifier) {
	$inthemeads = get_option('wp_insert_inthemeads');
	if(isset($inthemeads[$identifier]) && is_array($inthemeads[$identifier]) && wp_insert_get_ad_status($inthemeads[$identifier])) {
		echo wp_insert_get_ad_unit($inthemeads[$identifier]);
	}
}
/* End In-Theme Ad Insertion */
?>