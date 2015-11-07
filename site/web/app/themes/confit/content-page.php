<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Confit
 * @since Confit 1.0
 */
// Access global variable directly to set content_width
if ( isset( $GLOBALS['content_width'] ) )
	$GLOBALS['content_width'] = 558;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'confit' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'confit' ), __( '1 Comment', 'confit' ), __( '% Comments', 'confit' ) ); ?></span>
		<?php endif; ?>
		<?php edit_post_link( __( 'Edit', 'confit' ), '<span class="edit-link">', '</span>' ); ?>
	</footer>
</article><!-- #post-<?php the_ID(); ?> -->
