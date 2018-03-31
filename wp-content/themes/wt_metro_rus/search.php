<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package  WellThemes
 * @file     search.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>

		<section id="primary">
			<div id="content" role="main">
				<div class="archive">
				
					<?php if ( have_posts() ) : ?>

						<header class="page-header">
							<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'wellthemes' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
						</header>
				
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
								/* Include the Post-Format-specific template for the content.
								* If you want to overload this in a child theme then include a file
								* called content-___.php (where ___ is the Post Format name) and that will be used instead.
								*/
								get_template_part( 'content', 'excerpt');
							?>

						<?php endwhile; ?>
						<?php wt_pagination(); ?>
					
					<?php else : ?>

						<article id="post-0" class="post no-results not-found">
							<header class="entry-header">
								<h1 class="entry-title"><?php _e( 'Nothing Found', 'wellthemes' ); ?></h1>
							</header><!-- /entry-header -->

							<div class="entry-content">
								<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'wellthemes' ); ?></p>
								<?php get_search_form(); ?>
							</div><!-- /entry-content -->
						</article><!-- /post-0 -->

					<?php endif; ?>
				</div><!-- /search-results -->
			</div><!-- /content -->
		</section><!-- /primary -->

<?php get_sidebar('left'); ?>		
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>