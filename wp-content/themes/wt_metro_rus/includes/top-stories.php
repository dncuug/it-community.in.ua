<?php
/**
 * The template for displaying the top stories category posts on homepage.
 * Gets the category for the posts from the theme options. 
 * If no category is selected, does not display.
 *
 * @package  WellThemes
 * @file     top-stories.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 *
 */
?>

<?php
	$cat_id = "";
	$cat_id = wt_get_option('wt_top_posts_cat');	//get category id
	$cat_name = get_cat_name($cat_id);			//get category name
	$cat_url = get_category_link($cat_id );		//get category url
	
	$args = array(
		'cat' => $cat_id,
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 5
	);
?>

<div id="top-stories">
	<?php $query = new WP_Query( $args ); ?>
		<?php if ( $query -> have_posts() ) : ?>
			<?php $i = 0 ; ?>
			<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
			<?php $i++ ; ?>
				<div class="box">
					<?php if ( has_post_thumbnail() ) {	
						$img = wp_get_attachment_image_src( get_post_thumbnail_id(  $post->ID ), "full" );
						$img_link = $img[0];
					?>
						<div class="thumb-wrap">
							<div class="thumb">
								<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'wt-medium-thumb' ); ?></a>
							</div>
								
							<div class="overlay">
								<a class="img-link" rel="lightbox" href="<?php echo $img_link; ?>">View Image</a>
							</div>
						</div>
					<?php } ?>
					<div class="right">
						<h4>
							<a href="<?php the_permalink() ?>">
								<?php 
									//display only first 45 characters in the title.	
									$short_title = mb_substr(the_title('','',FALSE),0, 45);
									echo $short_title; 
										if (strlen($short_title) > 44){ 
											echo '...'; 
										} 
									?>	
							</a>
						</h4>
						<div class="date"><?php echo wt_time_ago(); ?></div>
						<div class="number"><?php echo $i; ?></div>
					</div>
				</div>
			<?php endwhile; ?>
		<?php endif; ?>	
	<?php wp_reset_query();		//reset the query ?>	
</div>