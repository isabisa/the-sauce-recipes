<?php
/**
 * The template for displaying Testimonial Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Confit
 * @since Confit 1.1
 */

get_header();

$jetpack_options = get_theme_mod( 'jetpack_testimonials' ); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<article>
				<header class="entry-header">
					<h1 class="entry-title">
					<?php
						if ( isset( $jetpack_options['page-title'] ) && ! empty( $jetpack_options['page-title'] ) ) {
							echo esc_html( $jetpack_options['page-title'] );
						} else {
							_e( 'Testimonials', 'confit' );
						}
					?>
					</h1>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php
						if ( isset( $jetpack_options['page-content'] ) && ! empty( $jetpack_options['page-content'] ) ) {
								echo convert_chars( convert_smilies( wptexturize( stripslashes( wp_filter_post_kses( addslashes( $jetpack_options['page-content'] ) ) ) ) ) );
						}
					?>
				</div><!-- .entry-content -->
			</article>

			<div id="testimonials" class="testimonials">

			<?php
				if ( have_posts() ) :

					while ( have_posts() ) : the_post();

						get_template_part( 'content', 'testimonial' );

					endwhile;

					confit_content_nav( 'nav-below' );

				else :

					get_template_part( 'no-results', 'archive' );

				endif;
			?>

		</div><!-- #content .site-content -->
	</section><!-- #primary .content-area -->

<?php get_footer(); ?>