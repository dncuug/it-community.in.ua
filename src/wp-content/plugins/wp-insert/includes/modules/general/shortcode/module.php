<?php
/* Begin UI Functions */
add_action('wp_insert_plugin_card', 'wp_insert_shortcodeads_plugin_card', 50);
function wp_insert_shortcodeads_plugin_card() {
	wp_insert_get_plugin_card(
		'Shortcode Ads',
		'<p>Ads embedded directly inside post / page content via shortcodes.</p>',
		'shortcodeads',
		'Shortcode Ad'
	);
}

add_action('wp_ajax_wp_insert_shortcodeads_get_ad_form', 'wp_insert_get_ad_form');
add_action('wp_ajax_wp_insert_shortcodeads_save_ad_data', 'wp_insert_save_ad_data');
add_action('wp_ajax_wp_insert_shortcodeads_delete_ad_data', 'wp_insert_delete_ad_data');

add_filter('wp_insert_shortcodeads_form_accordion_tabs', 'wp_insert_shortcodeads_form_accordion_tabs_shortcode', 10, 3);
function wp_insert_shortcodeads_form_accordion_tabs_shortcode($control, $identifier, $location) {
	echo '<h3>Shortcode</h3>';
	echo '<div>';
		$control->set_HTML('<p class="codeSnippet"><code>[wpinsertshortcodead id="'.$identifier.'"]</code></p>');
		$control->create_section('Code to add to your post/page content');
		echo $control->HTML;
		$control->clear_controls();
	echo '</div>';
	return $control;
}
add_filter('wp_insert_shortcodeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_adcode', 20, 3);
add_filter('wp_insert_shortcodeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_rules', 30, 3);
add_filter('wp_insert_shortcodeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_geo_targeting', 40, 3);
add_filter('wp_insert_shortcodeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_devices_styles', 50, 3);
add_filter('wp_insert_shortcodeads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_notes', 60, 3);
/* End UI Functions */

/* Begin Shortcode Ad Insertion */
add_shortcode('wpshortcodead', 'wp_insert_shortcodeads_shortcode');
add_shortcode('wpinsertshortcodead', 'wp_insert_shortcodeads_shortcode');
function wp_insert_shortcodeads_shortcode($atts) {
	$atts = shortcode_atts(array('id' => ''), $atts, 'wpinsertshortcodead');
	if(isset($atts['id']) && ($atts['id'] != '')) {
		$shortcodeads = get_option('wp_insert_shortcodeads');
		if(isset($shortcodeads[$atts['id']]) && is_array($shortcodeads[$atts['id']]) && wp_insert_get_ad_status($shortcodeads[$atts['id']])) {
			return wp_insert_get_ad_unit($shortcodeads[$atts['id']]);
		}
	}
}
/* End Shortcode Ad Insertion */
?>