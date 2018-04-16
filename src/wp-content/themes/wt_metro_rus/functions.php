<?php
//update_option('siteurl','http://www.it-community.in.ua');
//update_option('home','http://www.it-community.in.ua');

require( get_template_directory() . '/framework/functions.php' );

/**
 * Set the format for the more in excerpt, return ... instead of [...]
 */ 
function wellthemes_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'wellthemes_excerpt_more');

function wt_time_ago() {
 
	global $post;
 
	$date = get_post_time('G', true, $post);
  
	// Array of time period chunks
	$chunks = array(
		array( 60 * 60 * 24 * 365 , __( 'year', 'wellthemes' ), __( 'years', 'wellthemes' ) ),
		array( 60 * 60 * 24 * 30 , __( 'month', 'wellthemes' ), __( 'months', 'wellthemes' ) ),
		array( 60 * 60 * 24 * 7, __( 'week', 'wellthemes' ), __( 'weeks', 'wellthemes' ) ),
		array( 60 * 60 * 24 , __( 'day', 'wellthemes' ), __( 'days', 'wellthemes' ) ),
		array( 60 * 60 , __( 'hour', 'wellthemes' ), __( 'hours', 'wellthemes' ) ),
		array( 60 , __( 'minute', 'wellthemes' ), __( 'minutes', 'wellthemes' ) ),
		array( 1, __( 'second', 'wellthemes' ), __( 'seconds', 'wellthemes' ) )
	);
 
	if ( !is_numeric( $date ) ) {
		$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
		$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
		$date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
	}
 
	$current_time = current_time( 'mysql', $gmt = 0 );
	$newer_date = strtotime( $current_time );
 
	// Difference in seconds
	$since = $newer_date - $date;
 
	// Something went wrong with date calculation and we ended up with a negative date.
	if ( 0 > $since )
		return __( 'sometime', 'wellthemes' );
 
	/**
	 * We only want to output one chunks of time here, eg:
	 * x years
	 * xx months
	 * so there's only one bit of calculation below:
	 */
 
	//Step one: the first chunk
	for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];
 
		// Finding the biggest chunk (if the chunk fits, break)
		if ( ( $count = floor($since / $seconds) ) != 0 )
			break;
	}
 
	// Set output var
	$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
  
	if ( !(int)trim($output) ){
		$output = '0 ' . __( 'seconds', 'wellthemes' );
	}
 
	$output .= __(' ago', 'wellthemes');
 
	return $output;
}

?>