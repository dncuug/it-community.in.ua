<?php
/* Begin Privacy Policy */
add_action('wp_ajax_wp_insert_legalpages_privacy_policy_form_get_content', 'wp_insert_legalpages_privacy_policy_form_get_content');
function wp_insert_legalpages_privacy_policy_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	
	$legalPages = get_option('wp_insert_legalpages');
	echo '<div class="wp_insert_popup_content_wrapper">';
		if(!(isset($legalPages['privacy_policy']['content']) && ($legalPages['privacy_policy']['content'] != ''))) {
			$legalPages['privacy_policy']['content'] = wp_insert_legalpages_get_default_data('privacy_policy');
		}
		$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_legalpages[privacy_policy]', 'values' => $legalPages['privacy_policy']));
		echo '<div id="wp_insert_legalpages_privacy_policy_accordion">';
			echo '<h3>Disclaimer</h3>';
			echo '<div>';
				echo '<p><b>By using this feature, you agree to this disclaimer.</b></p>';
				echo '<p>These templates are provided to you to understand your obligations better, but they are NOT meant to constitute client-attorney relationship or personalized legal advice.</p>';
				echo '<p>The developer is not eligible for any claim or action based on any information or functionality provided by this plugin.</p>';
				echo '<p>We expressly disclaim all liability in respect of usage of this plugin or its features.</p>';
				echo '<p>This plugin gives you general information and tools, but is NOT meant to serve as complete compliance package.</p>';
				echo '<p>As each business and situation is unique, you might need to modify, add or delete information in these templates.</p>';
				echo '<p>This information is provided just to get you started.</p>';
			echo '</div>';
			echo '<h3>Content</h3>';
			echo '<div style="max-height: 320px;">'; 
				$control->add_control(array('type' => 'textarea-wysiwyg', 'style' => 'height: 220px;', 'optionName' => 'content'));
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Assign Pages(s)</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'pages-select', 'optionName' => 'assigned_page'));
				$control->create_section('Assign a Page');
				echo $control->HTML;
				echo '<p class="wp_insert_OR">OR</p>';
				$control->set_HTML('<input type="button" id="wp_insert_legalpages_privacy_policy_generate_page" value="Click to Generate" class="input button-secondary wp_insert_generate_page_button" onclick="wp_insert_legalpages_generate_page(\'wp_insert_legalpages_privacy_policy\', \'Privacy Policy\')" /><div class="wp_insert_ajaxloader_flat" style="display: none;"></div>');
				$control->create_section('Generate New Page');
				echo $control->HTML;
			echo '</div>';
		echo '</div>';
		echo '<script type="text/javascript">';
			echo $control->JS;
			echo 'jQuery("#wp_insert_legalpages_privacy_policy_accordion").accordion({ icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }, heightStyle: "fill" });';
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_wp_insert_legalpages_privacy_policy_form_save_action', 'wp_insert_legalpages_privacy_policy_form_save_action');
function wp_insert_legalpages_privacy_policy_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$legalPages = get_option('wp_insert_legalpages');
	$legalPages['privacy_policy']['content'] = ((isset($_POST['wp_insert_legalpages_privacy_policy_content']))?$_POST['wp_insert_legalpages_privacy_policy_content']:'');
	$legalPages['privacy_policy']['assigned_page'] = ((isset($_POST['wp_insert_legalpages_privacy_policy_assigned_page']))?$_POST['wp_insert_legalpages_privacy_policy_assigned_page']:'');
	update_option('wp_insert_legalpages', $legalPages);
	die();
}

add_action('wp_ajax_wp_insert_legalpages_privacy_policy_form_generate_page_action', 'wp_insert_legalpages_privacy_policy_form_generate_page_action');
function wp_insert_legalpages_privacy_policy_form_generate_page_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$legalPages = get_option('wp_insert_legalpages');
	$postID = wp_insert_post(array(
		'post_type' => 'page',
		'post_title' => 'Privacy Policy',
		'post_content' => '',
		'post_status' => 'publish',
		'post_author' => get_current_user_id()
	));
	if(!is_wp_error($postID)) {
		echo $postID;
		$legalPages['privacy_policy']['assigned_page'] = $postID;
		update_option('wp_insert_legalpages', $legalPages);
	} else {
		echo '0';
	}
	die();
}
/* End Privacy Policy */

/* Begin Terms and Conditions */
add_action('wp_ajax_wp_insert_legalpages_terms_conditions_form_get_content', 'wp_insert_legalpages_terms_conditions_form_get_content');
function wp_insert_legalpages_terms_conditions_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	
	$legalPages = get_option('wp_insert_legalpages');
	echo '<div class="wp_insert_popup_content_wrapper">';
		if(!(isset($legalPages['terms_conditions']['content']) && ($legalPages['terms_conditions']['content'] != ''))) {
			$legalPages['terms_conditions']['content'] = wp_insert_legalpages_get_default_data('terms_conditions');
		}
		$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_legalpages[terms_conditions]', 'values' => $legalPages['terms_conditions']));
		echo '<div id="wp_insert_legalpages_terms_conditions_accordion">';
			echo '<h3>Disclaimer</h3>';
			echo '<div>';
				echo '<p><b>By using this feature, you agree to this disclaimer.</b></p>';
				echo '<p>These templates are provided to you to understand your obligations better, but they are NOT meant to constitute client-attorney relationship or personalized legal advice.</p>';
				echo '<p>The developer is not eligible for any claim or action based on any information or functionality provided by this plugin.</p>';
				echo '<p>We expressly disclaim all liability in respect of usage of this plugin or its features.</p>';
				echo '<p>This plugin gives you general information and tools, but is NOT meant to serve as complete compliance package.</p>';
				echo '<p>As each business and situation is unique, you might need to modify, add or delete information in these templates.</p>';
				echo '<p>This information is provided just to get you started.</p>';
			echo '</div>';
			echo '<h3>Content</h3>';
			echo '<div style="max-height: 320px;">'; 
				$control->add_control(array('type' => 'textarea-wysiwyg', 'style' => 'height: 220px;', 'optionName' => 'content'));
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Assign Pages(s)</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'pages-select', 'optionName' => 'assigned_page'));
				$control->create_section('Assign a Page');
				echo $control->HTML;
				echo '<p class="wp_insert_OR">OR</p>';
				$control->set_HTML('<input type="button" id="wp_insert_legalpages_terms_conditions_generate_page" value="Click to Generate" class="input button-secondary wp_insert_generate_page_button" onclick="wp_insert_legalpages_generate_page(\'wp_insert_legalpages_terms_conditions\', \'Terms and Conditions\')" /><div class="wp_insert_ajaxloader_flat" style="display: none;"></div>');
				$control->create_section('Generate New Page');
				echo $control->HTML;
			echo '</div>';
		echo '</div>';
		echo '<script type="text/javascript">';
			echo $control->JS;
			echo 'jQuery("#wp_insert_legalpages_terms_conditions_accordion").accordion({ icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }, heightStyle: "fill" });';
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_wp_insert_legalpages_terms_conditions_form_save_action', 'wp_insert_legalpages_terms_conditions_form_save_action');
function wp_insert_legalpages_terms_conditions_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$legalPages = get_option('wp_insert_legalpages');
	$legalPages['terms_conditions']['content'] = ((isset($_POST['wp_insert_legalpages_terms_conditions_content']))?$_POST['wp_insert_legalpages_terms_conditions_content']:'');
	$legalPages['terms_conditions']['assigned_page'] = ((isset($_POST['wp_insert_legalpages_terms_conditions_assigned_page']))?$_POST['wp_insert_legalpages_terms_conditions_assigned_page']:'');
	update_option('wp_insert_legalpages', $legalPages);
	die();
}

add_action('wp_ajax_wp_insert_legalpages_terms_conditions_form_generate_page_action', 'wp_insert_legalpages_terms_conditions_form_generate_page_action');
function wp_insert_legalpages_terms_conditions_form_generate_page_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$legalPages = get_option('wp_insert_legalpages');
	$postID = wp_insert_post(array(
		'post_type' => 'page',
		'post_title' => 'Terms and Conditions',
		'post_content' => '',
		'post_status' => 'publish',
		'post_author' => get_current_user_id()
	));
	if(!is_wp_error($postID)) {
		echo $postID;
		$legalPages['terms_conditions']['assigned_page'] = $postID;
		update_option('wp_insert_legalpages', $legalPages);
	} else {
		echo '0';
	}
	die();
}
/* End Terms and Conditions */

/* Begin Disclaimer */
add_action('wp_ajax_wp_insert_legalpages_disclaimer_form_get_content', 'wp_insert_legalpages_disclaimer_form_get_content');
function wp_insert_legalpages_disclaimer_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	
	$legalPages = get_option('wp_insert_legalpages');
	echo '<div class="wp_insert_popup_content_wrapper">';
		if(!(isset($legalPages['disclaimer']['content']) && ($legalPages['disclaimer']['content'] != ''))) {
			$legalPages['disclaimer']['content'] = wp_insert_legalpages_get_default_data('disclaimer');
		}
		$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_legalpages[disclaimer]', 'values' => $legalPages['disclaimer']));
		echo '<div id="wp_insert_legalpages_disclaimer_accordion">';
			echo '<h3>Disclaimer</h3>';
			echo '<div>';
				echo '<p><b>By using this feature, you agree to this disclaimer.</b></p>';
				echo '<p>These templates are provided to you to understand your obligations better, but they are NOT meant to constitute client-attorney relationship or personalized legal advice.</p>';
				echo '<p>The developer is not eligible for any claim or action based on any information or functionality provided by this plugin.</p>';
				echo '<p>We expressly disclaim all liability in respect of usage of this plugin or its features.</p>';
				echo '<p>This plugin gives you general information and tools, but is NOT meant to serve as complete compliance package.</p>';
				echo '<p>As each business and situation is unique, you might need to modify, add or delete information in these templates.</p>';
				echo '<p>This information is provided just to get you started.</p>';
			echo '</div>';
			echo '<h3>Content</h3>';
			echo '<div style="max-height: 320px;">'; 
				$control->add_control(array('type' => 'textarea-wysiwyg', 'style' => 'height: 220px;', 'optionName' => 'content'));
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Assign Pages(s)</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'pages-select', 'optionName' => 'assigned_page'));
				$control->create_section('Assign a Page');
				echo $control->HTML;
				echo '<p class="wp_insert_OR">OR</p>';
				$control->set_HTML('<input type="button" id="wp_insert_legalpages_disclaimer_generate_page" value="Click to Generate" class="input button-secondary wp_insert_generate_page_button" onclick="wp_insert_legalpages_generate_page(\'wp_insert_legalpages_disclaimer\', \'Disclaimer\')" /><div class="wp_insert_ajaxloader_flat" style="display: none;"></div>');
				$control->create_section('Generate New Page');
				echo $control->HTML;
			echo '</div>';
		echo '</div>';
		echo '<script type="text/javascript">';
			echo $control->JS;
			echo 'jQuery("#wp_insert_legalpages_disclaimer_accordion").accordion({ icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }, heightStyle: "fill" });';
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_wp_insert_legalpages_disclaimer_form_save_action', 'wp_insert_legalpages_disclaimer_form_save_action');
function wp_insert_legalpages_disclaimer_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$legalPages = get_option('wp_insert_legalpages');
	$legalPages['disclaimer']['content'] = ((isset($_POST['wp_insert_legalpages_disclaimer_content']))?$_POST['wp_insert_legalpages_disclaimer_content']:'');
	$legalPages['disclaimer']['assigned_page'] = ((isset($_POST['wp_insert_legalpages_disclaimer_assigned_page']))?$_POST['wp_insert_legalpages_disclaimer_assigned_page']:'');
	update_option('wp_insert_legalpages', $legalPages);
	die();
}

add_action('wp_ajax_wp_insert_legalpages_disclaimer_form_generate_page_action', 'wp_insert_legalpages_disclaimer_form_generate_page_action');
function wp_insert_legalpages_disclaimer_form_generate_page_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$legalPages = get_option('wp_insert_legalpages');
	$postID = wp_insert_post(array(
		'post_type' => 'page',
		'post_title' => 'Disclaimer',
		'post_content' => '',
		'post_status' => 'publish',
		'post_author' => get_current_user_id()
	));
	if(!is_wp_error($postID)) {
		echo $postID;
		$legalPages['disclaimer']['assigned_page'] = $postID;
		update_option('wp_insert_legalpages', $legalPages);
	} else {
		echo '0';
	}
	die();
}
/* End Disclaimer */

/* Begin Copyright Notice */
add_action('wp_ajax_wp_insert_legalpages_copyright_form_get_content', 'wp_insert_legalpages_copyright_form_get_content');
function wp_insert_legalpages_copyright_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	
	$legalPages = get_option('wp_insert_legalpages');
	echo '<div class="wp_insert_popup_content_wrapper">';
		if(!(isset($legalPages['copyright']['content']) && ($legalPages['copyright']['content'] != ''))) {
			$legalPages['copyright']['content'] = wp_insert_legalpages_get_default_data('copyright');
		}
		$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_legalpages[copyright]', 'values' => $legalPages['copyright']));
		echo '<div id="wp_insert_legalpages_copyright_accordion">';
			echo '<h3>Disclaimer</h3>';
			echo '<div>';
				echo '<p><b>By using this feature, you agree to this disclaimer.</b></p>';
				echo '<p>These templates are provided to you to understand your obligations better, but they are NOT meant to constitute client-attorney relationship or personalized legal advice.</p>';
				echo '<p>The developer is not eligible for any claim or action based on any information or functionality provided by this plugin.</p>';
				echo '<p>We expressly disclaim all liability in respect of usage of this plugin or its features.</p>';
				echo '<p>This plugin gives you general information and tools, but is NOT meant to serve as complete compliance package.</p>';
				echo '<p>As each business and situation is unique, you might need to modify, add or delete information in these templates.</p>';
				echo '<p>This information is provided just to get you started.</p>';
			echo '</div>';
			echo '<h3>Content</h3>';
			echo '<div style="max-height: 320px;">'; 
				$control->add_control(array('type' => 'textarea-wysiwyg', 'style' => 'height: 220px;', 'optionName' => 'content'));
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Assign Pages(s)</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'pages-select', 'optionName' => 'assigned_page'));
				$control->create_section('Assign a Page');
				echo $control->HTML;
				echo '<p class="wp_insert_OR">OR</p>';
				$control->set_HTML('<input type="button" id="wp_insert_legalpages_copyright_generate_page" value="Click to Generate" class="input button-secondary wp_insert_generate_page_button" onclick="wp_insert_legalpages_generate_page(\'wp_insert_legalpages_copyright\', \'Copyright Notice\')" /><div class="wp_insert_ajaxloader_flat" style="display: none;"></div>');
				$control->create_section('Generate New Page');
				echo $control->HTML;
			echo '</div>';
		echo '</div>';
		echo '<script type="text/javascript">';
			echo $control->JS;
			echo 'jQuery("#wp_insert_legalpages_copyright_accordion").accordion({ icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }, heightStyle: "fill" });';
		echo '</script>';
	echo '</div>';
	die();
}

add_action('wp_ajax_wp_insert_legalpages_copyright_form_save_action', 'wp_insert_legalpages_copyright_form_save_action');
function wp_insert_legalpages_copyright_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$legalPages = get_option('wp_insert_legalpages');
	$legalPages['copyright']['content'] = ((isset($_POST['wp_insert_legalpages_copyright_content']))?$_POST['wp_insert_legalpages_copyright_content']:'');
	$legalPages['copyright']['assigned_page'] = ((isset($_POST['wp_insert_legalpages_copyright_assigned_page']))?$_POST['wp_insert_legalpages_copyright_assigned_page']:'');
	update_option('wp_insert_legalpages', $legalPages);
	die();
}

add_action('wp_ajax_wp_insert_legalpages_copyright_form_generate_page_action', 'wp_insert_legalpages_copyright_form_generate_page_action');
function wp_insert_legalpages_copyright_form_generate_page_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	
	$legalPages = get_option('wp_insert_legalpages');
	$postID = wp_insert_post(array(
		'post_type' => 'page',
		'post_title' => 'Copyright Notice',
		'post_content' => '',
		'post_status' => 'publish',
		'post_author' => get_current_user_id()
	));
	if(!is_wp_error($postID)) {
		echo $postID;
		$legalPages['copyright']['assigned_page'] = $postID;
		update_option('wp_insert_legalpages', $legalPages);
	} else {
		echo '0';
	}
	die();
}
/* End Copyright Notice */

/* Begin Database Upgrade */
add_action('wp_insert_upgrade_database', 'wp_insert_legalpages_upgrade_database');
function wp_insert_legalpages_upgrade_database() {
	if(!get_option('wp_insert_legalpages')) {
		$oldValues = get_option('wp_insert_legal_options');
		$newValues = array(
			'privacy_policy' => array(
				'content' => ((isset($oldValues['privacy-policy']['content']))?$oldValues['privacy-policy']['content']:''),
				'assigned_page' => ((isset($oldValues['privacy-policy']['pages']))?$oldValues['privacy-policy']['pages']:''),
			),
			'terms_conditions' => array(
				'content' => ((isset($oldValues['terms-and-conditions']['content']))?$oldValues['terms-and-conditions']['content']:''),
				'assigned_page' => ((isset($oldValues['terms-and-conditions']['pages']))?$oldValues['terms-and-conditions']['pages']:''),
			),
			'disclaimer' => array(
				'content' => ((isset($oldValues['disclaimer']['content']))?$oldValues['disclaimer']['content']:''),
				'assigned_page' => ((isset($oldValues['disclaimer']['pages']))?$oldValues['disclaimer']['pages']:''),
			),
			'copyright' => array(
				'content' => ((isset($oldValues['copyright-notice']['content']))?$oldValues['copyright-notice']['content']:''),
				'assigned_page' => ((isset($oldValues['copyright-notice']['pages']))?$oldValues['copyright-notice']['pages']:''),
			),
		);		
		update_option('wp_insert_legalpages', $newValues);
	}
}
/* End Database Upgrade */

/* Begin Legal Pages Content Insertion */
add_filter('the_content', 'wp_insert_legalpages_the_content');
function wp_insert_legalpages_the_content($content) {
	global $post;
	$legalPages = get_option('wp_insert_legalpages');
	
	if(isset($post) && (isset($post->ID))) {
		if(isset($legalPages['privacy_policy']['assigned_page']) && ($legalPages['privacy_policy']['assigned_page'] != '') && ($post->ID == $legalPages['privacy_policy']['assigned_page'])) {
			return do_shortcode(stripslashes($legalPages['privacy_policy']['content']));
		}
		if(isset($legalPages['terms_conditions']['assigned_page']) && ($legalPages['terms_conditions']['assigned_page'] != '') && ($post->ID == $legalPages['terms_conditions']['assigned_page'])) {
			return do_shortcode(stripslashes($legalPages['terms_conditions']['content']));
		}
		if(isset($legalPages['disclaimer']['assigned_page']) && ($legalPages['disclaimer']['assigned_page'] != '') && ($post->ID == $legalPages['disclaimer']['assigned_page'])) {
			return do_shortcode(stripslashes($legalPages['disclaimer']['content']));
		}
		if(isset($legalPages['copyright']['assigned_page']) && ($legalPages['copyright']['assigned_page'] != '') && ($post->ID == $legalPages['copyright']['assigned_page'])) {
			return do_shortcode(stripslashes($legalPages['copyright']['content']));
		}
	}

	return $content;
}

add_shortcode('sitename', 'wp_insert_legalpages_sitename_shortcode');
function wp_insert_legalpages_sitename_shortcode($atts) {
	return '<i>'.get_bloginfo('name').'</i>';
}

function wp_insert_legalpages_get_default_data($pageType) {
	$output = '';
	switch($pageType) {
		case 'privacy_policy':
			$output = '<p>At [sitename], the privacy of our visitors is of extreme importance to us. This privacy policy document outlines the types of personal information is received and collected by [sitename] and how it is used.</p>';
			$output .= '<p><b>Log Files</b></p><p>Like many other Web sites, [sitename] makes use of log files. The information inside the log files includes internet protocol (IP) addresses, type of browser, Internet Service Provider (ISP), date/time stamp, referring/exit pages, and number of clicks to analyze trends, administer the site, track user\'s movement around the site, and gather demographic information. IP addresses, and other such information are not linked to any information that is personally identifiable.</p>';
			$output .= '<p><b>Cookies and Web Beacons</b></p><p>[sitename] does use cookies to store information about visitors preferences, record user-specific information on which pages the user access or visit, customize Web page content based on visitors browser type or other information that the visitor sends via their browser.</p>';
			$output .= '<p><b>DoubleClick DART Cookie</b></p><ul><li>Google, as a third party vendor, uses cookies to serve ads on [sitename].</li><li>Google\'s use of the DART cookie enables it to serve ads to users based on their visit to [sitename] and other sites on the Internet.</li><li>Users may opt out of the use of the DART cookie by visiting the Google ad and content network privacy policy at the following URL - <a href="https://www.google.com/policies/privacy/" rel="nofollow">https://www.google.com/policies/privacy/</a>.</li></ul>';
			$output .= '<p>These third-party ad servers or ad networks use technology to the advertisements and links that appear on [sitename] send directly to your browsers. They automatically receive your IP address when this occurs. Other technologies ( such as cookies, JavaScript, or Web Beacons ) may also be used by the third-party ad networks to measure the effectiveness of their advertisements and / or to personalize the advertising content that you see.</p>';
			$output .= '<p>[sitename] has no access to or control over these cookies that are used by third-party advertisers.</p>';
			$output .= '<p>You should consult the respective privacy policies of these third-party ad servers for more detailed information on their practices as well as for instructions about how to opt-out of certain practices. [sitename]\'s privacy policy does not apply to, and we cannot control the activities of, such other advertisers or web sites.</p>';
			$output .= '<p>If you wish to disable cookies, you may do so through your individual browser options. More detailed information about cookie management with specific web browsers can be found at the browser\'s respective websites.</p>';
			break;
		case 'terms_conditions':
			$output = '<p>Welcome to [sitename]. If you continue to browse and use this website you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern [sitename]\'s relationship with you in relation to this website.</p>';
			$output .= '<p>The term [sitename] or \'us\' or \'we\' refers to the owner of the website. The term \'you\' refers to the user or viewer of our website.  The use of this website is subject to the following terms of use:</p>';
			$output .= '<ul>';
				$output .= '<li>The content of the pages of this website is for your general information and use only. It is subject to change without notice.</li>';
				$output .= '<li>Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</li>';
				$output .= '<li>Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</li>';
				$output .= '<li>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</li>';
				$output .= '<li>All trademarks reproduced in this website, which are not the property of, or licensed to the operator, are acknowledged on the website.</li>';
				$output .= '<li>Unauthorized use of this website may give rise to a claim for damages and/or be a criminal offense.</li>';
				$output .= '<li>From time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</li>';
				$output .= '<li>You may not create a link to this website from another website or document without [sitename]\'s prior written consent.</li>';
			$output .= '</ul>';
			break;
		case 'disclaimer':
			$output = '<p>The information contained in this website is for general information purposes only. The information is provided by [sitename] and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</p>';
			$output .= '<p>In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>';
			$output .= '<p>Through this website you are able to link to other websites which are not under the control of [sitename]. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</p>';
			$output .= '<p>Every effort is made to keep the website up and running smoothly. However, [sitename] takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.</p>';
			break;
		case 'copyright':
			$output = '<p>This website and its content is copyright of [sitename] - &copy; [sitename] '.date('Y').'. All rights reserved.</p>';
			$output .= '</p>Any redistribution or reproduction of part or all of the contents in any form is prohibited other than the following:</p>';
			$output .= '<ul>';
				$output .= '<li>you may print or download to a local hard disk extracts for your personal and non-commercial use only</li>';
				$output .= '<li>you may copy the content to individual third parties for their personal use, but only if you acknowledge the website as the source of the material</li>';
			$output .= '</ul>';
			$output .= '<p>You may not, except with our express written permission, distribute or commercially exploit the content. Nor may you transmit it or store it in any other website or other form of electronic retrieval system.</p>';
			break;
	}
	return $output;
}
/* End Legal Pages Content Insertion */
?>