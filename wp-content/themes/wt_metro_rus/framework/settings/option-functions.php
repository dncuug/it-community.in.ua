<?php
/**
 * The Theme Option Functions page
 *
 * This page is implemented using the Settings API.
 * 
 * @package  WellThemes
 * @file     option-functions.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */

 
/**
 * Set custom RSS feed links.
 *
 */
$options = get_option('wt_options');
	
function wt_custom_feed( $output, $feed ) {

	$options = get_option('wt_options');
	$url = $options['wt_rss_url'];	
	
	if ( $url ) {
		$outputarray = array( 'rss' => $url, 'rss2' => $url, 'atom' => $url, 'rdf' => $url, 'comments_rss2' => '' );
		$outputarray[$feed] = $url;
		$output = $outputarray[$feed];
	}
	return $output;
}
add_filter( 'feed_link', 'wt_custom_feed', 1, 2 );

/**
 * Set custom Favicon.
 *
 */
function wt_custom_favicon() {
	$options = get_option('wt_options');
	$favicon_url = $options['wt_favicon'];	
	
    if (!empty($favicon_url)) {
		echo '<link rel="shortcut icon" href="'. $favicon_url. '" />	'. "\n";
	}
}
add_action('wp_head', 'wt_custom_favicon');


/**
 * Set apple touch icon.
 *
 */
function wt_apple_touch() {
	$options = get_option('wt_options');
	$apple_touch = $options['wt_apple_touch'];	
	
    if (!empty($apple_touch)) {
		echo '<link rel="apple-touch-icon" href="'. $apple_touch. '" />	'. "\n";
	}
}
add_action('wp_head', 'wt_apple_touch');

/**
 * Set meta description.
 *
 */
function wt_meta_description() {
    $options = get_option('wt_options');
	$wt_meta_description = $options['wt_meta_description'];
    
	if (!empty($wt_meta_description)) {
		echo '<meta name="description" content=" '. $wt_meta_description .'"  />' . "\n";
	}
}
add_action('wp_head', 'wt_meta_description');


/**
 * Set meta keywords.
 *
 */
function wt_meta_keywords() {
    $options = get_option('wt_options');
	$wt_meta_keywords = $options['wt_meta_keywords'];
    
	if (!empty($wt_meta_keywords)) {
		echo '<meta name="keywords" content=" '. $wt_meta_keywords .'"  />' . "\n";
	}
}
add_action('wp_head', 'wt_meta_keywords');


/**
 * Set Google site verfication code
 *
 */
function wt_google_verification() {
    $options = get_option('wt_options');
	$wt_google_verification = $options['wt_google_verification'];
   
   if (!empty($wt_google_verification)) {
		echo '<meta name="google-site-verification" content="' . $wt_google_verification . '" />' . "\n";
	}
}
add_action('wp_head', 'wt_google_verification');

/**
 * Set Bing site verfication code
 *
 */
function wt_bing_verification() {	
    $options = get_option('wt_options');
	$wt_bing_verification = $options['wt_bing_verification'];	
	
    if (!empty($wt_bing_verification)) {
        echo '<meta name="msvalidate.01" content="' . $wt_bing_verification . '" />' . "\n";
	}
}
add_action('wp_head', 'wt_bing_verification');


/**
 * Add code in the header.
 *
 */
function wt_header_code() {
    $options = get_option('wt_options');
	$wt_header_code = $options['wt_header_code'];
    if (!empty($wt_header_code)) {
        echo $wt_header_code;
	}
}
add_action('wp_head', 'wt_header_code');


/**
 * Add code in the footer.
 *
 */
function wt_footer_code() {
    $options = get_option('wt_options');
	$wt_footer_code = $options['wt_footer_code'];
    if (!empty($wt_footer_code)) {
        echo $wt_footer_code;
	}
}
add_action('wp_footer', 'wt_footer_code');

/**
 * Get Google Fonts
 *
 */ 
function wt_get_google_fonts() {
	include( get_template_directory() . '/framework/settings/google-fonts.php' );
	$google_font_array = json_decode ($google_api_output,true) ;
	$items = $google_font_array['items'];
	
	$fonts_list = array();

	$fontID = 0;
	foreach ($items as $item) {
		$fontID++;
		$variants='';
		$variantCount=0;
		foreach ($item['variants'] as $variant) {
			$variantCount++;
			if ($variantCount>1) { $variants .= '|'; }
			$variants .= $variant;
		}
		$variantText = ' (' . $variantCount . ' Varaints' . ')';
		if ($variantCount <= 1) $variantText = '';
		$fonts_list[ $item['family'] . ':' . $variants ] = $item['family']. $variantText;
	}
	return $fonts_list;
}

function wt_get_font($font_string) {
	if ($font_string) {
		$font_pieces = explode(":", $font_string);			
		$font_name = $font_pieces[0];
	
		return $font_name;
	}
}

function wt_get_rgb_color($color){
			
		if ( $color[0] == '#' ) {
                $color = substr( $color, 1 );
        }
        if ( strlen( $color ) == 6 ) {
                list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return false;
        }
		
		$r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );
        
		
		$rgb =$r.','.$g.','.$b;
		return $rgb;
       /*  $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );
	
	$rgb = $r.','.$g.','.$b;
	return($rgb); */
}

/**
 * Set custom CSS styles
 */ 
function wt_custom_styles(){
	$options = get_option('wt_options');
	
	$wt_custom_style = '';
		
	//text styles
	$text_fontsize = $options['wt_text_fontsize'];
	$text_lineheight = $options['wt_text_lineheight'];
	
	$raw_text_style = $options['wt_text_fontstyle'];
	$formatted_text_style = wt_set_font_style($raw_text_style);
	$wt_text_font_string = $options['wt_text_font_name'];
	$wt_text_color = $options['wt_text_color'];   
	
	if ((!empty ($text_fontsize)) or (!empty ($text_style)) or (!empty ($text_lineheight))  or (!empty ($wt_text_font_string))  or (!empty($wt_bg_style)) or (!empty($wt_text_color)) ){
		$wt_custom_style .= "body{\n" ;
		
		if ( !empty ($text_fontsize) ) {
			$wt_custom_style .= "	font-size: " .$text_fontsize. ";\n";
		}
			
		if ( !empty ($formatted_text_style) ) {
			$wt_custom_style .= $formatted_text_style."\n";
		}
			
		if ( !empty ($text_lineheight) ) {
			$wt_custom_style .= "	line-height: " .$text_lineheight. ";\n";
		}
			
		if (!empty($wt_text_font_string)){
			wt_enqueue_font( $wt_text_font_string ) ;
			$font_name = wt_get_font($wt_text_font_string);
			$wt_custom_style .= "	font-family: " .$font_name. ", sans-serif, serif;\n";
		}
		
		if (!empty($wt_text_color) ){
			$wt_custom_style .= "	color: " .$wt_text_color. ";\n";
		}
		
		if (!empty($wt_bg_style) ){
			$wt_custom_style .= "	background: " .$wt_bg_style. ";\n";
		}			
			
		$wt_custom_style .="}\n\n";
	}
	
	//heading styles
	for ($i = 1; $i < 7; $i++){ 
		$raw_font_style = $options['wt_h'.$i.'_fontstyle'];
		$formatted_font_style = wt_set_font_style($raw_font_style);
				
		$font_size = $options['wt_h'.$i.'_fontsize'];
		$font_style = $formatted_font_style;
		$font_lineheight = $options['wt_h'.$i.'_lineheight'];
		$font_marginbottom = $options['wt_h'.$i.'_marginbottom'];
		
		if ((!empty ($font_size)) or (!empty ($font_style)) or (!empty ($font_lineheight)) or (!empty ($font_marginbottom))){
			$wt_custom_style .= "h".$i."{\n" ;
			if ( !empty ($font_size) ) {
				$wt_custom_style .= "	font-size: " .$font_size. ";\n";
			}
			
			if ( !empty ($font_style) ) {
				$wt_custom_style .= $font_style."\n";
			}
				
			if ( !empty ($font_lineheight) ) {
				$wt_custom_style .= "	line-height: " .$font_lineheight. ";\n";
			}
			
			if ( !empty ($font_marginbottom) ) {
				$wt_custom_style .= "	margin-bottom: " .$font_marginbottom. ";\n";
			}				
				
			$wt_custom_style .="}\n\n";	
		}
	}	
		
	//headings font and color	
	$wt_headings_font_string = $options['wt_headings_font_name'];
			
	if (!empty($wt_headings_font_string)){
		$wt_custom_style .= "h1, h2, h3, h4, h5, h6 {\n";
		
		if (!empty($wt_headings_font_string)){
			wt_enqueue_font( $wt_headings_font_string ) ;
			$font_name = wt_get_font($wt_headings_font_string);
			$wt_custom_style .= "    font-family: ".$font_name.", sans-serif, serif;\n";	
		}
			
		$wt_custom_style .= "}\n\n";
	}
	
	//links color
	$wt_links_color = $options['wt_links_color'];	
	if (!empty($wt_links_color)){
		$wt_custom_style .= "a:link {\n    color: ".$wt_links_color.";\n}\n\n";	
		$wt_custom_style .= "a:visited {\n    color: ".$wt_links_color.";\n}\n\n";		
	}
	
	//links hover color
	$wt_links_hover_color = $options['wt_links_hover_color'];
	if (!empty($wt_links_hover_color)){
		$wt_custom_style .= "a:hover, \n .entry-meta a:hover {\n    color: ".$wt_links_hover_color.";\n}\n\n";	
	}
		
	//custom css field
	$wt_custom_css_field = $options['wt_custom_css'];
	if (!empty($wt_custom_css_field)){
		$wt_custom_style .= $wt_custom_css_field;	
	}
	
	
	//custom color schemes
	
	//set primary color
	$wt_primary_color = $options['wt_primary_color'];
	
	if (!empty($wt_primary_color)){
		$wt_custom_style .= "#main-menu, #main-menu ul li ul li a, \n #main-menu ul li:hover ul li a, \n #main-menu ul li.over ul li a { \n    background: ".$wt_primary_color." \n}\n\n";		
		$wt_custom_style .= "#wt-slider .slider-nav,\n #wt-slider .slider-text, \n .widget_carousel li h4{\n    background: ".$wt_primary_color." \n}\n";
		$wt_custom_style .= ".post-nav .prev,\n .post-nav .next,\n #content .pagination a:hover,\n #content .pagination .current, \n .widget_comments ul li{\n    background: ".$wt_primary_color." \n}\n";
		$wt_custom_style .= ".widget h3{\n    background-color: ".$wt_primary_color." \n}\n";		
		$wt_custom_style .= "#comments .reply, \n #respond input[type=submit], \n #content .pagination a:hover, \n #content .pagination .current, \n .widget_tags a.button, \n .tagcloud a, \n .entry-footer .entry-tags a, \n .widget_polls-widget .wp-polls .pollbar,\n .widget_polls-widget .wp-polls input.Buttons, \n .button {\n    background: ".$wt_primary_color." \n}\n\n";
		$wt_custom_style .= ".widget_polls-widget .wp-polls .pollbar{\n    border: 1px solid ".$wt_primary_color." \n}\n";		
	}
	
	//set secondary color
	/* $wt_second_color = $options['wt_second_color'];
	if (!empty($wt_second_color)){
		$wt_custom_style .= "#main-menu, \n #wt-slider .slider-text p, \n .widget_polls-widget .wp-polls a, \n #footer {\n    background-color: ".$wt_second_color." \n}\n\n";
	}
	 */
	$wt_color1 = $options['wt_color1'];
	$wt_color2 = $options['wt_color2'];
	$wt_color3 = $options['wt_color3'];
	$wt_color4 = $options['wt_color4'];
	$wt_color5 = $options['wt_color5'];
	
	
	/* $wt_color2_rgb = wt_get_rgb_color($wt_color2);
	$wt_color3_rgb = wt_get_rgb_color($wt_color3);
	$wt_color4_rgb = wt_get_rgb_color($wt_color4);
	$wt_color5_rgb = wt_get_rgb_color($wt_color5); */
	
	
	//set color 1
	if (!empty($wt_color1)){
		$wt_color1_rgb = wt_get_rgb_color($wt_color1);
		$wt_custom_style .= "#feat-cat1 .cat-header,\n #feat-cat1 .tag-title,\n #feat-cat1 .overlay a{\n    background-color: ".$wt_color1." \n}\n\n";		
		$wt_custom_style .= "#feat-cat1 .overlay:hover{\n background: ".$wt_color1.";\n background: rgba(".$wt_color1_rgb." ,0.8)\n}\n\n";		
	}
	
	//set color 2
	if (!empty($wt_color2)){
		$wt_color2_rgb = wt_get_rgb_color($wt_color2);
		$wt_custom_style .= "#feat-cat2 .cat-header,\n #feat-cat2 .tag-title,\n #feat-cat2 .overlay a{\n    background-color: ".$wt_color2." \n}\n\n";		
		$wt_custom_style .= "#feat-cat2 .overlay:hover{\n background: ".$wt_color2.";\n background: rgba(".$wt_color2_rgb." ,0.8)\n}\n\n";		
	}
	
	//set color 3
	if (!empty($wt_color3)){
		$wt_color3_rgb = wt_get_rgb_color($wt_color3);
		$wt_custom_style .= "#feat-cat3 .cat-header,\n #feat-cat3 .tag-title,\n #feat-cat3 .overlay a{\n    background-color: ".$wt_color3." \n}\n\n";		
		$wt_custom_style .= "#feat-cat3 .overlay:hover{\n background: ".$wt_color3.";\n background: rgba(".$wt_color3_rgb." ,0.8)\n}\n\n";		
	}
	
	//set color 4
	if (!empty($wt_color4)){
		$wt_color4_rgb = wt_get_rgb_color($wt_color4);
		$wt_custom_style .= "#feat-cat4 .cat-header,\n #feat-cat4 .tag-title,\n #feat-cat4 .overlay a{\n    background-color: ".$wt_color4." \n}\n\n";		
		$wt_custom_style .= "#feat-cat4 .overlay:hover{\n background: ".$wt_color4.";\n background: rgba(".$wt_color4_rgb." ,0.8)\n}\n\n";		
	}
	
	//set color 5
	if (!empty($wt_color5)){
		$wt_color5_rgb = wt_get_rgb_color($wt_color5);
		$wt_custom_style .= "#feat-cat5 .cat-header,\n #feat-cat5 .tag-title,\n #feat-cat5 .overlay a{\n    background-color: ".$wt_color5." \n}\n\n";		
		$wt_custom_style .= "#feat-cat5 .overlay:hover{\n background: ".$wt_color5.";\n background: rgba(".$wt_color5_rgb." ,0.8)\n}\n\n";		
	}	
	
	$wt_custom_css_output = "\n<!-- Custom CSS Styles -->\n<style type=\"text/css\"> \n" . $wt_custom_style . " \n</style>\n<!-- /Custom CSS Styles -->\n\n";
	echo $wt_custom_css_output;
}
add_action('wp_head', 'wt_custom_styles');


/**
 * Set font styles
 */ 
function wt_set_font_style($fontstyle){
	$stack = '';
		
	switch ( $fontstyle ) {

		case "normal":
			$stack .= "";
		break;
		case "italic":
			$stack .= "    font-style: italic;";
		break;
		case 'bold':
			$stack .= "    font-weight: bold;";
		break;
		case 'bold-italic':
			$stack .= "    font-style: italic;\n    font-weight: bold;";
		break;
	}
	return $stack;
}

/**
 * Include Google fonts
 */ 
function wt_enqueue_font($wt_text_font_string){

	$font_pieces = explode(":", $wt_text_font_string);
	$font_name = $font_pieces[0];
	$font_name = str_replace (" ","+", $font_pieces[0] );
				
	$font_variants = $font_pieces[1];
	$font_variants = str_replace ("|",",", $font_pieces[1] );

	wp_enqueue_style( $font_name , 'http://fonts.googleapis.com/css?family='.$font_name . ':' . $font_variants );
}

?>