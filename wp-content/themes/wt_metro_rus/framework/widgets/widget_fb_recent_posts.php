<?php
/*
 * Plugin Name: Well Themes: Facebook Recent Posts
 * Plugin URI: http://wellthemes.com/
 * Description: A widget to display last tweets in the sidebar or footer of the theme.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com/
 */

 /**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wellthemes_last_fb_post_widgets' );

function wellthemes_last_fb_post_widgets() {
	register_widget( 'wellthemes_last_fb_post_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_last_fb_post_widget extends WP_Widget {
	
	/**
	 * Widget setup.
	 */
	function wellthemes_last_fb_post_widget() {
		/* Widget settings */
		$widget_ops = array( 'classname' => 'widget_fbpost', 'description' => __('A widget to display recent facebook posts in the sidebar.', 'wellthemes') );

		/* Create the widget */
		$this->WP_Widget( 'wellthemes_last_fb_post_widget', __('Well Themes: Facebook Recent Posts', 'wellthemes'), $widget_ops );
	}
	
	/**
	 * display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$user_id = $instance['user_id'];
		$app_id = $instance['app_id'];
		$secret_code = $instance['secret_code'];
		$post_count = $instance['post_count'];
		if( empty($post_count) ){ $post_count = '5'; }
		
		echo $before_widget;
				
		if (($user_id) and ($app_id) and ($secret_code)){
				$user_data = get_option('wt_recent_fb_posts_user');
												
				if ((!$user_data) OR ($user_data[0]->id !== $user_id)){
					$user_data = $this -> fetch_userdata($user_id);				
				}				
				
				$fb_posts = get_transient('wt_recent_fb_posts');				
				if (!$fb_posts){ //no messages, fetch from facebook				
					$fb_posts = $this->fetch_fbposts($user_id, $post_count, $app_id, $secret_code);				
				} else {
					$fb_posts = get_transient( 'wt_recent_fb_posts' );					
				}
				
				if(isset($user_data[0]->name)){
					echo "<div class='user'><h3><a href='".$user_data[0]->url."' target='_blank'>".$user_data[0]->name."</a></h3></div>";
				} else {
					echo "<div class='user'>Facebook</div>";
				}
				
				echo "<div class='fb-posts-list'><ul class='slides'>";
				
				foreach ($fb_posts as $fb_post) {
					echo "<li>";
					echo "<div class='message'>".$fb_post->message."</div>";
					$created_time = $fb_post->created_time;
					$time_ago = sprintf(__('%s ago', 'wellthemes'), human_time_diff(strtotime($created_time)));	
					echo "<div class='time'>".$time_ago."</div>";									
					echo "</li>";
				}
				echo "</ul></div><div class='fb-posts-nav'></div>";				
			} 
			?>		
		
		
			
		</script>            
    <?php
		echo $after_widget;
	}
	
	function fetch_userdata($user_id){
		$profile_data = wp_remote_retrieve_body(wp_remote_get('http://graph.facebook.com/' . $user_id));
		
		$user_data = array();
		if (!is_wp_error($profile_data)) {
			$profile_content = json_decode($profile_data, true);
			
			$fb_user = new stdClass();
			$fb_user->name =  $profile_content['name'];
			$fb_user->url =  $profile_content['link'];
			$fb_user->id =  $profile_content['id'];			
			
			$user_data[] = $fb_user;
			
			update_option('wt_recent_fb_posts_user', $user_data); // 30 minutes
			return $user_data;
		}
		
		
	}
	/**
	 * function to fetch posts
	 */	
	function fetch_fbposts($user_id, $post_count, $app_id, $secret_code){
				
					$graph_url =  "https://graph.facebook.com/oauth/access_token?client_id=".$app_id."&client_secret=".$secret_code."&grant_type=client_credentials";
					$data = wp_remote_get( $graph_url, array( 'sslverify' => false ) );
					
					if (!is_wp_error($data)) {
						$token = $data['body'];
						if (strpos($token,'access_token=') !== false) {
							$access_token = str_replace('access_token=','',$token);						
						}
					}
					
					if ($access_token){					
						
						$fb_url = "https://graph.facebook.com/".$user_id."?fields=posts&access_token=".$access_token;
						$filecontent = wp_remote_retrieve_body(wp_remote_get($fb_url,  array( 'sslverify' => false )));
						
						if (!is_wp_error($filecontent)) {
							$json = json_decode($filecontent, true);							
							
							$posts = array();
							foreach ($json['posts']['data'] as $postKey => $post) {								
								
								if (isset($post['message'])) {
									if ( $post_count-- === 0 ) break;
									$fb_post = new stdClass();
									$fb_post->user = $post['from']['name'];
									$fb_post->message =  $post['message'];
									$fb_post->created_time = $post['created_time'];
																	
									$posts[] = $fb_post;									
								}
							
							}					
							
							set_transient('wt_recent_fb_posts', $posts, 60 * 30); // 30 minutes
							return $posts;
						}
					}
				}
	
	/**
	 * update widget settings
	 */	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['user_id'] = $new_instance['user_id'];	
		$instance['app_id'] = $new_instance['app_id'];	
		$instance['secret_code'] = $new_instance['secret_code'];
		$instance['post_count'] = $new_instance['post_count'];
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
			'user_id' => '',
			'app_id' => '',			
			'secret_code' => '',
			'post_count' => 5	
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'user_id' ); ?>"><?php _e('User ID:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'user_id' ); ?>" name="<?php echo $this->get_field_name( 'user_id' ); ?>" value="<?php echo $instance['user_id']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'app_id' ); ?>"><?php _e('App ID:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'app_id' ); ?>" name="<?php echo $this->get_field_name( 'app_id' ); ?>" value="<?php echo $instance['app_id']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'secret_code' ); ?>"><?php _e('App Secret Code:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'secret_code' ); ?>" name="<?php echo $this->get_field_name( 'secret_code' ); ?>" value="<?php echo $instance['secret_code']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e('Number of posts to display:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" value="<?php echo $instance['post_count']; ?>" />
		</p>
		
	<?php
	}
}

?>