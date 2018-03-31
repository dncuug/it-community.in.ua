<?php
/**
 * The template for displaying image attachments.
 *
 * @package  WellThemes
 * @file     image.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>
<section id="primary">
	<div id="content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
				<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>

							<div class="entry-meta">
								<span class="date"><?php the_time('F j, Y'); ?> </span>
								 
								<span class="image-link"><?php _e('Original Image size: ', 'wellthemes'); ?>
									<?php
										$metadata = wp_get_attachment_metadata();
										printf( __( '<a href="%3$s" rel="lightbox">%1$s &times; %2$s</a>', 'wellthemes' ),
											$metadata['width'],
											$metadata['height'],
											esc_url( wp_get_attachment_url() )										
										);
									?>							
								</span>								
							</div><!-- /entry-meta -->

						</header><!-- /entry-header -->

						<div class="entry-content">

							<div class="entry-attachment">
								<div class="attachment">
									<?php
										/**
										* Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
										* or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
										*/
										$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
										foreach ( $attachments as $k => $attachment ) {
											if ( $attachment->ID == $post->ID )
												break;
										}
										$k++;
	
										// If there is more than 1 attachment in a gallery
										if ( count( $attachments ) > 1 ) {
											if ( isset( $attachments[ $k ] ) )
												// get the URL of the next image attachment
												$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
											else
												// or get the URL of the first image attachment
												$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
										} else {
												// or, if there's only 1 image, get the URL of the image
												$next_attachment_url = wp_get_attachment_url();
										}
									?>
									
									<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php
									$attachment_size = apply_filters( 'twentyeleven_attachment_size', 848 );
									echo wp_get_attachment_image( $post->ID, array( $attachment_size, 1024 ) ); // filterable image width with 1024px limit for image height.
									?></a>

									<?php if ( ! empty( $post->post_excerpt ) ) : ?>
									<div class="entry-caption">
										<?php the_excerpt(); ?>
									</div>
									<?php endif; ?>
								</div><!-- /attachment -->

							</div><!-- /entry-attachment -->

							<div class="entry-description">
								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'wellthemes' ) . '</span>', 'after' => '</div>' ) ); ?>
							</div><!-- /entry-description -->
							

						</div><!-- /entry-content -->
						
						<nav id="nav-single">
							<span class="nav-previous"><?php previous_image_link( false, __( '&larr; Previous' , 'wellthemes' ) ); ?></span>
							<span class="nav-next"><?php next_image_link( false, __( 'Next &rarr;' , 'wellthemes' ) ); ?></span>
						</nav><!-- /nav-single -->
						
						<div class="image-post-link">
							<a href="<?php echo get_permalink($post->post_parent) ?>" title="<?php printf( __( 'Return to %s', 'wellthemes' ), esc_html( get_the_title($post->post_parent), 1 ) ) ?>" rev="attachment"><?php echo get_the_title($post->post_parent) ?></a>
						</div>
								
						<footer class="entry-footer">
							<?php if ( wt_get_option( 'wt_show_img_social' ) == 1 ) { ?>
								<div class="entry-social">
		
								<span class="fb">
									<div id="fb-root"></div>
									<script>
										(function(d, s, id) {
										var js, fjs = d.getElementsByTagName(s)[0];
										if (d.getElementById(id)) return;
										js = d.createElement(s); js.id = id;
										js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
										fjs.parentNode.insertBefore(js, fjs);
										}(document, 'script', 'facebook-jssdk'));
									</script>
									<div class="fb-like" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
								</span>
			
								<span class="twitter">			
									<script src="https://platform.twitter.com/widgets.js" type="text/javascript"></script>
									<a href="https://twitter.com/share" class="twitter-share-button"
										data-url="<?php the_permalink(); ?>"
										data-text="<?php the_title(); ?>"
										data-count="horizontal">Tweet
									</a>
								</span>
			
								<span class="gplus">	
									<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
									<div class="g-plusone" data-size="medium"></div>
								</span>
			
								<span class="pinterest">
									<a href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' ); echo $thumb['0']; ?>&description=<?php the_title(); ?>" class="pin-it-button" count-layout="horizontal">Pin It</a><script type="text/javascript" src="https://assets.pinterest.com/js/pinit.js"></script>
								</span>
			
								<span class="linkedin">	
								<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
									<script type="IN/Share" data-counter="right"></script>
								</span>	
				  
								</div><!-- /entry-social -->
							<?php } ?>
						</footer>
				</article><!-- /post-<?php the_ID(); ?> -->

				<?php if ( wt_get_option( 'wt_show_img_comments' ) == 1 ) { ?>
					<?php comments_template( '', true ); ?>	
				<?php } ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- /content -->	
</section><!-- /primary -->	
<?php get_sidebar('left'); ?>		
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>