<?php
/**
 * The template for displaying the featured posts on homepage.
 * Gets the category for the posts from the theme options. 
 * If no category is selected, does not display.
 *
 * @package  WellThemes
 * @file     feat-posts2.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 *
 */
?>
<?php	
	$cat_id = wt_get_option('wt_feat_posts_cat');		//number of posts
	$cat_name = get_cat_name($cat_id);					//get category name
	$cat_url = get_category_link($cat_id );				//get category url	
?>

<div id="feat-posts">
	<header class="cat-header">	
		<?php
			if ($cat_id == 0 ) {	?>								
				<h3> <?php _e('Latest Posts', 'wellthemes'); ?></h3>	
				<a class="rss" href="<?php bloginfo('rss2_url'); ?>" >RSS</a>
			<?php					
			} else {													
				?>
				<h3><a href="<?php echo esc_url( $cat_url ); ?>" ><?php echo $cat_name; ?></a></h3>	
				<a class="rss" href="<?php home_url(); ?>?cat=<?php echo $cat_id; ?>&feed=rss2" >RSS</a>
				<?php
			}						
		?>
	</header>
		
	<div class="left">
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
						if ( has_post_thumbnail() ) { 
							$img = wp_get_attachment_image_src( get_post_thumbnail_id(  $post->ID ), "full" );
							$img_link = $img[0];
							?>
							<div class="thumb-wrap">
								<div class="thumb">
									<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'wt-feat-large' ); ?></a>
								</div>
								<div class="overlay">
									<a class="img-link" rel="lightbox" href="<?php echo $img_link; ?>">View Image</a>
									<a class="post-link" href="<?php the_permalink() ?>">View Post</a>							
								</div>
							</div>
						<?php }	?>
						<div class="post-text">
							<h2>
									<a href="<?php the_permalink() ?>">
										<?php 
											//display only first 35 characters in the title.	
											$short_title = mb_substr(the_title('','',FALSE),0, 35);
											echo $short_title; 
											if (strlen($short_title) > 34){ 
												echo '...'; 
											} 
										?>	
									</a>
								</h2>
								<p>
									<?php 
									$excerpt = get_the_excerpt();
										echo mb_substr($excerpt,0, 100);
										if (strlen($excerpt) > 99){
											echo '...'; 
										}
									?>
								</p>
						</div>
						<?php
					endwhile;
				endif;
			?>
		<?php wp_reset_query();		//reset the query ?>			
	</div><!-- /left -->
	<div class="right">
		<?php 
			$args = array(
				'cat' => $cat_id,
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'offset' => 1,
				'posts_per_page' => 2
			);
			$query = new WP_Query( $args ); ?>
				<?php if ( $query -> have_posts() ) : ?>
					<?php $i = 0; 
						$data_delay = "3000";
					?>
					<div id="tiles">
						<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
							<?php $i++; 
								$data_delay = $data_delay + 1000;
							?>
							<?php if ( has_post_thumbnail() ) {	?>
								<?php
									global $post;  
									$args = array( 
										'post_parent'	=> $post->ID, 
										'post_type' => 'attachment', 
										'post_mime_type' => 'image', 
										'orderby' => 'menu_order', 
										'order' => 'ASC', 
										'numberposts' => 3 );
										
										$images = get_posts($args);
										$count = count($images);
										
										if ($count == 3){											
											?>											
											<div class="tile-slider green live-tile" data-stops="-100%,-200%, 0" data-mode="slide" data-direction="horizontal" data-delay=<?php echo $data_delay; ?>>
												<span class="tile-title">
													<a href="<?php the_permalink() ?>" rel="bookmark">
															<?php 
																//display only first 60 characters in the title.	
																$short_title = mb_substr(the_title('','',FALSE),0, 60);
																echo $short_title; 
																if (strlen($short_title) > 59){ 
																	echo '...'; 
																} 
															?>	
													</a>
												</span>
												<div class="wide-slide">													
													<?php 
														$image_count = 0;													
														while($image_count <= 2){
															echo wp_get_attachment_image( $images[$image_count]->ID, array(207,207) );
															$image_count++;
														}
													?>
													
												</div>
												<div><!-- back tile --></div>
											</div>
											
										<?php											
										} else if ($count == 2){
											$img1 = wp_get_attachment_image( $images[0]->ID, array(207,207) );
											$img2 = wp_get_attachment_image( $images[1]->ID, array(207,207) );
												
											if ( $i % 3 == 2 ){ ?>
												<div class="green live-tile" data-mode="flip" data-delay=<?php echo $data_delay; ?>>
													<div>
														<div><?php echo $img1 ;?></div>														
														<span class="tile-title">
														<a href="<?php the_permalink() ?>" rel="bookmark">
															<?php 
																//display only first 60 characters in the title.	
																$short_title = mb_substr(the_title('','',FALSE),0, 60);
																echo $short_title; 
																if (strlen($short_title) > 59){ 
																	echo '...'; 
																} 
															?>	
														</a>
														</span>														
													</div>
													<div>
														<div><?php echo $img2 ;?></div>
														<span class="tile-title">
														<a href="<?php the_permalink() ?>" rel="bookmark">
															<?php 
																//display only first 60 characters in the title.	
																$short_title = mb_substr(the_title('','',FALSE),0, 60);
																echo $short_title; 
																if (strlen($short_title) > 59){ 
																	echo '...'; 
																} 
															?>	
														</a>
														</span>
													</div>		
												</div>
											<?php } else if ( $i % 3 == 1 ){ ?>
													<div class="live-tile green" data-stops="100%" data-speed="750" data-delay=<?php echo $data_delay; ?>>
														<span class="tile-title">
															<a href="<?php the_permalink() ?>" rel="bookmark">
																<?php 
																	//display only first 60 characters in the title.	
																	$short_title = mb_substr(the_title('','',FALSE),0, 60);
																	echo $short_title; 
																	if (strlen($short_title) > 59){ 
																		echo '...'; 
																	} 
																?>	
															</a>
														</span>
														
														<div>
															<div><?php echo $img1 ;?></div>
														</div>
														<div>
															<div><?php echo $img2 ;?></div>
														</div>
													</div>
												<?php } else { ?>
												
												<div class="green live-tile" data-mode="flip" data-direction="horizontal" data-delay=<?php echo $data_delay; ?>>
													<div>
														<div><?php echo $img1 ;?></div>
															<span class="tile-title">
															<a href="<?php the_permalink() ?>" rel="bookmark" >
																<?php 
																	//display only first 60 characters in the title.	
																	$short_title = mb_substr(the_title('','',FALSE),0, 60);
																	echo $short_title; 
																	if (strlen($short_title) > 59){ 
																		echo '...'; 
																	} 
																?>	
															</a>
															</span>
													</div>
													<div>
														<div><?php echo $img2 ;?></div>
															<span class="tile-title">
															<a href="<?php the_permalink() ?>" rel="bookmark">
																<?php 
																	//display only first 60 characters in the title.	
																	$short_title = mb_substr(the_title('','',FALSE),0, 60);
																	echo $short_title; 
																	if (strlen($short_title) > 59){ 
																		echo '...'; 
																	} 
																?>	
															</a>
															</span>
													</div>		
												</div>
											<?php }
										} else { //only featured image ?>
											<?php if($i % 3 == 2){ ?>
												<div id="tile1" class="live-tile green" data-delay=<?php echo $data_delay; ?> data-initdelay="500">        
													<div>
														<?php the_post_thumbnail( 'wt-feat-img' ); ?>
														<span class="tile-title">
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
														</span>
													</div>
													<div>
														<p>
															<?php 
																//display only first 125 characters in the excerpt.								
																$excerpt = get_the_excerpt();																
																echo mb_substr($excerpt,0, 125);									
																if (strlen($excerpt) > 124){ 
																	echo '...'; 
																} 
															?>
															<span class="tile-title">
																<a href="<?php the_permalink() ?>" rel="bookmark">
																	<?php 
																		//display only first 60 characters in the title.	
																		$short_title = mb_substr(the_title('','',FALSE),0, 60);
																		echo $short_title; 
																		if (strlen($short_title) > 59){ 
																			echo '...'; 
																		} 
																	?>	
																</a>
															</span>
														</p>
													</div>
												</div>
											<?php } else if($i % 3 == 1){ ?>
												
												<div class="live-tile green" data-stack="true" data-stops="100%,50%,0" data-delay=<?php echo $data_delay; ?>>
													<span class="tile-title">
														<a href="<?php the_permalink() ?>" rel="bookmark">
															<?php 
																//display only first 60 characters in the title.	
																$short_title = mb_substr(the_title('','',FALSE),0, 60);
																echo $short_title; 
																if (strlen($short_title) > 59){ 
																	echo '...'; 
																} 
															?>	
														</a>
													</span>
													<div>
														<?php the_post_thumbnail( 'wt-feat-img' ); ?>
													</div>
													<div>
														<p>
															<?php 
																//display only first 125 characters in the excerpt.								
																$excerpt = get_the_excerpt();									
																echo mb_substr($excerpt,0, 125);							
																if (strlen($excerpt) > 124){ 
																	echo '...'; 
																} 
															?>														
														</p>
													</div>
												</div>												
											<?php } else { ?>
												<div class="green live-tile" data-mode="flip" data-direction="horizontal" data-delay="6000">
													<div>
														<div><?php the_post_thumbnail( 'wt-feat-img' ); ?></div>
														<span class="tile-title ">
														<a href="<?php the_permalink() ?>" rel="bookmark">
															<?php 
																//display only first 60 characters in the title.	
																$short_title = mb_substr(the_title('','',FALSE),0, 60);
																echo $short_title; 
																if (strlen($short_title) > 59){ 
																	echo '...'; 
																} 
															?>	
														</a>
														</span>
													</div>
													<div> 
														<p>
														<?php 
																//display only first 125 characters in the excerpt.								
																$excerpt = get_the_excerpt();																
																echo mb_substr($excerpt,0, 125);									
																if (strlen($excerpt) > 124){ 
																	echo '...'; 
																} 
															?>
														</p>
														<span class="tile-title">
														<a href="<?php the_permalink() ?>" rel="bookmark">
															<?php 
																//display only first 60 characters in the title.	
																$short_title = mb_substr(the_title('','',FALSE),0, 60);
																echo $short_title; 
																if (strlen($short_title) > 59){ 
																	echo '...'; 
																} 
															?>	
														</a>
														</span>
													</div>		
												</div>
												
											<?php } ?>
										<?php }
									?>
							<?php } else { ?>
								<div class="green live-tile exclude">
									<span class="tile-title">
										<a href="<?php the_permalink() ?>" rel="bookmark">
											<?php 
												//display only first 60 characters in the title.	
												$short_title = mb_substr(the_title('','',FALSE),0, 60);
												echo $short_title; 
												if (strlen($short_title) > 59){ 
													echo '...'; 
												} 
											?>	
										</a>
									</span>
									<p>
										<?php 
											//display only first 125 characters in the excerpt.								
											$excerpt = get_the_excerpt();																
											echo mb_substr($excerpt,0, 125);									
											if (strlen($excerpt) > 124){ 
												echo '...'; 
											} 
										?>
									</p>
								</div>
							<?php } ?>
						<?php endwhile; ?>	
					</div><!-- /tiles -->
				<?php endif; ?>	
	<?php wp_reset_query();		//reset the query ?>
	
	</div><!-- /right -->
</div> <!--/feat-posts -->