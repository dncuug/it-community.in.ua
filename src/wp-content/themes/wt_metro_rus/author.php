<?php
/**
 * The template for displaying Author Archive pages.
 *
 * @package  WellThemes
 * @file     author.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>

		<section id="primary">
			<div id="content" role="main">
				<div class="archive">
					
					<?php if ( have_posts() ) : ?>

						<?php
							/* Queue the first post, that way we know
							* what author we're dealing with (if that is the case).
							*
							* We reset this later so we can run the loop
							* properly with a call to rewind_posts().
							*/
							the_post();
						?>

						<header class="page-header">
							<h1 class="page-title author"><?php printf( __( 'Author Archives: %s', 'wellthemes' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
						</header>

						<?php
							/* Since we called the_post() above, we need to
							* rewind the loop back to the beginning that way
							* we can run the loop properly, in full.
							*/
							rewind_posts();
						?>

						<?php
						// If a user has filled out their description, show a bio on their entries.
						if (( wt_get_option( 'wt_show_archive_author_info' ) == 1 ) AND ( get_the_author_meta( 'description' ))) {?>
							<div class="archive-meta archive-author-info">							
								<div class="author-avatar">
									<?php echo get_avatar( get_the_author_meta( 'user_email' ), 150 ); ?>
								</div>	
								
								<div class="author-description">								
									<h4><?php printf( __( 'About %s', 'wellthemes' ), get_the_author() ); ?></h4>
									<?php the_author_meta( 'description' ); ?>																		
								</div><!-- /author-description -->
							</div><!-- /author-info -->
						<?php } ?>

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
							</header><!-- .entry-header -->

							<div class="entry-content">
								<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'wellthemes' ); ?></p>
								<?php get_search_form(); ?>
							</div>
						</article><!-- /post-0 -->

					<?php endif; ?>
				</div><!-- /archive -->
			</div><!-- /content -->
		</section><!-- /primary -->

<?php get_sidebar('left'); ?>
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>