<?php
/*
 * Plugin Name: Well Themes: Weather
 * Plugin URI: http://wellthemes.com/
 * Description: A widget by WellThemes to display weather.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com/
 */

 /**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wellthemes_weather_widgets' );

function wellthemes_weather_widgets() {
	register_widget( 'wellthemes_weather_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_weather_widget extends WP_Widget {
	
	/**
	 * Widget setup.
	 */
	function wellthemes_weather_widget() {
		/* Widget settings */
		$widget_ops = array( 'classname' => 'widget_weather', 'description' => __('A widget to display weather in the sidebar.', 'wellthemes') );

		/* Create the widget */
		$this->WP_Widget( 'wellthemes_weather_widget', __('Well Themes: Weather', 'wellthemes'), $widget_ops );
	}
	
	/**
	 * display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$location_id = $instance['location_id'];
		$weather_unit = $instance['weather_unit'];
				
		echo $before_widget;
		
		/*--
		The location parameter needs to be a WOEID. To find your WOEID, browse or search for your city from the Weather home page. The WOEID is in the URL for the forecast page for that city. You can also get the WOEID by entering your zip code on the home page. 
		
		For example, if you search for Los Angeles on the Weather home page, the forecast page for that city is http://weather.yahoo.com/united-states/california/los-angeles-2442047/. The WOEID is 2442047.
		--*/
		
				
		if ( $weather_unit == "celsius" ){
			$weather_unit = "c";
			$unit_name = "&deg;C";
		} else {
			$weather_unit = "f";
			$unit_name = "F";
		}
		
				
		if ( !empty( $location_id ) ){			
			$interval = 3600; //one hour	
			$stored_location_id = get_option('wellthemes_weather_location_id');
			
			if( ($location_id != $stored_location_id) OR ($_SERVER['REQUEST_TIME'] > get_option('wellthemes_weather_cache_time'))) {
			
				$query_url = 'http://weather.yahooapis.com/forecastrss?w=' . $location_id . '&u=' . $weather_unit;  
				if($xml = @simplexml_load_file($query_url)){  
					$error = strpos(strtolower($xml->channel->description), 'error');	//server response but no weather data for woeid  
				}else{  
					$error = TRUE;
				}	 
	  
				if(!$error){  
					$city = $xml->channel->children('yweather', TRUE)->location->attributes()->city;  
		
					$temperature = $xml->channel->item->children('yweather', TRUE)->condition->attributes()->temp;			
					$conditions_text = $xml->channel->item->children('yweather', TRUE)->condition->attributes()->text; 
					$today_code = $xml->channel->item->children('yweather', TRUE)->condition->attributes()->code; 
			
					$humidity = $xml->channel->children('yweather', TRUE)->atmosphere->attributes()->humidity; 
					$wind = $xml->channel->children('yweather', TRUE)->wind->attributes()->speed; 
			 
					$next_day = $xml->channel->item->children('yweather', TRUE)->forecast[0]->attributes()->date;
					$next_day_low = $xml->channel->item->children('yweather', TRUE)->forecast[0]->attributes()->low;
					$next_day_high = $xml->channel->item->children('yweather', TRUE)->forecast[0]->attributes()->high;
					$next_day_code = $xml->channel->item->children('yweather', TRUE)->forecast[0]->attributes()->code;
			
					$day_after = $xml->channel->item->children('yweather', TRUE)->forecast[1]->attributes()->date;
					$day_after_low = $xml->channel->item->children('yweather', TRUE)->forecast[1]->attributes()->low;
					$day_after_high = $xml->channel->item->children('yweather', TRUE)->forecast[1]->attributes()->high;
					$day_after_code = $xml->channel->item->children('yweather', TRUE)->forecast[1]->attributes()->code;
				
					$code_types = array(
						'storm'	=> array(
							0,1,2,3,4,37,38,39,45,47
						),
						'snow'	=> array(
							7,13,14,15,16,17,18,41,42,43
						),
						'rain'	=> array(
							5,6,8,9,10,11,12,35,40
						),
						'cloudy' => array(
							19,20,21,22,23,24,25,26,27,28,29,30,44
						),
						'sunny'	=> array(
							31,32,33,34,36
						)
					);
									
					if(in_array($today_code, $code_types['storm'])) {
						$today_icon = 'storm.png';
					} elseif(in_array($today_code, $code_types['snow'])) {
						$today_icon = 'snow.png';
					} elseif(in_array($today_code, $code_types['rain'])) {
						$today_icon = 'rain.png';
					} elseif(in_array($today_code, $code_types['cloudy'])) {
						$today_icon = 'cloud.png';
					} elseif(in_array($today_code, $code_types['sunny'])) {
						$today_icon = 'sun.png';
					} else {
						$today_icon = 'mysterious.png';
					}
				
					if(in_array($next_day_code, $code_types['storm'])) {
						$next_day_icon = 'storm.png';
					} elseif(in_array($next_day_code, $code_types['snow'])) {
						$next_day_icon = 'snow.png';
					} elseif(in_array($next_day_code, $code_types['rain'])) {
						$next_day_icon = 'rain.png';
					} elseif(in_array($next_day_code, $code_types['cloudy'])) {
						$next_day_icon = 'cloud.png';
					} elseif(in_array($next_day_code, $code_types['sunny'])) {
						$next_day_icon = 'sun.png';
					} else {
						$next_day_icon = 'mysterious.png';
					}
				
					if(in_array($day_after_code, $code_types['storm'])) {
						$day_after_icon = 'storm.png';
					} elseif(in_array($day_after_code, $code_types['snow'])) {
						$day_after_icon = 'snow.png';
					} elseif(in_array($day_after_code, $code_types['rain'])) {
						$day_after_icon = 'rain.png';
					} elseif(in_array($day_after_code, $code_types['cloudy'])) {
						$day_after_icon = 'cloud.png';
					} elseif(in_array($day_after_code, $code_types['sunny'])) {
						$day_after_icon = 'sun.png';
					} else {
						$day_after_icon = 'mysterious.png';
					}
					
					if (!empty($city)){
						update_option('wellthemes_weather_cache_time', $_SERVER['REQUEST_TIME'] + $interval);
						update_option('wellthemes_weather_location_id', (string)$location_id);
						update_option('wellthemes_weather_city', (string)$city);
						update_option('wellthemes_weather_temp', (string)$temperature);
						update_option('wellthemes_weather_condition', (string)$conditions_text);
						update_option('wellthemes_weather_today_icon', (string)$today_icon);
						update_option('wellthemes_weather_humidity', (string)$humidity);
						update_option('wellthemes_weather_wind', (string)$wind);
						update_option('wellthemes_weather_nextday', (string)$next_day);
						update_option('wellthemes_weather_nextday_low', (string)$next_day_low);
						update_option('wellthemes_weather_nextday_high', (string)$next_day_high);
						update_option('wellthemes_weather_nextday_icon', (string)$next_day_icon);
						update_option('wellthemes_weather_dayafter', (string)$day_after);
						update_option('wellthemes_weather_dayafter_low', (string)$day_after_low);
						update_option('wellthemes_weather_dayafter_high', (string)$day_after_high);
						update_option('wellthemes_weather_dayafter_icon', (string)$day_after_icon);
					}				
				} // no error			
			} //update
		?>
			
			
			<div class="today">
				<div class="left">
					<img src="<?php echo get_template_directory_uri() .'/images/weather/'.get_option('wellthemes_weather_today_icon'); ?>" />
					<div class="temp"><?php echo get_option('wellthemes_weather_temp'); ?> <?php echo $unit_name; ?></div>
				</div>
				<div class="right">
					<h2><?php echo get_option('wellthemes_weather_city'); ?></h2>			
					<div class="condition"><?php echo get_option('wellthemes_weather_condition'); ?></div>
					<div class="humidity"><?php _e('Humidity:', 'wellthemes'); ?> <?php echo get_option('wellthemes_weather_humidity'); ?></div>			
					<div class="wind"><?php _e('Wind:', 'wellthemes'); ?> <?php echo get_option('wellthemes_weather_wind'); ?> <?php _e('km/h', 'wellthemes'); ?></div>		
				</div>
			</div>
		
			<div class="forecast">
				<div class="nextday">			
					<div class="date"><?php echo get_option('wellthemes_weather_nextday'); ?></div>
					<img src="<?php echo get_template_directory_uri() .'/images/weather/'.get_option('wellthemes_weather_nextday_icon'); ?>" />
					<div class="temp">
						<span class="nextday-low"><?php echo get_option('wellthemes_weather_nextday_low'); ?> <?php echo $unit_name; ?></span>
						<span class="nextday-high"><?php echo get_option('wellthemes_weather_nextday_high'); ?> <?php echo $unit_name; ?></span>			
					</div>
				</div>
			
				<div class="dayafter">			
					<div class="date"><?php echo get_option('wellthemes_weather_dayafter'); ?></div>
					<img src="<?php echo get_template_directory_uri() .'/images/weather/'.get_option('wellthemes_weather_dayafter_icon'); ?>" />
					<div class="temp">
						<span class="dayafter-low"><?php echo get_option('wellthemes_weather_dayafter_low'); ?> <?php echo $unit_name; ?></span>
						<span class="dayafter-high"><?php echo get_option('wellthemes_weather_dayafter_high'); ?> <?php echo $unit_name; ?></span>
					</div>					
				</div>        		
			</div>		
		
		<?php				
		//}  
		} 
      ?>		
		
	          
    <?php
		echo $after_widget;
	}
	
	/**
	 * update widget settings
	 */	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['location_id'] = $new_instance['location_id'];
		$instance['weather_unit'] = $new_instance['weather_unit'];			
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
			'location_id' => '',
			'weather_unit' => 'celsius'			
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		

		<p>
			<label for="<?php echo $this->get_field_id( 'location_id' ); ?>"><?php _e('Location ID:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'location_id' ); ?>" name="<?php echo $this->get_field_name( 'location_id' ); ?>" value="<?php echo $instance['location_id']; ?>" />
			<?php _e( 'Read theme documentation for info.', 'wellthemes' ); ?>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'unit' ); ?>"><?php _e('Weather unit:', 'wellthemes') ?></label>			
			<select name="<?php echo $this->get_field_name('weather_unit'); ?>" id="<?php echo $this->get_field_id('weather_unit'); ?>" class="widefat">
				<option value="celsius"<?php selected( $instance['weather_unit'], 'celsius' ); ?>><?php _e('Celsius', 'wellthemes'); ?></option>
				<option value="fahrenheit"<?php selected( $instance['weather_unit'], 'fahrenheit' ); ?>><?php _e('Fahrenheit', 'wellthemes'); ?></option>
			</select>
			
		</p>
	<?php
	}
}

?>