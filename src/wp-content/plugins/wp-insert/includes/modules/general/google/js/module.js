jQuery(document).ready(function() {	
	wp_insert_google_login_handler();
	wp_insert_google_logout_handler();
	wp_insert_google_chart_draw();
	wp_insert_google_get_ad_units();
});

function wp_insert_google_login_handler() {
	wp_insert_click_handler(
		'wp_insert_google_login',
		'Adsense by Google: Login / Authorize',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('.ui-dialog-buttonset').find('button').first().unbind('click').click(function() {
				if((jQuery('#wp_insert_google_login_client_id').val() != '') && (jQuery('#wp_insert_google_login_client_secret').val() != '') && (jQuery('#wp_insert_google_login_auth_code').val() != '')) {
					jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
					jQuery('.ui-dialog-buttonset').find('button').last().button('disable');
					jQuery('.ui-dialog-titlebar').find('button').last().button('disable');
					var wp_insert_google_login_client_id = jQuery.trim(jQuery('#wp_insert_google_login_client_id').val());
					var wp_insert_google_login_client_secret = jQuery.trim(jQuery('#wp_insert_google_login_client_secret').val());
					var wp_insert_google_login_auth_code = jQuery.trim(jQuery('#wp_insert_google_login_auth_code').val());
					jQuery('.ui-dialog-content').html('<div class="wp_insert_ajaxloader"></div>');
					jQuery('.wp_insert_ajaxloader').show();
					jQuery.post(
						jQuery('#wp_insert_admin_ajax').val(), {
							'action': 'wp_insert_google_login_form_save_action',
							'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
							'wp_insert_google_login_client_id': wp_insert_google_login_client_id,
							'wp_insert_google_login_client_secret': wp_insert_google_login_client_secret,
							'wp_insert_google_login_auth_code': wp_insert_google_login_auth_code,
						}, function(response) {
							if(response.indexOf('###SUCCESS###') !== -1) {
								jQuery('.google-card .plugin-card-bottom, .google-card .plugin-card-top-content').animate({'opacity': 0}, 1000);
								jQuery('.google-card').html(response.replace('###SUCCESS###', ''));
								wp_insert_google_chart_draw();
								wp_insert_google_get_ad_units();
								wp_insert_google_logout_handler();
								jQuery(window).resize();
								jQuery('.google-card .plugin-card-bottom, .google-card .plugin-card-top-content').animate({'opacity': 1}, 1000);
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable').click();								
							} else {
								jQuery('.ui-dialog-buttonset').find('button').first().button('enable');
								jQuery('.ui-dialog-buttonset').find('button').last().button('enable');
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable');
								jQuery('.ui-dialog-content').html(response);
							}
						}
					);
				} else {
					jQuery('#wp_insert_google_login_client_id').css('border-color', '#dddddd');
					jQuery('#wp_insert_google_login_client_secret').css('border-color', '#dddddd');
					jQuery('#wp_insert_google_login_auth_code').css('border-color', '#dddddd');
					if(jQuery('#wp_insert_google_login_client_id').val() == '') {
						jQuery('#wp_insert_google_login_client_id').css('border-color', '#ff0000');
					}
					if(jQuery('#wp_insert_google_login_client_secret').val() == '') {
						jQuery('#wp_insert_google_login_client_secret').css('border-color', '#ff0000');
					}
					if(jQuery('#wp_insert_google_login_auth_code').val() == '') {
						jQuery('#wp_insert_google_login_auth_code').css('border-color', '#ff0000');
					}
				}
			});
		},
		function() { },
		function() { }
	);
}

function wp_insert_google_login_get_auth_code() {
	var wp_insert_google_login_client_id = jQuery.trim(jQuery('#wp_insert_google_login_client_id').val());
	if(wp_insert_google_login_client_id != '') {
		jQuery('#wp_insert_google_login_client_id').css('border-color', '#dddddd');
		jQuery.post(
			jQuery('#wp_insert_admin_ajax').val(), {
				'action': 'wp_insert_google_login_generate_auth_url',
				'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
				'wp_insert_google_login_client_id': jQuery('#wp_insert_google_login_client_id').val(),
			}, function(response) {
				if(response.indexOf('###SUCCESS###') !== -1) {
					var gauth = window.open(response.replace('###SUCCESS###', ''), 'Authenticate', 'width=480, height=600');
					gauth.focus();
				}
			}
		);
	} else {
		jQuery('#wp_insert_google_login_client_id').css('border-color', '#ff0000');
	}	
}

function wp_insert_google_logout_handler() {
	jQuery('#wp_insert_google_logout').click(function() {
		jQuery.post(
			jQuery('#wp_insert_admin_ajax').val(), {
				'action': 'wp_insert_google_logout_action',
				'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
			}, function(response) {
				if(response.indexOf('###SUCCESS###') !== -1) {
					jQuery('.google-card').html(response.replace('###SUCCESS###', ''));
					wp_insert_google_login_handler();
					jQuery(window).resize();
				}
				jQuery('.google-card .plugin-card-bottom, .google-card .plugin-card-top-content').animate({'opacity': 1}, 1000);
			}
		);
		jQuery('.google-card .plugin-card-bottom, .google-card .plugin-card-top-content').animate({'opacity': 0}, 1000);
		
	});
}

function wp_insert_google_chart_draw() {
	if(jQuery('#wp_insert_google_earnings_wrapper').length) {
		jQuery.post(
			jQuery('#wp_insert_admin_ajax').val(), {
				'action': 'wp_insert_google_get_chart',
				'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
			}, function(response) {
				if(response.indexOf('###SUCCESS###') !== -1) {
					jQuery('#wp_insert_google_earnings_wrapper').html(response.replace('###SUCCESS###', ''));
					if(jQuery('#wp_insert_google_chart_data').length) {
						var ctx = document.getElementById("myChart");
						var wp_insert_google_chart = new Chart(jQuery('#wp_insert_google_chart'), {
							type: 'line',
							data: {
								datasets: [{
									data: JSON.parse(jQuery('#wp_insert_google_chart_data').val()),
									backgroundColor: '#EDF5FB',
									borderColor: '#186EAE',/*E8EBEF*/
									borderWidth: 1
								}]
							},
							options: {
								title: {
									display: false,
									backgroundColor: '#EDF5FB'
								},
								legend: {
									display: false,
								},
								scales: {
									xAxes: [{
										type: "time",
										display: true,
										scaleLabel: {
											display: false
										},
										gridLines: {
											display: false,
											drawTicks: false
										},
										ticks: {
											display: false
										}
									}],
									yAxes: [{
										display: true,
										scaleLabel: {
											display: false
										},
										gridLines: {
											display: true,
											drawTicks: false
										},
										ticks: {
											display: false
										}
									}]
								},
								tooltips: {
									displayColors: false,
									callbacks: {
										label: function(tooltipItem, data) {
											return '$ '+parseFloat(tooltipItem.yLabel).toFixed(2);
										},
										title: function(tooltipItem, data) {
											var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
											var dateParts = tooltipItem[0].xLabel.split('/');
											var date = new Date(dateParts[2], dateParts[0]-1, dateParts[1]);
											return monthNames[date.getMonth()]+' '+date.getDate();
										}
									}
								}
							}
						});
					}
				} else {
					jQuery('#wp_insert_google_earnings_wrapper').parent().html(response);
				}
				jQuery(window).resize();
			}
		);
	}
}

function wp_insert_google_get_ad_units() {
	if(jQuery('#wp_insert_google_ad_units_wrapper').length) {
		jQuery.post(
			jQuery('#wp_insert_admin_ajax').val(), {
				'action': 'wp_insert_google_get_ad_units',
				'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
			}, function(response) {
				if(response.indexOf('###SUCCESS###') !== -1) {
					jQuery('#wp_insert_google_ad_units_wrapper').html(response.replace('###SUCCESS###', ''));
				} else {
					jQuery('#wp_insert_google_ad_units_wrapper').parent().html(response);
				}
				jQuery(window).resize();
			}
		);
	}
}

function wp_insert_google_inactive_ad_units_toggle() {
	if(jQuery('#wp_insert_google_inactive_ad_units').height() == '16') {
		jQuery('#wp_insert_google_inactive_ad_units').addClass('expanded');
		jQuery('#wp_insert_google_inactive_ad_units_button').html('Hide Inactive Ad Units<span class="dashicons dashicons-arrow-up" title="Hide Inactive Ad Units"></span>');
	} else {
		jQuery('#wp_insert_google_inactive_ad_units').removeClass('expanded');
		jQuery('#wp_insert_google_inactive_ad_units_button').html('Show Inactive Ad Units<span class="dashicons dashicons-arrow-down" title="Show Inactive Ad Units"></span>');
	}
	jQuery(window).resize();
}

function wp_insert_google_adunit_stats_handler(adUnitID, adUnitName, accountID) {
	jQuery('<div id="wp_insert_google_active_adunit_stats_dialog"></div>').html('<div class="wp_insert_ajaxloader"></div>').dialog({
		'modal': true,
		'resizable': false,
		'width': jQuery("body").width() * 0.8,
		'maxWidth': jQuery("body").width() * 0.8,
		'maxHeight': jQuery("body").height() * 0.8,
		'title': adUnitName + ': Performance Stats (Last 3 Months)',
		position: { my: 'center', at: 'center', of: window },
		open: function (event, ui) {
			jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
			jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
			jQuery.post(
				jQuery('#wp_insert_admin_ajax').val(), {
					'action': 'wp_insert_google_adunit_get_stats',
					'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
					'wp_insert_google_account_id': accountID,
					'wp_insert_google_adunit_id': adUnitID				
				}, function(response) {
					jQuery('.wp_insert_ajaxloader').hide();
					jQuery('.ui-dialog-content').html(response.replace('###SUCCESS###', ''));
					jQuery('#wp_insert_google_adunit_chart').attr('width', ((jQuery("body").width() * 0.8) - 60));
					jQuery('#wp_insert_google_adunit_chart').attr('height', ((jQuery("body").height() * 0.8) - 150));			
					jQuery('.ui-dialog').css({'position': 'fixed'});
					jQuery('#wp_insert_google_active_adunit_stats_dialog').delay(500).dialog({position: { my: 'center', at: 'center', of: window }});
					
					if(response.indexOf('###SUCCESS###') !== -1) {
						if(jQuery('#wp_insert_google_adunit_chart_data').length) {
							var ctx = document.getElementById("myChart");
							var wp_insert_google_adunit_chart = new Chart(jQuery('#wp_insert_google_adunit_chart'), {
								type: 'line',
								data: {
									datasets: [{
										data: JSON.parse(jQuery('#wp_insert_google_adunit_chart_data').val()),
										backgroundColor: '#EDF5FB',
										borderColor: '#186EAE',
										borderWidth: 1
									}]
								},
								options: {
									title: {
										display: false,
										backgroundColor: '#EDF5FB'
									},
									legend: {
										display: false,
									},
									scales: {
										xAxes: [{
											type: "time",
											display: true,
											scaleLabel: {
												display: false
											},
											gridLines: {
												display: false,
												drawTicks: false
											},
											ticks: {
												display: false
											}
										}],
										yAxes: [{
											display: true,
											scaleLabel: {
												display: false
											},
											gridLines: {
												display: true,
												drawTicks: false
											},
											ticks: {
												display: false
											}
										}]
									},
									tooltips: {
										displayColors: false,
										callbacks: {
											label: function(tooltipItem, data) {
												return '$ '+parseFloat(tooltipItem.yLabel).toFixed(2);
											},
											title: function(tooltipItem, data) {
												var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
												var dateParts = tooltipItem[0].xLabel.split('/');
												var date = new Date(dateParts[2], dateParts[0]-1, dateParts[1]);
												return monthNames[date.getMonth()]+' '+date.getDate();
											}
										}
									}
								}
							});
						}
					}
				}			
			);
		},
		buttons: {
			Cancel: {
				text: 'Close',
				icons: { primary: "ui-icon-cancel" },
				click: function() {
					jQuery(this).dialog('close');
				}
			}
		},
		close: function() {
			jQuery(this).dialog('destroy');
		}
	});		
}