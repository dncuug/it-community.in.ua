<?php
/**
 * Plugin Name: Well Themes: Social Links
 * Plugin URI: http://wellthemes.com/
 * Description: A widget to display the social media links in footer or sidebar.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com/
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */

add_action( 'widgets_init', 'wellthemes_social_widgets' );

function wellthemes_social_widgets() {
	register_widget( 'wellthemes_social_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_social_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function wellthemes_social_widget() {
	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_social', 'description' => __('Displays the social media links. Suitable for sidebar and footer.', 'wellthemes') );

		/* Create the widget */
		$this->WP_Widget( 'wellthemes_social_widget', __('Well Themes: Social Links', 'wellthemes'), $widget_ops );
	}
	
	/**
	 * display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$twitter_url = $instance['twitter_url'];
		$facebook_url = $instance['facebook_url'];
		$gplus_url = $instance['gplus_url'];
		$rss_url = $instance['rss_url'];
		$contact = $instance['contact'];
		
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;		
			?>
           <ul>
		   <?php if(!empty($twitter_url)){	?>
				<li class="twitter">
				<a href="<?php echo $twitter_url; ?>" target="_blank" title="<?php _e( 'Follow us on twitter', 'wellthemes' ); ?>"><?php _e( 'Follow us on Twitter', 'wellthemes' ); ?></a>
				</li>
			<?php
			} 
			if(!empty($facebook_url)){	?>
				<li class="facebook">
				<a href="<?php echo $facebook_url; ?>" target="_blank" title="<?php _e( 'Like us on Facebook', 'wellthemes' ); ?>"><?php _e( 'Like us on Facebook', 'wellthemes' ); ?></a>
				</li>
			<?php }
			if(!empty($gplus_url)){	?>
				<li class="gplus">
				<a href="<?php echo $gplus_url; ?>" target="_blank" title="<?php _e( 'Join us on Google+', 'wellthemes' ); ?>"><?php _e( 'Join us on Google+', 'wellthemes' ); ?></a>
				</li>
			<?php }
			if(!empty($rss_url)){	?>
				<li class="rss">
				<a href="<?php echo $rss_url; ?>" target="_blank" title="<?php _e( 'Subscribe RSS Feeds', 'wellthemes' ); ?>"><?php _e( 'Subscribe RSS Feeds', 'wellthemes' ); ?></a>
				</li>
			<?php }
			if(!empty($contact)){	?>
				<li class="contact">
				<a href="<?php echo $contact; ?>" target="_blank" title="<?php _e( 'Contact us', 'wellthemes' ); ?>"><?php _e( 'Contact us', 'wellthemes' ); ?></a>
				</li>
			<?php } ?>
			
		   </ul>
        <?php
		echo $after_widget;
	}
	
	/**
	 * update widget settings
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['twitter_url'] = $new_instance['twitter_url'];
		$instance['facebook_url'] = $new_instance['facebook_url'];
		$instance['gplus_url'] = $new_instance['gplus_url'];
		$instance['rss_url'] = $new_instance['rss_url'];
		$instance['contact'] = $new_instance['contact'];
		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
	
		/* Set up some default widget settings. */
		$defaults = array(
		'title' => 'Найдите нас',
		'twitter_url' => '',
		'facebook_url' => '',
		'gplus_url' => '',
		'rss_url' => '',
		'contact' => '',
		
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<!-- Ad image url: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_url' ); ?>"><?php _e('Twitter URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_url' ); ?>" name="<?php echo $this->get_field_name( 'twitter_url' ); ?>" value="<?php echo $instance['twitter_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook_url' ); ?>"><?php _e('Facebook URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'facebook_url' ); ?>" name="<?php echo $this->get_field_name( 'facebook_url' ); ?>" value="<?php echo $instance['facebook_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'gplus_url' ); ?>"><?php _e('Google Plus URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'gplus_url' ); ?>" name="<?php echo $this->get_field_name( 'gplus_url' ); ?>" value="<?php echo $instance['gplus_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'rss_url' ); ?>"><?php _e('RSS URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'rss_url' ); ?>" name="<?php echo $this->get_field_name( 'rss_url' ); ?>" value="<?php echo $instance['rss_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'contact' ); ?>"><?php _e('Contact Page URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'contact' ); ?>" name="<?php echo $this->get_field_name( 'contact' ); ?>" value="<?php echo $instance['contact']; ?>" />
		</p>
		
	<?php
	}
}

?>