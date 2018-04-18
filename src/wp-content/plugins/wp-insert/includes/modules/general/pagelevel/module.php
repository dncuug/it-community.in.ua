<?php
/* Begin UI Functions */
add_action('wp_insert_plugin_card', 'wp_insert_pagelevelads_plugin_card', 60);
function wp_insert_pagelevelads_plugin_card() {
	wp_insert_get_plugin_card(
		'Adsense Auto Ads / Page Level Ads',
		'<p>Auto ads use machine learning to make smart placement and monetization decisions on your behalf, saving you time. Place one piece of code just once to all of your pages, and let Adsense take care of the rest.</p>',
		'pagelevelads',
		'Page-Level Ad'
	);
}

add_action('wp_ajax_wp_insert_pagelevelads_get_ad_form', 'wp_insert_get_ad_form');
add_action('wp_ajax_wp_insert_pagelevelads_save_ad_data', 'wp_insert_save_ad_data');
add_action('wp_ajax_wp_insert_pagelevelads_delete_ad_data', 'wp_insert_delete_ad_data');

add_filter('wp_insert_pagelevelads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_adcode', 20, 3);
add_filter('wp_insert_pagelevelads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_rules', 30, 3);
add_filter('wp_insert_pagelevelads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_geo_targeting', 40, 3);
add_filter('wp_insert_pagelevelads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_notes', 60, 3);
/* End UI Functions */

/* Begin Page-Level Ad Insertion */
add_action('wp_head', 'wp_insert_pagelevelads_wp_head');
function wp_insert_pagelevelads_wp_head() {
	$pagelevelads = get_option('wp_insert_pagelevelads');
	if(isset($pagelevelads) && is_array($pagelevelads)) {
		foreach($pagelevelads as $pagelevelad) {
			if(isset($pagelevelad) && is_array($pagelevelad) && wp_insert_get_ad_status($pagelevelad)) {
				echo wp_insert_get_ad_unit_code($pagelevelad);
			}
		}
	}
}
/* End Page-Level Ad Insertion */
?>