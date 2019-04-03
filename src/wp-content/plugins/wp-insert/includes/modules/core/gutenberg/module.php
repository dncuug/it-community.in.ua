<?php
add_action('init', 'wp_insert_gutenberg_block_init');
function wp_insert_gutenberg_block_init() {
	if(function_exists('register_block_type')) {
		wp_register_script('wp_insert_gutenberg', WP_INSERT_URL.'includes/modules/core/gutenberg/js/gutenberg.js', array('wp-blocks', 'wp-editor', 'wp-element'), WP_INSERT_VERSION.rand(0,9999));
		register_block_type('wp-insert/wp-insert-gutenberg', array('editor_script' => 'wp_insert_gutenberg',));
	}
}

add_action('admin_footer-post-new.php', 'wp_insert_gutenberg_admin_footer');
add_action('admin_footer-post.php', 'wp_insert_gutenberg_admin_footer');
function wp_insert_gutenberg_admin_footer() {
	echo '<input type="hidden" id="wp_insert_gutenberg_admin_ajax" name="wp_insert_admin_ajax" value="'.admin_url('admin-ajax.php').'" />';
	echo '<input type="hidden" id="wp_insert_gutenberg_nonce" name="wp_insert_nonce" value="'.wp_create_nonce('wp-insert-gutenberg').'" />';
}

add_action('wp_ajax_wp_insert_gutenberg_get_ad_data', 'wp_insert_gutenberg_get_ad_data');
function wp_insert_gutenberg_get_ad_data() {
	check_ajax_referer('wp-insert-gutenberg', 'wp_insert_gutenberg_nonce');
	$adData = array();
	$inpostads = get_option('wp_insert_inpostads');
	if(isset($inpostads) && is_array($inpostads)) {
		foreach($inpostads as $key => $inpostad) {
			/* Begin Workaround for migrating old users to new system (can be removed in a later version) */
			$title = $key;
			if(!isset($inpostad['title']) || ($inpostad['title'] == '')) {
				switch($key) {
					case 'above':
						$title = 'Above Post Content';
						break;
					case 'middle':
						$title = 'Middle of Post Content';
						break;
					case 'below':
						$title = 'Below Post Content';
						break;
					case 'left':
						$title = 'To the Left of Post Content';
						break;
					case 'right':
						$title = 'To the Right of Post Content';
						break;
				}
			} else {
				$title = $inpostad['title'];
			}
			/* End Workaround for migrating old users to new system (can be removed in a later version) */
			$adData[] = array(
				'type' => 'inpostads',
				'id' => $key,
				'title' => 'In-Post Ad : '.$title,
			);
		}
	}
	
	$shortcodeads = get_option('wp_insert_shortcodeads');
	if(isset($shortcodeads) && is_array($shortcodeads)) {
		foreach($shortcodeads as $key => $shortcodead) {
			$title = $shortcodead['title'];
			if(!isset($shortcodead['title']) || ($shortcodead['title'] == '')) {
				$title = $key;
			}
			$adData[] = array(
				'type' => 'shortcodeads',
				'id' => $key,
				'title' => 'Shortcode Ad : '.$title,
			);
		}
	}
	echo '###SUCCESS###';
	echo json_encode($adData);
	die();
}
?>