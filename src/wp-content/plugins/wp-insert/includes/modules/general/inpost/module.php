<?php
/* Begin Add Assets */
add_action('wp_insert_modules_js', 'wp_insert_module_inpostads_js', 0);
function wp_insert_module_inpostads_js() {
	wp_register_script('wp-insert-module-inpostads-js', WP_INSERT_URL.'includes/modules/general/inpost/js/module.js', array('wp-insert-js'), WP_INSERT_VERSION.((WP_INSERT_DEBUG)?rand(0,9999):''));
	wp_enqueue_script('wp-insert-module-inpostads-js');
}
/* End Add Assets */

/* Begin UI Functions */
add_action('wp_insert_plugin_card', 'wp_insert_inpostads_plugin_card', 20);
function wp_insert_inpostads_plugin_card() {
	wp_insert_get_plugin_card(
		'In-Post Ads',
		'<p>Ads shown within the post content.<br />You can choose different locations to insert ads from Above /  Below / Inside / Middle Of / To the Left / To the Right of post content</p>',
		'inpostads',
		'In-Post Ad'
	);
}

add_action('wp_ajax_wp_insert_inpostads_get_ad_form', 'wp_insert_inpostads_get_ad_form');
function wp_insert_inpostads_get_ad_form() {
	wp_insert_get_ad_form('wp_insert_inpostads_primary_ad_code_type_change("###IDENTIFIER###"); wp_insert_inpostads_primary_ad_code_location_change_action("###IDENTIFIER###"); wp_insert_inpostads_vi_customize_adcode();');
}
add_action('wp_ajax_wp_insert_inpostads_save_ad_data', 'wp_insert_save_ad_data');
add_action('wp_ajax_wp_insert_inpostads_delete_ad_data', 'wp_insert_delete_ad_data');

add_filter('wp_insert_inpostads_form_accordion_tabs', 'wp_insert_inpostads_form_accordion_tabs_location', 10, 3);
function wp_insert_inpostads_form_accordion_tabs_location($control, $identifier, $location) {
	echo '<h3>Location</h3>';
	echo '<div>';
		$paragraphPositioningOptions = array(
			array('text' => '1st', 'value' => '1'),
			array('text' => '2nd', 'value' => '2'),
			array('text' => '3rd', 'value' => '3'),
			array('text' => '4th', 'value' => '4'),
			array('text' => '5th', 'value' => '5'),
			array('text' => '6th', 'value' => '6'),
			array('text' => '7th', 'value' => '7'),
			array('text' => '8th', 'value' => '8'),
			array('text' => '9th', 'value' => '9'),
			array('text' => '10th', 'value' => '10'),
		);
		$control->add_control(array('type' => 'select', 'className' => 'input', 'style' => 'display: inline;', 'useParagraph' => false, 'optionName' => 'paragraphtopposition', 'options' => $paragraphPositioningOptions));
		$nthParagraphTopControl = $control->HTML;
		$control->clear_controls();
		$control->add_control(array('type' => 'select', 'className' => 'input', 'style' => 'display: inline;', 'useParagraph' => false, 'optionName' => 'paragraphbottomposition', 'options' => $paragraphPositioningOptions));
		$nthParagraphBottomControl = $control->HTML;
		$control->clear_controls();
		
		$location = '';
		if(!isset($control->values['location'])) {
			switch($identifier) {
				case 'above':
					$location = 'above';
					break;
				case 'middle':
					$location = 'middle';
					break;
				case 'below':
					$location = 'below';
					break;
				case 'left':
					$location = 'left';
					break;
				case 'right':
					$location = 'right';
					break;
				default:
					$location = 'above';
					break;
			}
		} else {
			$location = $control->values['location'];
		}
		$locations = array(
			array('text' => 'Above Post Content', 'value' => 'above'),
			array('text' => 'Middle of Post Content', 'value' => 'middle'),
			array('text' => 'Below Post Content', 'value' => 'below'),
			array('text' => 'To the Left of Post Content', 'value' => 'left'),
			array('text' => 'To the Right of Post Content', 'value' => 'right'),
			array('text' => 'After '.$nthParagraphTopControl.' Paragraph in Post Content (From the Top)', 'value' => 'paragraphtop'),
			array('text' => 'After '.$nthParagraphBottomControl.' Paragraph in Post Content (From the Bottom)', 'value' => 'paragraphbottom'),
		);
		$control->add_control(array('type' => 'radio-group', 'style' => 'line-height: 40px; margin-top: 3px;', 'optionName' => 'location', 'options' => $locations, 'value' => $location));
		$control->create_section('Location');
		echo $control->HTML;
		$control->clear_controls();
	echo '</div>';
	return $control;
}
add_filter('wp_insert_inpostads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_adcode', 20, 3);
add_filter('wp_insert_inpostads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_rules', 30, 3);
add_filter('wp_insert_inpostads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_geo_targeting', 40, 3);
add_filter('wp_insert_inpostads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_devices_styles', 50, 3);
add_filter('wp_insert_inpostads_form_accordion_tabs', 'wp_insert_form_accordion_tabs_notes', 60, 3);
add_filter('wp_insert_inpostads_form_accordion_tabs', 'wp_insert_inpostads_form_accordion_tabs_manual_override', 70, 3);
add_filter('wp_insert_inpostads_form_accordion_tabs', 'wp_insert_inpostads_form_accordion_tabs_positioning', 80, 3);

function wp_insert_inpostads_form_accordion_tabs_manual_override($control, $identifier, $location) {
	echo '<h3 class="wp_insert_inpostads_location_manual_override_panel">Manual Override</h3>';
	echo '<div>';
		$control->set_HTML('<p class="codeSnippet"><code>[wpinsertinpostad id="'.$identifier.'"]</code></p><p>For those extreme cases when auto positioning just doesnt work out the way you want, use the shortcode above to precisely position your ad unit inside your post content.<br>Gutenberg users can utilize "Wp-Insert" blocks for manual positioning within post content.</p>');
		$control->create_section('Code to add to your post/page content');
		echo $control->HTML;
		$control->clear_controls();
	echo '</div>';
	return $control;
}

function wp_insert_inpostads_form_accordion_tabs_positioning($control, $identifier, $location) {
	echo '<h3 class="wp_insert_inpostads_location_middle_panel">Positioning</h3>';
	echo '<div>';
		$control->add_control(array('type' => 'text', 'label' => 'Minimum Character Count', 'optionName' => 'minimum_character_count', 'helpText' => 'Show the ad only if the Content meets the minimum character count. If this parameter is set to 0 (or empty) minimum character count check will be deactivated.'));
		$control->add_control(array('type' => 'text', 'label' => 'Paragraph Buffer Count', 'optionName' => 'paragraph_buffer_count', 'helpText' => 'Shows the ad after X number of Paragraphs. If this parameter is set to 0 (or empty) the ad will appear in the middle of the content.'));
		$control->create_section('Positioning');
		echo $control->HTML;
		$control->clear_controls();
	echo '</div>';
	return $control;
}
/* End UI Functions */

/* Begin In-Post Ads Ad Insertion */
add_filter('the_content', 'wp_insert_inpostads_the_content', 100);
function wp_insert_inpostads_the_content($content) {
	global $post;
	if(!is_feed() && is_main_query()) { 
		$inpostads = get_option('wp_insert_inpostads');
		if(isset($inpostads) && is_array($inpostads)) {
			$shortcodeInsertions = array();
			if(preg_match_all('/'.get_shortcode_regex().'/s', $post->post_content, $matches) && array_key_exists(2, $matches) && in_array('wpinsertinpostad', $matches[2])) {
				foreach($matches[2] as $key => $value) {
					if('wpinsertinpostad' === $value) {
						$shortcodeAtts = shortcode_parse_atts($matches[3][$key]);
						if(isset($shortcodeAtts) && isset($shortcodeAtts['id']) && ($shortcodeAtts['id'] != '')) {
							$shortcodeInsertions[] = $shortcodeAtts['id'];
						}
					}
				}
			}
			
			$paragraphCount = wp_insert_inpostads_get_paragraph_count($content);
			foreach($inpostads as $key => $inpostad) {
				if(!in_array($key, $shortcodeInsertions)) {
					if(!isset($inpostad['location'])) { //Get the location value from the key for old users who doesnt have a location saved.
						switch($key) {
							case 'above':
								$inpostad['location'] = 'above';
								break;
							case 'middle':
								$inpostad['location'] = 'middle';
								break;
							case 'below':
								$inpostad['location'] = 'below';
								break;
							case 'left':
								$inpostad['location'] = 'left';
								break;
							case 'right':
								$inpostad['location'] = 'right';
								break;
							default:
								$inpostad['location'] = 'above';
								break;
						}
					}
					
					if(wp_insert_get_ad_status($inpostad)) {
						switch($inpostad['location']) {
							case 'above':
								$content = wp_insert_get_ad_unit($inpostad).$content;
								break;
							case 'middle':
								if($paragraphCount > 1) {
									if(($inpostad['paragraph_buffer_count'] == 0) || ($inpostad['paragraph_buffer_count'] == '')) {
										$position = wp_insert_inpostads_get_insertion_position('/p>', $content, round($paragraphCount / 2));
									} else {			
										$position = wp_insert_inpostads_get_insertion_position('/p>', $content, $inpostad['paragraph_buffer_count']);
									}
									if($position) {
										if(($inpostad['minimum_character_count'] == 0) || ($inpostad['minimum_character_count'] == '')) {
											$content = substr_replace($content, '/p>'.wp_insert_get_ad_unit($inpostad), $position, 3);
										} else {
											if(strlen(strip_tags($content)) > $inpostad['minimum_character_count']) {
												$content = substr_replace($content, '/p>'.wp_insert_get_ad_unit($inpostad), $position, 3);
											}
										}
									}
								}
								break;
							case 'below':
								$content = $content.wp_insert_get_ad_unit($inpostad);
								break;
							case 'left':
								$content = wp_insert_get_ad_unit($inpostad, 'float: left;').$content;
								break;
							case 'right':
								$content = wp_insert_get_ad_unit($inpostad, 'float: right;').$content;
								break;
							case 'paragraphtop':
								if($paragraphCount > 1) {
									$position = wp_insert_inpostads_get_insertion_position('/p>', $content, $inpostad['paragraphtopposition']);
									if($position) {
										$content = substr_replace($content, '/p>'.wp_insert_get_ad_unit($inpostad), $position, 3);
									}
								}
								break;
							case 'paragraphbottom':
								if($paragraphCount > 1) {
									$paragraphbottomposition = ($paragraphCount - (int)$inpostad['paragraphbottomposition']);
									if(($paragraphbottomposition > 0) && ($paragraphbottomposition < $paragraphCount)) {
										$position = wp_insert_inpostads_get_insertion_position('/p>', $content, $paragraphbottomposition);
										if($position) {
											$content = substr_replace($content, '/p>'.wp_insert_get_ad_unit($inpostad), $position, 3);
										}
									}
								}
								break;
						}
					}
				}
			}
		}
	}
	return $content;
}

function wp_insert_inpostads_get_paragraph_count($content) {
	$paragraphs = explode('/p>', $content);
	$paragraphCount = 0;
	if(is_array($paragraphs)) {
		foreach($paragraphs as $paragraph) {
			if(strlen($paragraph) > 1) {
				$paragraphCount++;
			}
		}
	}
	return $paragraphCount;
}

function wp_insert_inpostads_get_insertion_position($search, $string, $offset) {
    $arr = explode($search, $string);
    switch($offset) {
        case $offset == 0:
			return false;
			break;
        case $offset > max(array_keys($arr)):
			return false;
			break;
        default:
			return strlen(implode($search, array_slice($arr, 0, $offset)));
			break;
    }
}
/* End In-Post Ads Ad Insertion */

/* Begin In-Post Ads Shortcode Ad Insertion */
add_shortcode('wpinsertinpostad', 'wp_insert_inpostads_get_shortcode');
function wp_insert_inpostads_get_shortcode($atts) {
	$atts = shortcode_atts(array('id' => ''), $atts, 'wpinsertinpostad');
	if(isset($atts['id']) && ($atts['id'] != '')) {
		$adunit = get_option('wp_insert_inpostads');
		if(isset($adunit) && isset($adunit[$atts['id']]) && is_array($adunit[$atts['id']]) && wp_insert_get_ad_status($adunit[$atts['id']])) {
			return wp_insert_get_ad_unit($adunit[$atts['id']]);
		}
	}
}
/* End In-Post Ads Shortcode Ad Insertion */
?>