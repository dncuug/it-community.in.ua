<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package  WellThemes
 * @file     footer.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
	</div><!-- /main -->

	<footer id="footer" role="contentinfo">
		
		<div class="footer-widgets">
			
			<?php if ( ! dynamic_sidebar( 'sidebar-3' ) ) : ?>				
				
				<div class="widget widget_text">
					<h3>Спасибо вам</h3>
					<div class="textwidget">Спасибо, что заинтересовались этой темой. Мы очень ценим ваш выбор и будем надеяться, что вы останетесь довольны.</div>
				</div>
				
				<div class="widget widget_categories">
					<h3><?php _e( 'Popular Categories', 'wellthemes' ); ?></h3>
					<ul><?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'title_li' => '', 'number' => 5 ) ); ?></ul>
				</div>
				
				<?php the_widget('WP_Widget_Recent_Posts', 'number=5', 'before_title=<h3>&after_title=</h3>'); ?>
				<?php the_widget('WP_Widget_Recent_Comments', 'number=5', 'before_title=<h3>&after_title=</h3>'); ?> 
			
			<?php endif; // end footer widget area ?>	
		
		</div><!-- /footer widgets -->
		
		<div class="footer-info">
			<?php if (wt_get_option( 'wt_footer_text_left' )){ ?> 
				<div class="footer-left">
					<?php echo wt_get_option( 'wt_footer_text_left' ); ?>			
				</div>
			<?php } ?>
			
						
        </div> <!--/foote-info -->
		
	</footer><!-- /footer -->

</div><!-- /container -->

<?php wp_footer(); ?>

</body>
</html>