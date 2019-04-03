<?php
$wpInsertPostInstance;
$wpInsertABTestingMode;
$wpInsertVIAdDisplayed = false;
$wpInsertGeoLocation;
	
/* Begin Ad Unit */
function wp_insert_get_ad_unit($data, $additionalStyles = '') {
	/* Begin Ad Styles */
	$adunitClass = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 5)), 0, 5).uniqid();
	$adUnitStyles = wp_insert_get_ad_unit_styles($data, $adunitClass);
	$adUnitCode = wp_insert_get_ad_unit_code($data);
	if($adUnitCode != '') {
		return '<div class="'.$adunitClass.'" '.(($additionalStyles != '')?'style="'.$additionalStyles.'"':'').'>'.$adUnitCode.'</div>'.$adUnitStyles;
	}
	return '';
}
/* End Ad Unit */

/* Begin Ad Unit Styles */
function wp_insert_get_ad_unit_styles($data, $adunitClass) {
	$adBreakpoints =array(
		'device_large_desktop_width' => (int)((!isset($data['device_large_desktop_width']) || ($data['device_large_desktop_width'] == ''))?'1200':$data['device_large_desktop_width']),
		'device_medium_desktop_width' => (int)((!isset($data['device_medium_desktop_width']) || ($data['device_medium_desktop_width'] == ''))?'992':$data['device_medium_desktop_width']),
		'device_tablet_width' => (int)((!isset($data['device_tablet_width']) || ($data['device_tablet_width'] == ''))?'768':$data['device_tablet_width']),
		'device_mobile_width' => (int)((!isset($data['device_mobile_width']) || ($data['device_mobile_width'] == ''))?'480':$data['device_mobile_width']),
	);
	$adUnitStyles = '<style type="text/css">'."\r\n";
	if(isset($data['styles']) && ($data['styles'] != '')) {
		$adUnitStyles .= '.'.$adunitClass.' {'."\r\n";
			$adUnitStyles .= $data['styles']."\r\n";
		$adUnitStyles .= '}'."\r\n";
	}
	$adUnitStyles .= '@media screen and (min-width: '.($adBreakpoints['device_large_desktop_width'] + 1).'px) {'."\r\n";
		$adUnitStyles .= '.'.$adunitClass.' {'."\r\n";
		if(isset($data['device_exclude_large_desktop']) && wp_validate_boolean($data['device_exclude_large_desktop'])) {
			$adUnitStyles .= 'display: none;'."\r\n";
		} else {
			$adUnitStyles .= 'display: block;'."\r\n";
			if(isset($data['device_large_desktop_adwidth']) && ($data['device_large_desktop_adwidth'] != '') && ($data['device_large_desktop_adwidth'] != '0')) {
				$adUnitStyles .= 'width: '.$data['device_large_desktop_adwidth'].'px;'."\r\n";
			}
			if(isset($data['device_large_desktop_styles']) && ($data['device_large_desktop_styles'] != '')) {
				$adUnitStyles .= $data['device_large_desktop_styles']."\r\n";
			}
		}
		$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '@media screen and (min-width: '.($adBreakpoints['device_medium_desktop_width'] + 1).'px) and (max-width: '.$adBreakpoints['device_large_desktop_width'].'px) {'."\r\n";
		$adUnitStyles .= '.'.$adunitClass.' {'."\r\n";
		if(isset($data['device_exclude_medium_desktop']) && wp_validate_boolean($data['device_exclude_medium_desktop'])) {
			$adUnitStyles .= 'display: none;'."\r\n";
		} else {
			$adUnitStyles .= 'display: block;'."\r\n";
			if(isset($data['device_medium_desktop_adwidth']) && ($data['device_medium_desktop_adwidth'] != '') && ($data['device_medium_desktop_adwidth'] != '0')) {
				$adUnitStyles .= 'width: '.$data['device_medium_desktop_adwidth'].'px;'."\r\n";
			}
			if(isset($data['device_medium_desktop_styles']) && ($data['device_medium_desktop_styles'] != '')) {
				$adUnitStyles .= $data['device_medium_desktop_styles']."\r\n";
			}
		}
		$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '@media screen and (min-width: '.($adBreakpoints['device_tablet_width'] + 1).'px) and (max-width: '.$adBreakpoints['device_medium_desktop_width'].'px) {'."\r\n";
		$adUnitStyles .= '.'.$adunitClass.' {'."\r\n";
		if(isset($data['device_exclude_tablet']) && wp_validate_boolean($data['device_exclude_tablet'])) {
			$adUnitStyles .= 'display: none;'."\r\n";
		} else {
			$adUnitStyles .= 'display: block;'."\r\n";
			if(isset($data['device_tablet_adwidth']) && ($data['device_tablet_adwidth'] != '') && ($data['device_tablet_adwidth'] != '0')) {
				$adUnitStyles .= 'width: '.$data['device_tablet_adwidth'].'px;'."\r\n";
			}
			if(isset($data['device_tablet_styles']) && ($data['device_tablet_styles'] != '')) {
				$adUnitStyles .= $data['device_tablet_styles']."\r\n";
			}
		}
		$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '@media screen and (min-width: '.$adBreakpoints['device_tablet_width'].'px) and (max-width: '.$adBreakpoints['device_tablet_width'].'px) {'."\r\n";
		$adUnitStyles .= '.'.$adunitClass.' {'."\r\n";
		if(isset($data['device_exclude_mobile']) && wp_validate_boolean($data['device_exclude_mobile'])) {
			$adUnitStyles .= 'display: none;'."\r\n";
		} else {
			$adUnitStyles .= 'display: block;'."\r\n";
			if(isset($data['device_mobile_adwidth']) && ($data['device_mobile_adwidth'] != '') && ($data['device_mobile_adwidth'] != '0')) {
				$adUnitStyles .= 'width: '.$data['device_mobile_adwidth'].'px;'."\r\n";
			}
			if(isset($data['device_mobile_styles']) && ($data['device_mobile_styles'] != '')) {
				$adUnitStyles .= $data['device_mobile_styles']."\r\n";
			}
		}
		$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '@media screen and (max-width: '.($adBreakpoints['device_tablet_width'] - 1).'px) {'."\r\n";
		$adUnitStyles .= '.'.$adunitClass.' {'."\r\n";
		if(isset($data['device_exclude_small_mobile']) && wp_validate_boolean($data['device_exclude_small_mobile'])) {
			$adUnitStyles .= 'display: none;'."\r\n";
		} else {
			$adUnitStyles .= 'display: block;'."\r\n";
			if(isset($data['device_small_mobile_adwidth']) && ($data['device_small_mobile_adwidth'] != '') && ($data['device_small_mobile_adwidth'] != '0')) {
				$adUnitStyles .= 'width: '.$data['device_small_mobile_adwidth'].'px;'."\r\n";
			}
			if(isset($data['device_small_mobile_styles']) && ($data['device_small_mobile_styles'] != '')) {
				$adUnitStyles .= $data['device_small_mobile_styles']."\r\n";
			}
		}
		$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '}'."\r\n";
	$adUnitStyles .= '</style>'."\r\n";
	return $adUnitStyles;
}
/* End Ad Unit Styles */

/* Begin Ad Unit Code */
function wp_insert_get_ad_unit_code($data) {
	global $wpInsertABTestingMode;
	global $wpInsertVIAdDisplayed;
	global $wpInsertGeoLocation;
	
	$adUnitCode = '';
	if(($wpInsertGeoLocation != false) && ($wpInsertGeoLocation != '') && ((is_array($data['geo_group1_countries']) && (count($data['geo_group1_countries']) > 0)) || (is_array($data['geo_group2_countries']) && (count($adOptions['geo_group1_countries']) > 0)))) {
		if(($data['geo_group1_adcode'] != '') && in_array($wpInsertGeoLocation, $data['geo_group1_countries'])) {
			$adUnitCode = do_shortcode(stripslashes($data['geo_group1_adcode']));
		}
		if(($data['geo_group2_adcode'] != '') && in_array($wpInsertGeoLocation, $data['geo_group2_countries'])) {
			$adUnitCode = do_shortcode(stripslashes($data['geo_group2_adcode']));
		}
	}
	if($adUnitCode == '') {
		switch($wpInsertABTestingMode) {
			case 1:			
				if(isset($data['primary_ad_code_type']) && ($data['primary_ad_code_type'] == 'vicode')) {
					if($wpInsertVIAdDisplayed !== true) {
						$wpInsertVIAdDisplayed = true;
						$adUnitCode = '<div id="wp_insert_vi_ad">'.wp_insert_vi_api_get_vi_code('wp_insert_vi_code_settings').'</div>';
					} else {
						$adUnitCode = '';
					}
				} else {
					$adUnitCode = do_shortcode(stripslashes($data['primary_ad_code']));
				}
				break;
			case 2:
				$adUnitCode = do_shortcode(stripslashes($data['secondary_ad_code']));
				break;
			case 3:
				$adUnitCode = do_shortcode(stripslashes($data['tertiary_ad_code']));
				break;
			default:
				$adUnitCode = do_shortcode(stripslashes($data['primary_ad_code']));
		}
	}
	return $adUnitCode;
}
/* End Ad Unit Code */

/* Begin Assign Instance Identifier */
add_action('the_content', 'wp_insert_track_post_instance', 1);
function wp_insert_track_post_instance($content) {
	global $wpInsertPostInstance;
	if(is_main_query()) {
		if($wpInsertPostInstance == '') {
			$wpInsertPostInstance = 1;
		} else {
			$wpInsertPostInstance++;
		}
	}
	return $content;
}
/* End Assign Instance Identifier */

/* Begin Assign AB Testing Mode */
add_action('wp', 'wp_insert_track_ad_instance', 1);
function wp_insert_track_ad_instance() {
	global $wpInsertABTestingMode;
	$abtestingMode = get_option('wp_insert_abtesting_mode');
	if(isset($abtestingMode)) {
		$wpInsertABTestingMode = rand(1, floatval($abtestingMode));
	} else {
		$wpInsertABTestingMode = 1;
	}
}
/* End Assign AB Testing Mode */

/* Begin Get Current Page Type */
function wp_insert_get_page_details() {
	global $post;
	$page_details = array(
		'type' => 'POST',
		'ID' => (isset($post->ID)?$post->ID:'')
	);
	if(is_home() || is_front_page()) {
		$page_details['type'] = 'HOME';
	} else if(is_category()) {
		$page_details['type'] = 'CATEGORY';
		$page_details['ID'] = get_query_var('cat');
	} else if(is_archive()) {
		$page_details['type'] = 'ARCHIVE';
	} else if(is_search()) {
		$page_details['type'] = 'SEARCH';
	} else if(is_page()) {
		$page_details['type'] = 'PAGE';
	} else if(is_single()) {
		if(is_singular('post')) {
			$page_details['type'] = 'POST';
			$page_details['categories'] = wp_get_post_categories($page_details['ID']);
		} else {
			$page_details['type'] = 'CUSTOM';
			$page_details['type_name'] = $post->post_type;
		}
	} else if(is_404()) {
		$page_details['type'] = '404';
	}
	return $page_details;
}
/* End Get Current Page Type */

/* Begin Get Ad Status */
function wp_insert_get_ad_status($rules) {
	if(!isset($rules)) { return false; }
	
	if(!wp_validate_boolean($rules['status'])) {
		return false;
	}

	if(function_exists('is_amp_endpoint') && is_amp_endpoint()) {
		return false;
	}
	
	if(function_exists('is_woocommerce') && is_woocommerce()) {
		return false;
	}
	
	if(isset($rules['rules_exclude_loggedin']) && wp_validate_boolean($rules['rules_exclude_loggedin']) && is_user_logged_in()) {
		return false;
	}
	
	if(isset($rules['rules_exclude_mobile_devices']) && wp_validate_boolean($rules['rules_exclude_mobile_devices']) && wp_is_mobile()) {
		return false;
	}
	
	global $wpInsertPostInstance;
	$page_details = wp_insert_get_page_details();
	switch($page_details['type']) {
		case 'HOME':
			if(isset($rules['rules_exclude_home']) && wp_validate_boolean($rules['rules_exclude_home']) ) {
				return false;
			} else if(isset($rules['rules_home_instances']) && is_array($rules['rules_home_instances']) && (in_array($wpInsertPostInstance, $rules['rules_home_instances']))) {
				return false;
			}
			break;
		case 'ARCHIVE':
			if(isset($rules['rules_exclude_archives']) && wp_validate_boolean($rules['rules_exclude_archives']) ) {
				return false;
			} else if(isset($rules['rules_archives_instances']) && is_array($rules['rules_archives_instances']) && (in_array($wpInsertPostInstance, $rules['rules_archives_instances']))) {
				return false;
			}
			break;
		case 'SEARCH':
			if(isset($rules['rules_exclude_search']) && wp_validate_boolean($rules['rules_exclude_search']) ) {
				return false;
			} else if(isset($rules['rules_search_instances']) && is_array($rules['rules_search_instances']) && (in_array($wpInsertPostInstance, $rules['rules_search_instances']))) {
				return false;
			}
			break;
		case 'PAGE':
			if(isset($rules['rules_exclude_page']) && wp_validate_boolean($rules['rules_exclude_page']) ) {
				if((!isset($rules['rules_page_exceptions'])) || (!is_array($rules['rules_page_exceptions'])) || (!in_array($page_details['ID'], $rules['rules_page_exceptions']))) {
					return false;
				}
			} else if(isset($rules['rules_page_exceptions']) && is_array($rules['rules_page_exceptions']) && (in_array($page_details['ID'], $rules['rules_page_exceptions']))) {
				return false;
			}
			break;
		case 'POST':
			if(isset($rules['rules_exclude_post']) && wp_validate_boolean($rules['rules_exclude_post']) ) {
				if((!isset($rules['rules_post_exceptions'])) || (!is_array($rules['rules_post_exceptions'])) || (!in_array($page_details['ID'], $rules['rules_post_exceptions']))) {
					return false;
				} else if ((!isset($rules['rules_post_categories_exceptions'])) || (!is_array($rules['rules_post_categories_exceptions'])) || (!isset($page_details['categories'])) || (!is_array($page_details['categories'])) || (!(count(array_intersect($page_details['categories'], $rules['rules_post_categories_exceptions'])) > 0))) {
					return false;
				}
			} else if(isset($rules['rules_post_exceptions']) && is_array($rules['rules_post_exceptions']) && (in_array($page_details['ID'], $rules['rules_post_exceptions']))) {
				return false;
			} else if(isset($rules['rules_post_categories_exceptions']) && isset($page_details['categories']) && is_array($rules['rules_post_categories_exceptions']) && is_array($page_details['categories']) && (count(array_intersect($page_details['categories'], $rules['rules_post_categories_exceptions'])) > 0)) {
				return false;
			}
			break;
		case 'CATEGORY':
			if(isset($rules['rules_exclude_categories']) && wp_validate_boolean($rules['rules_exclude_categories'])) {
				if((!isset($rules['rules_categories_exceptions'])) || (!is_array($rules['rules_categories_exceptions'])) || (!in_array($page_details['ID'], $rules['rules_categories_exceptions']))) {
					return false;
				}
			} else if(isset($rules['rules_categories_exceptions']) && is_array($rules['rules_categories_exceptions']) && (in_array($page_details['ID'], $rules['rules_categories_exceptions']))) {
				return false;
			} else if(isset($rules['rules_categories_instances']) && is_array($rules['rules_categories_instances']) && (in_array($wpInsertPostInstance, $rules['rules_categories_instances']))) {
				return false;
			}
			break;
		case '404':
			if(isset($rules['rules_exclude_404']) &&  wp_validate_boolean($rules['rules_exclude_404'])) {
				return false;
			}
			break;
		case 'CUSTOM':
			if(isset($rules['rules_exclude_cpt_'.$page_details['type_name']]) &&  wp_validate_boolean($rules['rules_exclude_cpt_'.$page_details['type_name']])) {
				return false;
			}
			break;
	}
	return true;
}
/* End Get Ad Status */
?>