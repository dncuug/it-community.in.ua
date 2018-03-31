<?php
/*
Plugin Name: Advanced Spoiler

Plugin URI: http://082net.com/tag/advanced-spoiler/

Description: New version of <a href="http://082net.com/tag/aj-spoiler">Ajax Spoiler</a> plugin renamed to 'Advanced Spoiler'.
Show or hide contents(text, image etc.) wrapped by spoiler markup tag([spoiler][/spoiler]). Requires WP 2.7 or greater.

Version: 2.02

Author: Choen, Young-Min
Author URI: http://082net.com/
*/
/*  Copyright 2006  Cheon, Young-Min  (email : 082net@gmail.com, site : http://082net.com)
**
**  This program is free software; you can redistribute it and/or modify
**  it under the terms of the GNU General Public License as published by
**  the Free Software Foundation; either version 2 of the License, or
**  (at your option) any later version.
**
**  This program is distributed in the hope that it will be useful,
**  but WITHOUT ANY WARRANTY; without even the implied warranty of
**  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**  GNU General Public License for more details.
**
**  You should have received a copy of the GNU General Public License
**  along with this program; if not, write to the Free Software
**  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/*

This Plugin is based on Leong Wai Kay(http://www.waikay.net)'s Simple-Spoiler

Installation : 
	1. Put "advanced-spoiler" folder to plugin folder (e.g. wp-content/plugins)
	2. Open advanced-spoiler.php file with text-editor and edit some options like 
			$feed_visibility, $use_html_tagset, $js_library, $default_effect
	2. Activate the plugin in your WP admin
	3. That's all :)

Usage :
	1. Enclose your post text, images.. or something within [spoiler] [/spoiler] that's all.
	2. You can change showtext, hidetext, effect.
		[spoiler effect="blind" show="showtext" hide="hidetext"]your content...[/spoiler] or just [spoiler] [/spoiler]
	3. Available effect : appear, blind, apblind, phase, slide, simple
*/


class AdvSpoiler {
	var $use_html_tagset = false;	 // Use <spoiler></spoiler> set. true or false (recommended is false)
	var $counts;
	var $mode, $js_library;
	var $url, $hook, $folder, $admin_page;
	var $sep = "||"; // old plugin support
	var $version = '2.02';
	var $effects = array('appear', 'blind', 'apblind', 'phase', 'slide', 'simple');
//	var $regex = '|\[spoiler\s+([^]]*)\]((?!\[/?spoiler.*?\]).*?)\[/spoiler\]|isme';
	var $regex = '#\[spoiler\s+([^]]*)\]((?!\[spoiler\s+([^]]*)\)).)?\[/spoiler]#ise';
	var $domain = 'adv-spoiler';

	function AdvSpoiler() {
		$this->__construct();
	}

	function __construct() {
		$this->hook = plugin_basename(__FILE__);
		$this->folder = dirname($this->hook);
		$this->url = plugins_url($this->folder);
		$this->init_options();
		$this->register_hooks();
		$this->js_library = 'jquery';// locked now...
	}

	function default_options() {
		$defaults = array(
			'default_effect' => 'appear',
			'show_text' => __('Show', $this->domain),
			'hide_text' => __('Hide', $this->domain),
			'effect_speed' => '3',
			'show_marker' => '&raquo;',
			'hide_marker' => '&laquo;',
			'feed_visibility' => 'hide',
			'plugin_mode' => 'jquery',
			'allow_nested' => 0,
			'parse_old_param' => 0,
			);
		return $defaults;
	}

	function init_options() {
		$defaults = $this->default_options();
		$this->op = get_option('advanced_spoiler');
		if (!is_array($this->op)) {
			$this->op = $defaults;
			return;
		}
		$this->op = array_merge($defaults, $this->op);
	}

	function register_hooks() {
		// Register Hooks
		add_filter('the_content', array(&$this, '_spoiler'), 0);
		add_filter('the_content_rss', array(&$this, '_spoiler'), 0);
		add_filter('the_excerpt', array(&$this, '_spoiler'), 0);
		add_filter('the_excerpt_rss', array(&$this, '_spoiler'), 0);
		add_filter('comment_text', array(&$this, '_spoiler'), 0);
		add_filter('comment_text_rss', array(&$this, '_spoiler'), 0);

		if (is_admin()) {
			add_action('admin_menu', array(&$this, 'add_options_page'));
		} else {
			load_plugin_textdomain($this->domain, false, $this->folder . '/langs');
			if ($this->op['plugin_mode'] == 'simple')
				add_action('wp_head', array(&$this, 'wp_head'), 15);
			else
				add_action('wp_head', array(&$this, 'enqueue_script'), 5);
			add_action('wp_head', array(&$this, 'enqueue_style'), 5);
		}

//		add_shortcode('spoiler', array(&$this, 'shortcode'));
	}

	function add_options_page() {
		global $pagenow;
		if (function_exists('add_options_page')) {
			$page = add_options_page(__("Advanced Spoiler", $this->domain), __("Advanced Spoiler", $this->domain), 9, 'adv-spoiler-options', array(&$this, 'option_page'));
			$this->admin_page = $page;
		}
		if ( in_array( $pagenow, array('post.php', 'post-new.php', 'page.php', 'page-new.php') ) ) {
			load_plugin_textdomain($this->domain, false, $this->folder . '/langs');
			// init process for tinymce button control
			$this->mce_addbuttons();
			add_action("admin_print_styles", array(&$this, 'admin_style'));
			add_action("admin_print_scripts", array(&$this, 'admin_script'));
		}
	}

	function _fix_attr($attr, $key=0) {
		$sep = strpos($attr[$key], $this->sep) !== false ? $this->sep : "''";
		$v = explode($sep, $attr[$key], 4);
		$v = array_map(create_function('$a', 'return trim($a, " '.$sep[0].'");'), $v);
		if ($v[0] && strpos($v[0], '_hidden') !== false) // removed. just use effect name
			$v[0] = str_replace('_hidden', '', $v[0]);
		if ($v[0] && !in_array($v[0], $this->effects))
			array_unshift($v, '');
		if (!empty($v[0]) && !isset($attr['effect']))
			$attr['effect'] = $v[0];
		if (!empty($v[1]) && !isset($attr['show']))
			$attr['show'] = $v[1];
		if (!empty($v[2]) && !isset($attr['hide']))
			$attr['hide'] = $v[2];
		unset($attr[$key]);
		return $attr;
	}

	function _reset() {
		$this->open = $this->depth = $this->counts = 0;
		$this->prv = '';
		$this->sp = array();
	}

	function _spoiler($content) {
		$this->_reset();
		if ($this->use_html_tagset) {
			$content = preg_replace('/<(\/?spoiler)(\s*)([^>]*)>/s', '[\1\2\3]', $content);
		}
		if ($this->op['allow_nested'])
			return $this->_spoiler_deep($content);

		$content = preg_replace_callback('/(<(p|div)>)?\[spoiler\b(.*?)\](.*?)\[\/spoiler\](<\/\2>)?/s', array(&$this, '_replace'), $content);
		return $content;
	}

	function _spoiler_deep($content) {
		$temp_content = preg_replace_callback('/\[(\/?spoiler)\b(.*?)\]/s', array(&$this, '_temp'), $content);
		if (!$this->open)
			return $content;
		for ($i=1; $i<=$this->open; $i++) {
			$regex[] = '/(<(p|div)>)?\[spoiler'.$i.'\b(.*?)\](.*?)\[\/spoiler'.$i.'\](<\/\2>)?/s';
		}
		$temp_content = preg_replace_callback($regex, array(&$this, '_replace'), $temp_content);
		return $temp_content;

/*		while(preg_match($this->regex, $content)) {
			$content = preg_replace($this->regex, '$this->_replace("\1", "\2");', $content);
		}
		return $content;*/
	}

	function _temp($m) {
		if (strpos($m[1], '/') === 0) {
			if ($this->prv == 'close') $this->depth--;
			$num = array_pop($this->sp[$this->depth]);
			 $this->prv = 'close';
		} else { 
			$this->open++;
			if ($this->prv == 'open') $this->depth++;
			$this->sp[$this->depth][] = $this->open;
			$num = $this->open;
			$this->prv = 'open';
		}
		$text = '['.$m[1].$num.$m[2].']';
		return $text;
	}

	function _replace($m) {
		global $post, $doing_rss, $comment;
		$this->counts++;
		$op = stripslashes($m[3]);
		$text = stripslashes($m[4]);

		$id = "SID".$post->ID."_".$this->counts;
		if (isset($comment->comment_ID)) {
			$id .= "c";
		}
		$href = get_permalink()."#{$id}_tgl";

		if ($this->op['feed_visibility'] == 'hide' && (is_feed() || $doing_rss)) {
			return "<p><a href='$href' title='".__('Visit blog to check out this spoiler', $this->domain)."'>[[".__('Visit blog to check out this spoiler', $this->domain)."]]</a></p>";
		}

		$def = array('effect' => $this->op['default_effect'],
			'show' => $this->op['show_text'],
			'hide' => $this->op['hide_text'],
			'speed' => $this->op['effect_speed'],
			);

		$attr = shortcode_parse_atts($op);
		if ( $this->op['parse_old_param'] && isset($attr[0]) && (strpos($attr[0], $this->sep) !== false || strpos($attr[0], "''") !== false) ) {
			$attr = $this->_fix_attr($attr);
		}
		extract(shortcode_atts($def, $attr));

		$show = $show ." ". $this->op['show_marker'];
		$hide = $hide ." ". $this->op['hide_marker'];

		if ($this->op['plugin_mode'] == 'simple') {
			return "<p><a onfocus='this.blur();' class='spoiler-tgl collapsed' href='$href' id='{$id}_tgl' onclick=\"s_toggleDisplay(document.getElementById('{$id}'), this, '".attribute_escape($show)."', '".attribute_escape($hide)."'); return false;\">$show</a></p>\n\n<div id='$id' class='spoiler-body' style='display:none;'>\n\n".trim($text)."</div>";
		}

		$effect = in_array($effect, $this->effects) ? $effect : 'simple';
		$speed = (int)$speed;
		if ($speed < 2) $speed = 2;
		elseif ($speed > 10) $speed = 10;
		$speed = $speed*100;
		$rev = $effect.'||'.attribute_escape($show).'||'.attribute_escape($hide).'||'.$speed;

		return "<p><a class='spoiler-tgl' href='$href' id='{$id}_tgl' rev='$rev'>$show</a></p>\n\n<div id='$id' class='spoiler-body'>\n\n".trim($text)."</div>";
	}

	function wp_head($s) {
	  echo "
    <script type='text/javascript' language='Javascript'>
      function s_toggleDisplay(his, me, show, hide) {
        if (his.style.display != 'none') {
          his.style.display = 'none';
          me.innerHTML = show;
					me.className += ' collapsed';
        } else {
          his.style.display = '';
          me.innerHTML = hide;
					me.className = me.className.replace(' collapsed', '');
        }
      }
      </script>";
		return $s;
	}

	function admin_script() {
		wp_enqueue_script('admin-adv-spoiler', $this->url . '/js/spoiler-admin.js', array('jquery-ui-dialog', 'jquery-form'), $this->version);
		wp_localize_script( 'admin-adv-spoiler', '_spoilerL10n', array(
			'title' => __('Make a spoiler', $this->domain),
			'effect_title' => __('Select a effect type:', $this->domain),
			'showtext_title' => __('Show Text:', $this->domain),
			'hidetext_title' => __('Hide Text:', $this->domain),
			'default_effect' => $this->op['default_effect'],
			'showtext' => $this->op['show_text'],
			'hidetext' => $this->op['hide_text'],
			'button_title' => __('Insert Spoiler', $this->domain)
			) );
	}

	function admin_style() {
		wp_enqueue_style('jquery-ui-lightness-dialog', $this->url . '/css/ui-lightness/ui.dialog.css', false, '1.5.2');
	}

	function enqueue_script() {
		global $wp_scripts;
		switch($this->op['plugin_mode']):
		case 'simple':
			return;
		break;
		case 'prototype':
			wp_enqueue_script('adv-spoiler', $this->url.'/js/prototype-spoiler.js', array('scriptaculous-effects'), $this->version);
		break;
		case 'mootools':
			if (!in_array('mootools-Fx', $wp_scripts->registered))
				return;
			wp_enqueue_script('adv-spoiler', $this->url.'/js/moo-spoiler.js', array('mootools-Fx'), $this->version);
		break;
		case 'jquery': default:
			wp_enqueue_script('adv-spoiler', $this->url.'/js/jquery-spoiler.js', array('jquery'), $this->version);
		break;
		endswitch;
	}

	function enqueue_style() {
		wp_enqueue_style('adv-spoiler', $this->url . '/css/advanced-spoiler.css', false, $this->version);
	}

	function mce_addbuttons() {
		// Don't bother doing this stuff if the current user lacks permissions
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;
		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
			add_filter("mce_external_plugins", array(&$this, "add_mce_plugin"));
			add_filter('mce_buttons_2', array(&$this, 'register_mce_button'));
			add_filter( 'tiny_mce_version', array(&$this, 'mce_version') );
		}
	}
	 
	function register_mce_button($buttons) {
		 array_push($buttons, "separator", "spoiler");
		 return $buttons;
	}
	 
	function add_mce_plugin($plugin_array) {
		 $plugin_array['spoiler'] = $this->url . '/tinymce/editor_plugin.js';
		 return $plugin_array;
	}

	function mce_version( $version ) {
		return $version+6;
	}

	function save_changes() {
		if ( $_POST['option_page'] != $this->admin_page )
			return;
		check_admin_referer(  $this->admin_page . '-options' );
		$htmls = array('show_text', 'show_marker', 'hide_text', 'hide_marker');
		foreach ($this->op as $key => $val) {
			$this->op[$key] = stripslashes(trim($_POST[$key]));
			if ( in_array($key, $htmls) )
				$this->op[$key] = wp_specialchars($this->op[$key]);
		}
		update_option('advanced_spoiler', $this->op);
		echo '<div id="message" class="updated fade"><p><strong>'.__('Settings saved.', $this->domain).'</strong></p></div>';
	}

	function option_page() {
		load_plugin_textdomain($this->domain, false, $this->folder . '/langs');
		$this->save_changes();
		extract($this->op, EXTR_SKIP);
?>
<div class="wrap"> 
<?php if (function_exists('screen_icon')) screen_icon(); ?>
  <h2><?php _e('Advanced Spoiler', $this->domain) ?></h2>

  <form id="adv_spoiler_option" name="adv_spoiler_option" method="post" action=""> 
	<?php settings_fields($this->admin_page); ?>

	<h3><?php _e('General Options', $this->domain) ?></h3>
	<table class="form-table">

	<tr valign="top">
	<th scope="row"><?php _e('Mode:', $this->domain); ?></th> 
	<td><select name="plugin_mode">
	<option value="jquery"<?php selected($plugin_mode, 'jquery') ?>><?php _e('jQuery', $this->domain) ?></option>
	<!-- <option value="prototype"<?php selected($plugin_mode, 'prototype') ?>><?php _e('Prototype', $this->domain) ?></option>
	<option value="mootools"<?php selected($plugin_mode, 'mootools') ?>><?php _e('Mootools', $this->domain) ?></option> -->
	<option value="simple"<?php selected($plugin_mode, 'simple') ?>><?php _e('Simple', $this->domain) ?></option>
	</select>
	<br />
	<?php _e('If you don\'t want animated show/hide spoiler, select \'Simple\'', $this->domain) ?>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Feed Visibility:', $this->domain); ?></th> 
	<td><select name="feed_visibility">
	<option value="show"<?php selected($feed_visibility, 'show') ?>><?php _e('Show', $this->domain) ?></option>
	<option value="hide"<?php selected($feed_visibility, 'hide') ?>><?php _e('Hide', $this->domain) ?></option>
	</select> 
	<br />
	<?php _e('If you want to hide spoiler content on feed, select \'hide\'', $this->domain) ?>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Show Text:', $this->domain); ?></th> 
	<td>
	<input type="text" size="14" name="show_text" id="show_text" value="<?php echo attribute_escape($show_text) ?>" />
	<input type="text" size="7" name="show_marker" id="show_marker" value="<?php echo attribute_escape($show_marker) ?>" /> <?php printf(__('(default: %s)', 'adv-spoiler'), '&amp;raquo;'); ?>
	<br />
	<?php _e('Preview:', $this->domain) ?> <code><?php echo $show_text . ' ' . $show_marker; ?></code>
	<br />
	<?php _e('Text for spoiler link when it\'s content is collapsed and marker for the text', $this->domain) ?>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Hide Text:', $this->domain); ?></th> 
	<td>
	<input type="text" size="14" name="hide_text" id="hide_text" value="<?php echo attribute_escape($hide_text) ?>" />
	<input type="text" size="7" name="hide_marker" id="hide_marker" value="<?php echo attribute_escape($hide_marker) ?>" /> <?php printf(__('(default: %s)', 'adv-spoiler'), '&amp;laquo;'); ?>
	<br />
	<?php _e('Preview:', $this->domain) ?> <code><?php echo $hide_text . ' ' . $hide_marker; ?></code>
	<br />
	<?php _e('Text for spoiler link when it\'s content is expanded and marker for the text', $this->domain) ?>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Default Effect:', $this->domain); ?></th> 
	<td><select name="default_effect">
	<?php foreach ($this->effects as $effect) { ?>
	<option value="<?php echo $effect; ?>"<?php selected($default_effect, $effect) ?>><?php echo $effect; ?></option>
	<?php } ?>
	</select> 
	<br />
	<?php _e('Default effect for spoilers. (useless if mode is simple)', $this->domain) ?>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Effect Speed:', $this->domain); ?></th> 
	<td><select name="effect_speed">
	<?php $speed_names = array('2' => __('fast', $this->domain), '4' => __('normal', $this->domain), '9' => __('slow', $this->domain)); ?>
	<?php for ($i=2; $i<=10; $i++) { ?>
	<option value="<?php echo $i; ?>"<?php selected($effect_speed, $i) ?>><?php echo (isset($speed_names[$i]) ? $speed_names[$i] : $i); ?></option>
	<?php } ?>
	</select> 
	<br />
	<?php _e('The lower value, the faster speed.', $this->domain) ?>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Allow Nested:', $this->domain); ?></th> 
	<td>
	<select name="allow_nested">
	<option value="0"<?php selected($allow_nested, '0') ?>><?php _e('No', $this->domain) ?></option>
	<option value="1"<?php selected($allow_nested, '1') ?>><?php _e('Yes', $this->domain) ?></option>
	</select> 
	<br />
	<?php _e('Allow nested child spoilers.', $this->domain) ?>
	<br />
	<?php _e('e.g.', $this->domain) ?> <code><?php _e('[spoiler] my content [spoiler] child content [/spoiler] rest of content [/spoiler]', $this->domain) ?></code>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e('Parse Old Parameters:', $this->domain); ?></th> 
	<td>
	<select name="parse_old_param">
	<option value="0"<?php selected($parse_old_param, '0') ?>><?php _e('No', $this->domain) ?></option>
	<option value="1"<?php selected($parse_old_param, '1') ?>><?php _e('Yes', $this->domain) ?></option>
	</select> 
	<br />
	<?php _e('Parse old plugin(Ajax Spoiler) parameters.', $this->domain) ?>
	</td>
	</tr>

	</table>

	<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'adv-spoiler') ?>" />
	</p>

	</form>
</div><!-- wrap -->
<?php
	}

	function &get_instance() {
		static $instance = array();
		if ( empty( $instance ) ) {
			$instance[] =& new AdvSpoiler();
		}
		return $instance[0];
	}
}

// Instance of plugin
$AdvSpoiler =& AdvSpoiler::get_instance();

//unset($AdvSpoiler);
?>