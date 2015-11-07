<?php
/**
 * Template Name: Menu Template
 * The template for displaying menu items.
 *
 * @package Confit
 * @since Confit 1.0
 */
get_header();
?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
				<?php
					$pdf_menu_url = get_theme_mod( 'menu_upload', '' );
					if ( '' != $pdf_menu_url ) :
				?>
					<span class="pdf-menu"><a href="<?php echo esc_url( $pdf_menu_url ); ?>"><?php _e( 'Download Menu', 'confit' ); ?></a></span>
				<?php endif; ?>

				<?php
					while ( have_posts() ) : the_post();
						get_template_part( 'content', 'page' );
					endwhile; // end of the normal loop.


					$loop = new WP_Query( array( 'post_type' => 'nova_menu_item' ) );
					while ( $loop->have_posts() ) : $loop->the_post();
						get_template_part( 'content', 'menu' );
					endwhile; // end of the Menu Item Loop
					wp_reset_postdata();

					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
				?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_footer(); ?>
