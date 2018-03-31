<?php
/**
 * The template for displaying the featured slider on homepage.
 * Gets the category for the posts from the theme options. 
 * If no category is selected, displays the latest posts.
 *
 * @package  WellThemes
 * @file     feat-slider.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 * 
 **/
?>
<?php		
	$cat_id = wt_get_option('wt_slider_category');	//get category id
	
	$args = array(
		'cat' => $cat_id,
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 5
	);
		
?>
<div id="wt-slider">	
	<ul class="slides">
		<?php $query = new WP_Query( $args ); ?>
			<?php if ( $query -> have_posts() ) : ?>
				<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
					<?php if ( has_post_thumbnail()) { ?>
							<li>
								<a href="<?php the_permalink(); ?>" >
									<?php the_post_thumbnail( 'wt-slider-img', array('title' => '') ); ?>
								</a>
			
								<div class="slider-text">
									<div class="wrap">
										<h2>
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
										</h2>
										<p>
											<?php 
												$excerpt = get_the_excerpt();
												echo mb_substr($excerpt,0, 150);
												if (strlen($excerpt) > 149){ 
													echo '...'; 
												}
											?>
										</p>
									</div>								
								</div>									
							</li>
						<?php } ?>
				<?php endwhile; ?>
			<?php endif;?>
		<?php wp_reset_query();?>				
	</ul>
	<div class="slider-nav"></div>
</div>