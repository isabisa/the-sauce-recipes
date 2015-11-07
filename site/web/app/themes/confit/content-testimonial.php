<?php
/**
 * The template used for displaying Testimonials.
 *
 * @package Confit
 * @since Confit 1.1
 */
// Access global variable directly to set content_width
if ( isset( $GLOBALS['content_width'] ) )
	$GLOBALS['content_width'] = 558;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>
	</header>

	<?php edit_post_link( __( 'Edit', 'confit' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>

	<?php if ( '' != get_the_post_thumbnail() ) : ?>
		<div class="testimonial-thumbnail">
			<?php the_post_thumbnail( 'confit-testimonial-thumbnail' ); ?>
		</div>
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
