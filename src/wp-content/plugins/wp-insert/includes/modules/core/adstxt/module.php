<?php
require_once(dirname(__FILE__).'/adsense.php');

/* Begin Add Assets */
add_action('wp_insert_modules_js', 'wp_insert_module_adstxt_js', 0);
function wp_insert_module_adstxt_js() {
	wp_register_script('wp-insert-module-adstxt-js', WP_INSERT_URL.'includes/modules/core/adstxt/js/module.js', array('wp-insert-js'), WP_INSERT_VERSION.((WP_INSERT_DEBUG)?rand(0,9999):''));
	wp_enqueue_script('wp-insert-module-adstxt-js');
}
/* End Add Assets */

/* Begin Add Card in Admin Panel */
add_action('wp_insert_plugin_card', 'wp_insert_adstxt_plugin_card', 100);
function wp_insert_adstxt_plugin_card() {
	echo '<div class="plugin-card adstxt-card">';
		echo '<div class="plugin-card-top">';
			echo '<h4>Authorized Digital Sellers / ads.txt</h4>';
			echo '<p>Authorized Digital Sellers, or ads.txt, is an <a href="https://iabtechlab.com/">IAB</a> initiative to improve transparency in programmatic advertising.</p>';
			echo '<p>You can easily manage your ads.txt from within Wp-Insert, providing confidence to brands they are buying authentic publisher inventory, protect you from counterfiet inventory and might even lead to higher monetization for your ad invertory.</p>';
		echo '</div>';
		echo '<div class="plugin-card-bottom">';
			if(wp_insert_adstxt_file_exists()) {
				echo '<a id="wp_insert_adstxt_generate" href="javascript:;" class="button button-primary">Modify ads.txt</a>';
			} else {
				echo '<a id="wp_insert_adstxt_generate" href="javascript:;" class="button button-primary">Generate ads.txt</a>';
			}
		echo '</div>';
	echo '</div>';
}
/* End Add Card in Admin Panel */

/* Begin Create Ads.txt */
add_action('wp_ajax_wp_insert_adstxt_generate_form_get_content', 'wp_insert_adstxt_generate_form_get_content');
function wp_insert_adstxt_generate_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	echo '<div class="wp_insert_popup_content_wrapper">';
		echo '<div id="wp_insert_adstxt_accordion">';
			$control = new smartlogixControls();
			echo '<h3>ads.txt Content</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'textarea', 'id' => 'wp_insert_adstxt_content', 'name' => 'wp_insert_adstxt_content', 'style' => 'height: 220px;', 'value' => wp_insert_adstxt_get_content(), 'helpText' => 'You can directly edit the entries here or you can use the entry generator below to quickly create new entries'));
				$control->create_section('ads.txt Content');
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Entry Generator</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'text', 'id' => 'wp_insert_adstxt_new_entry_domain', 'name' => 'wp_insert_adstxt_new_entry_domain', 'label' => 'Domain name of the advertising system <small style="font-size: 10px;">(Required)</small>', 'value' => '', 'helpText' => 'For Google Adsense Use "google.com"; for other networks, contact your service provider for values.'));
				$control->add_control(array('type' => 'text', 'id' => 'wp_insert_adstxt_new_entry_pid', 'name' => 'wp_insert_adstxt_new_entry_pid', 'label' => 'Publisher’s Account ID <small style="font-size: 10px;">(Required)</small>', 'value' => '', 'helpText' => 'For Google Adsense Use your Publisher ID "pub-xxxxxxxxxxxxxxxx"; for other networks, contact your service provider for values.'));
				$control->add_control(array('type' => 'select', 'id' => 'wp_insert_adstxt_new_entry_type', 'name' => 'wp_insert_adstxt_new_entry_type', 'label' => 'Type of Account / Relationship <small style="font-size: 10px;">(Required)</small>', 'value' => '', 'options' => array(array('text' => 'Direct', 'value' => 'DIRECT'), array('text' => 'Reseller', 'value' => 'RESELLER')), 'helpText' => 'For Google Adsense select "Reseller"; for other networks, contact your service provider for values.'));
				$control->add_control(array('type' => 'text', 'id' => 'wp_insert_adstxt_new_entry_certauthority', 'name' => 'wp_insert_adstxt_new_entry_certauthority', 'label' => 'Certification Authority ID', 'value' => '', 'helpText' => 'Contact your service provider for values.'));
				$control->HTML .= '<p><input id="wp_insert_adstxt_add_entry" onclick="wp_insert_adstxt_add_entry()" type="button" value="Add Entry" class="button button-primary" /></p>';
				$control->create_section('Entry Generator');
				echo $control->HTML;
			echo '</div>';
		echo '</div>';
		echo '<script type="text/javascript">';
		echo $control->JS;
		echo 'jQuery("#wp_insert_adstxt_accordion").accordion({ icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }, heightStyle: "fill" });';
		//echo 'jQuery(".ui-dialog-buttonset").find("button").first().remove();';
		echo '</script>';
	echo '</div>';
	die();
}
/* End Create Ads.txt */

/* Begin Update Ads.txt */
add_action('wp_ajax_wp_insert_adstxt_generate_form_save_action', 'wp_insert_adstxt_generate_form_save_action');
function wp_insert_adstxt_generate_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	$content = ((isset($_POST['wp_insert_adstxt_content']))?$_POST['wp_insert_adstxt_content']:'');
	$output = wp_insert_adstxt_updation_failed_message($content);
	$output .= '<script type="text/javascript">';
		$output .= 'jQuery(".ui-dialog-buttonset").find("button").first().hide();';
	$output .= '</script>';

	if(wp_insert_adstxt_update_content($content)) {
		echo '###SUCCESS###';
	} else {
		echo $output;
	}
	die();
}
/* End Update Ads.txt */

/* Begin Common Functions */
function wp_insert_adstxt_file_exists() {
	if(file_exists(trailingslashit(get_home_path()).'ads.txt')) {
		return true;
	}
	return false;
}

function wp_insert_adstxt_get_content() {
	if(wp_insert_adstxt_file_exists()) {
		return @file_get_contents(trailingslashit(get_home_path()).'ads.txt');
	}
	return '';
}

function wp_insert_adstxt_update_content($content) {
	wp_insert_adstxt_adsense_admin_notice_reset();
	if(get_filesystem_method() === 'direct') {
		$creds = request_filesystem_credentials(site_url().'/wp-admin/', '', false, false, array());
		if(!WP_Filesystem($creds)) {
			return false;
		}
		global $wp_filesystem;
		if(!$wp_filesystem->put_contents(trailingslashit(get_home_path()).'ads.txt', $content, FS_CHMOD_FILE)) {
			return false;
		}
	} else {
		return false;
	}
	return true;
}

function wp_insert_adstxt_updation_failed_message($content) {
	$output = '<div class="wp_insert_popup_content_wrapper">';
		$output .= '<p>Auto Creation / Updation of ads.txt failed due to access permission restrictions on the server.</p>';
		$output .= '<p>You have to manually upload the file using your Host\'s File manager or your favourite FTP program</p>';
		$output .= '<p>ads.txt should be located in the root of your server. After manually uploading the file click <a href="'.site_url().'/ads.txt">here</a> to check if its accessible from the correct location</p>';
		$output .= '<textarea style="display: none;" id="wp_insert_adstxt_content">'.$content.'</textarea>';
		$output .= '<p><a onclick="wp_insert_adstxt_content_download()" class="button button-primary" href="javascript:;">Download ads.txt</a></p>';
	$output .= '</div>';
	return $output;
}
/* End Common Functions */
?>