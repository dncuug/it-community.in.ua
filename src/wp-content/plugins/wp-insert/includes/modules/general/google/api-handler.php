<?php
function wp_insert_google_api_get_auth_url($clientId) {
	return 'https://accounts.google.com/o/oauth2/v2/auth?scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fadsense.readonly&access_type=offline&include_granted_scopes=true&redirect_uri=urn:ietf:wg:oauth:2.0:oob&response_type=code&client_id='.$clientId;
}

function wp_insert_google_api_get_authentication_data() {
	$authenticationData = get_option('wp_insert_google_api_authentication_data', true);
	if(isset($authenticationData) && (is_array($authenticationData)) && isset($authenticationData['clientId']) && ($authenticationData['clientId'] != '') && isset($authenticationData['clientSecret']) && ($authenticationData['clientSecret'] != '')) {
		return array(
			'clientId' => $authenticationData['clientId'],
			'clientSecret' => $authenticationData['clientSecret']
		);
	}
	return false;
}

function wp_insert_google_api_set_access_token($clientId, $clientSecret, $authCode) {
	try{
		$response = wp_remote_post(
			'https://www.googleapis.com/oauth2/v4/token',
			array(
				'timeout' => 15,
				'headers' => array(
					'Content-Type' => 'application/x-www-form-urlencoded'
				),
				'body' => array(
					'code' => $authCode,
					'client_id' => $clientId,
					'client_secret' => $clientSecret,
					'redirect_uri' => 'urn:ietf:wg:oauth:2.0:oob',
					'grant_type' => 'authorization_code',
				)
			)
		);
		if(!is_wp_error($response)) {
			if(200 == wp_remote_retrieve_response_code($response)) {
				$responseBody = json_decode($response['body']);
				if(json_last_error() == JSON_ERROR_NONE) {
					$authenticationData = array(
						'clientId' => $clientId,
						'clientSecret' => $clientSecret,
						'refreshToken' => $responseBody->refresh_token
					);
					update_option('wp_insert_google_api_authentication_data', $authenticationData);
					delete_transient('wp_insert_google_api_access_token');
					set_transient('wp_insert_vi_api_access_token', $responseBody->access_token, MINUTE_IN_SECONDS * 50);	
					return true;
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
}

function wp_insert_google_api_get_access_token() {
	$accessToken = get_transient('wp_insert_google_api_access_token');
	if(($accessToken === false) || ($accessToken == '')) {
		$authenticationData = get_option('wp_insert_google_api_authentication_data', true);
		if(isset($authenticationData) && (is_array($authenticationData)) && isset($authenticationData['clientId']) && ($authenticationData['clientId'] != '') && isset($authenticationData['clientSecret']) && ($authenticationData['clientSecret'] != '') && isset($authenticationData['refreshToken']) && ($authenticationData['refreshToken'] != '')) {
			try {
				$response = wp_remote_post(
					'https://www.googleapis.com/oauth2/v4/token',
					array(
						'timeout' => 15,
						'headers' => array(
							'Content-Type' => 'application/x-www-form-urlencoded'
						),
						'body' => array(
							'client_id' => $authenticationData['clientId'],
							'client_secret' => $authenticationData['clientSecret'],
							'refresh_token' => $authenticationData['refreshToken'],
							'grant_type' => 'refresh_token',
						)
					)
				);
				if(!is_wp_error($response)) {
					if(200 == wp_remote_retrieve_response_code($response)) {
						$responseBody = json_decode($response['body']);
						if(json_last_error() == JSON_ERROR_NONE) {
							delete_transient('wp_insert_google_api_access_token');
							set_transient('wp_insert_vi_api_access_token', $responseBody->access_token, MINUTE_IN_SECONDS * 50);	
							return $responseBody->access_token;
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
	}
	return $accessToken;
}

function wp_insert_google_api_revoke_access_token($accessToken = '') {
	if($accessToken == '') {
		$accessToken = wp_insert_google_api_get_access_token();
	}
	if($accessToken != false) {
		try {
			$response = wp_remote_get(
				'https://accounts.google.com/o/oauth2/revoke?token='.$accessToken,
				array(
					'timeout' => 15,
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded'
					),
					
				)
			);
			if(!is_wp_error($response)) {
				if(200 == wp_remote_retrieve_response_code($response)) {
					delete_transient('wp_insert_google_api_access_token');
					$authenticationData = get_option('wp_insert_google_api_authentication_data', true);
					$authenticationData['refreshToken'] = '';
					update_option('wp_insert_google_api_authentication_data', $authenticationData);
					return true;
				} else {
					return false;
				}
			}
		} catch(Exception $ex) {
			return false;
		}
	}
	return false;	
}

function wp_insert_google_api_get_accounts($accessToken = '') {
	if($accessToken == '') {
		$accessToken = wp_insert_google_api_get_access_token();
	}
	if($accessToken != false) {
		try {
			$response = wp_remote_get(
				'https://www.googleapis.com/adsense/v1.4/accounts',
				array(
					'timeout' => 15,
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
						'Authorization' => 'Bearer '.$accessToken
					),
					
				)
			);
			if(!is_wp_error($response)) {
				if(200 == wp_remote_retrieve_response_code($response)) {
					$responseBody = json_decode($response['body']);
					if(json_last_error() == JSON_ERROR_NONE) {
						$accounts = array();
						if(isset($responseBody->items) && (count($responseBody->items) > 0)) {
							foreach($responseBody->items as $account) {
								$accounts[] = array('name' => $account->name, 'id' => $account->id);
							}
						}
						if(count($accounts) > 0) {
							return $accounts;
						}
						return false;
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
	}
	return false;
}

function wp_insert_google_api_get_adclients($accountID, $accessToken = '') {
	if($accessToken == '') {
		$accessToken = wp_insert_google_api_get_access_token();
	}
	if($accessToken != false) {
		try {
			$response = wp_remote_get(
				'https://www.googleapis.com/adsense/v1.4/accounts/'.$accountID.'/adclients',
				array(
					'timeout' => 15,
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
						'Authorization' => 'Bearer '.$accessToken
					),
					
				)
			);
			if(!is_wp_error($response)) {
				if(200 == wp_remote_retrieve_response_code($response)) {
					$responseBody = json_decode($response['body']);
					if(json_last_error() == JSON_ERROR_NONE) {
						$adClients = array();
						if(isset($responseBody->items) && (count($responseBody->items) > 0)) {
							foreach($responseBody->items as $adClient) {
								$adClients[] = array('productCode' => $adClient->productCode, 'id' => $adClient->id);
							}
						}
						if(count($adClients) > 0) {
							return $adClients;
						}
						return false;
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
	}
	return false;
}

function wp_insert_google_api_get_ad_units($refresh = false, $accounts = '', $adClients = '', $accessToken = '') {
	$adUnits = false;
	if($refresh == false) {
		$adUnits = get_transient('wp_insert_google_api_ad_units');
	}	
	if(($adUnits === false) || ($adUnits == '')) {
		$adUnits = array();
		if($accessToken == '') {
			$accessToken = wp_insert_google_api_get_access_token();
		}
		if($accessToken != false) {
			if($accounts == '') {
				$accounts = wp_insert_google_api_get_accounts($accessToken);
				if($accounts == false || (!is_array($accounts))) {
					return false;
				}
			}
			foreach($accounts as $account) {
				if($adClients == '') {
					$adClients = wp_insert_google_api_get_adclients($account['id'], $accessToken);
					if(($adClients == false) || (!is_array($adClients))) {
						return false;
					}
				}
				try {
					foreach($adClients as $adClient) {
						$response = wp_remote_get(
							'https://www.googleapis.com/adsense/v1.4/accounts/'.$account['id'].'/adclients/'.$adClient['id'].'/adunits',
							array(
								'timeout' => 15,
								'headers' => array(
									'Content-Type' => 'application/x-www-form-urlencoded',
									'Authorization' => 'Bearer '.$accessToken
								),
								
							)
						);
						if(!is_wp_error($response)) {
							if(200 == wp_remote_retrieve_response_code($response)) {
								$responseBody = json_decode($response['body']);
								if(json_last_error() == JSON_ERROR_NONE) {
									if(isset($responseBody->items) && (count($responseBody->items) > 0)) {
										foreach($responseBody->items as $adUnit) {
											$adUnits[] = array(
												'accountID' => $account['id'],
												'code' => $adUnit->code,
												'id' => $adUnit->id,
												'kind' => $adUnit->kind,
												'name' => $adUnit->name,
												'status' => $adUnit->status
											);
										}
									}
								}
							}
						}
					}
				} catch(Exception $ex) {
					return false;
				}
			}
			if(count($adUnits) > 0) {
				delete_transient('wp_insert_google_api_ad_units');
				set_transient('wp_insert_google_api_ad_units', $adUnits, WEEK_IN_SECONDS);
				return $adUnits;
			} else {
				return false;
			}
		}
		return false;
	}
	return $adUnits;
}

function wp_insert_google_api_get_revenue_data($accountID, $startDate, $endDate, $accessToken = '') {
	if($accessToken == '') {
		$accessToken = wp_insert_google_api_get_access_token();
	}
	if($accessToken != false) {
		try {
			$response = wp_remote_get(
				'https://www.googleapis.com/adsense/v1.4/accounts/'.$accountID.'/reports?dimension=DATE&metric=EARNINGS&startDate='.$startDate.'&endDate='.$endDate,
				array(
					'timeout' => 15,
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
						'Authorization' => 'Bearer '.$accessToken
					),
					
				)
			);
			if(!is_wp_error($response)) {
				if(200 == wp_remote_retrieve_response_code($response)) {
					$responseBody = json_decode($response['body']);
					if(json_last_error() == JSON_ERROR_NONE) {
						if(isset($responseBody->totals[1]) && is_numeric($responseBody->totals[1]) && isset($responseBody->headers) && isset($responseBody->headers[1]->currency) && isset($responseBody->rows) && (count($responseBody->rows) > 0)) {
							$report = array();
							foreach($responseBody->rows as $row) {
								$report[] = array(
									'date' => $row[0],
									'revenue' => floatval($row[1])
								);
							}
							
							return array(
								'revenue' => array(
									'revenue' => floatval($responseBody->totals[1]),
									'currency' => $responseBody->headers[1]->currency
								),
								'report' => $report
							);
						}
						return false;
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
	}
	return false;
}

function wp_insert_google_api_get_adunit_revenue_data($adUnitID, $accountID, $startDate, $endDate, $accessToken = '') {
	if($accessToken == '') {
		$accessToken = wp_insert_google_api_get_access_token();
	}
	if($accessToken != false) {
		try {
			$response = wp_remote_get(
				'https://www.googleapis.com/adsense/v1.4/accounts/'.$accountID.'/reports?dimension=AD_UNIT_ID&dimension=DATE&metric=EARNINGS&metric=IMPRESSIONS&metric=CLICKS&startDate='.$startDate.'&endDate='.$endDate,
				array(
					'timeout' => 15,
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
						'Authorization' => 'Bearer '.$accessToken
					),
					
				)
			);
			if(!is_wp_error($response)) {
				if(200 == wp_remote_retrieve_response_code($response)) {
					$responseBody = json_decode($response['body']);
					if(json_last_error() == JSON_ERROR_NONE) {
						if(isset($responseBody->headers) && isset($responseBody->headers[2]->currency) && isset($responseBody->rows) && (count($responseBody->rows) > 0)) {
							$report = array();
							foreach($responseBody->rows as $row) {
								if($row[0] == $adUnitID) {
									$report[] = array(
										'date' => $row[1],
										'earnings' => floatval($row[2]),
										'impressions' => $row[3],
										'clicks' => $row[4]
									);
								}
							}							
							return array(
								'currency' => $responseBody->headers[2]->currency,
								'report' => $report
							);
						}
						return false;
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
	}
	return false;
}
?>