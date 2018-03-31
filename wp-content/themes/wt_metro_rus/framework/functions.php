<?php

/**
 * Tell WordPress to run max_magazine_setup() when the 'after_setup_theme' hook is run.
 */
 
add_action( 'after_setup_theme', 'max_magazine_setup' );

if ( ! function_exists( 'max_magazine_setup' ) ):

function max_magazine_setup() {

	/**
	 * Load up our required theme files.
	 */
	require( get_template_directory() . '/framework/settings/theme-options.php' );
	require( get_template_directory() . '/framework/settings/option-functions.php' );
	require( get_template_directory() . '/framework/shortcodes/wellthemes-shortcodes.php' );
	require( get_template_directory() . '/framework/shortcodes/shortcodes.php' );
	
	/**
	 * Load our theme widgets
	 */
	require( get_template_directory() . '/framework/widgets/widget_adsblock.php' );
	require( get_template_directory() . '/framework/widgets/widget_adsingle.php' );
	require( get_template_directory() . '/framework/widgets/widget_contact_form.php' );
	require( get_template_directory() . '/framework/widgets/widget_flickr.php' );
	require( get_template_directory() . '/framework/widgets/widget_recent_posts.php' );
	require( get_template_directory() . '/framework/widgets/widget_recent_posts_text.php' );
	require( get_template_directory() . '/framework/widgets/widget_popular_posts.php' );
	require( get_template_directory() . '/framework/widgets/widget_video.php' );
	require( get_template_directory() . '/framework/widgets/widget_facebook.php' );
	require( get_template_directory() . '/framework/widgets/widget_carousel.php' );
	require( get_template_directory() . '/framework/widgets/widget_social_links.php' );
	require( get_template_directory() . '/framework/widgets/widget_subscribers_count.php' );	
	require( get_template_directory() . '/framework/widgets/widget_pinterest.php' );
	require( get_template_directory() . '/framework/widgets/widget_recent_comments.php' );
	require( get_template_directory() . '/framework/widgets/widget_recent_tags.php' );
	require( get_template_directory() . '/framework/widgets/widget_latest_tweets.php' );
	require( get_template_directory() . '/framework/widgets/widget_fb_recent_posts.php' );
	require( get_template_directory() . '/framework/widgets/widget_weather.php' );
	require( get_template_directory() . '/framework/widgets/widget_google.php' );
	require( get_template_directory() . '/framework/widgets/widget_subscribe.php' );
	
	/* Add translation support.
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'wellthemes', get_template_directory() . '/languages' );
	
	/**
	 * Set the content width based on the theme's design and stylesheet.
	 */
	if ( ! isset( $content_width ) )
		$content_width = 600;
		
	/** 
	 * Add default posts and comments RSS feed links to <head>.
	 */
	add_theme_support( 'automatic-feed-links' );
	
	/**
	 * This theme styles the visual editor with editor-style.css to match the theme style.
	 */
	add_editor_style();
	
	/**
	 * Register menus
	 *
	 */
	register_nav_menus( array(
		'primary-menu' => __( 'Primary Menu', 'wellthemes' )		
	) );
	
	/**
	 * Add support for the featured images (also known as post thumbnails).
	 */
	if ( function_exists( 'add_theme_support' ) ) { 
		add_theme_support( 'post-thumbnails' );
	}
	
	/**
	 * Add custom image sizes
	 */
	add_image_size( 'wt-slider-img', 640, 320, true );		//for featured slider
	add_image_size( 'wt-feat-large', 423, 330, true );		//for featured posts
	add_image_size( 'wt-cat-img', 305, 175 , true );		//for featured category
	add_image_size( 'wt-tile-large', 270, 270 , true );		//for posts widgets
	add_image_size( 'wt-feat-img', 207, 207 , true );		//for featured posts
	add_image_size( 'wt-medium-thumb', 75, 75 , true );	//for featured categories and top posts
}
endif; // max_magazine_setup

/**
 * A safe way of adding JavaScripts to a WordPress generated page.
 */

if (!is_admin()){
    add_action('wp_enqueue_scripts', 'wellthemes_js');
}

if (!function_exists('wellthemes_js')) {

    function wellthemes_js() {
		wp_enqueue_script('wt_hoverIntent', get_template_directory_uri().'/js/hoverIntent.js',array('jquery'));
		wp_enqueue_script('wt_superfish', get_template_directory_uri().'/js/superfish.js',array('hoverIntent'));
		wp_enqueue_script('wt_slider', get_template_directory_uri() . '/js/flexslider-min.js', array('jquery')); 
		wp_enqueue_script('wt_lightbox', get_template_directory_uri() . '/js/lightbox.js', array('jquery')); 		
		wp_enqueue_script('wt_jflickrfeed', get_template_directory_uri() . '/js/jflickrfeed.min.js', array('jquery')); 
		wp_enqueue_script('wt_mobilemenu', get_template_directory_uri() . '/js/jquery.mobilemenu.js', array('jquery'));  
		wp_enqueue_script('wt_jcarousellite', get_template_directory_uri() . '/js/jcarousellite_1.0.1.min.js', array('jquery'));  
		wp_enqueue_script('wt_easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array('jquery')); 		
		wp_enqueue_script('wt_mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.js', array('jquery')); 		
		wp_enqueue_script('wt_metrojs', get_template_directory_uri() . '/js/MetroJs.min.js', array('jquery')); 		
		wp_enqueue_script('wt_custom', get_template_directory_uri() . '/js/custom.js', array('jquery')); 		
    }
	
}


/**
 * Register our sidebars and widgetized areas.
 *
 */
 
if ( function_exists('register_sidebar') ) {
	
	register_sidebar( array(
		'name' => __( 'Левая боковая панель', 'wellthemes' ),
		'id' => 'sidebar-1',
		'description' => __( 'Вставьте сюда виджеты', 'wellthemes' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Правая боковая панель', 'wellthemes' ),
		'id' => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );
	
	

	register_sidebar( array(
		'name' => __( 'Подвал', 'wellthemes' ),
		'id' => 'sidebar-3',
		'description' => __( 'Вставьте сюда виджеты', 'wellthemes' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );
}

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own wellthemes_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
if ( ! function_exists( 'wellthemes_comment' ) ) :
function wellthemes_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	global $post;
	
	switch ( $comment->comment_type ) :
		case '' :
		
		if($comment->user_id == $post->post_author) {
			$author_text = '<span class="author-comment">Author</span>';
		} else {
			$author_text = '';
		}
		
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
		
			<div class="author-avatar">
				<a href="<?php comment_author_url()?>"><?php echo get_avatar( $comment, 60 ); ?></a>
			</div>			
		
			<div class="comment-right">
						
				<div class="comment-meta"> 
					<span class="comment-author">
						<?php printf( __( '%s', 'wellthemes' ), sprintf( '<cite class="fn cufon">%s</cite>', get_comment_author_link() ) ); ?>						
					</span>
					<span class="comment-time">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<?php
							/* translators: 1: date, 2: time */
							printf( __( '%1$s at %2$s', 'wellthemes' ), get_comment_date(),  get_comment_time() ); ?></a>
					</span>
					<span class="sep">-</span>
					<span class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'wellthemes' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</span>
				
					<?php echo $author_text; ?>
					
					<?php edit_comment_link( __( '[ Edit ]', 'wellthemes' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- /comment-meta -->
			
				<div class="comment-text">
					<?php comment_text(); ?>
				</div>
		
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p class="moderation"><?php _e( 'Your comment is awaiting moderation.', 'wellthemes' ); ?></p>
				<?php endif; ?>

				<!-- /reply -->
		
			</div><!-- /comment-right -->
		
		</article><!-- /comment  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'wellthemes' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '[ Edit ]', 'wellthemes' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php	
			break;
	endswitch;
}
endif;	//ends check for wellthemes_comment()



/**
 * Pagination for archive, taxonomy, category, tag and search results pages
 *
 * @global $wp_query http://codex.wordpress.org/Class_Reference/WP_Query
 * @return Prints the HTML for the pagination if a template is $paged
 */
if ( ! function_exists( 'wt_pagination' ) ) :
function wt_pagination() {
	global $wp_query;
 
	$big = 999999999; // This needs to be an unlikely integer
 
	// For more options and info view the docs for paginate_links()
	// http://codex.wordpress.org/Function_Reference/paginate_links
	$paginate_links = paginate_links( array(
		'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'mid_size' => 5
	) );
 
	// Display the pagination if more than one page is found
	if ( $paginate_links ) {
		echo '<div class="pagination">';
		echo $paginate_links;
		echo '</div><!--// end .pagination -->';
	}
}
endif; // ends check for wt_pagination()


if ( ! function_exists( 'wellthemes_main_menu_fallback' ) ) :
	
	function wellthemes_main_menu_fallback() { ?>
		<ul class="menu">
			<?php
				wp_list_categories(array(
					'number' => 5,
					'exclude' => '1',		//exclude uncategorized posts
					'title_li' => '',
					'orderby' => 'count',
					'order' => 'DESC'  
				));
			?>  
		</ul>
    <?php
	}

endif; // ends check for wellthemes_main_menu_fallback()

if ( ! function_exists( 'wellthemes_first_post_tag_link' ) ) :
	
	function wellthemes_first_post_tag_link() {
		global $post;
		if ( $posttags = wp_get_post_tags($post->ID, array('orderby' => 'none')) ){	
			$tag = current( $posttags );
			printf(
				'<span class="tag-title"><a href="%1$s">%2$s</a></span>',
				get_tag_link( $tag->term_id ),
				esc_html( $tag->name )
			);
		}
	}
	
endif; // ends check for wellthemes_first_post_tag_link()

?>