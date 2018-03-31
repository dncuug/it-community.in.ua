<?php
/**
 * Plugin Name: Well Themes: Featured Video
 * Plugin URI: http://wellthemes.com
 * Description: A widget that allows you embed videos into the sidebar.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wellthemes_video_widgets' );

function wellthemes_video_widgets() {
	register_widget( 'wellthemes_video_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_video_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function wellthemes_video_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_video', 'description' => __('Embed a video in the sidebar.', 'wellthemes') );

		/* Create the widget. */
		$this->WP_Widget( 'wellthemes_video_widget', __('Well Themes: Featured Video', 'wellthemes'), $widget_ops);
	}

	/**
	 * display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
	    $title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;

		if ( $title )
		echo $before_title . $title . $after_title;
	   
	   $video_embed = $instance['video_embed'];

       ?>
	   <div class="embed">
		<?php printf( __('%1$s', 'wellthemes'), $video_embed ); ?>
	   </div>
	   <?php
		
		echo $after_widget;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('title' => 'Видео', 'video_embed' => '');
		$instance = wp_parse_args((array) $instance, $defaults);
		?>

        <p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>
			<?php $video_embed_c = stripslashes(htmlspecialchars($instance['video_embed'], ENT_QUOTES)); ?>
        <p>
			<label for="<?php echo $this->get_field_id( 'video_embed' ); ?>"><?php _e('Video Embed Code:', 'wellthemes'); ?></label>
			<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'video_embed' ); ?>" name="<?php echo $this->get_field_name( 'video_embed' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['video_embed'] ), ENT_QUOTES)); ?></textarea>
        </p>
		
	<?php
	}
}

?>