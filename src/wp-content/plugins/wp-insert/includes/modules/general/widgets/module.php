<?php
/* Begin UI Functions */
add_action('wp_insert_plugin_card', 'wp_insert_adwidgets_plugin_card', 30);
function wp_insert_adwidgets_plugin_card() {
	wp_insert_get_plugin_card(
		'Ad Widgets',
		'<p>Ads shown inside widget enabled areas.</p>',
		'adwidgets',
		'Ad Widget'
	);
}

add_action('wp_ajax_wp_insert_adwidgets_get_ad_form', 'wp_insert_get_ad_form');
add_action('wp_ajax_wp_insert_adwidgets_save_ad_data', 'wp_insert_save_ad_data');
add_action('wp_ajax_wp_insert_adwidgets_delete_ad_data', 'wp_insert_delete_ad_data');

add_filter('wp_insert_adwidgets_form_accordion_tabs', 'wp_insert_form_accordion_tabs_adcode', 20, 3);
add_filter('wp_insert_adwidgets_form_accordion_tabs', 'wp_insert_form_accordion_tabs_rules', 30, 3);
add_filter('wp_insert_adwidgets_form_accordion_tabs', 'wp_insert_form_accordion_tabs_geo_targeting', 40, 3);
add_filter('wp_insert_adwidgets_form_accordion_tabs', 'wp_insert_form_accordion_tabs_devices_styles', 50, 3);
add_filter('wp_insert_adwidgets_form_accordion_tabs', 'wp_insert_form_accordion_tabs_notes', 60, 3);
/* End UI Functions */

/* Begin Ad Widget Insertion */
add_action('widgets_init', create_function('', 'register_widget("wpInsertAdWidget");'));
class wpInsertAdWidget extends WP_Widget {
	public function __construct() {
		parent::__construct('wp_insert_ad_widget', 'Wp-Insert Ad Widget', array('description' => 'Wp-Insert Ad Widget'));
	}

	public function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', (isset($instance['title'])?$instance['title']:''));
		$adwidgets = get_option('wp_insert_adwidgets');
		if(isset($adwidgets[$instance['instance']]) && is_array($adwidgets[$instance['instance']])) {
			if(wp_insert_get_ad_status($adwidgets[$instance['instance']])) {
				echo $before_widget;
					if(!empty($title)) { echo $before_title.$title.$after_title; }
					echo wp_insert_get_ad_unit($adwidgets[$instance['instance']]);
				echo $after_widget;
			}
		}
	}

	public function update($new_opts, $old_opts) {
		$opts = array();
		$opts['title'] = $new_opts['title'];
		$opts['instance'] = $new_opts['instance'];
		return $opts;
	}

	public function form($instance) {
		$adwidgets = get_option('wp_insert_adwidgets');
		echo '<p>';
			echo '<label for="'.$this->get_field_id('title').'">Title:</label>';
			echo '<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.((isset($instance['title']))?$instance['title']:'').'" />';
		echo '</p>';
		echo '<p>';
			if(is_array($adwidgets) && (count($adwidgets) > 0)) {
				echo '<label for="'.$this->get_field_id('instance').'">Select Ad-Widget:</label>';
				echo '<select class="widefat" id="'.$this->get_field_id('instance').'" name="'.$this->get_field_name('instance').'">';
					foreach($adwidgets as $identifier => $adwidget) {
						echo '<option value="'.$identifier.'" '.selected($identifier, ((isset($instance['instance']))?$instance['instance']:''), false).'>Ad Widget : '.((isset($adwidget['title']))?$adwidget['title']:'').'</option>';
					}
				echo '</select>';
			} else {
				echo 'Please <a href="'.admin_url('admin.php?page=wp-insert').'">Configure an Ad-Widget</a> to Proceed.';
				echo '<input class="widefat" id="'.$this->get_field_id('instance').'" name="'.$this->get_field_name('instance').'" type="hidden" value="" />';
			}
		echo '</p>';
	}
}
/* End Ad Widget Insertion */
?>