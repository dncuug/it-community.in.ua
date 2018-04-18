<?php
/* Begin Admin Notice */
add_action('init', 'wp_insert_adstxt_adsense_admin_notice_reactivate');
function wp_insert_adstxt_adsense_admin_notice_reactivate() {
	if(isset($_GET['wp_insert_adstxt_adsense_reset'])) {
		wp_insert_adstxt_adsense_admin_notice_reset();
		wp_redirect(esc_url(admin_url('/admin.php?page=wp-insert')));
	}
}

function wp_insert_adstxt_adsense_admin_notice_reset() {
	delete_option('wp_insert_adstxt_adsense_admin_notice_dismissed');
	delete_transient('wp_insert_adstxt_adsense_autocheck_content');
}

add_action('admin_notices', 'wp_insert_adstxt_adsense_admin_notice');
function wp_insert_adstxt_adsense_admin_notice() {	
	if(current_user_can('manage_options')) {
		if(!get_option('wp_insert_adstxt_adsense_admin_notice_dismissed')) {
			$adstxtNewAdsenseEntries = get_transient('wp_insert_adstxt_adsense_autocheck_content');
			if($adstxtNewAdsenseEntries == '###CHECKED###') {
			} else {
				if($adstxtNewAdsenseEntries === false) {
					$adstxtNewAdsenseEntries = wp_insert_adstxt_adsense_get_status();
				}
				if($adstxtNewAdsenseEntries !== false) {
					set_transient('wp_insert_adstxt_adsense_autocheck_content', $adstxtNewAdsenseEntries, DAY_IN_SECONDS);
					echo '<div class="notice notice-error wp_insert_adstxt_adsense_notice is-dismissible" style="padding: 15px;">';
						echo '<p><b>Wp-Insert</b> had detected that your ads.txt file does not have all your Google Adsense Publisher IDs.<br />This will severely impact your adsense earnings and your immediate attention is required.</p>';
						echo '<p>Your recommended google entries for ads.txt is as given below.<br />You can manually copy this to your ads.txt file or ';
							$screen = get_current_screen();
							if($screen->id != 'toplevel_page_wp-insert') {
								echo '<a href="'.esc_url(admin_url('/admin.php?page=wp-insert#wp_insert_adstxt_adsense_auto_update')).'">CLICK HERE</a>';
							} else {
								echo '<a href="javascript:;" onclick="wp_insert_adstxt_adsense_auto_update()">CLICK HERE</a>';
							}
						echo ' to instruct Wp-Insert to try and add the entries automatically.</p>';
						echo '<p><code style="display: block; padding: 2px 10px;">'.implode("<br />", $adstxtNewAdsenseEntries).'</code></p>';
						echo '<p><small><i><b>We recommend you not to dismiss this notice for continued daily ads.txt monitoring.  This notice will stop appearing automatically once Wp-Insert detects correct entries in ads.txt (rechecked daily).</b></i></small></p>';
						echo '<div class="clear"></div>';
						echo '<input type="hidden" id="wp_insert_adstxt_adsense_admin_notice_nonce" name="wp_insert_adstxt_adsense_admin_notice_nonce" value="'.wp_create_nonce('wp-insert-adstxt-adsense-admin-notice').'" />';
						echo '<input type="hidden" id="wp_insert_adstxt_adsense_admin_notice_ajax" name="wp_insert_adstxt_adsense_admin_notice_ajax" value="'.admin_url('admin-ajax.php').'" />';
					echo '</div>';
				} else {
					set_transient('wp_insert_adstxt_adsense_autocheck_content', '###CHECKED###', DAY_IN_SECONDS);
				}
			}
		}
	}
}

add_action('wp_ajax_wp_insert_adstxt_adsense_admin_notice_dismiss', 'wp_insert_adstxt_adsense_admin_notice_dismiss');
function wp_insert_adstxt_adsense_admin_notice_dismiss() {
	check_ajax_referer('wp-insert-adstxt-adsense-admin-notice', 'wp_insert_adstxt_adsense_admin_notice_nonce');	
	update_option('wp_insert_adstxt_adsense_admin_notice_dismissed', 'true');
	die();
}
/* End Admin Notice */

/* Begin Auto Update Ads.txt (Adsense) */
add_action('wp_ajax_wp_insert_adstxt_adsense_auto_update', 'wp_insert_adstxt_adsense_auto_update');
function wp_insert_adstxt_adsense_auto_update() {
	check_ajax_referer('wp-insert-adstxt-adsense-admin-notice', 'wp_insert_adstxt_adsense_admin_notice_nonce');
	$adstxtNewAdsenseEntries = wp_insert_adstxt_adsense_get_status();
	if($adstxtNewAdsenseEntries !== false) {
		$adstxtContent = wp_insert_adstxt_get_content();
		$adstxtContentData = array_filter(explode("\n", trim($adstxtContent)), 'trim');
		$adstxtUpdatedContent = array_filter(array_merge($adstxtContentData, $adstxtNewAdsenseEntries), 'trim');
	}

	if(isset($adstxtUpdatedContent) && is_array($adstxtUpdatedContent) && (count($adstxtUpdatedContent) > 0)) {
		$adstxtUpdatedContent = implode("\n", $adstxtUpdatedContent);
		if(wp_insert_adstxt_update_content($adstxtUpdatedContent)) {
			echo '###SUCCESS###';
		} else {
			echo wp_insert_adstxt_updation_failed_message($adstxtUpdatedContent);
		}
	}
	die();
}
/* End Auto Update Ads.txt (Adsense) */

/* Begin ads.txt Adsense Check */
function wp_insert_adstxt_adsense_get_status() {
	if(wp_insert_adstxt_file_exists()) {
		$adsensePublisherIds = wp_insert_adstxt_adsense_get_publisherids();
		$adstxtContent = wp_insert_adstxt_get_content();
		$adstxtContentData = array_filter(explode("\n", trim($adstxtContent)), 'trim');
		$adstxtExistingAdsenseEntries = array();
		foreach($adstxtContentData as $line) {
			if(strpos($line, 'google.com') !== false) {
				$adstxtExistingAdsenseEntries[] = $line;
			}
		}
		
		$adstxtNewAdsenseEntries = array();
		if(count($adstxtExistingAdsenseEntries) == 0) {
			if(is_array($adsensePublisherIds) && (count($adsensePublisherIds) > 0)) {
				foreach($adsensePublisherIds as $adsensePublisherId) {
					$adstxtNewAdsenseEntries[] = 'google.com, '.$adsensePublisherId.', DIRECT, f08c47fec0942fa0';
				}
			}
		} else {
			if(is_array($adsensePublisherIds) && (count($adsensePublisherIds) > 0)) {
				foreach($adsensePublisherIds as $adsensePublisherId) {
					$entryExists = false;
					foreach($adstxtExistingAdsenseEntries as $adstxtExistingAdsenseEntry) {
						if(strpos($adstxtExistingAdsenseEntry, $adsensePublisherId) !== false) {
							$entryExists = true;
						}
					}
					if($entryExists == false) {
						$adstxtNewAdsenseEntries[] = 'google.com, '.$adsensePublisherId.', DIRECT, f08c47fec0942fa0';
					}
				}
			}
		}
	}
	if(isset($adstxtNewAdsenseEntries) && count($adstxtNewAdsenseEntries) > 0) {
		return $adstxtNewAdsenseEntries;
	}
	return false;
}
/* End ads.txt Adsense Check */

/* Begin Extract Publisher Ids from Ads */
function wp_insert_adstxt_adsense_get_publisherids() {
	$adsensePublisherIds = array();
	$data = get_option('wp_insert_inpostads');
	if(isset($data) && is_array($data)) {
		foreach($data as $adUnit) {
			$temp = wp_insert_adstxt_adsense_extract_publisherids($adUnit);
			if($temp !== false) {
				$adsensePublisherIds = array_merge($adsensePublisherIds, $temp);
			}
		}
	}
	
	$data = get_option('wp_insert_adwidgets');
	if(isset($data) && is_array($data)) {
		foreach($data as $adUnit) {
			$temp = wp_insert_adstxt_adsense_extract_publisherids($adUnit);
			if($temp !== false) {
				$adsensePublisherIds = array_merge($adsensePublisherIds, $temp);
			}
		}
	}
	
	$data = get_option('wp_insert_inthemeads');
	if(isset($data) && is_array($data)) {
		foreach($data as $adUnit) {
			$temp = wp_insert_adstxt_adsense_extract_publisherids($adUnit);
			if($temp !== false) {
				$adsensePublisherIds = array_merge($adsensePublisherIds, $temp);
			}
		}
	}
	
	$data = get_option('wp_insert_shortcodeads');
	if(isset($data) && is_array($data)) {
		foreach($data as $adUnit) {
			$temp = wp_insert_adstxt_adsense_extract_publisherids($adUnit);
			if($temp !== false) {
				$adsensePublisherIds = array_merge($adsensePublisherIds, $temp);
			}
		}
	}
	
	$data = get_option('wp_insert_pagelevelads');
	if(isset($data) && is_array($data)) {
		foreach($data as $adUnit) {
			$temp = wp_insert_adstxt_adsense_extract_publisherids($adUnit);
			if($temp !== false) {
				$adsensePublisherIds = array_merge($adsensePublisherIds, $temp);
			}
		}
	}
	$adsensePublisherIds = array_unique($adsensePublisherIds);
	
	if(count($adsensePublisherIds) > 0) {
		return $adsensePublisherIds;
	}
	return false;
}

function wp_insert_adstxt_adsense_extract_publisherids($adUnit) {
	$publisherIds = array();
	if(isset($adUnit['primary_ad_code']) && ($adUnit['primary_ad_code'] != '')) {
		if(preg_match('/googlesyndication.com/', $adUnit['primary_ad_code'])) {
			if(preg_match('/data-ad-client=/', $adUnit['primary_ad_code'])) { //ASYNS AD CODE
				$adCodeParts = explode('data-ad-client', $adUnit['primary_ad_code']);
			} else {
				$adCodeParts = explode('google_ad_client', $adUnit['primary_ad_code']); //ORDINARY AD CODE
			}
			if(isset($adCodeParts[1]) && ($adCodeParts[1] != '')) {
				preg_match('#"([a-zA-Z0-9-\s]+)"#', stripslashes($adCodeParts[1]), $matches);
				if(isset($matches[1]) && ($matches[1] != '')) {
					$publisherIds[] = str_replace(array('"', ' ', 'ca-'), array(''), $matches[1]);
				}
			}
		}
	}
	if(isset($adUnit['secondary_ad_code']) && ($adUnit['secondary_ad_code'] != '')) {
		if(preg_match('/googlesyndication.com/', $adUnit['secondary_ad_code'])) {
			if(preg_match('/data-ad-client=/', $adUnit['secondary_ad_code'])) { //ASYNS AD CODE
				$adCodeParts = explode('data-ad-client', $adUnit['secondary_ad_code']);
			} else {
				$adCodeParts = explode('google_ad_client', $adUnit['secondary_ad_code']); //ORDINARY AD CODE
			}
			if(isset($adCodeParts[1]) && ($adCodeParts[1] != '')) {
				preg_match('#"([a-zA-Z0-9-\s]+)"#', stripslashes($adCodeParts[1]), $matches);
				if(isset($matches[1]) && ($matches[1] != '')) {
					$publisherIds[] = str_replace(array('"', ' ', 'ca-'), array(''), $matches[1]);
				}
			}
		}
	}
	if(isset($adUnit['tertiary_ad_code']) && ($adUnit['tertiary_ad_code'] != '')) {
		if(preg_match('/googlesyndication.com/', $adUnit['tertiary_ad_code'])) {
			if(preg_match('/data-ad-client=/', $adUnit['tertiary_ad_code'])) { //ASYNS AD CODE
				$adCodeParts = explode('data-ad-client', $adUnit['tertiary_ad_code']);
			} else {
				$adCodeParts = explode('google_ad_client', $adUnit['tertiary_ad_code']); //ORDINARY AD CODE
			}
			if(isset($adCodeParts[1]) && ($adCodeParts[1] != '')) {
				preg_match('#"([a-zA-Z0-9-\s]+)"#', stripslashes($adCodeParts[1]), $matches);
				if(isset($matches[1]) && ($matches[1] != '')) {
					$publisherIds[] = str_replace(array('"', ' ', 'ca-'), array(''), $matches[1]);
				}
			}
		}
	}
	if(count($publisherIds) > 0) {
		return $publisherIds;
	}
	return false;
}
/* End Extract Publisher Ids from Ads */
?>