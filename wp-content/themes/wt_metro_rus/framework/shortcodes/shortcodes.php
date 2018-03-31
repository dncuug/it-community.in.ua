<?php

/* MESSAGE BOXES
================*/
add_shortcode( 'box', 'wellthemes_msgbox_shortcode' );
function run_box_shortcode( $content ) {
    global $shortcode_tags;
    $orig_shortcode_tags = $shortcode_tags;
    remove_all_shortcodes(); 
    add_shortcode( 'box', 'wellthemes_msgbox_shortcode' );	
    $content = do_shortcode( $content );
    $shortcode_tags = $orig_shortcode_tags; 
    return $content;
} 
add_filter( 'the_content', 'run_box_shortcode', 7 );

if (!function_exists('wellthemes_msgbox_shortcode')) {

	function wellthemes_msgbox_shortcode( $atts, $content = null ) {
 	    extract(shortcode_atts(array(
	   		'style' => '',
			'width' => '',
		), $atts));
	   
	   	$class = 'msgbox ';
		$class .= 'msgbox-';
		$class .= $style; 
		
		$cssstyle = '';
		if ($width){ 
			$cssstyle = 'style="width:'.$width.'"';
		}

		$box = '<div class="'.$class.'" '.$cssstyle.'>'.$content.'</div>';
		return $box;
	}
}

/* LISTS
================*/
//lists
add_shortcode( 'list', 'wellthemes_list_shortcode' );
function run_list_shortcode( $content ) {
    global $shortcode_tags;
    $orig_shortcode_tags = $shortcode_tags;
    remove_all_shortcodes(); 
    add_shortcode( 'list', 'wellthemes_list_shortcode' );	
    $content = do_shortcode( $content );
    $shortcode_tags = $orig_shortcode_tags; 
    return $content;
} 
add_filter( 'the_content', 'run_list_shortcode', 7 );

if (!function_exists('wellthemes_list_shortcode')) {
	function wellthemes_list_shortcode( $atts, $content = null ) {
 	    extract(shortcode_atts(array(
	   		'style' => '',
	       ), $atts));
	   
	   	$class = 'wt-list ';
		$class .= 'wtlist-';
		$class .= $style; 
	   	
		$list = '<ul class="'.$class.'">'.$content.'</ul>';
		return $list;
	}	
}

//list items
add_shortcode( 'list_item', 'wellthemes_list_item_shortcode' );
function run_list_item_shortcode( $content ) {
    global $shortcode_tags;
    $orig_shortcode_tags = $shortcode_tags;
    remove_all_shortcodes(); 
    add_shortcode( 'list_item', 'wellthemes_list_item_shortcode' );	
    $content = do_shortcode( $content );
    $shortcode_tags = $orig_shortcode_tags; 
    return $content;
} 
add_filter( 'the_content', 'run_list_item_shortcode', 7 );

if (!function_exists('wellthemes_list_item_shortcode')) {	
	function wellthemes_list_item_shortcode( $atts, $content = null ) {	
		return '<li>' . $content . '</li>';		
	}
}

/* BUTTONS
================*/
add_shortcode( 'button', 'wellthemes_button_shortcode' );
function run_button_shortcode( $content ) {
    global $shortcode_tags;
    $orig_shortcode_tags = $shortcode_tags;
    remove_all_shortcodes(); 
    add_shortcode( 'button', 'wellthemes_button_shortcode' );	
    $content = do_shortcode( $content );
    $shortcode_tags = $orig_shortcode_tags; 
    return $content;
} 
add_filter( 'the_content', 'run_button_shortcode', 7 );

if (!function_exists('wellthemes_button_shortcode')) {

	function wellthemes_button_shortcode( $atts, $content = null ) {
	    extract(shortcode_atts(array(
			'id' 		=> '',
			'title'		=> '',			
			'url'		=> '#',
 			'target'	=> '',
			'style'		=> '',
			'size'      => '',
			
	    ), $atts));
		
		// variable setup
		$title = ($title) ? ' title="'.$title .'"' : '';
 		$id = ($id) ? ' id="'.$id .'"' : '';
		
		if ($style){ $style = $style; } else { $style = 'default'; }
		if ($size){ $size = $size; } else { $size = 'medium'; }
		
		$class = 'wt-btn ';
		$class .= 'wt-btn-';
		$class .= $style; 
		
		$class .= ' wt-btn-';
		$class .= $size;
		
 		// target setup
		if		($target == 'blank' || $target == '_blank' || $target == 'new' ) { $target = ' target="_blank"'; }
		elseif	($target == 'parent')	{ $target = ' target="_parent"'; }
		elseif	($target == 'self')		{ $target = ' target="_self"'; }
		elseif	($target == 'top')		{ $target = ' target="_top"'; }
		else	{$target = '';}
		
		$button = '<a' .$target .$title. '  ' .$id. ' class="' .$class.'" href="' .$url. '">' .$content. '</a>';
	    
	    return $button;
	}	
}

/* HIGHLIGHT
================*/
add_shortcode( 'highlight', 'wellthemes_highlight_shortcode' );
function run_highlight_shortcode( $content ) {
    global $shortcode_tags;
    $orig_shortcode_tags = $shortcode_tags;
    remove_all_shortcodes(); 
    add_shortcode( 'highlight', 'wellthemes_highlight_shortcode' );	
    $content = do_shortcode( $content );
    $shortcode_tags = $orig_shortcode_tags; 
    return $content;
} 
add_filter( 'the_content', 'run_highlight_shortcode', 7 );

if (!function_exists('wellthemes_highlight_shortcode')) {

	function wellthemes_highlight_shortcode( $atts, $content = null ) {
 	    extract(shortcode_atts(array(
	   		'style' => '',
	       ), $atts));
	   
		$class = 'wt-highlight ';
		$class .= 'wt-highlight-';
		$class .= $style; 

		$highlight = '<span class="'.$class.'">'.$content.'</span>';
		return $highlight;
	}
}

/* SOCIAL
================*/
//twitter
if (!function_exists('wellthemes_twitter_shortcode')) {

	function wellthemes_twitter_shortcode( $atts, $content = null ) {
	
		extract(shortcode_atts(array(
				'layout'        => 'vertical',
				'username'		  => '',
				'text' 			  => '',
				'url'			  => '',
				'related'		  => '',
				'lang'			  => '',
	    	), $atts));
			
		if ($text != '') { $text = "data-text='".$text."'"; }
	    if ($url != '') { $url = "data-url='".$url."'"; }
	    if ($related != '') { $related = "data-related='".$related."'"; }
	    if ($lang != '') { $lang = "data-lang='".$lang."'"; }
		
		$out = '<span class = "wt_social"><a href="http://twitter.com/share" class="twitter-share-button" '.$url.' '.$lang.' '.$text.' '.$related.' data-count="'.$layout.'" data-via="'.$username.'">Tweet</a>';
		$out .= '<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></span>';
		
		return $out;
	
	}
	
add_shortcode('twitter', 'wellthemes_twitter_shortcode');

}

//facebook
if (!function_exists('wellthemes_facebook_shortcode')) {

	function wellthemes_facebook_shortcode( $atts, $content = null ) {
	
		extract(shortcode_atts(array(
				'layout'		=> 'box_count',
				'width'			=> '',
				'height'		=> '',
				'show_faces'	=> 'false',
				'action'		=> 'like',
				'font'			=> 'lucida+grande',
				'colorscheme'	=> 'light',
	    	), $atts));
		
		if ($layout == 'standard') { $width = '450'; $height = '35';  if ($show_faces == 'true') { $height = '80'; } }
	    if ($layout == 'box_count') { $width = '55'; $height = '65'; }
	    if ($layout == 'button_count') { $width = '90'; $height = '20'; }
		
		$out = '<span class = "wt_social wt_social_fb"><iframe src="http://www.facebook.com/plugins/like.php?href='.get_permalink();
		$out .= '&layout='.$layout.'&show_faces=false&width='.$width.'&action='.$action.'&font='.$font.'&colorscheme='.$colorscheme.'"';
		$out .= 'allowtransparency="true" style="border: medium none; overflow: hidden; width: '.$width.'px; height: '.$height.'px;"';
		$out .= 'frameborder="0" scrolling="no"></iframe></span>';
		
		return $out;
	
	}
	
add_shortcode('facebook', 'wellthemes_facebook_shortcode');

}

//google+
if (!function_exists('wellthemes_gplus_shortcode')) {

	function wellthemes_gplus_shortcode( $atts, $content = null ) {
	
		extract(shortcode_atts(array(
				'size'			=> 'tall',
				'lang'			=> 'en-US',
	    ), $atts));
		
		if ($size != '') { $size = "size='".$size."'"; }
	    if ($lang != '') { $lang = "{lang: '".$lang."'}"; }
		
		$out = '<span class = "wt_social"><script type="text/javascript" src="https://apis.google.com/js/plusone.js">'.$lang.'</script>';
		$out .= '<g:plusone '.$size.'></g:plusone></span>';
		
		return $out;
	
	}
	
add_shortcode('gplus', 'wellthemes_gplus_shortcode');

}


//digg
//DiggCompact 
if (!function_exists('wellthemes_digg_shortcode')) {

	function wellthemes_digg_shortcode( $atts, $content = null ) {
	
		extract(shortcode_atts(array(
			'layout'        => 'DiggMedium',
			'url'			=> get_permalink(),
			'title'			=> '',
			'type'			=> '',
			'description'	=> '',
			'related'		=> '',
	    	), $atts));
	    
	    if ($title != '') { $title = "&title='".$title."'"; }
	    if ($type != '') { $type = "rev='".$type."'"; }
	    if ($description != '') { $description = "<span style = 'display: none;'>".$description."</span>"; }
	    if ($related != '') { $related = "&related=no"; }
	    	
		$out = '<span class = "wt_social"><a class="DiggThisButton '.$layout.'" href="http://digg.com/submit?url='.$url.$title.$related.'"'.$type.'>'.$description.'</a>';
		$out .= '<script type = "text/javascript" src = "http://widgets.digg.com/buttons.js"></script></span>';
		
		return $out;
	
	}
	
add_shortcode('digg', 'wellthemes_digg_shortcode');

}

//stumbleupon
//layout 1, 2, 3,4, 5, 6
if (!function_exists('wellthemes_stumbleupon_shortcode')) {

	function wellthemes_stumbleupon_shortcode( $atts, $content = null ) {
	
		extract(shortcode_atts(array(
			'layout'        => '5',
			'url'			=> '',
	    	), $atts));
	    	
	    if ($url != '') { $url = "&r=".$url; }
		
		$out=  '<span class = "wt_social"><script src="http://www.stumbleupon.com/hostedbadge.php?s='.$layout.$url.'"></script></span>';
		return $out;
	}
	
add_shortcode('stumbleupon', 'wellthemes_stumbleupon_shortcode');

}

//linkedin
//layout 1,2 3,
if (!function_exists('wellthemes_linkedin_shortcode')) {

	function wellthemes_linkedin_shortcode( $atts, $content = null ) {
	
		extract(shortcode_atts(array(
			'layout'        => '3',
			'url'			=> '',
	    	), $atts));
	    	
	    if ($url != '') { $url = "data-url='".$url."'"; }
	    if ($layout == '2') { $layout = 'right'; }
		if ($layout == '3') { $layout = 'top'; }
	    	
		$out = '<span class = "wt_social"><script type="text/javascript" src="http://platform.linkedin.com/in.js"></script><script type="in/share" data-counter = "'.$layout.'" '.$url.'></script></span>';
		
		return $out;
	}	
	
add_shortcode('linkedin', 'wellthemes_linkedin_shortcode');

}

/* DROPCAPS
================*/
if (!function_exists('wellthemes_dropcap_shortcode')) {

	function wellthemes_dropcap_shortcode( $atts, $content = null ) {
 	    extract(shortcode_atts(array(
	   		'style' => 'default',
	       ), $atts));
	   
		$class  = 'wt-dropcap-';
		$class .= $style; 
	   	
	   	$content = do_shortcode($content);
		$dropcap = '<span class="'.$class.'">'.$content.'</span>';
		
		return $dropcap;
	}
	
	add_shortcode('dropcap', 'wellthemes_dropcap_shortcode');

}

/* LIGHTBOX IMAGE
====================*/
//image
if (!function_exists('wellthemes_lightbox_image_shortcode')) {
	function wellthemes_lightbox_image_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'src' => '',
			'bigimage' => '',
			'align' => 'aligncenter',
			'title' => 'Image',
			'alt' => '',
		), $atts ) );

		if ($title != '') { $title = "title='".$title."'"; }
		
		if ($align == 'left') { $align = 'alignleft'; }
		if ($align == 'center') { $align = 'aligncenter'; }
		if ($align == 'right') { $align = 'alignright'; }
		
		$lightbox_image = '<a href="'.esc_attr($bigimage).'" '.$title.' rel="lightbox"><img class="'.esc_attr($align).'" src="'.esc_attr($src).'" alt="'.esc_attr($alt).'" /></a>';
		
		return $lightbox_image;
	}
	
	add_shortcode( 'lightbox_image', 'wellthemes_lightbox_image_shortcode' );
}


/* VIDEO
====================*/

if (!function_exists('wellthemes_video_shortcode')) {
	
	function wellthemes_video_shortcode( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'type' => 'youtube',
			'height' => '420',
			'width' => '315',
			'align' => 'aligncenter',
			'id' => ''
		),$atts));
	
		if($height == ''){	$height = '420'; }
		if($width == ''){	$width = '315'; }
		
		if ($align == 'left') { $align = 'alignleft'; }
		if ($align == 'center') { $align = 'aligncenter'; }
		if ($align == 'right') { $align = 'alignright'; }
		
		if($type == 'youtube'){
			return '<iframe class="wt-video '.esc_attr($align).'" width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'?wmode=transparent" frameborder="0" allowfullscreen></iframe>';
		} else	{
			return '<iframe class="wt-video '.esc_attr($align).'" src="http://player.vimeo.com/video/'.$id.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}
	}
	add_shortcode('video', 'wellthemes_video_shortcode');
}