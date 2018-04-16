<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package  WellThemes
 * @file     header.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<meta property="fb:pages" content="1433941660200957" />

<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'wellthemes' ), max( $paged, $page ) );

	?>
</title>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<script type="text/javascript">
	var themeDir = "<?php echo get_template_directory_uri(); ?>";
</script>
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>
<body <?php body_class(); ?>>

<div id="container" class="hfeed">	
	<?php
		if (is_home() && $paged < 2 ){
			if ( wt_get_option( 'wt_show_top_posts' ) == 1 ) {
				get_template_part( 'includes/top-stories' );
			}
		}
	?>
	<header id="header" role="banner">			
		<div class="wrap">						
			<div class="logo">
			<?php if (wt_get_option( 'wt_logo_url' )) { ?>
				<h1>
					<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
						<img src="<?php echo wt_get_option( 'wt_logo_url' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
				</h1>	
			<?php } else {?>
				<h1 class="site-title">
					<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
						<?php bloginfo('name'); ?>
					</a>
				</h1> 
				<h3>
					<?php bloginfo('description'); ?>
				</h3>
			<?php } ?>	
			</div>	<!-- /logo -->	
		
			<div class="date">				
				<?php 
					//get the time zone from the WordPress settings
					$wp_timezone =  get_option('timezone_string');
					if(!empty($wp_timezone)){
						date_default_timezone_set(get_option('timezone_string'));
					}
										
					//display date in WordPress local language
					echo date_i18n('l, j F Y', time()); 
					
					//if there is problem with date, you can use following function
					//echo date('l, jS F Y');
					
				?>
			</div>
			
			<div class="search">
				<?php get_search_form(); ?>
			</div>
			
			<div class="social">
				<ul>
					<?php if (wt_get_option( 'wt_twitter_url' )) { ?>
						<li><a class="twitter" href="<?php echo wt_get_option( 'wt_twitter_url' ); ?>">Twitter</a></li>
					<?php } ?>
					
					<?php if (wt_get_option( 'wt_fb_url' )) { ?>
						<li><a class="fb" href="<?php echo wt_get_option( 'wt_fb_url' ); ?>">Facebook</a></li>
					<?php } ?>
					
					<?php if (wt_get_option( 'wt_gplus_url' )) { ?>
						<li><a class="gplus" href="<?php echo wt_get_option( 'wt_gplus_url' ); ?>">Google+</a></li>
					<?php } ?>
					
					<?php if (wt_get_option( 'wt_rss_url' )) { ?>
						<li><a class="rss" href="<?php echo wt_get_option( 'wt_rss_url' ); ?>">RSS</a></li>
					<?php } else { ?>
						<li><a class="rss" href="<?php bloginfo('rss2_url'); ?>">RSS</a></li>
					<?php } ?>					
				</ul>
			</div>
		
		
		</div> <!-- /wrap -->		
		
		<div id="main-menu">
			<?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '0', 'fallback_cb' => 'wellthemes_main_menu_fallback',) ); ?>	
		</div>
		
		<div class="clearfix"></div>
			
	</header>

	<div id="main">
