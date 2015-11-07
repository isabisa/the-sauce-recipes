<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Confit
 * @since Confit 1.0
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php confit_content_nav( 'nav-above' ); ?>

				<?php
					if ( 'jetpack-testimonial' == get_post_type() )
						get_template_part( 'content', 'testimonial' );
					else
						get_template_part( 'content', 'single' );
				?>

				<?php confit_content_nav( 'nav-below' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
				?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_footer(); ?>