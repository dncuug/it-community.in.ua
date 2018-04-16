<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package  WellThemes
 * @file     tag.php
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
						<h1 class="page-title"><?php printf( __( 'Tag Archives: %s', 'wellthemes' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h1>
						<?php
							$tag_description = tag_description();
							if (( wt_get_option( 'wt_show_archive_tag_info' ) == 1 ) AND ( ! empty( $tag_description ))) {
								echo apply_filters( 'tag_archive_meta', '<div class="archive-meta">' . $tag_description . '</div>' );
							}
						?>
					</header>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
					<?php
						/* Include the Post-Format-specific template for the content.
						* If you want to overload this in a child theme then include a file
						* called content-___.php (where ___ is the Post Format name) and that will be used instead.
						*/
						get_template_part( 'content', 'excerpt' );
					?>

					<?php endwhile; ?>
					<?php wt_pagination(); ?>
				<?php else : ?>

					<article id="post-0" class="post no-results not-found">
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'Nothing Found', 'wellthemes' ); ?></h1>
						</header>

						<div class="entry-content">
							<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'wellthemes' ); ?></p>
							<?php get_search_form(); ?>
						</div>
					</article><!-- /post-0 -->

				<?php endif; ?>
			</div> <!-- /archive -->
		</div><!-- /content -->
	</section><!-- /primary -->

<?php get_sidebar('left'); ?>
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>
