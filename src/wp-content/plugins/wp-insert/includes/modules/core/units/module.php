<?php
/* Begin Include Files */
require_once(dirname(__FILE__).'/adunit.php');
require_once(dirname(__FILE__).'/adcode.php');
require_once(dirname(__FILE__).'/device-styles.php');
require_once(dirname(__FILE__).'/geo-targeting.php');
require_once(dirname(__FILE__).'/notes.php');
require_once(dirname(__FILE__).'/rules.php');
/* End Include Files */

/* Begin Add Assets */
add_action('wp_insert_modules_js', 'wp_insert_module_adform_js', 0);
function wp_insert_module_adform_js() {
	wp_register_script('wp-insert-module-adform-js', WP_INSERT_URL.'includes/modules/core/units/js/module.js', array('wp-insert-js'), WP_INSERT_VERSION.((WP_INSERT_DEBUG)?rand(0,9999):''));
	wp_enqueue_script('wp-insert-module-adform-js');
}
/* End Add Assets */

/* Begin Get Admin Panel Card*/
function wp_insert_get_plugin_card($title, $description, $type, $preTitle) {
	echo '<div class="plugin-card">';
		echo '<div class="plugin-card-top">';
			echo '<h4>'.$title.'</h4>';
			echo $description;
		echo '</div>';
		echo '<div class="plugin-card-bottom">';
			$data = get_option('wp_insert_'.$type);
			if(isset($data) && is_array($data)) {
				foreach($data as $key => $value) {
					/* Begin Workaround for migrating old users to new system (can be removed in a later version) */
					$title = $key;
					if(!isset($value['title']) || ($value['title'] == '')) {
						switch($key) {
							case 'above':
								$title = 'Above Post Content';
								break;
							case 'middle':
								$title = 'Middle of Post Content';
								break;
							case 'below':
								$title = 'Below Post Content';
								break;
							case 'left':
								$title = 'To the Left of Post Content';
								break;
							case 'right':
								$title = 'To the Right of Post Content';
								break;
						}
					} else {
						$title = $value['title'];
					}
					/* End Workaround for migrating old users to new system (can be removed in a later version) */
					echo '<p>';
						echo '<a class="wp_insert_ad_unit_title" title="Edit Ad Unit" id="wp_insert_'.$type.'_ad_'.$key.'" href="javascript:;" data-pre-title="'.$preTitle.'" onclick="wp_insert_ads_click_handler(\''.$type.'\', \''.$key.'\', \''.$title.'\', false)">'.$preTitle.' : '.$title.'</a>';
						echo '<span class="dashicons dashicons-no wp_insert_delete_icon" title="Delete Ad Unit" onclick="wp_insert_ad_delete_handler(\''.$type.'\', \''.$key.'\')"></span>';
						echo '<span class="dashicons dashicons-format-gallery wp_insert_duplicate_icon" title="Duplicate Ad Unit" onclick="wp_insert_ads_click_handler(\''.$type.'\', \'###DUPLICATE###'.$key.'\', \''.$title.' Duplicate\', true)"></span>';						
					echo '</p>';
				}
			}				
			echo '<p style="text-align: center; padding: 20px 0 10px;"><a id="wp_insert_'.$type.'_ad_new" data-pre-title="'.$preTitle.'" href="#" class="button-secondary" onclick="wp_insert_ads_click_handler(\''.$type.'\', \'new\', \'Add New\', true)">Add New</a></p>';
		echo '</div>';
	echo '</div>';
}
/* End Get Admin Panel Card*/

/* Begin Get Ad Form */
function wp_insert_get_ad_form($script = '') {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	if(isset($_POST['wp_insert_identifier']) && isset($_POST['wp_insert_type'])) {
		$type = $_POST['wp_insert_type'];
		$data = get_option('wp_insert_'.$type);
		
		$identifier = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 5)), 0, 5).uniqid();
		$dataIdentifier = $identifier;
		if(strpos($_POST['wp_insert_identifier'], '###DUPLICATE###') !== false) {
			$dataIdentifier = str_replace('###DUPLICATE###', '', $_POST['wp_insert_identifier']);
			$data[$dataIdentifier]['title'] = $data[$dataIdentifier]['title'].' (Duplicate)';
		} else if($_POST['wp_insert_identifier'] != 'new') {
			$identifier = $_POST['wp_insert_identifier'];
			$dataIdentifier = $identifier;
		}
		
		echo '<div class="wp_insert_popup_content_wrapper">';
			$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_'.$type.'['.$identifier.']', 'values' => $data[$dataIdentifier]));
			$control->add_control(array('type' => 'ipCheckbox', 'className' => 'wp_insert_'.$type.'_status', 'optionName' => 'status'));
			$control->add_control(array('type' => 'hidden', 'className' => 'wp_insert_'.$type.'_identifier', 'optionName' => 'identifier', 'value' => $identifier));
			echo $control->HTML;
			$control->clear_controls();
			echo '<div id="wp_insert_'.$type.'_'.$identifier.'_accordion">';
				$control = apply_filters('wp_insert_'.$type.'_form_accordion_tabs', $control, $identifier, $type);
			echo '</div>';
			echo '<script type="text/javascript">';
				echo $control->JS;
				echo 'jQuery("#wp_insert_'.$type.'_'.$identifier.'_accordion").accordion({ icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }, heightStyle: "auto" });';
				if($script != '') {
					echo str_replace('###IDENTIFIER###', $identifier, $script);
				}
			echo '</script>';
		echo '</div>';
	}
	die();
}
/* End Get Ad Form */

/* Begin Save Ad Data */
function wp_insert_save_ad_data() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	if((isset($_POST['wp_insert_identifier']) && ($_POST['wp_insert_identifier'] != '')) && (isset($_POST['wp_insert_type'])&& ($_POST['wp_insert_type'] != '')) && (isset($_POST['wp_insert_parameters']) && (is_array($_POST['wp_insert_parameters'])))) {
		$type = $_POST['wp_insert_type'];
		$parameters = $_POST['wp_insert_parameters'];		
		$data =  get_option('wp_insert_'.$type);
		foreach($parameters as $parameter) {
			$data[$_POST['wp_insert_identifier']][str_replace(array('wp_insert_', $type.'_', $_POST['wp_insert_identifier'].'_'), '', $parameter)] = ((isset($_POST[$parameter]))?$_POST[$parameter]:'');
		}
		echo '<pre>'; print_r($data); echo '</pre>';
		
		update_option('wp_insert_'.$type, $data);
		
		if(function_exists('wp_insert_adstxt_adsense_admin_notice_reset')) {
			wp_insert_adstxt_adsense_admin_notice_reset();
		}
	}
	die();
}
/* End Save Ad Data */

/* Begin Delete Ad Data */
function wp_insert_delete_ad_data() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	if((isset($_POST['wp_insert_identifier']) && ($_POST['wp_insert_identifier'] != '')) && (isset($_POST['wp_insert_type'])&& ($_POST['wp_insert_type'] != ''))) {
		$type = $_POST['wp_insert_type'];
		$data =  get_option('wp_insert_'.$type);
		unset($data[$_POST['wp_insert_identifier']]);
		update_option('wp_insert_'.$type, $data);
		
		if(function_exists('wp_insert_adstxt_adsense_admin_notice_reset')) {
			wp_insert_adstxt_adsense_admin_notice_reset();
		}
	}
	die();
}
/* End Delete Ad Data */
?>