<?php
/*
 * Plugin Name: Well Themes: Last Tweet
 * Plugin URI: http://wellthemes.com/
 * Description: A widget to display lastest tweets in the sidebar or footer of the theme.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com/
 */

/**
 * Include required files
 */
if ( !class_exists('tmhOAuth') ) {
	require 'lib/tmhOAuth.php';
	require 'lib/tmhUtilities.php';
}

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wellthemes_last_tweet_widgets' );

function wellthemes_last_tweet_widgets() {
	register_widget( 'wellthemes_last_tweet_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_last_tweet_widget extends WP_Widget {
	
	/**
	 * Widget setup.
	 */
	function wellthemes_last_tweet_widget() {
		/* Widget settings */
		$widget_ops = array( 'classname' => 'widget_tweet', 'description' => __('A widget to display lastest tweets in the sidebar.', 'wellthemes') );

		/* Create the widget */
		$this->WP_Widget( 'wellthemes_last_tweet_widget', __('Well Themes: Lastest Tweets', 'wellthemes'), $widget_ops );
	}
	
	/**
	 * display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$username = $instance['username'];
		$post_count = $instance['post_count'];
		if( empty($post_count) ){ $post_count = '5'; }
		
		$wt_consumer_key = $instance['wt_consumer_key'];
		$wt_consumer_secret = $instance['wt_consumer_secret'];
		$wt_access_token = $instance['wt_access_token'];
		$wt_access_secret = $instance['wt_access_secret'];
		
						
		echo $before_widget;
		
		
		if((empty($wt_consumer_key)) OR (empty($wt_consumer_secret)) OR (empty($wt_access_token)) OR (empty($wt_access_secret))){
			echo '<strong>Please enter API data in widget. </strong>' . $after_widget;
			return;
		}
						
						
		if ($username){						
				$user_tweets = get_option('wt_recent_tweets');			
								
				if ((empty($user_tweets)) OR ($_SERVER['REQUEST_TIME'] > get_option('wellthemes_ltweet_cache_time'))){
					$new_tweets = $this -> fetch_tweets($username, $post_count, $wt_consumer_key, $wt_consumer_secret, $wt_access_token, $wt_access_secret);				
				}				

				if (!empty($new_tweets)){
					$user_tweets = $new_tweets;
				}
				
				if ($user_tweets){
				
					echo "<div class='user'><h3>@<a href='https://twitter.com/".$username."'>" . $username ."</a></h3></div>";
					echo "<div class='recent-tweets-list'><ul class='slides'>";				
					foreach ($user_tweets as $tweet) {					
						echo "<li>";
						$filter_tweet =  $this->filter_tweet( $tweet->tweet_text );
						echo "<div class='tweet'>" . $filter_tweet ."</div>";
						$created_time = $tweet->tweet_time;
						$time_ago = sprintf(__('%s ago', 'wellthemes'), human_time_diff(strtotime($created_time)));	
						echo "<div class='time'>" . $time_ago ."</div>";
						echo "<div class='retweet'><a target='_blank' href='https://twitter.com/home/?status=RT @".$username." ". $tweet->tweet_text ."'>Retweet</a></div>";									
						echo "</li>";
					}
					echo "</ul></div><div class='recent-tweets-nav'></div>";				
				}
			} 
			?>
	           
    <?php
		echo $after_widget;
	}
	
	/**
	 * function to fetch posts
	 */	
	function fetch_tweets($username, $post_count, $wt_consumer_key, $wt_consumer_secret, $wt_access_token, $wt_access_secret){
		$interval = 3600;
		
		$tmhOAuth = new tmhOAuth(
					array(
						'consumer_key' => $wt_consumer_key,
						'consumer_secret' => $wt_consumer_secret,
						'user_token' => $wt_access_token,
						'user_secret' => $wt_access_secret,
						'curl_ssl_verifypeer' => false 
					)
				);
		
		$request_array = array();
		$request_array['screen_name'] = $username;
		$request_array['count'] = $post_count;
		$request_array['include_rts'] = false;
		
		$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline'), $request_array);
		
		if ($code != 200) {
			return false;			
		}
		
		$response = $tmhOAuth->response['response'];	
		$tweets_content = json_decode($response);	
		
		$tweets = array();			
		foreach($tweets_content as $tweet) {
			$data = new StdClass();
			$data->tweet_text = $tweet->text;
			$data->tweet_time = $tweet->created_at;
			$tweets[] = $data;
		}
		
		if (!empty($data->tweet_text)){
			update_option('wellthemes_ltweet_cache_time', $_SERVER['REQUEST_TIME'] + $interval);
			update_option('wt_recent_tweets', $tweets);
			return $tweets;
		}			
			
		
	}
	
	 private function filter_tweet($tweet){
        $tweet = preg_replace('/(http[^\s]+)/im', '<a href="$1">$1</a>', $tweet);		//url links
        $tweet = preg_replace('/@([^\s]+)/i', '<a href="https://twitter.com/$1">@$1</a>', $tweet);	//user links       
        return $tweet;
    }
	
	/**
	 * update widget settings
	 */	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['username'] = $new_instance['username'];
		$instance['post_count'] = $new_instance['post_count'];	
		$instance['wt_consumer_key'] = $new_instance['wt_consumer_key'];	
		$instance['wt_consumer_secret'] = $new_instance['wt_consumer_secret'];	
		$instance['wt_access_token'] = $new_instance['wt_access_token'];	
		$instance['wt_access_secret'] = $new_instance['wt_access_secret'];			
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
			'username' => '',	
			'post_count' => '',	
			'wt_consumer_key' => '',	
			'wt_consumer_secret' => '',	
			'wt_access_token' => '',	
			'wt_access_secret' => '',	
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		

		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Twitter username:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e('Number of posts to display:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" value="<?php echo $instance['post_count']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'wt_consumer_key' ); ?>"><?php _e('Consumer key', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'wt_consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'wt_consumer_key' ); ?>" value="<?php echo $instance['wt_consumer_key']; ?>" />			
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'wt_consumer_secret' ); ?>"><?php _e('Consumer secret', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'wt_consumer_secret' ); ?>" name="<?php echo $this->get_field_name( 'wt_consumer_secret' ); ?>" value="<?php echo $instance['wt_consumer_secret']; ?>" />			
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'wt_access_token' ); ?>"><?php _e('Access token', 'wellthemes'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'wt_access_token' ); ?>" name="<?php echo $this->get_field_name( 'wt_access_token' ); ?>" value="<?php echo $instance['wt_access_token']; ?>" />			
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'wt_access_secret' ); ?>"><?php _e('Access token secret', 'wellthemes'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'wt_access_secret' ); ?>" name="<?php echo $this->get_field_name( 'wt_access_secret' ); ?>" value="<?php echo $instance['wt_access_secret']; ?>" />			
		</p>
		
	<?php
	}
}

?>