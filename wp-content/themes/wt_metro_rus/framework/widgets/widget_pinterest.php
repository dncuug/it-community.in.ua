<?php
/**
 * Plugin Name: Well Themes: Pinterest Widget
 * Plugin URI: http://wellthemes.com/
 * Description: A widget to display the pinterest feeds
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com/
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */

add_action( 'widgets_init', 'wellthemes_pinterest_widgets' );

function wellthemes_pinterest_widgets() {
	register_widget( 'wellthemes_pinterest_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_pinterest_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function wellthemes_pinterest_widget() {
	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_pinterest', 'description' => __('Displays the recent pins from pinterest.', 'wellthemes') );

		/* Create the widget */
		$this->WP_Widget( 'wellthemes_pinterest_widget', __('Well Themes: Pinterest', 'wellthemes'), $widget_ops );
	}
	
	/**
	 * display the widget on the screen.
	 */
	function widget($args, $instance){
	 
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );		
		echo $before_widget;		
		$title = ( $title ) ? $title : 'Recent Pins';		
		echo $before_title . $title . $after_title;
		
		if( !empty( $instance['username'] ) ) {		
		if ( !empty( $instance['number_of_pins'] )  && is_int( $instance['number_of_pins'] ) ) {
			$number_of_pins = esc_attr ( $instance['number_of_pins'] );
		} else {
			$number_of_pins = 6;
		}
		if( !empty( $instance['specific_board'] ) ) {	
			$feed_url = 'http://pinterest.com/'.$instance['username'].'/'.$instance['specific_board'].'/rss';
		} else {
			$feed_url = 'http://pinterest.com/'.$instance['username'].'/feed.rss';	
		}
		
		//get rss feed
		$latest_pins = $this->get_rss_feed( $instance['username'], $instance['number_of_pins'], $feed_url );
		?>
	
		<ul id="recent-pins-widget">			
		<?php 
			if(!empty( $latest_pins ) ){
				foreach ( $latest_pins as $item ):
					$rss_pin_description = $item->get_description();			
					preg_match('/<img[^>]+>/i', $rss_pin_description, $pin_image); 
					?>
				<li class="recent-pin">
					<div class="pinterest-image overlay">
						<a href="<?php echo esc_url( $item->get_permalink() ); ?>" title="<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>"><?php echo $pin_image[0];?></a>			
					</div>
				</li>
				<?php endforeach; 
			}
			if( $instance['show_follow_button'] ){
			?>
			<li class="pinterest-follow-me"><a href="http://pinterest.com/<?php echo $instance['username'];?>/" target="_blank"><img src="http://passets-lt.pinterest.com/images/about/buttons/follow-me-on-pinterest-button.png" width="156" height="26" alt="Follow Me on Pinterest" /></a></li>
			<?php
			}
			?>		
		</ul>	
			
				
		<?php		
		}		
		echo $after_widget;
	}
	
	function get_rss_feed( $username, $number_of_pins, $feed_url ){				
	
		$rss = fetch_feed( $feed_url );
		if (!is_wp_error( $rss ) ) : 
		
			$maxitems = $rss->get_item_quantity( $number_of_pins ); 
			$rss_items = $rss->get_items( 0, $maxitems ); 
		endif;		
		return $rss_items;
	}
	
	function trim_text( $text, $length ) {
	
		$text = strip_tags( $text );	  

		if (strlen($text) <= $length) {
			return $text;
		}		
		$last_space = strrpos( mb_substr( $text, 0, $length ), ' ');
		$trimmed_text = mb_substr( $text, 0, $last_space );		
		$trimmed_text .= '...';	  
		return $trimmed_text;
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance){
		$instance = wp_parse_args( $old_instance, $new_instance );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number_of_pins'] = strip_tags($new_instance['number_of_pins']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['specific_board'] = strip_tags($new_instance['specific_board']);
		$instance['show_follow_button'] = strip_tags($new_instance['show_follow_button']);
		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Последние Pins', 'username' => '', 'number_of_pins' => '6', 'show_follow_button' => '0', 'specific_board' => '') );
		
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
			$number_of_pins = esc_attr( $instance[ 'number_of_pins' ] );
			$username = esc_attr( $instance[ 'username' ] );
			$specific_board = esc_attr( $instance[ 'specific_board' ] );	
			$show_follow_button = esc_attr( $instance[ 'show_follow_button' ] );			
		}		
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:', 'wellthemes'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Pinterest Username:', 'wellthemes'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('specific_board'); ?>"><?php _e('Specific Board (optional):', 'wellthemes'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('specific_board'); ?>" name="<?php echo $this->get_field_name('specific_board'); ?>" type="text" value="<?php echo $specific_board; ?>" />
		</p>		
		<p>
		<label for="<?php echo $this->get_field_id('number_of_pins'); ?>"><?php _e('Number of Pins To Show:', 'wellthemes'); ?></label>		
		<select name="<?php echo $this->get_field_name( 'number_of_pins' );?>">
		<?php 
		for ( $i = 1; $i <= 25; ++$i ){?>
			<option value="<?php echo $i;?>" <?php selected( $number_of_pins, $i );?>><?php echo $i;?></option>
		<?php
		}
		?>		
		</select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('show_follow_button'); ?>"><?php _e('Show "Follow Me" Button?:', 'wellthemes'); ?></label>
		<input type="checkbox" name="<?php echo $this->get_field_name('show_follow_button')?>" value="1" <?php checked( $show_follow_button, 1 ); ?> />	
		</p>
		
		<?php
	}
	
	
}

?>