<?php
/**
 * Template Name: 1 сайдбар
 * Description: A Page Template to display page content with one only sidebar.
 *
 * @package  WellThemes
 * @file     page-full.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>
<section id="primary">
	<div id="content" class="onesidebar-page role="main">
	<?php if (have_posts()) : ?>
		<?php while ( have_posts() ) : the_post(); ?>				
			<?php get_template_part( 'content', 'page' ); ?>
		<?php endwhile; // end of the loop. ?>
	<?php endif ?>	

	</div><!-- /content -->
</section><!-- /primary -->
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>