jQuery(document).ready(function($){

	$(".tab_block").hide();
	$(".tabs ul li:first").addClass("active").show();	
	$(".tab_block:first").show();
	
	$(".tabs ul li").click(function() {
		$(".tabs ul li").removeClass("active");
		$(this).addClass("active");
		$(".tab_block").hide();
		var activeTab = $(this).find("a").attr("href");
		$(activeTab).fadeIn();
		return false;
	});	
			
	jQuery('#wt_bg_color_selector').ColorPicker({													
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_bg_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_bg_color').val('#'+hex);
		}
	});
		
	jQuery('#wt_text_color_selector').ColorPicker({													
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_text_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_text_color').val('#'+hex);
		}
	});
											
	jQuery('#wt_links_color_selector').ColorPicker({													
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_links_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_links_color').val('#'+hex);
		}
	});
	
	jQuery('#wt_links_hover_color_selector').ColorPicker({													
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_links_hover_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_links_hover_color').val('#'+hex);
		}
	});	
	
	jQuery('#wt_primary_color_selector').ColorPicker({													
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_primary_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_primary_color').val('#'+hex);
		}
	});

	jQuery('#wt_second_color_selector').ColorPicker({													
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_second_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_second_color').val('#'+hex);
		}
	});	

	jQuery('#wt_color1_color_selector').ColorPicker({								
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_color1_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_color1').val('#'+hex);
		}
	});	
	
	jQuery('#wt_color2_color_selector').ColorPicker({								
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_color2_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_color2').val('#'+hex);
		}
	});	
	
	jQuery('#wt_color3_color_selector').ColorPicker({								
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_color3_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_color3').val('#'+hex);
		}
	});	
	
	jQuery('#wt_color4_color_selector').ColorPicker({								
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_color4_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_color4').val('#'+hex);
		}
	});	
	
	jQuery('#wt_color5_color_selector').ColorPicker({								
		onChange: function (hsb, hex, rgb) {
				jQuery('#wt_color5_color_selector div').css('backgroundColor', '#' + hex);
				jQuery('#wt_color5').val('#'+hex);
		}
	});	
											
});

jQuery(document).ready(function ($) {
	setTimeout(function () {
		$(".fade").fadeOut("slow", function () {
			$(".fade").remove();
		});
	}, 2000);
});

jQuery(document).ready(function() {
	jQuery("#wt-bg-default-pattern input:checked").parent().addClass("selected");
	jQuery("#wt-bg-default-pattern .checkbox-select").click(
		function(event) {
			event.preventDefault();
			jQuery("#wt-bg-default-pattern li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");			 
		}
	);		
});