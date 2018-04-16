<?php
/**
 * Plugin Name: Well Themes: Flickr Widget
 * Plugin URI: http://wellthemes.com
 * Description: This widget allows to display flickr images in sidebar or the footer of the well themes.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wellthemes_flickr_widgets' );

function wellthemes_flickr_widgets() {
	register_widget( 'wellthemes_flickr_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */ 
class wellthemes_flickr_widget extends WP_Widget {
	
	/**
	 * Widget setup.
	 */
	function wellthemes_flickr_widget() {
		/* Widget settings. */		
		$widget_ops = array('classname' => 'widget_flickr', 'description' => 'Показывает Flickr в сайдбаре или подвале.' );
		
		/* Create the widget. */
		$this->WP_Widget('wellthemes_flickr_widget', 'Well Themes: Flickr', $widget_ops);
	}

	/**
	 * display the widget on the screen.
	 */
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $flickr_id = empty($instance['flickr_id']) ? ' ' : apply_filters('widget_user', $instance['flickr_id']);
        $flickr_counter = empty($instance['flickr_counter']) ? ' ' : apply_filters('widget_counter', $instance['flickr_counter']);
		
		echo $before_title;
        echo $title;
		echo $after_title;
        ?>
		<script type="text/javascript">
				<!--
				jQuery(document).ready(function() {                
					jQuery('.flickr_thumbs').jflickrfeed({
						limit: <?php echo $flickr_counter; ?>,
						qstrings: {
							id: '<?php echo $flickr_id; ?>'
						},
						itemTemplate: '<li><div class="widget-overlay">'+
										'<a rel="lightbox[flickr-gallery]" href="{{image}}" title="{{title}}">' +
											'<img src="{{image_s}}" alt="{{title}}" width="75" height="75" />' +
										'</a>' +
									  '</div></li>'
					});
				});
				// -->
			</script>
			<div class="flickr_stream">                 
                <ul id="flickr_thumbs" class="flickr_thumbs"></ul>
            </div>
			
		<?php
		
        echo $after_widget;
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['flickr_id'] = strip_tags($new_instance['flickr_id']);
        $instance['flickr_counter'] = strip_tags($new_instance['flickr_counter']);
		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */	 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'flickr_id' => '', 'flickr_counter' => '' ) );
	?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wellthemes') ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></label></p>
			
			<p><label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr User ID:', 'wellthemes') ?>(<a href="http://www.idgettr.com" target="_blank" >idGettr</a>): <input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo $instance['flickr_id']; ?>" /></label></p>
			
			<p><label for="<?php echo $this->get_field_id('flickr_counter'); ?>"><?php _e('Number of images:', 'wellthemes') ?> <input class="widefat" id="<?php echo $this->get_field_id('flickr_counter'); ?>" name="<?php echo $this->get_field_name('flickr_counter'); ?>" type="text" value="<?php echo $instance['flickr_counter']; ?>" /></label></p>
<?php
	}
}