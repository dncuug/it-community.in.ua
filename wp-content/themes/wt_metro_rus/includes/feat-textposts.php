<?php
/**
 * The template for displaying the featured posts on homepage.
 * Gets the category for the posts from the theme options. 
 * If no category is selected, does not display.
 *
 * @package  WellThemes
 * @file     feat-textposts.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 *
 */
?>
<?php	
	$cat_id = wt_get_option('wt_feat_textposts_cat');		//number of posts
	$cat_name = get_cat_name($cat_id);					//get category name
	$cat_url = get_category_link($cat_id );				//get category url	
?>

<div id="feat-textposts">
	
	<header class="cat-header1">	
		<h6> <?php _e('Post of the day', 'wellthemes'); ?></h6>				
	</header>
		
	<div class="main-post">
		<?php 
				$args = array(
						'cat' => $cat_id,
						'post_status' => 'publish',
						'ignore_sticky_posts' => 1,
						'posts_per_page' => 1
					);
				
				$query = new WP_Query( $args );
				if ( $query -> have_posts() ) :
					while ( $query -> have_posts() ) : $query -> the_post();
						?>
						<?php wellthemes_first_post_tag_link(); ?>
						<h1>
							<a href="<?php the_permalink() ?>">
								<?php 
									//display only first 60 characters in the title.	
									$short_title = mb_substr(the_title('','',FALSE),0, 60);
									echo $short_title; 
									if (strlen($short_title) > 59){ 
										echo '...'; 
									} 
								?>	
							</a>
						</h1>
						<div class="entry-meta">			
							<span class="date"><?php the_time('F j, Y'); ?> </span>
							<span class="author"><?php the_author_posts_link(); ?></span>
							<span class="category"><?php the_category(', ' ); ?></span>
							<?php if ( comments_open() ) : ?>
								<span class="comments"><?php comments_popup_link( __('No comments', 'wellthemes'), __( '1 comment', 'wellthemes'), __('% comments', 'wellthemes')); ?></span>		
							<?php endif; ?>	
						</div><!-- /entry-meta -->
						<?php 
							the_excerpt();	
					endwhile;
				endif;
			?>
		<?php wp_reset_query();		//reset the query ?>			
	</div><!-- /left -->
	<div class="more-posts">
		<h6> <?php _e('Recent Posts', 'wellthemes'); ?></h6>			
		<?php 
			$args = array(
				'cat' => $cat_id,
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'offset' => 1,
				'posts_per_page' => 6
			);
			$query = new WP_Query( $args ); ?>
				<?php if ( $query -> have_posts() ) : ?>
						<ul>
							<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
							<li>
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
							</li>							
							<?php endwhile; ?>
						</ul>						
				<?php endif; ?>	
	<?php wp_reset_query();		//reset the query ?>	
	</div><!-- /right -->
</div> <!--/feat-posts -->