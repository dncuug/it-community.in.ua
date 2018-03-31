<?php
/**
 * Plugin Name: Well Themes: Google+ Widget
 * Plugin URI: http://wellthemes.com
 * Description: Displays Google+ Profile badge.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com
 *
 */

 
/**
 * Add function to widgets_init that'll load our widget.
 */ 
add_action( 'widgets_init', 'wellthemes_googleplus_widgets' );
function wellthemes_googleplus_widgets() {
	register_widget( 'wellthemes_googleplus_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
 class wellthemes_googleplus_widget extends WP_Widget {
	
	/**
	 * Widget setup.
	 */
	function wellthemes_googleplus_widget() {
		
		/* Widget settings. */
		$widget_ops = array('classname' => 'widget_googleplus','description' => __('Displays Google+ profile badge.', 'wellthemes'));
		
		/* Create the widget. */
		$this->WP_Widget('wellthemes_googleplus_widget',__('Well Themes: Google+ Badge', 'wellthemes'), $widget_ops);
		
	}
	
	/**
	 * display the widget on the screen.
	 */	
	function widget( $args, $instance ) {
		extract( $args );
		
		//user settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$page_url = $instance['page_url'];

		echo $before_widget;
		if ( $title )
			echo $before_title;
			echo $title ; ?>
		<?php echo $after_title; ?>
			<div class="google-box">
				<!-- Google +1 script -->
				<script type="text/javascript">
				  (function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>
				<!-- Link blog to Google+ page -->
				<a style='display: block; height: 0;' href="<?php echo $page_url ?>" rel="publisher">&nbsp;</a>
				<!-- Google +1 Page badge -->
				<g:plus href="<?php echo $page_url ?>" height="131" width="270" theme="light"></g:plus>

			</div>
	<?php 
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['page_url'] = strip_tags( $new_instance['page_url'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' =>__( 'Присоединяйтесь к нам на Google+' , 'wellthemes') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes') ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'page_url' ); ?>"><?php _e('Page Url:', 'wellthemes') ?></label>
			<input id="<?php echo $this->get_field_id( 'page_url' ); ?>" name="<?php echo $this->get_field_name( 'page_url' ); ?>" value="<?php echo $instance['page_url']; ?>" class="widefat" type="text" />
		</p>


	<?php
	}
}
?>