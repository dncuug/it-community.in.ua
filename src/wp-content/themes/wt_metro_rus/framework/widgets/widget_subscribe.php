<?php
/*
 * Plugin Name: Well Themes: RSS Email Subscription
 * Plugin URI: http://wellthemes.com/
 * Description: A widget to display RSS email subscription form.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com/
 */

add_action( 'widgets_init', 'wellthemes_subscribe_widgets' );
function wellthemes_subscribe_widgets() {
	register_widget('wellthemes_subscribe_widget');
}

class wellthemes_subscribe_widget extends WP_Widget {

	function wellthemes_subscribe_widget() {
		$widget_ops = array( 'classname' => 'widget_subscribe', 'description' => 'Показывает форму подписки на RSS' );
		$this->WP_Widget( 'wellthemes_subscribe_widget', 'Well Themes: Подписка на RSS', $widget_ops);
	}
	
	function form($instance) {
	
		
		$instance = wp_parse_args( (array) $instance, array('title' => 'Подписка на RSS', 'subscribe_text' => 'Получайте интересные статьи с нашего сайта первыми.', 'feedid' => '') );

        $title = esc_attr($instance['title']);
		$feedid = $instance['feedid'];

?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
               <?php _e('Title', 'wellthemes'); ?>
            </label>			
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'subscribe_text' ); ?>"><?php _e('Text:', 'wellthemes'); ?></label>
			<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'subscribe_text' ); ?>" name="<?php echo $this->get_field_name( 'subscribe_text' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['subscribe_text'] ), ENT_QUOTES)); ?></textarea>
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('feedid'); ?>">
               <?php _e('Feedburner ID:', 'wellthemes'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('feedid'); ?>" name="<?php echo $this->get_field_name('feedid'); ?>" type="text" value="<?php echo $feedid; ?>" />
        </p>

<?php
    }

	function update($new_instance, $old_instance) {
        $instance=$old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
		$instance['feedid'] = $new_instance['feedid'];
        $instance['subscribe_text'] = $new_instance['subscribe_text'];
        return $instance;

    }

	function widget($args, $instance) {
	
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		if ( empty($title) ) $title = false;
		$feedid = $instance['feedid'];	
		$subscribe_text = $instance['subscribe_text'];	
		echo $before_widget;
	
		if($title){
			echo $before_title;
			echo $title; 
			echo $after_title;
		}
		
        ?>
			<p><?php echo $subscribe_text; ?></p>
            <form class="widget_rss_subscription" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $feedid ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
                <input type="text" placeholder="Ваш email" name="email"/>
                <input type="hidden" value="<?php echo $feedid ?>" name="uri"/>
                <input type="hidden" name="loc" value="en_US"/>
                <button type="submit" class="button" value="Subscribe"><span>Подписка</span></button>
            </form>
        
        <?php
        		
		echo $after_widget;
		
	}

}
?>