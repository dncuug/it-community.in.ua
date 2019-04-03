jQuery(document).ready(function() {	
	wp_insert_vi_signup_handler();	
	wp_insert_vi_login_handler();
	wp_insert_vi_logout_handler();
	wp_insert_vi_customize_adcode();
	wp_insert_vi_chart_draw()
});

function wp_insert_vi_signup_handler() {
	wp_insert_click_handler(
		'wp_insert_vi_signup',
		'video intelligence: Signup',
		'870',
		'554',
		function() { },
		function() { },
		function() { }
	);
}

function wp_insert_vi_login_handler() {
	wp_insert_click_handler(
		'wp_insert_vi_login',
		'video intelligence: Login',
		'540',
		'540',
		function() {
			jQuery('.ui-dialog-buttonset').find('button').first().unbind('click').click(function() {
				if((jQuery('#wp_insert_vi_login_username').val() != '') && (jQuery('#wp_insert_vi_login_password').val() != '')) {
					jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
					jQuery('.ui-dialog-buttonset').find('button').last().button('disable');
					jQuery('.ui-dialog-titlebar').find('button').last().button('disable');
					var wp_insert_vi_login_username = jQuery('#wp_insert_vi_login_username').val();
					var wp_insert_vi_login_password = jQuery('#wp_insert_vi_login_password').val();
					jQuery('.ui-dialog-content').html('<div class="wp_insert_ajaxloader"></div>');
					jQuery('.wp_insert_ajaxloader').show();
					jQuery.post(
						jQuery('#wp_insert_admin_ajax').val(), {
							'action': 'wp_insert_vi_login_form_save_action',
							'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
							'wp_insert_vi_login_username': wp_insert_vi_login_username,
							'wp_insert_vi_login_password': wp_insert_vi_login_password,
						}, function(response) {
							if(response.indexOf('###SUCCESS###') !== -1) {
								jQuery.post(
									jQuery('#wp_insert_admin_ajax').val(), {
										'action': 'wp_insert_vi_update_adstxt',
										'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
									}, function(response) {
										if(response.indexOf('###SUCCESS###') !== -1) {
											jQuery('.wrap #wp_insert_title').after(response.replace('###SUCCESS###', ''));
										} else if(response.indexOf('###FAIL###') !== -1) {
											jQuery('.wrap #wp_insert_title').after(response.replace('###FAIL###', ''));
										} else {
										}
									}
								);
								jQuery('.vi-card .plugin-card-bottom, .vi-card .plugin-card-top-content').animate({'opacity': 0}, 1000);
								jQuery('.vi-card').html(response.replace('###SUCCESS###', ''));
								wp_insert_vi_logout_handler();
								wp_insert_vi_customize_adcode();
								wp_insert_vi_chart_draw();
								jQuery(window).resize();
								jQuery('.vi-card .plugin-card-bottom, .vi-card .plugin-card-top-content').animate({'opacity': 1}, 1000);
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
					jQuery('#wp_insert_vi_login_username').css('border-color', '#dddddd');
					jQuery('#wp_insert_vi_login_password').css('border-color', '#dddddd');
					if(jQuery('#wp_insert_vi_login_username').val() == '') {
						jQuery('#wp_insert_vi_login_username').css('border-color', '#ff0000');
					}
					if(jQuery('#wp_insert_vi_login_password').val() == '') {
						jQuery('#wp_insert_vi_login_password').css('border-color', '#ff0000');
					}
				}
			});
		},
		function() { },
		function() { }
	);
}

function wp_insert_vi_logout_handler() {
	jQuery('#wp_insert_vi_logout').click(function() {
		jQuery.post(
			jQuery('#wp_insert_admin_ajax').val(), {
				'action': 'wp_insert_vi_logout_action',
				'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
			}, function(response) {
				if(response.indexOf('###SUCCESS###') !== -1) {
					jQuery('.vi-card').html(response.replace('###SUCCESS###', ''));
					wp_insert_vi_signup_handler();	
					wp_insert_vi_login_handler();
					jQuery(window).resize();
				}
				jQuery('.vi-card .plugin-card-bottom, .vi-card .plugin-card-top-content').animate({'opacity': 1}, 1000);
			}
		);
		jQuery('.vi-card .plugin-card-bottom, .vi-card .plugin-card-top-content').animate({'opacity': 0}, 1000);
		
	});
}

function wp_insert_vi_customize_adcode() {
	wp_insert_click_handler(
		'wp_insert_vi_customize_adcode',
		'video intelligence: Customize vi Code',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('#wp_insert_vi_code_settings_keywords').attr('maxlength', '200');
			jQuery('#wp_insert_vi_code_settings_optional_1').attr('maxlength', '200');
			jQuery('#wp_insert_vi_code_settings_optional_2').attr('maxlength', '200');
			jQuery('#wp_insert_vi_code_settings_optional_3').attr('maxlength', '200');
			jQuery('.ui-dialog-buttonset').find('button').first().unbind('click').click(function() {
				var keywordsRegex = /[ ,a-zA-Z0-9-’'‘\u00C6\u00D0\u018E\u018F\u0190\u0194\u0132\u014A\u0152\u1E9E\u00DE\u01F7\u021C\u00E6\u00F0\u01DD\u0259\u025B\u0263\u0133\u014B\u0153\u0138\u017F\u00DF\u00FE\u01BF\u021D\u0104\u0181\u00C7\u0110\u018A\u0118\u0126\u012E\u0198\u0141\u00D8\u01A0\u015E\u0218\u0162\u021A\u0166\u0172\u01AFY\u0328\u01B3\u0105\u0253\u00E7\u0111\u0257\u0119\u0127\u012F\u0199\u0142\u00F8\u01A1\u015F\u0219\u0163\u021B\u0167\u0173\u01B0y\u0328\u01B4\u00C1\u00C0\u00C2\u00C4\u01CD\u0102\u0100\u00C3\u00C5\u01FA\u0104\u00C6\u01FC\u01E2\u0181\u0106\u010A\u0108\u010C\u00C7\u010E\u1E0C\u0110\u018A\u00D0\u00C9\u00C8\u0116\u00CA\u00CB\u011A\u0114\u0112\u0118\u1EB8\u018E\u018F\u0190\u0120\u011C\u01E6\u011E\u0122\u0194\u00E1\u00E0\u00E2\u00E4\u01CE\u0103\u0101\u00E3\u00E5\u01FB\u0105\u00E6\u01FD\u01E3\u0253\u0107\u010B\u0109\u010D\u00E7\u010F\u1E0D\u0111\u0257\u00F0\u00E9\u00E8\u0117\u00EA\u00EB\u011B\u0115\u0113\u0119\u1EB9\u01DD\u0259\u025B\u0121\u011D\u01E7\u011F\u0123\u0263\u0124\u1E24\u0126I\u00CD\u00CC\u0130\u00CE\u00CF\u01CF\u012C\u012A\u0128\u012E\u1ECA\u0132\u0134\u0136\u0198\u0139\u013B\u0141\u013D\u013F\u02BCN\u0143N\u0308\u0147\u00D1\u0145\u014A\u00D3\u00D2\u00D4\u00D6\u01D1\u014E\u014C\u00D5\u0150\u1ECC\u00D8\u01FE\u01A0\u0152\u0125\u1E25\u0127\u0131\u00ED\u00ECi\u00EE\u00EF\u01D0\u012D\u012B\u0129\u012F\u1ECB\u0133\u0135\u0137\u0199\u0138\u013A\u013C\u0142\u013E\u0140\u0149\u0144n\u0308\u0148\u00F1\u0146\u014B\u00F3\u00F2\u00F4\u00F6\u01D2\u014F\u014D\u00F5\u0151\u1ECD\u00F8\u01FF\u01A1\u0153\u0154\u0158\u0156\u015A\u015C\u0160\u015E\u0218\u1E62\u1E9E\u0164\u0162\u1E6C\u0166\u00DE\u00DA\u00D9\u00DB\u00DC\u01D3\u016C\u016A\u0168\u0170\u016E\u0172\u1EE4\u01AF\u1E82\u1E80\u0174\u1E84\u01F7\u00DD\u1EF2\u0176\u0178\u0232\u1EF8\u01B3\u0179\u017B\u017D\u1E92\u0155\u0159\u0157\u017F\u015B\u015D\u0161\u015F\u0219\u1E63\u00DF\u0165\u0163\u1E6D\u0167\u00FE\u00FA\u00F9\u00FB\u00FC\u01D4\u016D\u016B\u0169\u0171\u016F\u0173\u1EE5\u01B0\u1E83\u1E81\u0175\u1E85\u01BF\u00FD\u1EF3\u0177\u00FF\u0233\u1EF9\u01B4\u017A\u017C\u017E\u1E93]/g;
				if(
				(jQuery('#wp_insert_vi_code_settings_ad_unit_type').val() != 'select') && 
				(jQuery('#wp_insert_vi_code_settings_iab_category_child').val() != 'select') && 
				(jQuery('#wp_insert_vi_code_settings_language').val() != 'select') && 
				((jQuery('#wp_insert_vi_code_settings_keywords').val() == '') || ((jQuery(jQuery('#wp_insert_vi_code_settings_keywords').val().match(/./g)).not(jQuery('#wp_insert_vi_code_settings_keywords').val().match(keywordsRegex)).get().length == 0) && (jQuery('#wp_insert_vi_code_settings_keywords').val().length < 200)))
				) {
					jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
					jQuery('.ui-dialog-buttonset').find('button').last().button('disable');
					jQuery('.ui-dialog-titlebar').find('button').last().button('disable');
					var wp_insert_vi_code_settings_ad_unit_type = jQuery('#wp_insert_vi_code_settings_ad_unit_type').val();
					var wp_insert_vi_code_settings_keywords = jQuery('#wp_insert_vi_code_settings_keywords').val();
					var wp_insert_vi_code_settings_iab_category_parent = jQuery('#wp_insert_vi_code_settings_iab_category_parent').val();
					var wp_insert_vi_code_settings_iab_category_child = jQuery('#wp_insert_vi_code_settings_iab_category_child').val();
					var wp_insert_vi_code_settings_language = jQuery('#wp_insert_vi_code_settings_language').val();
					var wp_insert_vi_code_settings_native_bg_color = jQuery('#wp_insert_vi_code_settings_native_bg_color').val();
					var wp_insert_vi_code_settings_native_text_color = jQuery('#wp_insert_vi_code_settings_native_text_color').val();
					var wp_insert_vi_code_settings_font_family = jQuery('#wp_insert_vi_code_settings_font_family').val();
					var wp_insert_vi_code_settings_font_size = jQuery('#wp_insert_vi_code_settings_font_size').val();
					var wp_insert_vi_code_settings_show_gdpr_authorization = jQuery('#wp_insert_vi_code_settings_show_gdpr_authorization').prop('checked');
					jQuery('.ui-dialog-content').html('<div class="wp_insert_ajaxloader"></div>');
					jQuery('.wp_insert_ajaxloader').show();
					jQuery.post(
						jQuery('#wp_insert_admin_ajax').val(), {
							'action': 'wp_insert_vi_customize_adcode_form_save_action',
							'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
							'wp_insert_vi_code_settings_ad_unit_type': wp_insert_vi_code_settings_ad_unit_type,
							'wp_insert_vi_code_settings_keywords': wp_insert_vi_code_settings_keywords,
							'wp_insert_vi_code_settings_iab_category_parent': wp_insert_vi_code_settings_iab_category_parent,
							'wp_insert_vi_code_settings_iab_category_child': wp_insert_vi_code_settings_iab_category_child,
							'wp_insert_vi_code_settings_language': wp_insert_vi_code_settings_language,
							'wp_insert_vi_code_settings_native_bg_color': wp_insert_vi_code_settings_native_bg_color,
							'wp_insert_vi_code_settings_native_text_color': wp_insert_vi_code_settings_native_text_color,
							'wp_insert_vi_code_settings_font_family': wp_insert_vi_code_settings_font_family,
							'wp_insert_vi_code_settings_font_size': wp_insert_vi_code_settings_font_size,
							'wp_insert_vi_code_settings_show_gdpr_authorization': wp_insert_vi_code_settings_show_gdpr_authorization,
						}, function(response) {
							if(response.indexOf('###SUCCESS###') !== -1) {
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable').click();
							} else {
								jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
								jQuery('.ui-dialog-buttonset').find('button').last().button('enable');
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable');
								jQuery('.ui-dialog-content').html(response.replace('###FAIL###', ''));								
							}
						}						
					);					
				} else {
					jQuery('#wp_insert_vi_customize_adcode_keywords_required_error').hide();
					jQuery('#wp_insert_vi_customize_adcode_keywords_error').hide();
					jQuery('#wp_insert_vi_customize_adcode_required_error').hide();
					jQuery('#wp_insert_vi_code_settings_ad_unit_type').css('border-color', '#dddddd');
					jQuery('#wp_insert_vi_code_settings_iab_category_parent').css('border-color', '#dddddd');
					jQuery('#wp_insert_vi_code_settings_iab_category_child').css('border-color', '#dddddd');
					jQuery('#wp_insert_vi_code_settings_language').css('border-color', '#dddddd');
					jQuery('#wp_insert_vi_code_settings_keywords').css('border-color', '#dddddd');
					var wp_insert_vi_customize_adcode_keywords_error = false;
					var wp_insert_vi_customize_adcode_required_error = false;
					if(jQuery('#wp_insert_vi_code_settings_ad_unit_type').val() == 'select') {						
						jQuery('#wp_insert_vi_code_settings_ad_unit_type').css('border-color', '#ff0000');
						wp_insert_vi_customize_adcode_required_error = true;
					}
					if(jQuery('#wp_insert_vi_code_settings_iab_category_parent').val() == 'select') {
						jQuery('#wp_insert_vi_code_settings_iab_category_parent').css('border-color', '#ff0000');
						wp_insert_vi_customize_adcode_required_error = true;
					}
					if(jQuery('#wp_insert_vi_code_settings_iab_category_child').val() == 'select') {
						jQuery('#wp_insert_vi_code_settings_iab_category_child').css('border-color', '#ff0000');
						wp_insert_vi_customize_adcode_required_error = true;
					}
					if(jQuery('#wp_insert_vi_code_settings_language').val() == 'select') {
						jQuery('#wp_insert_vi_code_settings_language').css('border-color', '#ff0000');
						wp_insert_vi_customize_adcode_required_error = true;
					}
					if(jQuery('#wp_insert_vi_code_settings_keywords').val() != '') {
						if(jQuery('#wp_insert_vi_code_settings_keywords').val().length > 200) {
							jQuery('#wp_insert_vi_code_settings_keywords').css('border-color', '#ff0000');
							wp_insert_vi_customize_adcode_keywords_error = true;
						}
						if(jQuery(jQuery('#wp_insert_vi_code_settings_keywords').val().match(/./g)).not(jQuery('#wp_insert_vi_code_settings_keywords').val().match(keywordsRegex)).get().length != 0) {
							jQuery('#wp_insert_vi_code_settings_keywords').css('border-color', '#ff0000');
							wp_insert_vi_customize_adcode_keywords_error = true;
						}
					}
					if(wp_insert_vi_customize_adcode_keywords_error && wp_insert_vi_customize_adcode_required_error) {
						jQuery('#wp_insert_vi_customize_adcode_keywords_required_error').show();
					} else if(wp_insert_vi_customize_adcode_keywords_error) {
						jQuery('#wp_insert_vi_customize_adcode_keywords_error').show();
					} else if(wp_insert_vi_customize_adcode_required_error) {
						jQuery('#wp_insert_vi_customize_adcode_required_error').show();
					} else {}
				}
			});
		},
		function() { },
		function() { }
	);
}

function wp_insert_vi_code_iab_category_parent_change() {
	jQuery('#wp_insert_vi_code_settings_iab_category_parent').change(function() {
		var wp_insert_vi_code_iab_category = jQuery(this).val();
		jQuery('#wp_insert_vi_code_settings_iab_category_child option').prop('disabled', true).hide();
		jQuery('#wp_insert_vi_code_settings_iab_category_child option').each(function() {
			if((jQuery(this).attr('data-parent') == wp_insert_vi_code_iab_category) || (jQuery(this).val() == 'select')) {
				jQuery(this).prop('disabled', false).show();
			}
		});
		if(jQuery('#wp_insert_vi_code_settings_iab_category_child option:selected').prop('disabled') != false) {
			jQuery('#wp_insert_vi_code_settings_iab_category_child').val('select');
		}
	});
	jQuery('#wp_insert_vi_code_settings_iab_category_parent').change();
}

function wp_insert_vi_chart_draw() {
	if(jQuery('#wp_insert_vi_earnings_wrapper').length) {
		jQuery.post(
			jQuery('#wp_insert_admin_ajax').val(), {
				'action': 'wp_insert_vi_get_chart',
				'wp_insert_nonce': jQuery('#wp_insert_nonce').val(),
			}, function(response) {
				if(response.indexOf('###SUCCESS###') !== -1) {
					jQuery('#wp_insert_vi_earnings_wrapper').html(response.replace('###SUCCESS###', ''));
					if(jQuery('#wp_insert_vi_chart_data').length) {
						var ctx = document.getElementById("myChart");
						var wp_insert_vi_chart = new Chart(jQuery('#wp_insert_vi_chart'), {
							type: 'line',
							data: {
								datasets: [{
									data: JSON.parse(jQuery('#wp_insert_vi_chart_data').val()),
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
					jQuery('#wp_insert_vi_earnings_wrapper').parent().html(response);
				}
				jQuery(window).resize();
			}
		);
	}
}