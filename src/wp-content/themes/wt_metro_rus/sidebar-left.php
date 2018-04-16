<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package  WellThemes
 * @file     sidebar.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
 ?> 
<div id="left-sidebar" class="sidebar">
		<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>			
				
			<div class="widget widget_search">
				<?php get_search_form(); ?>
			</div>
			
			<?php the_widget('WP_Widget_Recent_Posts', 'number=5', 'before_title=<h3>&after_title=</h3>'); ?>					
					
			<div class="widget widget_categories">
				<h3><?php _e( 'Popular Categories', 'wellthemes' ); ?></h3>
				<ul><?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'title_li' => '', 'number' => 10 ) ); ?></ul>
			</div>
					
			<div class="widget widget_archive">
				<h3 class="widget-title"><?php _e( 'Archives', 'wellthemes' ); ?></h3>
				<ul>
					<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
				</ul>
			</div>
			
			<?php the_widget( 'WP_Widget_Tag_Cloud', array('title' => 'Popular tags', ), array('before_title' => '<h3>', 'after_title' => '</h3>')); ?>
			
				
		<?php endif; // end sidebar widget area ?>
		
</div><!-- /sidebar -->
		