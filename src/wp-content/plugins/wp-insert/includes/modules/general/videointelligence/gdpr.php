<?php
/* Begin Consent Popup */
add_action('init', 'wp_insert_vi_gdpr_popup_init');
function wp_insert_vi_gdpr_popup_init() {
	$vicodeSettings = get_option('wp_insert_vi_code_settings');
	if(isset($vicodeSettings['show_gdpr_authorization']) && wp_validate_boolean($vicodeSettings['show_gdpr_authorization']) ) {
		add_action('wp_enqueue_scripts', 'wp_insert_vi_gdpr_popup_wp_enqueue_style');
		add_action('wp_enqueue_scripts', 'wp_insert_vi_gdpr_popup_wp_enqueue_scripts');
		add_action('wp_footer', 'wp_insert_vi_gdpr_popup_wp_footer');
	}
}

function wp_insert_vi_gdpr_popup_wp_enqueue_style() {
	wp_enqueue_style('wp_insert_vi_gdpr', WP_INSERT_URL.'includes/modules/general/videointelligence/css/gdpr.css', array(), WP_INSERT_VERSION , 'all');
}


function wp_insert_vi_gdpr_popup_wp_enqueue_scripts() {
	wp_enqueue_script('wp_insert_vi_gdpr', WP_INSERT_URL.'includes/modules/general/videointelligence/js/gdpr.js', array(), WP_INSERT_VERSION , true);
}

function wp_insert_vi_gdpr_popup_wp_footer() {
	$showViConsent = true;
	$isEU = wp_insert_vi_api_is_eu();
	if(isset($_COOKIE['Viconsent'])) {
		$showViConsent = false;
	}
	$vicodeSettings = get_option('wp_insert_vi_code_settings');
	$labels = array();
	$viConsentPopupContent = wp_insert_vi_api_get_consent_popup_content();
	if($viConsentPopupContent != false) {
		switch($vicodeSettings['language']) {
			case 'de-de':
				$labels['popupContent'] = $viConsentPopupContent->es;
				$labels['accept'] = 'acepto';
				$labels['donotaccept'] = 'no acepto';
				$labels['showPurposes'] = 'Mostrar propósitos';
				$labels['showVendors'] = 'Mostrar vendedores';	
				
				break;
			case 'fr-fr':
				$labels['popupContent'] = $viConsentPopupContent->fr;
				$labels['accept'] = 'J’accepte';
				$labels['donotaccept'] = 'Je n’accepte pas';
				$labels['showPurposes'] = 'Plus de details';
				$labels['showVendors'] = 'Montrez les vendeurs';	
				break;
			case 'en-us':
			default:
				$labels['popupContent'] = $viConsentPopupContent->en;
				$labels['accept'] = 'I accept';
				$labels['donotaccept'] = 'I do not accept';
				$labels['showPurposes'] = 'Show purposes';
				$labels['showVendors'] = 'Show vendors';				
				break;
		}
	}
	
	echo '<div id="wp_insert_vi_consent_popup_wrapper" style="display: none;">';
		echo $labels['popupContent'];
		echo '<div id="wp_insert_vi_consent_popup_actions_wrapper">';
			echo '<input id="wp_insert_vi_consent_popup_disagree_btn" type="button" value="'.$labels['donotaccept'].'" onclick="wp_insert_vi_consent_popup_disagree()" />';
			echo '<input id="wp_insert_vi_consent_popup_agree_btn"  type="button" value="'.$labels['accept'].'" onclick="wp_insert_vi_consent_popup_agree()" />';
		echo '</div>';
		echo '<div id="wp_insert_vi_consent_popup_links_wrapper">';
			echo '<a href="https://www.vi.ai/purposes" target="_blank">'.$labels['showPurposes'].'</a>';
			echo '<a href="https://www.vi.ai/vendors" target="_blank">'.$labels['showVendors'].'</a>';
		echo '</div>';
		echo '<input id="wp_insert_vi_consent_popup_is_eu" type="hidden" value="'.$isEU.'" />';
		echo '<input id="wp_insert_vi_consent_popup_url" type="hidden" value="'.get_bloginfo('url').'" />';
		echo '<input id="wp_insert_vi_consent_popup_auth" type="hidden" value="'.wp_create_nonce('wp_insert_vi_consent').'" />';
		echo '<input id="wp_insert_vi_consent_popup_vendor_list_version" type="hidden" value="'.wp_insert_vi_api_get_vendor_list_version().'" />';
		$purposesBinary = '000000000000000000000000';
		$purposes = wp_insert_vi_api_get_consent_purposes();
		if(isset($purposes) && (count($purposes) > 0)) {
			foreach($purposes as $purpose) {
				$purposesBinary = substr_replace($purposesBinary, '1', ((24 - (int)$purpose->id) + 1), 1);
			}
		}
		echo '<input id="wp_insert_vi_consent_popup_vendor_list_purposes" type="hidden" value="'.$purposesBinary.'" />';
		echo '<input id="wp_insert_vi_consent_popup_vendor_list_vendors" type="hidden" value="999" />';
	echo '</div>';
	echo '<div id="wp_insert_vi_consent_popup_overlay" style="display: none;"></div>';
	echo '<span id="wp_insert_vi_consent_popup_settings_button" onclick="wp_insert_vi_consent_popup_settings()" unselectable="on" style="display: none;">Privacy settings</span>';
}
/* End Consent Popup */

/* Begin Data Storage */
add_action('init', 'wp_insert_vi_gdpr_data_init');
function wp_insert_vi_gdpr_data_init() {
	if(isset($_GET['wp_insert_vi_consent']) && ($_GET['wp_insert_vi_consent'] != '')) {
		check_ajax_referer('wp_insert_vi_consent', 'wp_insert_vi_consent');
		wp_insert_post(array(
		  'post_title'    => date('c'),
		  'post_content'  => ((isset($_COOKIE['Viconsent']))?$_COOKIE['Viconsent']:''),
		  'post_status'   => 'publish',
		  'post_type'   => 'viconsent',
		));
		die();
	}		
	
	
    $labels = array( 
        'name' => 'VI Consent',
        'singular_name' => 'VI Consent',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New VI Consent',
        'edit_item' => 'Edit VI Consent',
        'new_item' => 'New VI Consent',
        'view_item' => 'View VI Consent',
        'search_items' => 'Search VI Consent',
        'not_found' => 'No VI Consent found',
        'not_found_in_trash' => 'No VI Consent found in Trash',
        'parent_item_colon' => 'Parent VI Consent:',
        'menu_name' => 'VI Consent',
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'VI Consent',
        'supports' => array('title', 'editor'),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_nav_menus' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => false,
        'can_export' => true,
        'rewrite' => false,
        'capability_type' => 'post'
    );

    register_post_type('viconsent', $args);
}

add_filter('user_can_richedit', 'wp_insert_vi_gdpr_data_user_can_richedit');
function wp_insert_vi_gdpr_data_user_can_richedit($default) {
  if(get_post_type() === 'viconsent') {
	  return false;
  }
  return $default;
}
/* End Data Storage */
?>