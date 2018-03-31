<?php
/**
 * Plugin Name: Well Themes: Recent Tags
 * Plugin URI: http://wellthemes.com/
 * Description: This widhet displays the most recent posts with thumbnails in the sidebar.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com/
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wellthemes_recent_tags_widgets' );

function wellthemes_recent_tags_widgets() {
	register_widget( 'wellthemes_recent_tags_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_recent_tags_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function wellthemes_recent_tags_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_tags', 'description' => __('Displays the recent tags with the post count.', 'wellthemes') );

		/* Create the widget. */
		$this->WP_Widget( 'wellthemes_recent_tags_widget', __('Well Themes: Recent Tags', 'wellthemes'), $widget_ops);
	}

	/**
	 *display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;
	   
	    ?>
       <?php
	   $entries_display = $instance['entries_display'];
	  
		if(empty($entries_display)){ 
			$entries_display = 10; 
		}	
		
		$args = array(
			'number'  => $entries_display
		);
	
		$tags = get_tags($args);    
			if ($tags) {  ?>
				<ul>
					<?php
						foreach ($tags as $tag) {						
							echo '<li><a class="button" href="' . get_tag_link( $tag->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a> <span>'.$tag->count.'</span> </li> ';    
						} 
					?>
				</ul>
				<?php }; ?>
       
	   <?php
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('title' => 'Популярные метки', 'entries_display' => 10);
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'entries_display' ); ?>"><?php _e('How many entries to display?', 'wellthemes'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('entries_display'); ?>" name="<?php echo $this->get_field_name('entries_display'); ?>" value="<?php echo $instance['entries_display']; ?>" style="width:100%;" /></p>
		
	<?php
	}
}
?>