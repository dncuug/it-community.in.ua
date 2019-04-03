<?php
function wp_insert_vi_api_get_settings() {
	$viSettings = get_transient('wp_insert_vi_api_settings');
	if(($viSettings === false) || !is_array($viSettings)) {
		try {
			$response = wp_remote_get('https://dashboard-api.vidint.net/v1/api/widget/settings', array('timeout' => 15));
			if(!is_wp_error($response) && (200 == wp_remote_retrieve_response_code($response))) {
				$responseBody = json_decode($response['body']);
				//echo '<pre>'; print_r($responseBody->data); echo '</pre>';
				if((json_last_error() == JSON_ERROR_NONE) && ($responseBody->status == 'ok')) {
					$viSettings = array(
						'signupURL'	=> $responseBody->data->signupURL, 
						'demoPageURL' => $responseBody->data->demoPageURL,
						'iabCategoriesURL' => $responseBody->data->iabCategoriesURL,
						'loginAPI' => $responseBody->data->loginAPI,
						'directSellURL'	=> $responseBody->data->directSellURL,
						'dashboardURL' => $responseBody->data->dashboardURL,
						'revenueAPI' => $responseBody->data->revenueAPI,
						'adsTxtAPI' => $responseBody->data->adsTxtAPI,
						'languages' => $responseBody->data->languages,
						'jsTagAPI' => $responseBody->data->jsTagAPI,
						'vendorListURL' => $responseBody->data->vendorListURL,
						'vendorListVersion' => $responseBody->data->vendorListVersion,
						'consentPopupContent' => $responseBody->data->consentPopupContent,
						'purposes' => $responseBody->data->purposes,
					);
					delete_transient('wp_insert_vi_api_settings');
					set_transient('wp_insert_vi_api_settings', $viSettings, WEEK_IN_SECONDS);	
				} else {
					return false;
				}					
			}
		} catch(Exception $ex) {
			return false;
		}
	}
	return $viSettings;
}

function wp_insert_vi_api_reset_settings() {
	delete_transient('wp_insert_vi_api_settings');
}

function wp_insert_vi_api_get_signupurl() {
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		return $viSettings['signupURL'];
	}
	return false;
}

function wp_insert_vi_api_get_dashboardurl() {
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		return $viSettings['dashboardURL'];
	}
	return false;
}

function wp_insert_vi_api_get_iabCategoriesURL() {
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		return $viSettings['iabCategoriesURL'];
	}
	return false;
}

function wp_insert_vi_api_get_languages() {
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		$languages = array();
		foreach($viSettings['languages'] as $language) {
			foreach($language as $key => $value) {
				$languages[$key] = $value;
			}
		}
		if(count($languages) > 0) {
			return $languages;
		} else {
			return false;
		}
	}
	return false;
}

function wp_insert_vi_api_get_consent_popup_content() {
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		return $viSettings['consentPopupContent'];
	}
	return false;
}

function wp_insert_vi_api_get_consent_purposes() {
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		return $viSettings['purposes'];
	}
	return false;
}

function wp_insert_vi_api_get_adstxt_content() {
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		$viToken = wp_insert_vi_api_get_publisher_token();
		if($viToken !== false) {
			try{
				$response = wp_remote_get(
					$viSettings['adsTxtAPI'],
					array(
						'timeout' => 15,
						'headers' => array(
							'Content-Type' => 'application/json',
							'Authorization' => $viToken
						)
					)
				);
				if(!is_wp_error($response)) {
					if(200 == wp_remote_retrieve_response_code($response)) {
						$responseBody = json_decode($response['body']);
						if((json_last_error() == JSON_ERROR_NONE) && ($responseBody->status == 'ok')) {
							return $responseBody->data;
						} else {
							return false;
						}
					} else {
						return false;
					}
				}
			} catch(Exception $ex) {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function wp_insert_vi_api_login($email, $password) {
	if(($email != '') && ($password != '')) {
		$viSettings = wp_insert_vi_api_get_settings();
		if(($viSettings != false) && is_array($viSettings)) {
			try {
				$response = wp_remote_post(
					$viSettings['loginAPI'],
					array(
						'timeout' => 15,
						'headers' => array(
							'Content-Type' => 'application/json'
						),
						'body' => json_encode(array(
							'email' => $email,
							'password' => $password,
						))
					)
				);
				if(!is_wp_error($response)) {
					if(401 == wp_remote_retrieve_response_code($response)) {
						$responseBody = json_decode($response['body']);
						if((json_last_error() == JSON_ERROR_NONE) && ($responseBody->status == 'error')) {
							return array(
								'status' => 'error',
								'errorCode' => 'WIVI008',
								'message' => $responseBody->error->message.':'.$responseBody->error->description.'',
							);
						} else {
							return array(
								'status' => 'error',
								'errorCode' => 'WIVI007',
								'message' => 'Response JSON error, Please try again later!',
							);
						}
					} else if(200 == wp_remote_retrieve_response_code($response)) {
						$responseBody = json_decode($response['body']);
						if((json_last_error() == JSON_ERROR_NONE) && ($responseBody->status == 'ok')) {
							$viToken = $responseBody->data;
							delete_transient('wp_insert_vi_api_authetication_token');
							set_transient('wp_insert_vi_api_authetication_token', $viToken, MONTH_IN_SECONDS);
						} else {
							return array(
								'status' => 'error',
								'errorCode' => 'WIVI006',
								'message' => 'Response JSON error!',
							);
						}
					} else {
						return array(
							'status' => 'error',
							'errorCode' => 'WIVI005',
							'message' => 'Unknown response code',
						);
					}
				} else {
					return array(
						'status' => 'error',
						'errorCode' => 'WIVI004',
						'message' => 'API response error',
					);
				}
			} catch(Exception $ex) {
				return array(
					'status' => 'error',
					'errorCode' => 'WIVI003',
					'message' => 'Exception during API communication',
				);
			}
		} else {
			return array(
				'status' => 'error',
				'errorCode' => 'WIVI002',
				'message' => 'API is unreachable',
			);
		}
	} else {
		return array(
			'status' => 'error',
			'errorCode' => 'WIVI001',
			'message' => 'Email / Password is Empty!',
		);
	}
	return $viToken;
}

function wp_insert_vi_api_logout() {
	delete_transient('wp_insert_vi_api_authetication_token');
	//delete_transient('wp_insert_vi_api_settings');
}

function wp_insert_vi_api_get_publisher_id() {
	$viToken = get_transient('wp_insert_vi_api_authetication_token');
	if($viToken === false) {
		return false;
	}
	$viToken = explode('.', $viToken);
	$viToken = base64_decode($viToken[1]);
	$viToken = json_decode($viToken);
	if(json_last_error() == JSON_ERROR_NONE) {
		return $viToken->publisherId;
	}
	return false;	
}

function wp_insert_vi_api_get_publisher_token() {
	$viToken = get_transient('wp_insert_vi_api_authetication_token');
	if($viToken === false) {
		return false;
	}
	return $viToken;	
}

function wp_insert_vi_api_is_loggedin() {
	$viToken = get_transient('wp_insert_vi_api_authetication_token');
	if($viToken === false) {
		return false;
	}
	return true;
}

function wp_insert_vi_api_get_revenue_data() {
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		$viToken = wp_insert_vi_api_get_publisher_token();
		if($viToken !== false) {
			try{
				$response = wp_remote_get(
					$viSettings['revenueAPI'],
					array(
						'timeout' => 15,
						'headers' => array(
							'Content-Type' => 'application/json',
							'Authorization' => $viToken
						)
					)
				);
				if(!is_wp_error($response)) {
					if(200 == wp_remote_retrieve_response_code($response)) {
						$responseBody = json_decode($response['body']);
						if((json_last_error() == JSON_ERROR_NONE) && ($responseBody->status == 'ok')) {
							return json_decode(json_encode($responseBody->data), True);
						} else {
							return false;
						}
					} else {
						return false;
					}
				}
			} catch(Exception $ex) {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function wp_insert_vi_api_set_vi_code($args = null) {
	$domain = wp_insert_get_domain_name_from_url(get_bloginfo('url'));
	$selectedArgs = array();
	$selectedArgs['domain'] = $domain;
	$selectedArgs['divId'] = 'wp_insert_vi_ad';
	
	if(isset($args) && is_array($args)) {
		if(isset($args['ad_unit_type']) && ($args['ad_unit_type'] != '') && ($args['ad_unit_type'] != 'select') && ($args['ad_unit_type'] != 'undefined')) {
			$selectedArgs['adUnitType'] = $args['ad_unit_type'];
		} else {
			$selectedArgs['adUnitType'] = 'NATIVE_VIDEO_UNIT';
		}
		
		if(isset($args['language']) && ($args['language'] != '') && ($args['language'] != 'select') && ($args['language'] != 'undefined')) {
			$selectedArgs['language'] = $args['language'];
		}
		
		if(isset($args['iab_category_child']) && ($args['iab_category_child'] != '') && ($args['iab_category_child'] != 'select') && ($args['iab_category_child'] != 'undefined')) {
			$selectedArgs['iabCategory'] = $args['iab_category_child'];
		}
		
		if(isset($args['font_family']) && ($args['font_family'] != '') && ($args['font_family'] != 'select') && ($args['font_family'] != 'undefined')) {
			$selectedArgs['font'] = $args['font_family'];
		}
		
		if(isset($args['font_size']) && ($args['font_size'] != '') && ($args['font_size'] != 'select') && ($args['font_size'] != 'undefined')) {
			$selectedArgs['fontSize'] = $args['font_size'];
		}
		
		if(isset($args['keywords']) && ($args['keywords'] != '') && ($args['keywords'] != 'undefined')) {
			$selectedArgs['keywords'] = $args['keywords'];
		} else { //Send the keywords field even if it is empty
			$selectedArgs['keywords'] = '';
		}
		
		if(isset($args['native_text_color']) && ($args['native_text_color'] != '') && ($args['native_text_color'] != 'undefined')) {
			$selectedArgs['textColor'] = $args['native_text_color'];
		}
		
		if(isset($args['native_bg_color']) && ($args['native_bg_color'] != '') && ($args['native_bg_color'] != 'undefined')) {
			$selectedArgs['backgroundColor'] = $args['native_bg_color'];
		}
	}
	
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		$viToken = wp_insert_vi_api_get_publisher_token();
		if($viToken !== false) {
			try{
				$response = wp_remote_request(
					$viSettings['jsTagAPI'],
					array(
						'method' => 'POST',
						'timeout' => 15,
						'headers' => array(
							'Content-Type' => 'application/json',
							'Authorization' => $viToken
						),
						'body' => json_encode($selectedArgs)
					)
				);
				if(!is_wp_error($response)) {
					if(400 == wp_remote_retrieve_response_code($response)) {
						$responseBody = json_decode($response['body']);
						if((json_last_error() == JSON_ERROR_NONE) && ($responseBody->status == 'error')) {
							return array(
								'status' => 'error',
								'errorCode' => 'WIVI108',
								'message' => $responseBody->error->description,
							);
						} else {
							return array(
								'status' => 'error',
								'errorCode' => 'WIVI107',
								'message' => 'Response JSON error, Please try again later!',
							);
						}
					} else if(201 == wp_remote_retrieve_response_code($response)) {
						$responseBody = json_decode($response['body']);
						if((json_last_error() == JSON_ERROR_NONE) && ($responseBody->status == 'ok')) {
							delete_transient('wp_insert_vi_api_jstag');
							set_transient('wp_insert_vi_api_jstag', $responseBody->data, YEAR_IN_SECONDS);
							return $responseBody->data;
						} else {
							return array(
								'status' => 'error',
								'errorCode' => 'WIVI106',
								'message' => 'Response JSON error!',
							);
						}
					} else {
						return array(
							'status' => 'error',
							'errorCode' => 'WIVI105',
							'message' => 'Unknown response code',
						);
					}
				}
			} catch(Exception $ex) {
				return array(
					'status' => 'error',
					'errorCode' => 'WIVI103',
					'message' => 'Exception during API communication',
				);
			}
		} else {
			return array(
				'status' => 'error',
				'errorCode' => 'WIVI102',
				'message' => 'Authorization Token is Missing',
			);
		}
	} else {
		return array(
			'status' => 'error',
			'errorCode' => 'WIVI101',
			'message' => 'API is unreachable',
		);
	}
}

function wp_insert_vi_api_get_vi_code($settingsKey = '') {
	$jsTag = get_transient('wp_insert_vi_api_jstag');
	if(($jsTag === false) || ($jsTag == '') || is_array($jsTag)) {
		if($settingsKey != '') {
			$vicodeSettings = get_option($settingsKey);
			$jsTag = wp_insert_vi_api_set_vi_code($vicodeSettings);
		} else {
			$jsTag = wp_insert_vi_api_set_vi_code();
		}
		if(($jsTag === false) || ($jsTag == '') || is_array($jsTag)) {
			return false;
		}		
	}
	return '<script type="text/javascript">'.$jsTag.'</script>';
}

function wp_insert_vi_api_is_eu() {
	$userIp = $_SERVER["REMOTE_ADDR"];
	//$userIp = '185.216.33.82';
	$isEU = get_transient('wp_insert_vi_api_is_eu_'.$userIp);
	if($isEU === false) {
		try{
			$response = wp_remote_get(
				'http://gdpr-check.net/gdpr/is-eu?ip='.$userIp,
				array('timeout' => 15)
			);
			if(!is_wp_error($response)) {
				if(200 == wp_remote_retrieve_response_code($response)) {
					$responseBody = json_decode($response['body']);
					if((json_last_error() == JSON_ERROR_NONE)) {
						if((isset($responseBody->is_eu)) && ($responseBody->is_eu == '1')) {
							delete_transient('wp_insert_vi_api_is_eu_'.$userIp);
							set_transient('wp_insert_vi_api_is_eu_'.$userIp, '1', WEEK_IN_SECONDS);
							return true;
						} else {
							delete_transient('wp_insert_vi_api_is_eu_'.$userIp);
							set_transient('wp_insert_vi_api_is_eu_'.$userIp, '0', WEEK_IN_SECONDS);
							return false;
						}
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
		} catch(Exception $ex) {
			return false;
		}
	} else {
		if($isEU == '1') {
			return true;
		} else {
			return false;
		}
		
	}
}

function wp_insert_vi_api_get_vendor_list_version() {
	$viSettings = wp_insert_vi_api_get_settings();
	if(($viSettings != false) && is_array($viSettings)) {
		return $viSettings['vendorListVersion'];
	}
	return false;
}
?>