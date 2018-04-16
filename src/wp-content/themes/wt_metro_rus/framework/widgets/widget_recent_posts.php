<?php
/**
 * Plugin Name: Well Themes: Recent Posts
 * Plugin URI: http://wellthemes.com/
 * Description: This widhet displays the most recent posts with thumbnails in the sidebar.
 * Version: 1.0
 * Author: Well Themes Team
 * Author URI: http://wellthemes.com/
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wellthemes_recent_posts_widgets' );

function wellthemes_recent_posts_widgets() {
	register_widget( 'wellthemes_recent_posts_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_recent_posts_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function wellthemes_recent_posts_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_tile_posts', 'description' => __('Displays the recent posts with thumbnails. Suitable for sidebar only.', 'wellthemes') );

		/* Create the widget. */
		$this->WP_Widget( 'wellthemes_recent_posts_widget', __('Well Themes: Recent Posts', 'wellthemes'), $widget_ops);
	}

	/**
	 *display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
				
		echo $before_widget;
		
		if ( $title )
		echo $before_title . $title . $after_title;
		
		$entries_display = $instance['entries_display'];
		if(empty($entries_display)){ $entries_display = '5'; }
		$display_category = $instance['display_category'];
		$latest_posts = new WP_Query();
        $latest_posts->query('ignore_sticky_posts=1&showposts='.$entries_display.'&cat='.$display_category.'');
		$i = 0; 
		$data_delay = "3500"; ?>
		
		<div id="widget-posts-tiles">
			<?php while ($latest_posts->have_posts()) : $latest_posts->the_post();   ?>
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
						if ($count == 3){?>											
							<div class="tile-slider blue live-tile" data-stops="-100%,-200%, 0" data-mode="slide" data-direction="horizontal" data-delay=<?php echo $data_delay; ?>>
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
											echo wp_get_attachment_image( $images[$image_count]->ID, array(270, 270) );
											$image_count++;
										}
									?>
								</div>
								<div><!-- back tile --></div>
							</div>
											
						<?php											
							} else if ($count == 2){
								$img1 = wp_get_attachment_image( $images[0]->ID, array(270, 270) );
								$img2 = wp_get_attachment_image( $images[1]->ID, array(270, 270) );
												
								if ( $i % 3 == 2 ){ ?>
									<div class="red live-tile" data-mode="flip" data-delay=<?php echo $data_delay; ?>>
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
									<div class="live-tile orange" data-stops="100%" data-speed="750" data-delay=<?php echo $data_delay; ?>>
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
							} else if ($count == 1){
								$img1 = wp_get_attachment_image( $images[0]->ID, 'wt-tile-large' ); ?>
								
								<?php if($i % 3 == 2){ ?>
									<div id="tile1" class="live-tile green" data-delay=<?php echo $data_delay; ?> data-initdelay="500">        
									<div>
										<?php echo $img1 ;?>
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
												//display only first 250 characters in the excerpt.								
												$excerpt = get_the_excerpt();																
												echo mb_substr($excerpt,0, 250);									
												if (strlen($excerpt) > 249){ 
													echo '...'; 
												} 
											?>
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
										</p>
									</div>
								</div>
								<?php } else if($i % 3 == 1){ ?>
									<div class="live-tile orange" data-stack="true" data-stops="100%,50%,0" data-delay=<?php echo $data_delay; ?>>
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
											<?php echo $img1 ;?>
										</div>
										<div>
											<p>
												<?php 
													//display only first 250 characters in the excerpt.								
													$excerpt = get_the_excerpt();																
													echo mb_substr($excerpt,0, 250);									
													if (strlen($excerpt) > 249){ 
														echo '...'; 
													} 
												?>														
											</p>
										</div>
									</div>												
								<?php } else { ?>
									<div class="green live-tile" data-mode="flip" data-direction="horizontal" data-delay="6000">
										<div>
											<div><?php echo $img1 ;?></div>
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
													//display only first 250 characters in the excerpt.								
													$excerpt = get_the_excerpt();																
													echo mb_substr($excerpt,0, 250);									
													if (strlen($excerpt) > 249){ 
														echo '...'; 
													} 
												?>
											</p>
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
									</div>
							<?php }
								
							} else { //only featured image ?>
								<?php if($i % 3 == 0){ ?>
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
												//display only first 250 characters in the excerpt.								
												$excerpt = get_the_excerpt();																
												echo mb_substr($excerpt,0, 250);									
												if (strlen($excerpt) > 249){ 
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
									<div class="live-tile orange" data-stack="true" data-stops="100%,50%,0" data-delay=<?php echo $data_delay; ?>>
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
													//display only first 250 characters in the excerpt.								
													$excerpt = get_the_excerpt();																
													echo mb_substr($excerpt,0, 250);									
													if (strlen($excerpt) > 249){ 
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
													//display only first 250 characters in the excerpt.								
													$excerpt = get_the_excerpt();																
													echo mb_substr($excerpt,0, 250);									
													if (strlen($excerpt) > 249){ 
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
									<?php } ?>
							<?php } else { ?>
							<div class="blue live-tile exclude">
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
										//display only first 250 characters in the excerpt.								
										$excerpt = get_the_excerpt();																
										echo mb_substr($excerpt,0, 250);									
										if (strlen($excerpt) > 249){ 
											echo '...'; 
										} 
									?>
								</p>
							</div>
						<?php } ?>
					<?php endwhile; ?>	
				</div><!-- /tiles -->
	   <?php
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('title' => 'Последние записи', 'entries_display' => 5, 'display_category' => '');
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'entries_display' ); ?>"><?php _e('How many entries to display?', 'wellthemes'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('entries_display'); ?>" name="<?php echo $this->get_field_name('entries_display'); ?>" value="<?php echo $instance['entries_display']; ?>" style="width:100%;" /></p>
 
		<p><label for="<?php echo $this->get_field_id( 'display_category' ); ?>"><?php _e('Display specific categories? Enter category ids separated with a comma (e.g. - 1, 3, 8)', 'wellthemes'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('display_category'); ?>" name="<?php echo $this->get_field_name('display_category'); ?>" value="<?php echo $instance['display_category']; ?>" style="width:100%;" /></p>
	<?php
	}
}
?>